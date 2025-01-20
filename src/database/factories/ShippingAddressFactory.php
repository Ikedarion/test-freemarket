<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use App\Models\User;

/**
 * @extends
 */
class ShippingAddressFactory extends Factory
{
    /**
     * 商品データのテスト用ファクトリ定義
     * PHPUnitでの商品テストに使用
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $faker = Faker::create('ja_JP');
        $postalCode = sprintf('%03d-%04d', mt_rand(100, 999), mt_rand(100, 999));

        return [
            'user_id' => User::factory(),
            'postal_code' => $postalCode,
            'address' => $faker->prefecture . $faker->city . $faker->streetAddress,
            'building_name' => $faker->secondaryAddress,
        ];
    }
}
