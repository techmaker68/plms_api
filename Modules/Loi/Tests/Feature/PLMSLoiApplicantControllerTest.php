<?php

namespace Modules\Loi\Tests\Feature;

use App\Models\Country;
use Illuminate\Support\Arr;
use Tests\Feature\PLMSTestCase;
use Modules\Loi\Entities\PLMSLoi;
use Modules\Pax\Entities\PLMSPax;
use Modules\Company\Entities\PLMSCompany;
use Modules\Loi\Entities\PLMSLoiApplicant;

class PLMSLoiApplicantControllerTest extends PLMSTestCase
{
    protected $baseRoute;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseRoute = '/loi-applicants';
    }

    /** @test */
    public function test_get_loi_applicants()
    {
        $filters = [
            'batch_no' => PLMSLoiApplicant::pluck('batch_no')->random()
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_MANAGEMENT_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);

            $response->assertOk();
        }
    }

    /** @test */
    public function test_get_loi_applicants_with_invalid_filters()
    {
        $invalidFilters = [
            'batch_no' => 1000000 + rand(0, 999999),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_MANAGEMENT_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $invalidFilters);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_get_lois_with_all_filter()
    {
        $filters = [
            'batch_no' => PLMSLoiApplicant::pluck('batch_no')->random(),
            'all' => 'true'
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_MANAGEMENT_GET');
        });

        foreach ($permittedUsers as $user) {
            $responseWithAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);

            $responseWithAll->assertOk();
            $responseDataWithAll = $responseWithAll->json();
            $this->assertIsArray($responseDataWithAll);
            $this->assertArrayHasKey('data', $responseDataWithAll);
        }
    }

    public function test_get_loi_applicants_without_all_filter()
    {
        $filters = [
            'batch_no' => PLMSLoiApplicant::pluck('batch_no')->random(),
            'all' => 'false'
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_MANAGEMENT_GET');
        });

        foreach ($permittedUsers as $user) {
            $responseWithoutAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);

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
    public function test_get_loi_applicants_without_authentication()
    {
        $response = $this->json('GET', $this->prefix . $this->baseRoute);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_show_loi_applicant()
    {
        $loi_applicant = $this->createNewLoiApplicant();

        $this->assertNotNull($loi_applicant);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_MANAGEMENT_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $loi_applicant->id);

            $response->assertOk();

            $responseData = $response->json();
            $current = $responseData['current_loi_applicant'];
            $this->assertEquals($loi_applicant->id, $current['id']);
            $previous = $responseData['previous_loi_applicant'];
            if ($previous !== null) {
                $this->assertIsArray($previous);
            }
        }
    }

    /** @test */
    public function test_show_invalid_loi_applicant()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('LOI_MANAGEMENT_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . 999999);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_show_loi_applicant_without_authentication()
    {
        $loi_applicant = $this->createNewLoiApplicant();

        $this->assertNotNull($loi_applicant);

        $response = $this->json('GET', $this->prefix . $this->baseRoute . '/' . $loi_applicant->id);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_store_loi_applicant_successfully()
    {
        $validData = $this->getValidData();

        $mandatoryFields = [
            'batch_no' => $validData['batch_no'],
            'pax_id' => $validData['pax_id'],
        ];

        unset($validData['batch_no'], $validData['pax_id']);

        $numElements = rand(1, count($validData));
        $randomKeys = array_rand($validData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randomValidData = array_intersect_key($validData, array_flip($randomKeys));

        $combinedValidData = array_merge($mandatoryFields, $randomValidData);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('BLOOD_TEST_APPLICANT_ADD');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $combinedValidData);
            $response->assertStatus(201);
        }
    }


    /** @test */
    public function test_store_invalid_loi_applicant()
    {
        $invalidData = $this->getInvalidData();

        $numElements = rand(1, count($invalidData));

        $randomKeys = array_rand($invalidData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randomInvalidData = array_intersect_key($invalidData, array_flip($randomKeys));

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('BLOOD_TEST_APPLICANT_ADD');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $randomInvalidData);
            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_update_loi_applicant_successfully()
    {
        $loi_applicant = $this->createNewLoiApplicant();

        $this->assertNotNull($loi_applicant);

        $validData = $this->getValidData();

        
        $mandatoryFields = [
            'batch_no' => $validData['batch_no'],
            'pax_id' => $validData['pax_id'],
        ];

        unset($validData['batch_no'], $validData['pax_id']);

        $numElements = rand(1, count($validData));
        $randomKeys = array_rand($validData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randomValidData = array_intersect_key($validData, array_flip($randomKeys));

        $combinedValidData = array_merge($mandatoryFields, $randomValidData);

        $response = $this->actingAs($this->user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $loi_applicant->id, $combinedValidData);

        $response->assertOk();
    }

    /** @test */
    public function test_update_loi_applicant_with_invalid_data()
    {
        $loi_applicant = $this->createNewLoiApplicant();

        $this->assertNotNull($loi_applicant);

        $inValidData = $this->getinValidData();

        $numElements = rand(1, count($inValidData));
        $randomKeys = array_rand($inValidData, $numElements);

        if (!is_array($randomKeys)) {
            $randomKeys = [$randomKeys];
        }

        $randominValidData = array_intersect_key($inValidData, array_flip($randomKeys));

        $response = $this->actingAs($this->user, 'api')->json('PUT', $this->prefix.$this->baseRoute.'/'.$loi_applicant->id, $randominValidData);

        $response->assertStatus(422);
    }

    /** @test */
    public function test_delete_loi_applicant_successfully()
    {
        $loi_applicant = $this->createNewLoiApplicant();

        $this->assertNotNull($loi_applicant);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', $this->prefix.$this->baseRoute.'/'.$loi_applicant->id);

        $response->assertOk();
        $this->assertDatabaseMissing($loi_applicant->getTable(), ['id' => $loi_applicant->id]);
    }

    /** @test */
    public function test_delete_non_existent_loi_applicant()
    {
        $nonExistentLoiId = 1000000 + rand(0, 999999);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', $this->prefix.$this->baseRoute.'/'.$nonExistentLoiId);

        $response->assertStatus(422);
    }

    /** @test */
    public function test_delete_loi_applicant_with_invalid_id()
    {
        $invaLidloiId = 'invalid-id';

        $response = $this->actingAs($this->user, 'api')->json('DELETE', $this->prefix.$this->baseRoute.'/'.$invaLidloiId);

        $response->assertStatus(422);
    }

    /** @test */
    public function test_delete_loi_applicant_unauthenticated()
    {
        $loi_applicant = $this->createNewLoiApplicant();

        $this->assertNotNull($loi_applicant);

        $response = $this->json('DELETE', $this->prefix.$this->baseRoute.'/'.$loi_applicant->id);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_loi_applicant_approved_email()
    {
        // Create or fetch a LoiApplicant instance
        $loi_applicant = $this->createNewLoiApplicant();

        // Authenticate a user
        $this->actingAs($this->user, 'api');

        // Define the data to be sent with the request, if any
        $requestData = [
            'batch_no' => $loi_applicant->batch_no,
            'to' => 'itssmaaann@gmail.com',
            'subject' => 'Test',
            'content' => 'Testing'
        ];

        $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix.$this->baseRoute.'/approved_email/', $requestData);

        $response->assertStatus(200);
    }

    public function createNewLoiApplicant()
    {
        $pax_id = PLMSPax::pluck('pax_id')->random();
        $batch_no = PLMSLoi::max('batch_no');
        $sequence_no = PLMSLoiApplicant::max('sequence_no');
        $newSequenceNo = $sequence_no + 1;

        return PLMSLoiApplicant::create([
            'batch_no' => $batch_no,
            'sequence_no' => $newSequenceNo,
            'pax_id' => $pax_id,
            'remarks' => $this->faker->sentence,
        ]);
    }

    private function getValidData()
    {

        $pax_id = PLMSPax::pluck('pax_id')->random();
        $batch_no = PLMSLoi::max('batch_no');
        
        return [
            'batch_no' => intval($batch_no),
            'pax_id' => intval($pax_id),
            'remarks' => $this->faker->sentence,
            'deposit_amount' => $this->faker->randomNumber(),
            'loi_payment_date' =>  now()->format('Y-m-d'),
            'status' => $this->faker->randomNumber(),
        ];
    }

    private function getInvalidData()
    {
        return [
            'batch_no' => 9999999,
            'pax_id' => 9999999,
            'remarks' => str_repeat('a', 256),
            'deposit_amount' => 'invalid-type',
            'loi_payment_date' =>  'invalid-date',
            'status' => str_repeat('a', 256),
        ];
    }    
}
