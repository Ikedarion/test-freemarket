<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $assignments = [
            [
                'product_id' => 1,
                'category_ids' => [5, 12],
            ],[
                'product_id' => 2,
                'category_ids' => [2],
            ],[
                'product_id' => 3,
                'category_ids' => [10],
            ],[
                'product_id' => 4,
                'category_ids' => [1, 5],
            ],[
                'product_id' => 5,
                'category_ids' => [2],
            ],[
                'product_id' => 6,
                'category_ids' => [2],
            ],[
                'product_id' => 7,
                'category_ids' => [1, 4],
            ],[
                'product_id' => 8,
                'category_ids' => [4, 5],
            ],[
                'product_id' => 9,
                'category_ids' => [3, 10],
            ],[
                'product_id' => 10,
                'category_ids' => [1, 6],
            ],
        ];

        foreach ($assignments as $assignment)
        {
            $product = Product::find($assignment['product_id']);
            if ($product)
            {
                foreach ($assignment['category_ids'] as $category_id)
                $product->categories()->attach($category_id);
            }
        }
    }
}
