<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

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
