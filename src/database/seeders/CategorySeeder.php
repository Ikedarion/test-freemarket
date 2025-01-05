<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
                'name' => [
                    'ファッション',
                    '家電',
                    'インテリア',
                    'レディース',
                    'メンズ',
                    'コスメ',
                    '本',
                    'ゲーム',
                    'スポーツ',
                    'キッチン',
                    'ハンドメイド',
                    'アクセサリー',
                    'おもちゃ',
                    'ベビー・キッズ',
                ],
            ];

        foreach ($categories['name'] as $categoryName)
        {
            Category::create([
                'name' => $categoryName
            ]);
        }
    }
}
