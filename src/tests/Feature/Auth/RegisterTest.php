<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_registration_fails_without_name()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('name');

        $response->assertRedirect('/register');
        $response = $this->get('/register');

        $response->assertSee('お名前を入力してください。');
    }

    /**
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_registration_fails_without_email()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('register', [
            'name' => '山田 太郎',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('email');

        $response->assertRedirect('/register');
        $response = $this->get('/register');
        $response->assertSee('メールアドレスを入力してください。');
    }

    /**
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_registration_fails_without_password()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors('password');

        $response->assertRedirect('/register');
        $response = $this->get('/register');

        $response->assertSee('パスワードを入力してください。');
    }

    /**
     * パスワードが7文字以下の場合、バリデーションメッセージが表示される
     */
    public function test_registration_fails_short_password()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        $response->assertSessionHasErrors('password');

        $response->assertRedirect('/register');
        $response = $this->get('/register');

        $response->assertSee('パスワードは8文字以上で入力してください。');
    }

    /**
     * パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
     */
    public function test_registration_fails_mismatched_password_confirmation()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('password_confirmation');

        $response->assertRedirect('/register');
        $response = $this->get('/register');

        $response->assertSee('パスワードと一致しません。');
    }

    /**
     * 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される
     */
    public function test_registration_redirects_to_login_on_success()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);

        $response = $this->post('/register', [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/login');
    }
}