<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Stripe')]
class CancelSubscriptionController extends Controller
{
    /**
     * Rota para cancelar a assinatura do usuário
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        try {
            $user->subscription('default')->cancelNow();

            return $this->success('Assinatura cancelada com sucesso', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error('Erro ao cancelar a assinatura', Response::HTTP_INTERNAL_SERVER_ERROR, [$e->getMessage()]);
        }
    }
}
