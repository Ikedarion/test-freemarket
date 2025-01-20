<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $user = User::factory()->create([
            'profile_image' => 'profile_images/user.png',
        ]);

        Storage::disk('public')->put('profile_images/user.png', 'dummy_content');

        $shipping_address =ShippingAddress::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->get(route('profile.create'));
        $response->assertStatus(200);

        $response->assertSee(Storage::url($user->profile_image))
            ->assertSee($shipping_address->postal_code)
            ->assertSee($shipping_address->address)
            ->assertSee($shipping_address->building_name);
    }
}
