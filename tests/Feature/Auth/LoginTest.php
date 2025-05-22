<?php

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

describe('LoginController', function () {
    test('deve fazer login com sucesso', function () {
        $user = User::factory()->create([
            'email' => 'teste@teste.com',
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'token',
                'user'
            ]
        ]);

        $this->assertAuthenticatedAs($user);
    });

    test('deve retornar erro se o email não for encontrado', function () {
        $response = $this->postJson(route('auth.login'), [
            'email' => 'teste@teste.com',
            'password' => '12345678',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
            'status',
        ]);
    });

    test('deve retornar erro se a senha não for válida', function () {
        $user = User::factory()->create([
            'email' => 'teste@teste.com',
            'password' => Hash::make('12345678'),
        ]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'senha',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
            'status',
        ]);
    });
});
