<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
use App\Models\User;
use App\Models\Product;
use App\Models\ShippingAddress;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
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

        return [
            'price' => $faker->randomFloat(2, 100, 10000),
            'payment_status' => 'succeeded',
            'payment_method' => 'card',
            'stripe_payment_id' => $faker->uuid,
            'shipping_address_id' => ShippingAddress::factory(),
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
