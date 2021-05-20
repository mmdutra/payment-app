<?php

namespace Database\Factories;

use App\User\Models\Type;
use App\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'document' => $this->faker->cnpj(false),
            'type' => Type::SELLER,
            'password' => $this->faker->password
        ];
    }
}
