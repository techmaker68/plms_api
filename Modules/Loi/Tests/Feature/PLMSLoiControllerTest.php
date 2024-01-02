<?php

namespace Modules\Loi\Tests\Feature;

use App\Models\Country;
use Illuminate\Support\Arr;
use Tests\Feature\PLMSTestCase;
use Modules\Loi\Entities\PLMSLoi;
use Modules\Company\Entities\PLMSCompany;

class PLMSLoiControllerTest extends PLMSTestCase
{
    protected $baseRoute;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseRoute = '/lois';
    }

    /** @test */
    public function test_get_lois()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCHES_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute);
            $response->assertOk();
        }
    }

    /** @test */
    public function test_get_lois_with_valid_filters()
    {
        $statuses = config('loi.statuses');
        $randomStatus = Arr::random($statuses);

        $priorityTypes = config('loi.priority_types');
        $randomPriorityType = Arr::random($priorityTypes);

        $filters = [
            'status' => $randomStatus,
            'priority' => $randomPriorityType,
            'company_id' => PLMSCompany::pluck('id')->random(),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCHES_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertOk();
        }
    }

    /** @test */
    public function test_get_lois_with_invalid_filters()
    {
        $invalidFilters = [
            'company_id' => 1000000 + rand(0, 999999),
            'search' => str_repeat('a', 256),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCHES_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $invalidFilters);
            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_get_lois_with_all_filter()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCHES_GET');
        });

        foreach ($permittedUsers as $user) {
            $responseWithAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, ['all' => 'true']);

            $responseWithAll->assertOk();
            $responseDataWithAll = $responseWithAll->json();
            $this->assertIsArray($responseDataWithAll);
            $this->assertArrayHasKey('data', $responseDataWithAll);
        }
    }

    public function test_get_lois_without_all_filter()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCHES_GET');
        });

        foreach ($permittedUsers as $user) {
            $responseWithoutAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, ['all' => 'false']);

            $responseWithoutAll->assertOk();
            $responseDataWithoutAll = $responseWithoutAll->json();
            $this->assertArrayHasKey('data', $responseDataWithoutAll);
            $this->assertArrayHasKey('links', $responseDataWithoutAll);
            $this->assertArrayHasKey('meta', $responseDataWithoutAll);
            $this->assertIsArray($responseDataWithoutAll['data']);
            $this->assertIsArray($responseDataWithoutAll['links']);
            $this->assertIsArray($responseDataWithoutAll['meta']);
        }
    }

    /** @test */
    public function test_get_lois_without_authentication()
    {
        $response = $this->json('GET', $this->prefix . $this->baseRoute);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_show_loi()
    {
        $loi = $this->createNewloi();

        $this->assertNotNull($loi);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCHES_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $loi->id);

            $response->assertOk();

            $responseData = $response->json('data');
            $this->assertEquals($loi->id, $responseData['id']);
        }
    }

    /** @test */
    public function test_show_invalid_loi()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCHES_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . 999999);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_show_lois_without_authentication()
    {
        $loi = $this->createNewloi();

        $this->assertNotNull($loi);

        $response = $this->json('GET', $this->prefix . $this->baseRoute . '/' . $loi->id);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_store_loi_successfully()
    {
        $validData = $this->getValidData();

        $mandatoryFields = [
            'nation_category' => $validData['nation_category'],
            'loi_type' => $validData['loi_type'],
            'priority' => $validData['priority']
        ];

        unset($validData['nation_category'], $validData['loi_type'], $validData['priority']);

        $numElements = rand(1, count($validData));
        $randomKeys = array_rand($validData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randomValidData = array_intersect_key($validData, array_flip($randomKeys));

        $combinedValidData = array_merge($mandatoryFields, $randomValidData);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_ADD');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $combinedValidData);

            $response->assertStatus(201);
        }
    }


    /** @test */
    public function test_store_invalid_loi()
    {
        $invalidData = $this->getInvalidData();

        $numElements = rand(1, count($invalidData));

        $randomKeys = array_rand($invalidData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randomInvalidData = array_intersect_key($invalidData, array_flip($randomKeys));

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_ADD');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $randomInvalidData);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_update_loi_successfully()
    {
        $loi = $this->createNewLoi();

        $this->assertNotNull($loi);

        $validData = $this->getValidData();

        
        $maxBatchNo = PLMSLoi::max('batch_no');
        $newBatchNo = $maxBatchNo + 1;
        $mandatoryFields = [
            'priority' => $validData['priority'],
            'batch_no' => $newBatchNo
        ];

        unset($validData['priority']);

        $numElements = rand(1, count($validData));
        $randomKeys = array_rand($validData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randomValidData = array_intersect_key($validData, array_flip($randomKeys));

        $combinedValidData = array_merge($mandatoryFields, $randomValidData);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_EDIT');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $loi->id, $combinedValidData);

            $response->assertOk();
        }
    }

    /** @test */
    public function test_update_loi_with_invalid_data()
    {
        $loi = $this->createNewloi();

        $this->assertNotNull($loi);

        $inValidData = $this->getinValidData();

        $numElements = rand(1, count($inValidData));
        $randomKeys = array_rand($inValidData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randominValidData = array_intersect_key($inValidData, array_flip($randomKeys));

        $maxBatchNo = PLMSLoi::max('batch_no');
        $newBatchNo = $maxBatchNo + 1;
        $randominValidData['batch_no'] = intval($newBatchNo);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_EDIT');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix.$this->baseRoute.'/'.$loi->id, $randominValidData);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_update_loi_with_duplicate_unique_field()
    {
        $loiFirst = $this->createNewloi();

        $loiSecond = $this->createNewloi();

        $this->assertNotNull($loiFirst);
        $this->assertNotNull($loiSecond);

        $dataWithDuplicateUniqueField = [
            'batch_no' => $loiSecond->batch_no,
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_EDIT');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix.$this->baseRoute.'/'.$loiFirst->id, $dataWithDuplicateUniqueField);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_delete_loi_successfully()
    {
        $loi = $this->createNewloi();

        $this->assertNotNull($loi);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_DELETE');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix.$this->baseRoute.'/'.$loi->id);

            $response->assertOk();
            $this->assertDatabaseMissing($loi->getTable(), ['id' => $loi->id]);
        }
    }

    /** @test */
    public function test_delete_non_existent_loi()
    {
        $nonExistentLoiId = 1000000 + rand(0, 999999);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_DELETE');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix.$this->baseRoute.'/'.$nonExistentLoiId);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_delete_loi_with_invalid_id()
    {
        $invaLidloiId = 'invalid-id';

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_DELETE');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix.$this->baseRoute.'/'.$invaLidloiId);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_delete_loi_unauthenticated()
    {
        $loi = $this->createNewloi();

        $this->assertNotNull($loi);

        $response = $this->json('DELETE', $this->prefix.$this->baseRoute.'/'.$loi->id);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_renew_loi()
    {
        $loi =  $this->createNewloi();
        $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix.$this->baseRoute.'/renew/'.$loi->batch_no);
        $response->assertStatus(201);
    }

    /** @test */
    public function test_loi_delete_files()
    {
        $loi = PLMSLoi::inRandomOrder()->first();

        $data = [
            'boc_moo_index' => random_int(1, 5),
            'mfd_index' => random_int(1, 5),
            'hq_index' => random_int(1, 5),
            'payment_copy_index' => random_int(1, 5),
            'loi_photo_copy_index' => random_int(1, 5),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_BATCH_DELETE');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix.$this->baseRoute.'/files/delete/'.$loi->id, $data);

            $response->assertStatus(200);
        }
    }

    public function createNewloi()
    {
        $nationalityId = Country::pluck('id')->random();
        $companyId = PLMSCompany::pluck('id')->random();
        $maxBatchNo = PLMSLoi::max('batch_no');
        $newBatchNo = $maxBatchNo + 1;

        return PLMSLoi::create([
            'batch_no' => $newBatchNo,
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
        ]);
    }

    private function getValidData()
    {
        $nation_categories = config('loi.nation_categories');
        $random_nation_category = Arr::random($nation_categories);
        $loi_types = config('loi.loi_types');
        $random_loi_type = Arr::random($loi_types);
        $priority_types = config('loi.priority_types');
        $random_priority_type = Arr::random($priority_types);

        return [
            'nation_category' => $random_nation_category,
            'loi_type' => 1, $random_loi_type,
            'submission_date' => now()->format('Y-m-d'),
            'company_id' => PLMSCompany::pluck('id')->random(),
            'company_address_iraq_ar' => 'Sample Address in Arabic',
            'entry_purpose' => 'Business',
            'entry_type' => 'Single',
            'contract_expiry' => now()->addYear()->format('Y-m-d'),
            'company_address_ar' => 'Sample Company Address in Arabic',
            'company_country' => Country::pluck('id')->random(),
            'mfd_date' => now()->format('Y-m-d'),
            'mfd_ref' => 'MFD123',
            'hq_date' => now()->format('Y-m-d'),
            'hq_ref' => 'HQ123',
            'moo_date' => now()->format('Y-m-d'),
            'moo_ref' => 'MOO123',
            'boc_moo_date' => now()->format('Y-m-d'),
            'boc_moo_ref' => 'BOCMOO123',
            'moi_date' => now()->format('Y-m-d'),
            'moi_ref' => 'MOI123',
            'moi_2_date' => now()->format('Y-m-d'),
            'moi_2_ref' => 'MOI2123',
            'majnoon_date' => now()->format('Y-m-d'),
            'majnoon_ref' => 'MAJ123',
            'moi_payment_date' => now()->format('Y-m-d'),
            'moi_invoice' => 'INV123',
            'moi_deposit' => 'DEP123',
            'loi_issue_date' => now()->format('Y-m-d'),
            'loi_no' => 123,
            'sent_loi_date' => now()->format('Y-m-d'),
            'close_date' => now()->format('Y-m-d'),
            'mfd_submit_date' => now()->format('Y-m-d'),
            'mfd_received_date' => now()->format('Y-m-d'),
            'hq_submit_date' => now()->format('Y-m-d'),
            'hq_received_date' => now()->format('Y-m-d'),
            'boc_moo_submit_date' => now()->format('Y-m-d'),
            'moi_payment_letter_date' => now()->format('Y-m-d'),
            'moi_payment_letter_ref' => 'MOIPL123',
            'expected_issue_date' => now()->format('Y-m-d'),
            'priority' => $random_priority_type
        ];
    }

    private function getInvalidData()
    {
        return [
            'nation_category' => 'invalid',
            'loi_type' => 'invalid',
            'submission_date' => 'invalid-date',
            'company_id' => 9999999,
            'company_address_iraq_ar' => str_repeat('a', 256),
            'entry_purpose' => str_repeat('a', 256),
            'entry_type' => 'invalid-type',
            'contract_expiry' => 'invalid-date',
            'company_address_ar' => str_repeat('a', 256),
            'company_country' => 9999999, 
            'mfd_date' => 'invalid-date',
            'mfd_ref' => str_repeat('a', 256),
            'hq_date' => 'invalid-date',
            'hq_ref' => str_repeat('a', 256),
            'moo_date' => 'invalid-date',
            'moo_ref' => str_repeat('a', 256),
            'boc_moo_date' => 'invalid-date',
            'boc_moo_ref' => str_repeat('a', 256),
            'moi_date' => 'invalid-date',
            'moi_ref' => str_repeat('a', 256),
            'moi_2_date' => 'invalid-date',
            'moi_2_ref' => str_repeat('a', 256),
            'majnoon_date' => 'invalid-date',
            'majnoon_ref' => str_repeat('a', 256),
            'moi_payment_date' => 'invalid-date',
            'moi_invoice' => str_repeat('a', 256),
            'moi_deposit' => str_repeat('a', 256),
            'loi_issue_date' => 'invalid-date',
            'loi_no' => 'invalid', // Invalid type for loi_no
            'sent_loi_date' => 'invalid-date',
            'close_date' => 'invalid-date',
            'mfd_submit_date' => 'invalid-date',
            'mfd_received_date' => 'invalid-date',
            'hq_submit_date' => 'invalid-date',
            'hq_received_date' => 'invalid-date',
            'boc_moo_submit_date' => 'invalid-date',
            'moi_payment_letter_date' => 'invalid-date',
            'moi_payment_letter_ref' => str_repeat('a', 256),
            'expected_issue_date' => 'invalid-date',
            'priority' => 'invalid',
        ];
        
    }
}
