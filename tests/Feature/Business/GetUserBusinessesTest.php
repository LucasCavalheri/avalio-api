<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Mockery\MockInterface;
use Tests\Feature\Traits\FakeStorage;

use function Pest\Laravel\actingAs;

uses(FakeStorage::class);

describe('GetUserBusinessesController', function () {
    beforeEach(function () {
        $this->setUpFakeStorage();
    });

    test('retorna os negócios do usuário com sucesso', function () {
        $realUser = User::factory()->create();

        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->id = $realUser->id;

        // Cria 3 negócios para o usuário
        $businesses = Business::factory(3)->create([
            'user_id' => $user->id,
        ]);

        actingAs($user);

        $response = $this->getJson(route('businesses.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Negócios encontrados com sucesso',
            'status' => Response::HTTP_OK,
        ]);

        // Verifica se retornou exatamente os 3 negócios
        $this->assertCount(3, $response->json('data'));

        // Verifica se os IDs dos negócios retornados são os mesmos que criamos
        $returnedIds = collect($response->json('data'))->pluck('id')->sort()->values();
        $expectedIds = $businesses->pluck('id')->sort()->values();
        $this->assertEquals($expectedIds, $returnedIds);
    });

    test('retorna array vazio quando usuário não tem negócios', function () {
        $realUser = User::factory()->create();

        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->id = $realUser->id;

        actingAs($user);

        $response = $this->getJson(route('businesses.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'status',
            'data',
        ]);
        $response->assertJson([
            'message' => 'Negócios encontrados com sucesso',
            'status' => Response::HTTP_OK,
            'data' => [],
        ]);
    });

    test('retorna erro se o usuário não estiver autenticado', function () {
        $response = $this->getJson(route('businesses.index'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });
});
