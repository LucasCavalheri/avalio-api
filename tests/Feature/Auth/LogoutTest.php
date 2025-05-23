<?php

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;

describe('LogoutController', function () {
    test('deve fazer logout com sucesso', function () {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'email' => 'teste@teste.com',
            'password' => Hash::make('12345678'),
        ]);

        actingAs($user);

        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'status',
        ]);
    });

    test('deve retornar erro se o usuário não estiver autenticado', function () {
        $response = $this->postJson(route('auth.logout'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
        ]);
    });
});
