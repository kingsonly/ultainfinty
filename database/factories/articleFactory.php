<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class articleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            "image" => "https://via.placeholder.com/150",
            "title" => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            "details" => $this->faker->text($maxNbChars = 200),
            "views" => $this->faker->randomDigit(),
            "like" => $this->faker->randomDigit() ,
           
        ];
    }
}
