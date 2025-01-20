<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductListDisplayTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 全商品を取得できる
     */
    public function test_get_all_products()
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get('/');

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }

        $response->assertStatus(200);
    }

    /**
     * 購入済み商品は「Sold」と表示される
     */
    public function test_sold_products_display_sold_label()
    {
        Product::factory()->create(['status' => '売却済み']);

        $response = $this->get('/');

        // Sold」ラベルが含まれているかを確認
        $response->assertSee('Sold')
                ->assertStatus(200);
    }

    /**
     * 自分が出品した商品は表示されない
     */
    public function test_own_products_are_not_displayed_in_list()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $ownProduct = Product::factory()->create([
            'name' => '革靴',
            'user_id' => $user->id
        ]);

        $otherProduct = Product::factory()->create([
            'name' => '時計',
            'user_id' => $otherUser->id
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertDontSee($ownProduct->name)
                ->assertSee($otherProduct->name)
                ->assertStatus(200);
    }
}
