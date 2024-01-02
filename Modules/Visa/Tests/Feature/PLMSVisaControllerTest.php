<?php

namespace Modules\Visa\Tests\Feature;

use Modules\Department\Entities\PLMSDepartment;
use Modules\Passport\Entities\PLMSPassport;
use Modules\Visa\Entities\PLMSVisa;
use Modules\Loi\Entities\PLMSLoi;
use Illuminate\Http\UploadedFile;
use Tests\Feature\PLMSTestCase;


class PLMSVisaControllerTest extends PLMSTestCase
{
    protected $baseRoute;
    protected $permittedUsers = [];
    protected $unpermittedUsers = [];


    protected function setUp(): void
    {
        parent::setUp();
        $this->baseRoute = '/visa';
        $this->permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISAS_GET');
        });
        $this->unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISAS_GET');
        });
    }

    /** @test */
    public function test_get_visas()
    {
        foreach ($this->permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'visa_types_counts',
                ],
            ]);
        }

        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute);
            $response->assertStatus(403);
        }
    }


    // /** with filters */
    public function test_visas_with_valid_filters()
    {
        $filters = [
            'department' => PLMSDepartment::pluck('id')->random(),
            'passport_no' => PLMSPassport::pluck('passport_no')->random(),
            'status' => PLMSVisa::pluck('status')->random(),
        ];

        foreach ($this->permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'visa_types_counts',
                ],
            ]);
        }

        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertStatus(403);
        }
    }

    //  /** invalid filter */
    public function test_visas_with_invalid_filters()
    {
        $filters = [
            'department' => 9999,
            'passport_no' => str_repeat('a', 256),
            'status' => 999,
        ];

        foreach ($this->permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertStatus(422);
        }

        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertStatus(403);
        }
    }

    //  /** @test */
    public function test_visas_with_all_filter()
    {
        foreach ($this->permittedUsers as $user) {
            $responseWithAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute,  ['all' => 'true']);
            $responseWithAll->assertOk();
            $responseDataWithAll = $responseWithAll->json();
            $this->assertIsArray($responseDataWithAll);
            $this->assertArrayHasKey('data', $responseDataWithAll);
            $responseWithAll->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status',
                    ],
                ],
            ]);
            
        }
        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute,  ['all' => 'true']);
            $response->assertStatus(403);
        }
    }

    // /** tests with all check */
    public function test_visas_without_all_filter()
    {
        $filters = [
            'all' => 'false',
        ];
        foreach ($this->permittedUsers as $user) {
            $responseWithoutAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $responseWithoutAll->assertOk();
            $responseDataWithoutAll = $responseWithoutAll->json();
            $this->assertArrayHasKey('data', $responseDataWithoutAll);
            $this->assertArrayHasKey('links', $responseDataWithoutAll);
            $this->assertArrayHasKey('meta', $responseDataWithoutAll);
            $this->assertIsArray($responseDataWithoutAll['data']);
            $this->assertIsArray($responseDataWithoutAll['links']);
            $this->assertIsArray($responseDataWithoutAll['meta']);
            $responseWithoutAll->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'visa_types_counts',
                ],
            ]);
        }
        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertStatus(403);
        }
    }

    // /** show visa */
    public function test_show_visa()
    {
        $visa = PLMSVisa::inRandomOrder()->first();
        foreach ($this->permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $visa->id);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
                ],
            ]);

        }
        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $visa->id);
            $response->assertStatus(403);
        }
    }

    // /** show invalid visa */
    public function test_show_visa_with_invalid_id()
    {
        $invalidId = 9999999999999999;

        $this->assertNull(PLMSVisa::find($invalidId));
        foreach ($this->permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $invalidId);
            $response->assertStatus(422);
        }

        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $invalidId);
            $response->assertStatus(403);
        }
    }

    public function test_show_visa_with_invalid_type()
    {
        $invalidId = 'invalid_id';

        $this->assertNull(PLMSVisa::find($invalidId));

        foreach ($this->permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $invalidId);
            $response->assertStatus(422);
            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertNull($response->json('data'));
        }
        foreach ($this->unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $invalidId);

            $response->assertStatus(403);
        }
    }

    public function test_delete_visa()
    {
        $visa =  $this->createVisa();
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_DELETE');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $visa->id);
            $response->assertOk();
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_DELETE');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/'  . $visa->id);
            $response->assertStatus(403);
        }
    }

    public function test_delete_visa_with_invalid_id()
    {
        $invalidId = 9999;
        $this->assertNull(PLMSVisa::find($invalidId));
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_DELETE');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $invalidId);
            $response->assertStatus(422);
            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertNull(PLMSVisa::find($invalidId));
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_DELETE');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/'  . $invalidId);
            $response->assertStatus(403);
        }
    }

    public function test_delete_visa_with_invalid_type()
    {
        $invalidId = 'invalid_id';

        $this->assertNull(PLMSVisa::find($invalidId));
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_DELETE');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $invalidId);
            $response->assertStatus(422);
            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertNull(PLMSVisa::find($invalidId));
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_DELETE');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/'  . $invalidId);
            $response->assertStatus(403);
        }
    }

    public function test_create_new_visa()
    {
        // Call the visaData function to get visa data
        $visa = $this->visaData();

        $this->assertNotNull($visa);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $visa);
            $response->assertStatus(201);
            $responseData = $response->json('data');
            $this->assertEquals($visa['type'], $responseData['type']);

            $this->assertDatabaseHas('plms_visas', ['type' => $visa['type']]);
            $response->assertJsonStructure([
                'data' => [ 
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
                ],
            ]);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $visa);
            $response->assertStatus(403);
        }
    }

    public function test_create_new_visa_with_validation_errors()
    {
        $invalidVisaData = [];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $invalidVisaData);
            $response->assertStatus(422);
            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertArrayHasKey('message', $responseData);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $invalidVisaData);

            $response->assertStatus(403);
        }
    }

    public function test_create_new_visa_with_file_upload()
    {
        $visaData = $this->visaDataWithFileUpload();

        $this->assertNotNull($visaData);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $visaData);
            $response->assertStatus(201);
            $responseData = $response->json('data');
            $this->assertEquals($visaData['type'], $responseData['type']);
            $this->assertDatabaseHas('plms_visas', ['type' => $visaData['type']]);
            $response->assertJsonStructure([
                'data' => [ 
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
                ],
            ]);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $visaData);
            $response->assertStatus(403);
        }
    }

    public function test_create_new_visa_with_file_upload_validation_error()
    {

        $invalidVisaData = $this->visaDataWithInvalidFile();

        $this->assertNotNull($invalidVisaData);
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $invalidVisaData);
            $response->assertStatus(422);
            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertArrayHasKey('message', $responseData);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_ADD');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $invalidVisaData);
            $response->assertStatus(403);
        }
    }

    // //
    public function visaDataWithFileUpload()
    {
        $pax_id = PLMSVisa::pluck('pax_id')->random();
        $loi_id = PLMSLoi::pluck('id')->random();
        $passport_id = PLMSPassport::pluck('id')->random();
        // Create a temporary file for testing purposes
        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $data = [
            'pax_id' => intval($pax_id),
            'loi_id' => intval($loi_id),
            'passport_id' => intval($passport_id),            'type' => $this->faker->randomElement(['Visitor', '3 months', '6 months', '12 months']),
            'loi_no' => $this->faker->numberBetween(10000, 99999),
            'date_of_issue' => $this->faker->date(),
            'date_of_expiry' => $this->faker->date(),
            'visa_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'file' => $file,
        ];

        return $data;
    }

    public function visaDataWithInvalidFile()
    {
        $pax_id = PLMSVisa::pluck('pax_id')->random();

        // Create an invalid file (exceeding the allowed size)
        $invalidFile = UploadedFile::fake()->create('invalid_document.pdf', 20680); // Exceeds allowed size (in kilobytes)

        $data = [
            'pax_id' => $pax_id,
            'type' => $this->faker->randomElement(['Visitor', '3 months', '6 months', '12 months']),
            'loi_no' => $this->faker->numberBetween(10000, 99999),
            'date_of_issue' => $this->faker->date(),
            'date_of_expiry' => $this->faker->date(),
            'visa_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'file' => $invalidFile,
        ];

        return $data;
    }


    public function visaData()
    {
        $pax_id = PLMSVisa::pluck('pax_id')->random();
        $loi_id = PLMSLoi::pluck('id')->random();
        $passport_id = PLMSPassport::pluck('id')->random();

        // Attempt to generate a unique visa_no
        do {
            $visa_no = (string)$this->faker->numberBetween(1000, 9999);
        } while (PLMSVisa::where('visa_no', $visa_no)->exists());

        $data = [
            'pax_id' => intval($pax_id),
            'loi_id' => intval($loi_id),
            'passport_id' => intval($passport_id),
            'type' => $this->faker->randomElement(['Visitor', '3 months', '6 months', '12 months']),
            'loi_no' => $this->faker->numberBetween(10000, 99999),
            'date_of_issue' => $this->faker->date(),
            'date_of_expiry' => $this->faker->date(),
            'visa_no' => $visa_no,
        ];

        return $data;
    }

    public function test_update_visa()
    {
        $visa = $this->createVisa();
        $updatedVisaData = $this->visaData();
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($permittedUsers as $user)
            $response = $this->actingAs($user, 'api')
                ->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $updatedVisaData);
        $response->assertStatus(200);
        $responseData = $response->json('data');
        $this->assertEquals($updatedVisaData['type'], $responseData['type']);
        $this->assertDatabaseHas('plms_visas', ['id' => $visa->id, 'type' => $updatedVisaData['type']]);
        $response->assertJsonStructure([
            'data' => [ 
                    'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                    'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
            ],
        ]);

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $updatedVisaData);
            $response->assertStatus(403);
        }
    }


    public function test_update_visa_with_validation_error()
    {
        $visa = $this->createVisa();

        $invalidUpdatedVisaData = $this->visaDataWithValidationError();
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($permittedUsers as $user) {

            $response = $this->actingAs($user, 'api')
                ->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $invalidUpdatedVisaData);
            $response->assertStatus(422);
            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertArrayHasKey('message', $responseData);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $invalidUpdatedVisaData);
            $response->assertStatus(403);
        }
    }

    public function visaDataWithValidationError()
    {
        $invalidData = [
            'type' => '',
        ];

        return array_merge($this->visaData(), $invalidData);
    }

    public function test_update_visa_with_file_upload()
    {
        $visa = $this->createVisa();

        $updatedVisaData = $this->visaDataWithFileUpload();

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')
                ->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $updatedVisaData);
            $response->assertStatus(200);
            $responseData = $response->json('data');
            $this->assertEquals($updatedVisaData['type'], $responseData['type']);
            $this->assertDatabaseHas('plms_visas', ['id' => $visa->id, 'type' => $updatedVisaData['type']]);
            $response->assertJsonStructure([
                'data' => [ 
                        'id', 'pax', 'type', 'file', 'visa_image', 'date_of_issue', 'date_of_expiry',
                        'visa_location', 'visa_no', 'expire_in_days', 'reason', 'status_id', 'status', 
                ],
            ]);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $updatedVisaData);
            $response->assertStatus(403);
        }
    }

    public function test_update_visa_with_file_upload_validation_error()
    {
        $visa = $this->createVisa();
        $invalidUpdatedVisaData = $this->visaDataWithInvalidFile();
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')
                ->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $invalidUpdatedVisaData);

            $response->assertStatus(422);

            $responseData = $response->json();
            $this->assertArrayHasKey('error', $responseData);
            $this->assertArrayHasKey('message', $responseData);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('VISA_EDIT');
        });
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $visa->id, $invalidUpdatedVisaData);
            $response->assertStatus(403);
        }
    }

 

    private function createVisa()
    {
        $pax_id = PLMSVisa::pluck('pax_id')->random();
        $visa =  PLMSVisa::create([
            'pax_id' => $pax_id,
            'full_name' => $this->faker->name,
            'date_of_issue' => $this->faker->date(),
            'date_of_expiry' => $this->faker->date(),
            'type' =>  $this->faker->randomElement(['3 months', '6 months', '12 months']),
            'visa_no' => $this->faker->numerify('#########'),
            'passport_no' => $this->faker->numerify('#########')
        ]);
        return $visa;
    }
}
