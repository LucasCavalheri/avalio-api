<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Response;
use Mockery\MockInterface;

use function Pest\Laravel\actingAs;

describe('DeleteBusinessController', function () {
    test('deleta um negócio com sucesso', function () {
        $realUser = User::factory()->create();

        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->id = $realUser->id;

        $business = Business::factory()->create([
            'user_id' => $user->id,
        ]);

        actingAs($user);

        $response = $this->deleteJson(route('businesses.delete', $business->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'status',
        ]);
        $response->assertJson([
            'message' => 'Negócio deletado com sucesso',
            'status' => Response::HTTP_OK,
        ]);

        $this->assertDatabaseMissing('businesses', [
            'id' => $business->id,
        ]);
    });

    test('retorna erro se o usuário não estiver autenticado', function () {
        $business = Business::factory()->create();

        $response = $this->deleteJson(route('businesses.delete', $business->id));

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

        $response = $this->deleteJson(route('businesses.delete', 32));

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

    test('retorna erro se tentar deletar negócio de outro usuário', function () {
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

        $response = $this->deleteJson(route('businesses.delete', $business->id));

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJsonStructure([
            'message',
            'status',
            'errors',
            'data',
        ]);
        $response->assertJson([
            'message' => 'Você não tem permissão para deletar este negócio',
            'status' => Response::HTTP_FORBIDDEN,
            'errors' => [],
            'data' => [],
        ]);

        // Garante que o negócio não foi deletado
        $this->assertDatabaseHas('businesses', [
            'id' => $business->id,
        ]);
    });
});
