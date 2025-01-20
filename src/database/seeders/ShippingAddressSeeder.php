<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\ShippingAddress;
use App\Models\User;


class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');
        $users = User::all();

        foreach($users as $user) {
            $postalCode = sprintf('%03d-%04d', mt_rand(100, 999), mt_rand(100, 999));
            ShippingAddress::create([
                'user_id' => $user->id,
                'postal_code' => $postalCode,
                'address' => $faker->prefecture . $faker->city . $faker->streetAddress,
                'building_name' => $faker->secondaryAddress,
            ]);
        }
    }
}
