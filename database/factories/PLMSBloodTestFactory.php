<?php

namespace Database\Factories;

use App\Models\PLMSBloodTest;
use Illuminate\Database\Eloquent\Factories\Factory;

class PLMSBloodTestFactory extends Factory
{
    protected $model = PLMSBloodTest::class;

    public function definition()
    {
        return [
            'batch_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'submit_date' => $this->faker->date(),
            'test_date' => $this->faker->date(),
            'return_date' => $this->faker->date(),
            'venue' => $this->faker->word,
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'applicants_interval' => $this->faker->randomNumber(),
            'interval' => $this->faker->randomNumber(),
        ];
    }
}
