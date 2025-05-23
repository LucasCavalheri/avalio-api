<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Mockery\MockInterface;
use Tests\Feature\Traits\FakeStorage;

use function Pest\Laravel\actingAs;

uses(FakeStorage::class);

describe('UpdateBusinessController', function () {
    beforeEach(function () {
        $this->setUpFakeStorage();
    });

    test('atualiza um negócio com sucesso', function () {
        $realUser = User::factory()->create();

        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->id = $realUser->id;

        $business = Business::factory()->create([
            'user_id' => $user->id,
        ]);

        actingAs($user);

        $updatedData = [
            'name' => 'Novo Nome',
            'description' => 'Nova Descrição',
            'phone' => '11999999999',
        ];

        $response = $this->patchJson(route('businesses.update', $business->id), $updatedData);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'status',
            'data' => [
                'id',
                'name',
                'description',
                'phone',
            ],
        ]);
        $response->assertJson([
            'message' => 'Negócio atualizado com sucesso',
            'status' => Response::HTTP_OK,
            'data' => [
                'name' => 'Novo Nome',
                'description' => 'Nova Descrição',
                'phone' => '11999999999',
            ],
        ]);
    });

    test('retorna erro se o usuário não estiver autenticado', function () {
        $business = Business::factory()->create();

        $response = $this->patchJson(route('businesses.update', $business->id), [
            'name' => 'Novo Nome',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    test('retorna erro se o negócio não existir', function () {
        $realUser = User::factory()->create();

        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->id = $realUser->id;

        actingAs($user);

        $response = $this->patchJson(route('businesses.update', 32), [
            'name' => 'Novo Nome',
        ]);

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

    test('retorna erro se tentar atualizar negócio de outro usuário', function () {
        $realUser = User::factory()->create();

        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->id = $realUser->id;

        $outroUser = User::factory()->create();
        $business = Business::factory()->create([
            'user_id' => $outroUser->id,
        ]);

        actingAs($user);

        $response = $this->patchJson(route('businesses.update', $business->id), [
            'name' => 'Novo Nome',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            'message',
            'status',
            'errors',
            'data',
        ]);
        $response->assertJson([
            'message' => 'Você não tem permissão para atualizar este negócio',
            'status' => Response::HTTP_FORBIDDEN,
            'errors' => [],
            'data' => [],
        ]);
    });
});
