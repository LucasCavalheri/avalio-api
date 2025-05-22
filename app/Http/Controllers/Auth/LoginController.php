<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('Auth')]
class LoginController extends Controller
{
    /**
     * Rota para o usuário fazer o login
     *
     * @unauthenticated
     */
    public function __invoke(LoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt($data)) {
            return $this->error('Credenciais inválidas', Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success('Login realizado com sucesso', Response::HTTP_OK, [
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }
}
