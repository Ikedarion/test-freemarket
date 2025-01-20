<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\User;

class ProfileDisplayTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
     */
    public function test_user_profile_displays_correctly()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create([
            'profile_image' => 'profile_images/login_user.png',
        ]);
        $user = User::factory()->create([
            'profile_image' => 'profile_images/user.png',
        ]);

        Storage::disk('public')->put('profile_images/login_user.png', 'dummy_content');

        $category1 = Category::create(['name' => '家電']);
        $category2 = Category::create(['name' => '本']);
        $category3 = Category::create(['name' => 'インテリア']);

        $product1 = Product::factory()->create(['user_id' => $loginUser->id]);
        $product2 = Product::factory()->create(['user_id' => $loginUser->id]);
        $product3 = Product::factory()->create(['user_id' => $user->id]);

        $product1->categories()->attach($category1->id);
        $product2->categories()->attach($category2->id);
        $product3->categories()->attach($category3->id);

        // 購入商品作成
        $purchase = Purchase::factory()->create([
            'user_id' => $loginUser->id,
            'product_id' => $product3->id,
        ]);

        $response = $this->actingAs($loginUser)->get(route('my-page'));
        $response->assertStatus(200);

        $response = $this->actingAs($loginUser)->get('/mypage/?tab=buy');
        $response->assertSee($loginUser->name)
            ->assertSee(Storage::url($loginUser->profile_image))
            ->assertSee($purchase->product->name);

        $response = $this->actingAs($loginUser)->get('/mypage/?tab=sell');
        $response->assertSee($loginUser->name)
                ->assertSee(Storage::url($loginUser->profile_image));

        foreach ($loginUser->products as $product) {
            $response->assertSee($product->name);
        }
    }
}