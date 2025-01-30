<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session as StripeSession;
use Tests\TestCase;
use App\Models\ShippingAddress;
use App\Models\Product;
use App\Models\User;
use Stripe\StripeClient;
use Mockery;


class PaymentMethodSelectTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 小計画面で変更が即時反映される
     */
    public function testPaymentMethodSelection()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::factory()->create(['user_id' => $loginUser->id]);

        $sessionIds = ['test_session_id_12', 'test_session_id_123'];
        $paymentMethods = ['カード', 'コンビニ'];

        $stripeMock = Mockery::mock('alias:' . StripeSession::class);
        foreach ($paymentMethods as $index => $method) {
            $stripeMock->shouldReceive('create')
                ->once()
                ->andReturn((object) ['id' => $sessionIds[$index]]);
        }

        $this->app->instance(StripeClient::class, $stripeMock);

        foreach ($paymentMethods as $index => $method) {
            $response = $this->actingAs($loginUser)->postJson('/create-checkout-session', [
                'product_id' => $product->id,
                'shipping_address_id' => $shippingAddress->id,
                'payment_method' => $method,
            ]);

            $response->assertStatus(200)
                ->assertJson([
                    'id' => $sessionIds[$index],
                    'payment_method' => $method
                ]);
        }
    }

}
