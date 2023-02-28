<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\comment>
 */
class commentFactory extends Factory
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
            "subject" => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            "body" => $this->faker->text($maxNbChars = 200),
        ];
    }
}
