<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            'user_id'     => User::factory(),
            'amount'      => $this->faker->randomFloat(2, 10, 1000),
            'category'    => $this->faker->randomElement(['comestibles','ocio','electronica','utilidades','ropa','salud','otros']),
            'description' => $this->faker->sentence,
        ];
    }
}
