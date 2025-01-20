<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\ShippingAddress;
use App\Models\User;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所）
     */
    public function test_profile_edit_page_displays_default_user_information()
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('profile.create'));
        $response->assertStatus(200)
                ->assertSee($user->name);

        $image = UploadedFile::fake()->image('user_image.jpg');

        $response = $this->post(route('profile.store', $user->id), [
            'image' => $image,
            'name' => 'テスト',
            'postal_code' => '111-1111',
            'address' => 'テスト県',
            'building_name' => 'テストビル'
        ]);

        $imagePath = 'profile_images/' . $image->hashName();
        $this->assertDatabaseHas('users', [
            'profile_image' => $imagePath
        ]);

        $this->assertTrue(Storage::disk('public')->exists($imagePath));

        $shipping_address = ShippingAddress::where('user_id', $user->id)->first();

        //初期値確認
        $response = $this->get(route('profile.create'));
        $response->assertStatus(200);

        $response->assertSee(Storage::url($user->profile_image))
            ->assertSee($shipping_address->postal_code)
            ->assertSee($shipping_address->address)
            ->assertSee($shipping_address->building_name);
    }
}
