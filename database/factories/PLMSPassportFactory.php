<?php

namespace Database\Factories;

use App\Models\PLMSPassport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PLMSPassportFactory extends Factory
{
    protected $model = PLMSPassport::class;

    public function definition()
    {
        return [
            'pax_id' => 11701,
            'full_name' => $this->faker->name,
            'passport_no' => $this->faker->numerify('########'),
            'type' => $this->faker->randomElement(['O', 'P', 'D']),
            'date_of_issue' => $this->faker->date(),
            'date_of_expiry' => $this->faker->date(),
            'birthday' => $this->faker->date(),
            'place_of_issue' => 4,
            'passport_country' => 4,

        ];
    }

}
