<?php

namespace Tests\Feature\UserAction;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * いいねアイコンを押下することによって、いいねした商品として登録することができる。
     */
    public function test_user_can_like_product()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);

        Storage::disk('public')->put("{$product->image}", 'dummy_content');

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $this->post(route('like', $product->id));
        $this->assertTrue($product->likedByUsers->contains($loginUser));

        $this->assertEquals(1, $product->likedByUsers()->count());
        $this->assertDatabaseHas('likes', [
            'product_id' => $product->id,
            'user_id' => $loginUser->id
        ]);
    }

    /**
     * 追加済みのアイコンは色が変化する
     */
    public function test_icon_changes_color_on_like()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);

        Storage::disk('public')->put("{$product->image}", 'dummy_content');

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $response->assertSee('gray');

        $this->post(route('like', $product->id));

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $response->assertSee('yellow');
    }

    /**
     * 再度いいねアイコンを押下することによって、いいねを解除することができる。
     */
    public function test_user_can_delete_like_product()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);

        Storage::disk('public')->put("{$product->image}", 'dummy_content');

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $this->post(route('like', $product->id));
        $this->assertTrue($product->likedByUsers->contains($loginUser));

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);


        $this->post(route('like', $product->id));

        $product->load('likedByUsers');

        $this->assertFalse($product->likedByUsers->contains($loginUser));
        $this->assertEquals(0, $product->likedByUsers()->count());

        $this->assertDatabaseMissing('likes', [
            'product_id' => $product->id,
            'user_id' => $loginUser->id
        ]);
    }
}
