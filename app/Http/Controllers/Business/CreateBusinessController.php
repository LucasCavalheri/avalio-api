<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\CreateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Business')]
class CreateBusinessController extends Controller
{
    /**
     * Rota para criar um novo negócio
     */
    public function __invoke(CreateBusinessRequest $request)
    {
        $data = $request->validated();

        /** @var User */
        $user = Auth::user();

        $totalBusinesses = $user->businesses()->count();

        if ($totalBusinesses >= 1 && $user->subscribedToPrice(config('services.stripe.basic_price_id'))) {
            return $this->error('Usuário atingiu o limite de negócios', Response::HTTP_UNAUTHORIZED);
        }

        if ($totalBusinesses >= 3 && ! $user->subscribedToPrice(config('services.stripe.basic_price_id'))) {
            return $this->error('Usuário atingiu o limite de negócios', Response::HTTP_UNAUTHORIZED);
        }

        $data['user_id'] = $user->id;
        $business = Business::create($data);

        return $this->success('Negócio criado com sucesso', Response::HTTP_CREATED, BusinessResource::make($business));
    }
}
