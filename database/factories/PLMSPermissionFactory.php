<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Permission;

class PLMSPermissionFactory extends Factory
{
    protected $model = Permission::class;
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->word,
        ];
    }
}
