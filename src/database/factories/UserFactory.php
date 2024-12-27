<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $imagesPath = [
            '/Users/ikedarion/Downloads/firstview.jpg',
            '/Users/ikedarion/Downloads/mv.jpg',
            '/Users/ikedarion/Downloads/img/card4.jpg',
            '/Users/ikedarion/Downloads/img/card5.jpg',
        ];

        $imagePath = $this->faker->randomElement($imagesPath);

        $imageName = basename($imagePath);
        Storage::disk('public')->put('images/' . $imageName, file_get_contents($imagePath));

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('Password1'),
            'image' => 'storage/images/' . $imageName,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
