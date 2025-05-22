<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('Auth')]
class LogoutController extends Controller
{
    /**
     * Rota para o usuário fazer o logout
     */
    public function __invoke(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->success('Logout realizado com sucesso', Response::HTTP_OK);
    }
}
