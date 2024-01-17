<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nom'    => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'date_naissance'    => '1992-01-12',
            'cin'    => random_int(1111111111,99999999999),
            'lieu_naissance' => 'Toamasina',
            'address' => 'Mahajanga'
        ];
    }
}
