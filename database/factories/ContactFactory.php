<?php

namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition(): array
    {
        return [
            'name'  => $this->faker->name(),
            'phone' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'cep'   => $this->faker->postcode(),
        ];
    }
}
