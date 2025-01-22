<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $colors = [
            'name' => [
                'レッド',
                'ピンク',
                'オレンジ',
                'イエロー',
                'グリーン',
                'ブルー',
                'ネイビー',
                'パープル',
                'ブラウン',
                'ブラック',
                'ホワイト',
                'グレー',
                'ベージュ',
                'ゴールド',
                'シルバー',
                'クリア',
                'マルチカラー'
            ],
        ];

        foreach ($colors['name'] as $colorName) {
            Color::create([
                'name' => $colorName
            ]);
        }
    }
}
