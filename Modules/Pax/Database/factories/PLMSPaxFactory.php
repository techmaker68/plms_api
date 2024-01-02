<?php

namespace Module\Pax\Database\Factories;

use App\Models\Country;
use Modules\Pax\Entities\PLMSPax;
use Modules\Company\Entities\PLMSCompany;
use Modules\Department\Entities\PLMSDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PLMSPaxFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PLMSPax::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $nationalityId = Country::pluck('id')->random();
        $companyId = PLMSCompany::pluck('id')->random();
        $departmentId = PLMSDepartment::pluck('id')->random();
    
        return [
            'nationality' => $nationalityId,
            'pax_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'type' => $this->faker->randomElement(['Local', 'Expat']),
            'company_id' => $companyId,
            'employee_no' => $this->faker->numberBetween(10000, 99999),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'arab_full_name' => $this->faker->name,
            'department_id' => $departmentId,
            'position' => $this->faker->jobTitle,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'badge_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'eng_full_name' => $this->faker->name,
            'dob' => $this->faker->date(),
            'status' => $this->faker->randomElement([1, 2]),
            'offboard_date' => $this->faker->date(),
            'arab_position' => $this->faker->jobTitle,
            'country_residence' => $this->faker->numberBetween(1, 150),
            'image' => $this->faker->imageUrl()
        ];
    }
         
}
