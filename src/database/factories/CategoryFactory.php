<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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

        $categories = ['家電', 'インテリア', 'レディース', 'メンズ', '本'];
        $categoryName = $faker->unique()->randomElement($categories);

        return [
            'name' => $categoryName,
        ];
    }
}