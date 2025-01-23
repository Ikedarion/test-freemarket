<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');

        User::create([
            'id' => 1,
            'name' => $faker->name,
            'email' => 'test1@example.com',
            'password' => bcrypt('Password'),
            'email_verified_at' => Carbon::now(),
            'is_first_login' => false
        ]);

        User::create([
            'id' => 2,
            'name' => $faker->name,
            'email' => 'test2@example.com',
            'password' => bcrypt('Password'),
            'email_verified_at' => Carbon::now(),
            'is_first_login' => false
        ]);

        User::factory()->count(8)->create([
            'profile_image' => null,
            'is_first_login' => false,
        ]);
    }
}
