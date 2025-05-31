<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Traits\HttpResponse;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('User')]
class ListUserReviewsController extends Controller
{
    use HttpResponse;

    /**
     * Rota para obter todas as reviews dos negócios do usuário
     */
    public function __invoke(): JsonResponse
    {
        $user = Auth::user();

        $reviews = $user->businesses()
            ->with(['reviews' => function ($query) {
                $query->with('business')->latest();
            }])
            ->get()
            ->pluck('reviews')
            ->flatten();

        return $this->success(
            'Reviews obtidas com sucesso',
            Response::HTTP_OK,
            ReviewResource::collection($reviews)
        );
    }
}
