<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Review')]
class RespondToReviewController extends Controller
{
    /**
     * Rota para um negócio responder a uma review
     */
    public function __invoke(Request $request, string $id)
    {
        $user = $request->user();

        if ($user->subscribedToPrice(config('services.stripe.basic_price_id'))) {
            return $this->error('O seu plano não permite responder reviews', Response::HTTP_FORBIDDEN);
        }

        $request->validate([
            'response' => 'required|string|max:1000',
        ]);

        $review = Review::find($id);

        if (! $review) {
            return $this->error('Review não encontrada', Response::HTTP_NOT_FOUND);
        }

        if ($review->business->user_id !== Auth::id()) {
            return $this->error('Você não tem permissão para responder esta review', Response::HTTP_FORBIDDEN);
        }

        $review->update([
            'response' => $request->response,
        ]);

        return $this->success('Resposta adicionada com sucesso', Response::HTTP_OK, ReviewResource::make($review));
    }
}
