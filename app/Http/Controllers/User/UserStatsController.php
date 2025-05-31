<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('User')]
class UserStatsController extends Controller
{
    /**
     * Rota para obter as estatísticas do usuário
     */
    public function __invoke(): JsonResponse
    {
        $user = Auth::user();
        $businesses = $user->businesses()->with('reviews')->get();

        $now = Carbon::now();
        $startOfCurrentMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Total de avaliações
        $totalReviews = 0;
        $lastMonthReviews = 0;
        $currentMonthReviews = 0;
        $totalWithComments = 0;
        $approvedReviews = 0;
        $totalRating = 0;
        $reviewsWithRating = 0;

        foreach ($businesses as $business) {
            $reviews = $business->reviews;

            // Total de avaliações
            $totalReviews += $reviews->count();

            // Avaliações do mês passado
            $lastMonthReviews += $reviews
                ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
                ->count();

            // Avaliações do mês atual
            $currentMonthReviews += $reviews
                ->where('created_at', '>=', $startOfCurrentMonth)
                ->count();

            // Avaliações com comentários
            $totalWithComments += $reviews
                ->whereNotNull('comment')
                ->where('comment', '!=', '')
                ->count();

            // Avaliações aprovadas
            $approvedReviews += $reviews
                ->where('status', 'approved')
                ->count();

            // Soma das avaliações para média
            foreach ($reviews as $review) {
                if ($review->rating) {
                    $totalRating += $review->rating;
                    $reviewsWithRating++;
                }
            }
        }

        // Cálculo da variação percentual
        $percentageChange = $lastMonthReviews > 0
            ? (($currentMonthReviews - $lastMonthReviews) / $lastMonthReviews) * 100
            : 0;

        // Média de avaliações
        $averageRating = $reviewsWithRating > 0
            ? $totalRating / $reviewsWithRating
            : 0;

        // Porcentagem de comentários
        $commentPercentage = $totalReviews > 0
            ? ($totalWithComments / $totalReviews) * 100
            : 0;

        // Taxa de aprovação
        $approvalRate = $totalReviews > 0
            ? ($approvedReviews / $totalReviews) * 100
            : 0;

        $data = [
            'total_reviews' => [
                'value' => $totalReviews,
                'percentage_change' => round($percentageChange, 1),
                'comparison' => 'em relação ao mês passado'
            ],
            'average_rating' => [
                'value' => round($averageRating, 1),
                'max' => 5,
                'label' => 'De 5 estrelas possíveis'
            ],
            'comments' => [
                'value' => $totalWithComments,
                'percentage' => round($commentPercentage),
                'label' => 'das avaliações têm comentários'
            ],
            'approval_rate' => [
                'value' => round($approvalRate),
                'rejected_percentage' => round(100 - $approvalRate),
                'label' => 'de avaliações rejeitadas'
            ]
        ];

        return $this->success(
            'Estatísticas obtidas com sucesso',
            Response::HTTP_OK,
            $data
        );
    }
}
