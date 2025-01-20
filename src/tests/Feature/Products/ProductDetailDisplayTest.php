<?php

namespace Tests\Feature\Products;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;

class ProductDetailDisplayTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
     */
    public function test_product_detail_displays_required_information()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create([
            'profile_image' => 'profile_images/login_user.png',
        ]);

        $user = User::factory()->create([
            'profile_image' => 'profile_images/seller.jpg',
        ]);

        $product = Product::factory()->create(['user_id' => $user->id]);

        $commentUser = User::factory()->create([
            'profile_image' => 'profile_images/comment_user.jpg',
        ]);

        Storage::disk('public')->put('profile_images/comment_user.jpg', 'dummy content');
        Storage::disk('public')->put('profile_images/seller.jpg', 'dummy content');
        Storage::disk('public')->put('profile_images/login_user.png', 'dummy content');
        Storage::disk('public')->put("{$product->image}", 'dummy content');

        $category = Category::factory()->create();

        $comment = Comment::create([
            'user_id' => $commentUser->id,
            'product_id' => $product->id,
            'content' => 'テストコメント',
        ]);

        $product->likedByUsers()->attach($user->id);
        $product->categories()->attach($category->id);

        $product->load('categories', 'likedByUsers', 'comments.user');

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));

        $response->assertSee($product->name)
                ->assertSee($product->brand_name)
                ->assertSee(number_format($product->price))
                ->assertSee($product->color)
                ->assertSee($product->condition)
                ->assertSee($product->description)
                ->assertSee($product->user->name)
                ->assertSee($comment->content)
                ->assertSee($comment->user->name)
                ->assertSee((string)$product->likedByUsers()->count())
                ->assertSee((string)$product->comments()->count());

        foreach ($product->categories as $category) {
            $response->assertSee($category->name);
        }

        $response->assertSee(Storage::url($product->image), false)
                ->assertSee(Storage::url($user->image), false)
                ->assertSee(Storage::url($commentUser->image), false);

        $response->assertStatus(200);

        $this->assertDatabaseHas('products', [
            'name' => $product->name,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('comments', [
            'content' => $comment->content,
            'user_id' => $commentUser->id,
        ]);

    }

    /**
     * 複数選択されたカテゴリが表示されているか
     */
    public function test_product_detail_displays_required_categories()
    {
        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create([
            'user_id' => $user->id
        ]);

        $categories = [
            Category::create(['name' => '本']),
            Category::create(['name' => '家電']),
            Category::create(['name' => 'インテリア'])
        ];

        foreach ($categories as $category) {
            $product->categories()->attach($category->id);
        }

        $response = $this->actingAs($loginUser)->get("/product/{$product->id}");

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
        $response->assertStatus(200);
    }
}
