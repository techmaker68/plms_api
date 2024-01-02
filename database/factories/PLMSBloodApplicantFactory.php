<?php

namespace Database\Factories;

use App\Models\PLMSBloodApplicant;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class PLMSBloodApplicantFactory extends Factory
{
    protected $model = PLMSBloodApplicant::class;

    public function definition()
    {
        $nationalityId = Country::pluck('id')->random();

        return [
            'passport_no' => $this->faker->unique()->numerify('##########'),
            'full_name' => $this->faker->name,
            'nationality' => $nationalityId,
            'employer' => $this->faker->company,
            'batch_no' => $this->faker->numberBetween(1000, 9999),
            'submission_date' => $this->faker->date(),
        ];
    }
}