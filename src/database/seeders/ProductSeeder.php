<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Faker\Factory as Faker;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');

        $products = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'status' => '販売中',
                'condition' => '良好',
                'image_path' => '/Users/ikedarion/Downloads/Armani+Mens+Clock.jpg',
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'status' => '販売中',
                'condition' => '目立った傷や汚れなし',
                'image_path' => '/Users/ikedarion/Downloads/HDD+Hard+Disk.jpg',
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'status' => '販売中',
                'condition' => 'やや傷や汚れあり',
                'image_path' => '/Users/ikedarion/Downloads/iLoveIMG+d.jpg',
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'status' => '販売中',
                'condition' => '状態が悪い',
                'image_path' => '/Users/ikedarion/Downloads/Leather+Shoes+Product+Photo.jpg',
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'status' => '販売中',
                'condition' => '良好',
                'image_path' => '/Users/ikedarion/Downloads/Living+Room+Laptop.jpg',
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'status' => '販売中',
                'condition' => '目立った傷や汚れなし',
                'image_path' => '/Users/ikedarion/Downloads/Music+Mic+4632231.jpg',
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'status' => '販売中',
                'condition' => 'やや傷や汚れあり',
                'image_path' => '/Users/ikedarion/Downloads/Purse+fashion+pocket.jpg',
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'status' => '販売中',
                'condition' => '状態が悪い',
                'image_path' => '/Users/ikedarion/Downloads/Tumbler+souvenir.jpg',
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'status' => '販売中',
                'condition' => '良好',
                'image_path' => '/Users/ikedarion/Downloads/Waitress+with+Coffee+Grinder.jpg',
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'status' => '販売中',
                'condition' => '目立った傷や汚れなし',
                'image_path' => '/Users/ikedarion/Downloads/外出メイクアップセット.jpg',
            ],
        ];

        foreach ($products as $product) {
            $user = User::inRandomOrder()->first();
            $imageName = $this->saveImage($product['image_path']);

            Product::create([
                'name' => $product['name'],
                'price' => $product['price'],
                'brand_name' => $faker->company(),
                'color' => $faker->colorName(),
                'description' => $product['description'],
                'status' => $product['status'],
                'condition' => $product['condition'],
                'image' => 'storage/images/' . $imageName,
                'user_id' => $user->id,
            ]);
        }
    }

    /**
     * 画像を保存してファイル名を返す
     *
     * @param string $imagePath
     * @return string
     */
    private function saveImage(string $imagePath): string
    {
        $imageName = basename($imagePath);
        Storage::disk('public')->put('images/' . $imageName, file_get_contents($imagePath));
        return $imageName;
    }
}
