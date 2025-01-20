<?php

namespace Tests\Feature\UserAction;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;

class ProductCreateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、商品の説明、販売価格）
     */
    public function test_user_can_create_product()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('product.create'));
        $response->assertStatus(200);

        $image = UploadedFile::fake()->image('product_image.jpg');

        $categories = [
            Category::create(['name' => '本']),
            Category::create(['name' => '家電'])
        ];

        $response = $this->post(route('product.store'), [
            'image' => $image,
            'category_id' => [$categories[0]->id, $categories[1]->id],
            'color' => '黒',
            'condition' => '良好',
            'name' => 'バッグ',
            'brand_name' => 'テストブランド',
            'description' => str_repeat('あ', 60),
            'price' => 3000
        ]);

        $response->assertRedirect(route('my-page'));
        $response = $this->get(route('my-page'));
        $response->assertSee('商品が出品されました。');

        $product = Product::where('user_id', $user->id)->first();

        $this->assertDatabaseHas('products', [
            'user_id' => $user->id,
            'name' => 'バッグ',
            'description' => str_repeat('あ', 60),
            'price' => 3000
        ]);

        $this->assertDatabaseHas('product_category', [
            'product_id' => $product->id,
            'category_id' => $categories[0]->id
        ]);

        $this->assertDatabaseHas('product_category', [
            'product_id' => $product->id,
            'category_id' => $categories[1]->id
        ]);
    }
}
