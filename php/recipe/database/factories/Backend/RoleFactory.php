<?php

namespace Database\Factories\Backend;

use App\Models\Backend\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title(),
            'slug' => $this->faker->title(),
            'description' => $this->faker->sentence(),
            'active' => mt_rand(0, 1)
        ];
    }
}
