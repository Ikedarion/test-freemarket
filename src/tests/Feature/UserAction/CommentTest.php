<?php

namespace Tests\Feature\UserAction;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * ログイン済みのユーザーはコメントを送信できる
     */
    public function test_logged_in_user_can_submit_comment()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        Storage::disk('public')->put("{$product->image}", 'dummy_content');

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $response = $this->post(route('comment.store'), [
            'content' => 'テストコメント',
            'product_id' => $product->id
        ]);

        $response->assertRedirect(route('product.show', $product->id));
        $this->assertDatabaseHas('comments', [
            'user_id' => $loginUser->id,
            'product_id' => $product->id,
            'content' => 'テストコメント'
        ]);
    }

    /**
     * ログイン前のユーザーはコメントを送信できない
     */
    public function test_guest_user_cannot_submit_comment()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        Storage::disk('public')->put("{$product->image}", 'dummy_content');

        $response = $this->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $response = $this->post(route('comment.store'), [
            'content' => 'テストコメント',
            'product_id' => $product->id
        ]);

        $response->assertRedirect(route('login'));
    }

    /**
     * コメントが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_comment_is_required()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        Storage::disk('public')->put("{$product->image}", 'dummy_content');

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $response = $this->post(route('comment.store'), [
            'content' => '',
            'product_id' => $product->id
        ]);

        $response->assertSessionHasErrors('content');

        $response->assertRedirect(route('product.show', $product->id));
        $response = $this->get(route('product.show', $product->id));

        $response->assertSee('コメントを入力してください。');
    }

    /**
     * コメントが255字以上の場合、バリデーションメッセージが表示される
     */
    public function test_comment_length_validation()
    {
        Storage::fake('public');

        $loginUser = User::factory()->create();
        $user = User::factory()->create();

        $product = Product::factory()->create(['user_id' => $user->id]);
        Storage::disk('public')->put("{$product->image}", 'dummy_content');

        $response = $this->actingAs($loginUser)->get(route('product.show', $product->id));
        $response->assertStatus(200);

        $response = $this->post(route('comment.store'), [
            'content' => str_repeat('あ', 256),
            'product_id' => $product->id
        ]);

        $response->assertSessionHasErrors('content');

        $response->assertRedirect(route('product.show', $product->id));
        $response = $this->get(route('product.show', $product->id));

        $response->assertSee('コメントは255文字以内で入力してください。');
    }
}
