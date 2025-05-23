<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Response;

describe('GetBusinessController', function () {
    test('retorna um negócio com sucesso', function () {
        $user = User::factory()->create();
        $business = Business::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->getJson(route('businesses.show', $business->id));

        $response->assertStatus(Response::HTTP_OK);
    });

    test('retorna erro se o negócio não existir', function () {
        $response = $this->getJson(route('businesses.show', 32));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertJsonStructure([
            'message',
            'status',
            'errors',
            'data',
        ]);
        $response->assertJson([
            'message' => 'Negócio não encontrado',
            'status' => Response::HTTP_NOT_FOUND,
            'errors' => [],
            'data' => [],
        ]);
    });
});
