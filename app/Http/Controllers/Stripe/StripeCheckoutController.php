<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Stripe')]
class StripeCheckoutController extends Controller
{
    /**
     * Rota para iniciar o checkout com o Stripe
     */
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'price' => 'required|string|in:basic,pro',
        ]);

        /** @var \App\Models\User */
        $user = $request->user();

        if ($user->subscribed()) {
            return $this->error('Usuário já possui uma assinatura ativa', Response::HTTP_BAD_REQUEST);
        }

        $priceId = match ($data['price']) {
            'pro' => config('services.stripe.pro_price_id'),
            'basic' => config('services.stripe.basic_price_id'),
        };

        try {
            $session = $user->newSubscription('default', $priceId)->checkout([
                'success_url' => config('app.frontend_url').'/success',
                'cancel_url' => config('app.frontend_url').'/cancel',
            ]);

            return $this->success('Checkout realizado com sucesso', Response::HTTP_OK, [
                'url' => $session->url,
            ]);
        } catch (\Exception $e) {
            return $this->error('Erro ao realizar o checkout', Response::HTTP_INTERNAL_SERVER_ERROR, [$e->getMessage()]);
        }
    }
}
