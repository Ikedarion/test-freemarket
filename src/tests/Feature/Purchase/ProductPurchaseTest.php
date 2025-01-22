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


class ProductPurchaseTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 「購入する」ボタンを押下すると購入が完了する
     */
    public function test_user_can_purchase_a_product()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::factory()->create(['user_id' => $loginUser->id]);

        // モックされたStripeのCheckoutセッションを作成し、セッションID ('test_session_id_123') を返す。テスト用に作成されたダミーのセッションIDで、後のテストで決済成功時のセッションIDとして使用されます。
        $stripeMock = Mockery::mock('alias:' . StripeSession::class);
        $stripeMock->shouldReceive('create')->once()
                ->andReturn((object) ['id' => 'test_session_id_123']);

        $this->app->instance(StripeClient::class, $stripeMock);

        $response = $this->actingAs($loginUser)->postJson('/create-checkout-session', [
            'product_id' => $product->id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_method' => 'カード',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => 'test_session_id_123',
            ]);

        // DB確認: 購入データ
        $this->assertDatabaseHas('purchases', [
            'user_id' => $loginUser->id,
            'product_id' => $product->id,
            'payment_status' => 'pending',
            'price' => $product->price,
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => '取引中',
        ]);

        $purchase = Purchase::where([
            'user_id' => $loginUser->id,
            'product_id' => $product->id,
        ])->first();


        // Stripe支払いが成功したと仮定して`payment_status`が「succeeded」に変更されることを確認
        $this->get("/payment/success/{$purchase->id}");

        $purchase->refresh();
        $this->assertEquals('succeeded', $purchase->payment_status);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'status' => '売却済み',
        ]);
    }

    /**
     * 購入した商品は商品一覧画面にて「sold」と表示される
     */
    public function test_purchased_product_is_displayed_as_sold_in_product_list()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::factory()->create(['user_id' => $loginUser->id]);

        $stripeMock = Mockery::mock('alias:' . StripeSession::class);
        $stripeMock->shouldReceive('create')
        ->once()
        ->andReturn((object) ['id' => 'test_session_id_123']);

        $this->app->instance(StripeClient::class, $stripeMock);

        $response = $this->actingAs($loginUser)->postJson('/create-checkout-session', [
            'product_id' => $product->id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_method' => 'カード',
        ]);

        $response = $this->actingAs($loginUser)->get('/');
        $response->assertStatus(200)
                ->assertSee('Sold');
    }

    /**
     * 「プロフィール/購入した商品一覧」に追加されている
     */
    public function test_purchased_product_is_added_to_user_purchase_list()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        $shippingAddress = ShippingAddress::factory()->create(['user_id' => $loginUser->id]);

        $stripeMock = Mockery::mock('alias:' . StripeSession::class);
        $stripeMock->shouldReceive('create')
        ->once()
        ->andReturn((object) ['id' => 'test_session_id_123']);

        $this->app->instance(StripeClient::class, $stripeMock);

        $response = $this->actingAs($loginUser)->postJson('/create-checkout-session', [
            'product_id' => $product->id,
            'shipping_address_id' => $shippingAddress->id,
            'payment_method' => 'カード',
        ]);

        $response = $this->actingAs($loginUser)->get('/mypage?tab=buy');
        $response->assertStatus(200)
            ->assertSee($product->name);
    }
}
