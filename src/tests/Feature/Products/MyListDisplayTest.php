<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class MyListDisplayTest extends TestCase
{
    use RefreshDatabase;
    /**
     * いいねした商品だけが表示される
     */
    public function test_liked_products_are_displayed_in_my_list()
    {
        $user = User::factory()->create();
        $loginUser = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '靴'
        ]);
        $product2 = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '時計'
        ]);

        $loginUser->likedProducts()->attach($product->id);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertSee($product->name)
                ->assertDontSee($product2->name)
                ->assertStatus(200);
    }

    /**
     * 購入済み商品は「Sold」と表示される
     */
    public function test_purchased_products_display_sold_label_in_my_list()
    {
        $user = User::factory()->create();
        $loginUser = User::factory()->create();

        $product = Product::factory()->create([
            'status' => '売却済み',
            'user_id' => $user->id
        ]);

        $loginUser->likedProducts()->attach($product->id);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertSee('Sold')->assertStatus(200);
    }


    /**
     * 自分が出品した商品は表示されない
    */
    public function test_own_products_are_not_displayed_in_my_list()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $ownProduct = Product::factory()->create([
            'user_id' => $loginUser->id,
            'name' => '靴'
        ]);
        $otherProduct = Product::factory()->create([
            'user_id' => $user->id,
            'name' => '時計'
        ]);

        $loginUser->likedProducts()->attach([$ownProduct->id]);
        $loginUser->likedProducts()->attach([$otherProduct->id]);

        $response = $this->actingAs($loginUser)->get('/?tab=mylist');

        $response->assertDontSee($ownProduct->name)
            ->assertSee($otherProduct->name)
            ->assertStatus(200);
    }

    /**
     * 未認証の場合は何も表示されない
     */
    public function test_guest_user_cannot_view_my_list()
    {
        $guestUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);

        $guestUser->likedProducts()->attach($product->id);

        $response = $this->get('/?tab=mylist');

        $response->assertDontSee('Sold')
                ->assertDontSee($product->name)
                ->assertStatus(200);
    }
}
