<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

#[Group('Auth')]
class ResetPasswordController extends Controller
{
    /**
     * Rota para resetar a senha
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'token' => 'required|integer|min:6',
            'password' => 'required|string|min:4|confirmed',
        ]);

        $isTokenValid = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$isTokenValid) {
            return $this->error('Token inválido ou expirado', Response::HTTP_BAD_REQUEST);
        }

        $user = User::find($isTokenValid->user_id);

        if (!$user) {
            return $this->error('Usuário não encontrado', Response::HTTP_NOT_FOUND);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')
            ->where('user_id', $user->id)
            ->where('token', $request->token)
            ->delete();

        return $this->success('Senha redefinida com sucesso', Response::HTTP_OK);
    }
}
