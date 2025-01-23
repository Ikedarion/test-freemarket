<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Color>
 */
class ColorFactory extends Factory
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
            'name' => $faker->colorName,
        ];
    }
}
