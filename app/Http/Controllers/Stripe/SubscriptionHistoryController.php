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

            return response()->json([
                'subscriptions' => SubscriptionHistoryResource::collection($subscriptions),
                'invoices' => collect($invoices)->map(function ($invoice) {
                    return [
                        'id' => $invoice->id,
                        'total' => $invoice->total(),
                        'status' => $invoice->status,
                        'date' => $invoice->date()->toIso8601String(),
                        'url' => $invoice->asStripeInvoice()->hosted_invoice_url,
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao obter histórico',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
