<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\UpdateBusinessRequest;
use App\Http\Resources\BusinessResource;
use App\Models\Business;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Business')]
class UpdateBusinessController extends Controller
{
    /**
     * Rota para atualizar um negócio
     */
    public function __invoke(UpdateBusinessRequest $request, string $id)
    {
        $data = $request->validated();

        $business = Business::find($id);

        if (!$business) {
            return $this->error('Negócio não encontrado', Response::HTTP_NOT_FOUND);
        }

        if ($business->user_id !== Auth::id()) {
            return $this->error('Você não tem permissão para atualizar este negócio', Response::HTTP_FORBIDDEN);
        }

        $business->update($data);

        return $this->success('Negócio atualizado com sucesso', Response::HTTP_OK, BusinessResource::make($business));
    }
}
