<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_login_fails_without_email()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');

        $response->assertRedirect('/login');
        $response = $this->get('/login');

        $response->assertSee('メールアドレスを入力してください。');
    }

    /**
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_login_fails_without_password()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors('password');

        $response->assertRedirect('/login');
        $response = $this->get('/login');

        $response->assertSee('パスワードを入力してください。');
    }

    /**
     * 入力情報が間違っている場合、バリデーションメッセージが表示される
     */
    public function test_login_fails_with_invalid_credentials()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password'
        ]);

        $response->assertSessionHasErrors();

        $response->assertRedirect('/login');
        $response = $this->get('/login');

        $response->assertSee('ログイン情報が登録されていません。');
    }

    /**
     * 正しい情報が入力された場合、ログイン処理が実行される
     */
    public function test_login_redirects_to_home_on_success()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }
}
