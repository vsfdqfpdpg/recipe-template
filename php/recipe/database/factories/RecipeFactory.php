<?php

namespace Database\Factories;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Recipe::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $path = storage_path('app/avatars');

        return [
            "name" => $this->faker->title(),
            "preserve" => rand(0, 1),
            "image" => "avatars/" . $this->faker->image($path, 640, 480, null, false),
            "description" => $this->faker->sentence(),
            "duration" => mt_rand(1, 120),
            "cooking_style" => $this->faker->randomElement(\COOKING_STYLE),
            "category" => $this->faker->randomElement(array_keys(\CATEGORY)),
            "status" => "PENDING",
            "user_id" => User::factory()->create()->id,
        ];
    }
}
