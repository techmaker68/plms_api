<?php

namespace Database\Factories;

use App\Models\Country;
use Modules\Loi\Entities\PLMSLoi;
use Modules\Company\Entities\PLMSCompany;
use Illuminate\Database\Eloquent\Factories\Factory;

class PLMSLOIFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PLMSLoi::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $nationalityId = Country::pluck('id')->random();
        $companyId = PLMSCompany::pluck('id')->random();
    
        return [
            'batch_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'nation_category' => $nationalityId,
            'loi_type' => $this->faker->numberBetween(1, 3), // 1 = 3 Months, 2 = 6 Months, 3 = 12 Months
            'submission_date' => $this->faker->date(),
            'mfd_date' => $this->faker->date(),
            'mfd_ref' => $this->faker->word,
            'hq_date' => $this->faker->date(),
            'hq_ref' => $this->faker->word,
            'boc_moo_date' => $this->faker->date(),
            'boc_moo_ref' => $this->faker->word,
            'moo_date' => $this->faker->date(),
            'moo_ref' => $this->faker->word,
            'moi_date' => $this->faker->date(),
            'moi_ref' => $this->faker->word,
            'moi_2_date' => $this->faker->date(),
            'moi_2_ref' => $this->faker->word,
            'majnoon_date' => $this->faker->date(),
            'majnoon_ref' => $this->faker->word,
            'moi_payment_date' => $this->faker->date(),
            'loi_photo_copy' => $this->faker->imageUrl(),
            'payment_copy' => $this->faker->imageUrl(),
            'moi_invoice' => $this->faker->word,
            'moi_deposit' => $this->faker->randomNumber(),
            'loi_issue_date' => $this->faker->date(),
            'loi_no' => $this->faker->randomNumber(),
            'sent_loi_date' => $this->faker->date(),
            'close_date' => $this->faker->date(),
            'company_id' => $companyId,
            'company_address_iraq_ar' => $this->faker->address,
            'entry_purpose' => $this->faker->sentence,
            'company_address_ar' => $this->faker->address,
            'contract_expiry' => $this->faker->date(),
            'entry_type' => $this->faker->word,
            'mfd_copy' => $this->faker->imageUrl(),
            'hq_copy' => $this->faker->imageUrl(),
            'boc_moo_copy' => $this->faker->imageUrl(),
            'priority' => $this->faker->numberBetween(1, 3), // 1 = low, 2 = medium, 3 = high
            'mfd_submit_date' => $this->faker->date(),
            'mfd_received_date' => $this->faker->date(),
            'hq_submit_date' => $this->faker->date(),
            'hq_received_date' => $this->faker->date(),
            'boc_moo_submit_date' => $this->faker->date(),
            'moi_payment_letter_date' => $this->faker->date(),
            'moi_payment_letter_ref' => $this->faker->word,
            'expected_issue_date' => $this->faker->date()
        ];
    }
}
