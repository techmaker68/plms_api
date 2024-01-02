<?php

namespace Database\Factories;

use App\Models\PLMSPassport;
use App\Models\PLMSPax;
use App\Models\PLMSVisa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PLMSVisaFactory extends Factory
{
    protected $model = PLMSVisa::class;

    public function definition()
    {
        $pax = PLMSPax::factory()->create();
        return [
            'pax_id' => $pax->pax_id,
            'full_name' => $this->faker->name,
            'date_of_issue' => $this->faker->date(),
            'date_of_expiry' => $this->faker->date(),
            'type' =>  $this->faker->randomElement(['3 months', '6 months', '12 months']),
            'visa_no' => $this->faker->numerify('#########'),
            'passport_no' => $this->faker->numerify('#########')

        ];
    }
}
