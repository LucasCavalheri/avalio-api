<?php

namespace App\Http\Controllers\Review;

use App\Events\ReviewCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\CreateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Business;
use App\Models\Review;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;

#[Group('Review')]
class CreateReviewController extends Controller
{
    /**
     * Rota para criar uma review
     *
     * @unauthenticated
     */
    public function __invoke(CreateReviewRequest $request)
    {
        $data = $request->validated();

        $business = Business::with('user')->find($data['business_id']);

        if (!$business) {
            return $this->error('Negócio não encontrado', Response::HTTP_NOT_FOUND);
        }

        $subscription = $business->user->subscription('default');

        $isBasic = $subscription && $subscription->stripe_price === config('services.stripe.basic_price_id');

        $status = $isBasic ? 'approved' : 'pending';

        $review = Review::create([
            ...$data,
            'status' => $status,
        ]);

        $review->load(['business.user']);

        ReviewCreated::dispatch($review);

        return $this->success('Review criada com sucesso', Response::HTTP_CREATED, ReviewResource::make($review));
    }
}
