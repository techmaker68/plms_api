<?php

namespace Database\Factories;

use App\Models\PLMSCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class PLMSCompanyFactory extends Factory
{
    protected $model = PLMSCompany::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'type' => $this->faker->randomElement(['Partner', 'Owner', 'Contractor','Operator']), // Adjust types as needed
            'status' => $this->faker->numberBetween(1, 2), // Assuming status can be 1, 2, or 3
            'short_name' => $this->faker->word,
            'industry' => $this->faker->word,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'website' => $this->faker->url,
            'address_1' => $this->faker->address,
            'city' => $this->faker->city,
            'country_id' => $this->faker->numberBetween(1, 250), // Assuming there are 250 countries
            'poc_name' => $this->faker->name,
            'poc_email_or_username' => $this->faker->userName,
            'poc_mobile' => $this->faker->phoneNumber,
            'id' => null,

        ];
    }

}
