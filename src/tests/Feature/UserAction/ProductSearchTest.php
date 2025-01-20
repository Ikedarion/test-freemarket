<?php

namespace Tests\Feature\UserAction;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductSearchTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 「商品名」で部分一致検索ができる
     */
    public function test_partial_search_by_product_name()
    {
        $user = User::factory()->create();

        $product1 = Product::factory()->create(['name' => 'テスト商品１',]);
        $product2 = Product::factory()->create(['name' => 'サンプル商品2',]);

        $keyword = 'テスト';

        $response = $this->actingAs($user)->get('/?keyword=' . urlencode($keyword));

        $response->assertSee($product1->name)
                ->assertDontSee($product2->name)
                ->assertStatus(200);
    }

    /**
     * 検索状態がマイリストでも保持されている
     */
    public function test_search_keyword_is_retained_on_my_list_page()
    {
        $user = User::factory()->create();

        $product1 = Product::factory()->create(['name' => 'テスト商品1']);
        $product2 = Product::factory()->create(['name' => 'サンプル商品２']);

        $user->likedProducts()->attach($product1->id);
        $user->likedProducts()->attach($product2->id);

        $keyword = 'テスト';

        $response = $this->actingAs($user)->get('/?keyword=' . urlencode($keyword));

        $response->assertSee($product1->name)
                ->assertDontSee($product2->name);

        $response = $this->actingAs($user)->get('/?keyword=' . urlencode($keyword) . '&tab=mylist');

        $response->assertSee($product1->name)
                ->assertDontSee($product2->name)
                ->assertStatus(200);
    }
}
