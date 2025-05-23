<?php

use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Cashier\Subscription;

describe('SwapSubscriptionController', function () {
    beforeEach(function () {
        $this->user = mock(User::class)->makePartial();
        $this->actingAs($this->user);
    });

    test('altera assinatura de basic para pro com sucesso', function () {
        $subscription = mock(Subscription::class);
        $subscription->shouldReceive('onGracePeriod')->once()->andReturn(true);
        $subscription->shouldReceive('swap')->once()->andReturn(true);

        $this->user->shouldReceive('subscription')
            ->with('default')
            ->once()
            ->andReturn($subscription);

        $response = $this->postJson(route('stripe.swap-subscription'), [
            'price' => 'pro'
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'message' => 'Assinatura alterada com sucesso!',
            'status' => Response::HTTP_OK,
            'data' => [],
        ]);
    });

    test('retorna erro ao tentar alterar assinatura inexistente', function () {
        $this->user->shouldReceive('subscription')
            ->with('default')
            ->once()
            ->andReturn(null);

        $response = $this->postJson(route('stripe.swap-subscription'), [
            'price' => 'pro'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'Não é possível alterar uma assinatura inexistente ou fora do período de carência',
            'status' => Response::HTTP_BAD_REQUEST,
            'errors' => [],
            'data' => [],
        ]);
    });

    test('retorna erro ao tentar alterar assinatura fora do grace period', function () {
        $subscription = mock(Subscription::class);
        $subscription->shouldReceive('onGracePeriod')->once()->andReturn(false);

        $this->user->shouldReceive('subscription')
            ->with('default')
            ->once()
            ->andReturn($subscription);

        $response = $this->postJson(route('stripe.swap-subscription'), [
            'price' => 'pro'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson([
            'message' => 'Não é possível alterar uma assinatura inexistente ou fora do período de carência',
            'status' => Response::HTTP_BAD_REQUEST,
            'errors' => [],
            'data' => [],
        ]);
    });

    test('retorna erro de validação quando price é inválido', function () {
        $response = $this->postJson(route('stripe.swap-subscription'), [
            'price' => 'invalid-price'
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['price']);
    });

    test('retorna erro de validação quando price não é informado', function () {
        $response = $this->postJson(route('stripe.swap-subscription'), []);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['price']);
    });
});
