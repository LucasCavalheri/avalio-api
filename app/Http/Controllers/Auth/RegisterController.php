<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;

#[Group('Auth')]
class RegisterController extends Controller
{
    /**
     * Rota para cadastrar um novo usuário
     *
     * @unauthenticated
     */
    public function __invoke(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['phone'] = preg_replace('/[^0-9]/', '', $data['phone']);
        $data['phone'] = '+55' . $data['phone'];

        $user = User::create($data);

        return $this->success('Usuário cadastrado com sucesso', Response::HTTP_CREATED, UserResource::make($user));
    }
}
