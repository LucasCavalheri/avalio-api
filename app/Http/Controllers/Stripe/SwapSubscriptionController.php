<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Stripe')]
class SwapSubscriptionController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'price' => 'required|string|in:basic,pro',
        ]);

        $priceId = $request->price === 'pro' ? config('services.stripe.pro_price_id') : config('services.stripe.basic_price_id');

        $isDowngradingToBasic = $request->price === 'basic';

        /** @var \App\Models\User */
        $user = $request->user();

        $subscription = $user->subscription('default');

        // Verifica se existe uma assinatura cancelada em grace period
        if (!$subscription) {
            return $this->error(
                'Não é possível alterar uma assinatura inexistente',
                Response::HTTP_BAD_REQUEST
            );
        }

        if ($isDowngradingToBasic) {
            $activeBusinesses = $user->businesses()->where('active', true)->count();

            if ($activeBusinesses > 1) {
                return $this->error(
                    'Você só pode ter 1 negócio ativo no plano básico. Inative os outros para concluir a mudança de plano.',
                    Response::HTTP_BAD_REQUEST
                );
            }
        }

        $subscription->swap($priceId);

        return $this->success('Assinatura alterada com sucesso!', Response::HTTP_OK);
    }
}
