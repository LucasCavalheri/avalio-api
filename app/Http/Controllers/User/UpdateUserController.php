<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

#[Group('User')]
class UpdateUserController extends Controller
{
    /**
     * Rota para atualizar o perfil do usuário
     */
    public function __invoke(UpdateUserRequest $request)
    {
        $data = $request->validated();

        $user = Auth::user();

        // verifica se a senha atual está correta
        if (isset($data['current_password']) && !Hash::check($data['current_password'], $user->password)) {
            return $this->error('Senha atual incorreta', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user->update($data);

        return $this->success('Usuário atualizado com sucesso', Response::HTTP_OK, UserResource::make($user));
    }
}
