<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

#[Group('User')]
class DeleteAccountController extends Controller
{
    /**
     * Rota para deletar a conta do usuário
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $user->subscription('default')->cancelNow();

        $user->delete();

        return $this->success('Conta deletada com sucesso', Response::HTTP_OK);
    }
}
