<?php

use App\Models\User;
use Illuminate\Http\Response;

describe('RegisterController', function () {
    test('deve registrar um usuário com sucesso', function () {
        $user = User::factory()->make();

        $response = $this->postJson(route('auth.register'), [
            ...$user->toArray(),
            'password' => '12345678',
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'id',
                'name',
                'email',
                'phone',
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);
    });

    test('deve retornar um erro se o email já estiver em uso', function () {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.register'), [
            ...$user->toArray(),
            'phone' => fake()->numerify('###########'),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'email'
            ]
        ]);
    });

    test('deve retornar um erro se o celular já estiver em uso', function () {
        $user = User::factory()->create();

        $response = $this->postJson(route('auth.register'), [
            ...$user->toArray(),
            'email' => fake()->email(),
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'phone'
            ]
        ]);
    });

    test('deve retornar um erro se a senha não atender aos requisitos mínimos', function () {
        $user = User::factory()->make();

        $response = $this->postJson(route('auth.register'), [
            ...$user->toArray(),
            'password' => '123',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'password'
            ]
        ]);
    });
});
