<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

#[Group('User')]
class UserProfileController extends Controller
{
    /**
     * Rota para buscar o perfil do usuário
     */
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        return $this->success('Perfil do usuário encontrado com sucesso', Response::HTTP_OK, UserResource::make($user));
    }
}
