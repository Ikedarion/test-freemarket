<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;
use App\Models\Product;
use App\Models\User;

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
                'image_path' => '/dummy_images/Armani+Mens+Clock.jpg',
                'color' => 'ゴールド'
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'status' => '販売中',
                'condition' => '目立った傷や汚れなし',
                'image_path' => '/dummy_images/HDD+Hard+Disk.jpg',
                'color' => '黒'
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'status' => '販売中',
                'condition' => 'やや傷や汚れあり',
                'image_path' => '/dummy_images/iLoveIMG+d.jpg',
                'color' => 'ベージュ'
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'status' => '販売中',
                'condition' => '状態が悪い',
                'image_path' => '/dummy_images/Leather+Shoes+Product+Photo.jpg',
                'color' => '黒'
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'status' => '販売中',
                'condition' => '良好',
                'image_path' => '/dummy_images/Living+Room+Laptop.jpg',
                'color' => '黒'
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'status' => '販売中',
                'condition' => '目立った傷や汚れなし',
                'image_path' => '/dummy_images/Music+Mic+4632231.jpg',
                'color' => '黒'
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'status' => '販売中',
                'condition' => 'やや傷や汚れあり',
                'image_path' => '/dummy_images/Purse+fashion+pocket.jpg',
                'color' => '赤'
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'status' => '販売中',
                'condition' => '状態が悪い',
                'image_path' => '/dummy_images/Tumbler+souvenir.jpg',
                'color' => '黒'
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'status' => '販売中',
                'condition' => '良好',
                'image_path' => '/dummy_images/Waitress+with+Coffee+Grinder.jpg',
                'color' => '茶色'
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'status' => '販売中',
                'condition' => '目立った傷や汚れなし',
                'image_path' => '/dummy_images/外出メイクアップセット.jpg',
                'color' => '黒'
            ],
        ];

        foreach ($products as $product) {
            $user = User::inRandomOrder()->first();

            $imagePath = $product['image_path'];
            $imageName = basename($imagePath);

            $directory = 'product_images';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            if (file_exists(public_path($imagePath))) {
                Storage::disk('public')->put($directory . '/' . $imageName, file_get_contents(public_path($imagePath)));
            }

            Product::create([
                'name' => $product['name'],
                'price' => $product['price'],
                'brand_name' => $faker->company(),
                'color' => $product['color'],
                'description' => $product['description'],
                'status' => $product['status'],
                'condition' => $product['condition'],
                'image' => $directory . '/' . $imageName,
                'user_id' => $user->id,
            ]);
        }
    }
}

