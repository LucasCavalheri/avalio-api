<?php

use App\Models\User;
use Illuminate\Http\Response;
use Laravel\Cashier\Checkout;
use Stripe\Checkout\Session as StripeSession;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;
use Mockery\MockInterface;
use Illuminate\Contracts\Auth\Authenticatable;

describe('StripeCheckoutController', function () {
    beforeEach(function () {
        // Finge que temos as variáveis do Stripe definidas
        config()->set('services.stripe.basic_price_id', 'price_basic_test');
        config()->set('services.stripe.pro_price_id', 'price_pro_test');
        config()->set('app.frontend_url', 'http://localhost:3000');
    });

    test('usuário não autenticado não pode acessar o checkout', function () {
        $response = postJson(route('stripe.checkout'), [
            'price' => 'basic',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    });

    test('retorna erro se o usuário já tiver uma assinatura ativa', function () {
        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(true);

        actingAs($user);

        $response = postJson(route('stripe.checkout'), [
            'price' => 'basic',
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJsonFragment(['message' => 'Usuário já possui uma assinatura ativa']);
    });

    test('retorna erro se o preço for inválido', function () {
        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();

        actingAs($user);

        $response = postJson(route('stripe.checkout'), [
            'price' => 'invalid',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(['price']);
    });

    test('realiza o checkout com sucesso usando plano basic', function () {
        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(false);

        // Simula sessão de checkout
        $mockSession = new \stdClass();
        $mockSession->url = 'https://checkout.stripe.com/fake-session';

        $user->shouldReceive('newSubscription')
            ->once()
            ->with('default', 'price_basic_test')
            ->andReturn(new class($mockSession) {
                public function __construct(public $session) {}

                public function checkout($options)
                {
                    return $this->session;
                }
            });

        actingAs($user);

        $response = postJson(route('stripe.checkout'), [
            'price' => 'basic',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'status',
            'data' => ['url']
        ]);
    });

    test('realiza o checkout com sucesso usando plano pro', function () {
        /** @var User&Authenticatable&MockInterface $user */
        $user = \Mockery::mock(User::class, Authenticatable::class)->makePartial();
        $user->shouldReceive('subscribed')->andReturn(false);

        // Simula sessão de checkout
        $mockSession = new \stdClass();
        $mockSession->url = 'https://checkout.stripe.com/fake-session';

        $user->shouldReceive('newSubscription')
            ->once()
            ->with('default', 'price_pro_test')
            ->andReturn(new class($mockSession) {
                public function __construct(public $session) {}

                public function checkout($options)
                {
                    return $this->session;
                }
            });

        actingAs($user);

        $response = postJson(route('stripe.checkout'), [
            'price' => 'pro',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message',
            'status',
            'data' => ['url']
        ]);
    });
});
