<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\ForgotPasswordNotification;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

#[Group('Auth')]
class ForgotPasswordController extends Controller
{
    /**
     * Rota para enviar o token e link para resetar a senha
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return $this->error('Usuário não encontrado', Response::HTTP_NOT_FOUND);
        }

        $token = rand(100000, 999999);

        DB::table('password_reset_tokens')->insert([
            'token' => $token,
            'user_id' => $user->id,
            'expires_at' => now()->addMinutes(15),
        ]);

        $notification = new ForgotPasswordNotification($token);

        $user->notify($notification);

        return $this->success('Email enviado com sucesso', Response::HTTP_OK);
    }
}
