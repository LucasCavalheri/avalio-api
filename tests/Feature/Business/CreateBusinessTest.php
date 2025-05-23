<?php

use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Mockery\MockInterface;
use Tests\Feature\Traits\FakeStorage;

use function Pest\Laravel\actingAs;

uses(FakeStorage::class);

describe('CreateBusinessController', function () {
    beforeEach(function () {
        $this->setUpFakeStorage();
    });

    test('cria um novo negócio com sucesso', function () {
        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->shouldReceive('businesses->count')->andReturn(0);
        $user->shouldReceive('subscribedToPrice')->andReturn(true);
        $user->id = 1;

        actingAs($user);

        $business = Business::factory()->make();

        $response = $this->postJson(route('businesses.create'), [
            ...$business->toArray(),
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'message',
            'status',
        ]);
    });

    test('retorna erro se o usuário não estiver autenticado', function () {
        $response = $this->postJson(route('businesses.create'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
        ]);
        $response->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    test('retorna erro se o usuário não tiver uma assinatura ativa', function () {
        /** @var User $user */
        $user = User::factory()->create();

        actingAs($user);

        $response = $this->postJson(route('businesses.create'));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
            'status',
            'errors',
            'data',
        ]);
        $response->assertJson([
            'message' => 'Usuário não possui uma assinatura ativa',
            'status' => Response::HTTP_UNAUTHORIZED,
            'errors' => [],
            'data' => [],
        ]);
    });

    test('retorna erro se o usuário for plano basic e querer criar mais de 1 negócio', function () {
        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();

        // Mock da collection de businesses
        $businessesCollection = \Mockery::mock('Illuminate\Database\Eloquent\Relations\HasMany');
        $businessesCollection->shouldReceive('count')->andReturn(1);

        // Mock dos métodos do usuário
        $user->shouldReceive('businesses')->andReturn($businessesCollection);
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->shouldReceive('subscribedToPrice')
            ->with(config('services.stripe.basic_price_id'))
            ->andReturn(true);

        actingAs($user);

        $business = Business::factory()->make();

        $response = $this->postJson(route('businesses.create'), [
            ...$business->toArray(),
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
            'status',
            'errors',
            'data',
        ]);
        $response->assertJson([
            'message' => 'Usuário atingiu o limite de negócios',
            'status' => Response::HTTP_UNAUTHORIZED,
            'errors' => [],
            'data' => [],
        ]);
    });

    test('retorna erro se o usuário for plano basic e querer criar mais de 3 negócios', function () {
        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();

        // Mock da collection de businesses
        $businessesCollection = \Mockery::mock('Illuminate\Database\Eloquent\Relations\HasMany');
        $businessesCollection->shouldReceive('count')->andReturn(3);

        // Mock dos métodos do usuário
        $user->shouldReceive('businesses')->andReturn($businessesCollection);
        $user->shouldReceive('subscribed')->andReturn(true);
        $user->shouldReceive('subscribedToPrice')
            ->with(config('services.stripe.basic_price_id'))
            ->andReturn(false);

        actingAs($user);

        $business = Business::factory()->make();

        $response = $this->postJson(route('businesses.create'), [
            ...$business->toArray(),
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertJsonStructure([
            'message',
            'status',
            'errors',
            'data',
        ]);
        $response->assertJson([
            'message' => 'Usuário atingiu o limite de negócios',
            'status' => Response::HTTP_UNAUTHORIZED,
            'errors' => [],
            'data' => [],
        ]);
    });
});
