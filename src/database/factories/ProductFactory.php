<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'name' => $faker->randomElement(['腕時計', 'HDD', '革靴', 'マイク', 'タンブラー']),
            'price' => $faker->numberBetween(1000, 10000) . '.00',
            'brand_name' => $faker->company(),
            'color' => $faker->randomElement(['黒', '白', '青']),
            'description' => $faker->text(70),
            'status' => '販売中',
            'condition' => $faker->randomElement(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い']),
            'image' => 'product_images/' . $faker->randomElement([
                'watch.jpg', 'hdd.jpg', 'shoes.jpg', 'microphone.jpg',
            ]),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
