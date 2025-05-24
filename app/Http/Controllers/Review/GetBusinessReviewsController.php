<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;

#[Group('Review')]
class GetBusinessReviewsController extends Controller
{
    /**
     * Rota para buscar as reviews de um negócio
     *
     * @unauthenticated
     */
    public function __invoke(Request $request, string $id)
    {
        $reviews = Review::where('business_id', $id)->get();

        return $this->success('Reviews encontradas com sucesso', Response::HTTP_OK, ReviewResource::collection($reviews));
    }
}
