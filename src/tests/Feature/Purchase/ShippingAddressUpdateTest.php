<?php

namespace Tests\Feature\Purchase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Stripe\Checkout\Session as StripeSession;
use Tests\TestCase;
use App\Models\ShippingAddress;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Stripe\StripeClient;
use Mockery;

class ShippingAddressUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
     */
    public function test_example()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::factory()->create([
            'user_id' => $loginUser->id,
            'postal_code' => '111-111'
        ]);

        // 初期住所確認
        $response = $this->actingAs($loginUser)->get(route('purchase', $product->id));
        $response->assertSee($shippingAddress->postal_code)
                ->assertSee($shippingAddress->address)
                ->assertSee($shippingAddress->building_name);

        $response = $this->get(route('address', $product->id));
        $response->assertSee($shippingAddress->postal_code)
            ->assertSee($shippingAddress->address)
            ->assertSee($shippingAddress->building_name);

        $response = $this->patch(route('address.update', $shippingAddress->id), [
            'postal_code' => '222-2222',
            'address' => 'テスト県',
            'building_name' => 'テストビル',
        ]);

        $this->assertDatabaseHas('shipping_addresses', [
            'postal_code' => '222-2222',
            'address' => 'テスト県',
            'building_name' => 'テストビル',
        ]);
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録される
     */
    public function test_associates_shipping_address_with_purchase()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::factory()->create([
            'user_id' => $loginUser->id,
            'postal_code' => '111-111'
        ]);

        $response = $this->actingAs($loginUser)->patch(route('address.update', $shippingAddress->id), [
            'postal_code' => '222-2222',
            'address' => 'テスト県',
            'building_name' => 'テストビル',
        ]);

        $stripeMock = Mockery::mock('alias:' . StripeSession::class);
        $stripeMock->shouldReceive('create')->once()
                ->andReturn((object) ['id' => 'test_session_id_123']);

        $this->app->instance(StripeClient::class, $stripeMock);

        $response = $this->actingAs($loginUser)->postJson('/create-checkout-session',[
            'product_id' => $product->id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_method' => 'カード',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'id' => 'test_session_id_123',
                ]);

        $purchase = Purchase::where('stripe_payment_id', 'test_session_id_123')->first();

        $this->assertEquals('222-2222', $purchase->shipping_address->postal_code);
        $this->assertEquals('テスト県', $purchase->shipping_address->address);
        $this->assertEquals('テストビル', $purchase->shipping_address->building_name);
    }
}
