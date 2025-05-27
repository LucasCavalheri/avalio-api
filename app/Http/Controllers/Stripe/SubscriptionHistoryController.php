<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubscriptionHistoryResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Stripe')]
class SubscriptionHistoryController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        try {
            // Obtém todas as assinaturas (ativas, canceladas, etc)
            $subscriptions = $user->subscriptions()->latest()->get();

            // Obtém o histórico de faturas/pagamentos
            $invoices = $user->invoices();

            // Para cada assinatura ativa, obtém a data da próxima cobrança
            $subscriptions = $subscriptions->map(function ($subscription) {
                if ($subscription->stripe_status === 'active' && !$subscription->canceled()) {
                    $stripeSubscription = $subscription->asStripeSubscription();
                    $subscription->next_payment = $stripeSubscription->current_period_end;
                }
                return $subscription;
            });

            return response()->json([
                'subscriptions' => SubscriptionHistoryResource::collection($subscriptions),
                'invoices' => $invoices->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'total' => $invoice->total(),
                        'status' => $invoice->status,
                        'date' => $invoice->date()->toISOString(),
                        'url' => $invoice->hosted_invoice_url,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao obter histórico',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'errors' => [$e->getMessage()],
                'data' => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
