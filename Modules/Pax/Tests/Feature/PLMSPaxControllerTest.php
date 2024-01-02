<?php

namespace Modules\Pax\Tests\Feature;

use App\Models\Country;
use Illuminate\Support\Arr;
use Modules\Company\Entities\PLMSCompany;
use Modules\Department\Entities\PLMSDepartment;
use Modules\Pax\Entities\PLMSPax;
use Tests\Feature\PLMSTestCase;

class PLMSPaxControllerTest extends PLMSTestCase
{
    protected $baseRoute;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseRoute = '/paxes';
    }

    /** @test */
    public function test_get_paxes()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'company', 'lois', 'blood_tests', 'department', 'nationality', 'country_code',
                        'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone', 'email', 'status',
                        'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no', 'arab_position', 'file', 'position',
                        'passport_exist', 'visa_exist', 'created_at',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'type_counts', 'status_counts',
                ],
            ]);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute);
            $response->assertStatus(403);
        }
    }

    /** @test */
    public function test_get_paxes_with_valid_filters()
    {
        $filters = [
            'country_residence' => Country::pluck('id')->random(),
            'position' => PLMSPax::pluck('position')->random(),
            'company_id' => PLMSCompany::pluck('id')->random(),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAXES_GET');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'company', 'lois', 'blood_tests', 'department', 'nationality', 'country_code',
                        'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone', 'email', 'status',
                        'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no', 'arab_position', 'file', 'position',
                        'passport_exist', 'visa_exist', 'created_at',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'type_counts', 'status_counts',
                ],
            ]);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertStatus(403);
        }

    }

    /** @test */
    public function test_get_paxes_with_invalid_filters()
    {
        $invalidFilters = [
            'country_residence' => 999,
            'position' => str_repeat('a', 256),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAXES_GET');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $invalidFilters);
            $response->assertStatus(422);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $invalidFilters);
            $response->assertStatus(403);
        }
    }

    /** @test */
    public function test_get_paxes_with_all_filter()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAXES_GET');
        });
        foreach ($permittedUsers as $user) {
            $responseWithAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, ['all' => 'true']);
            $responseWithAll->assertOk();
            $responseDataWithAll = $responseWithAll->json();
            $this->assertIsArray($responseDataWithAll);
            $this->assertArrayHasKey('data', $responseDataWithAll);
            $responseWithAll->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'company', 'lois', 'blood_tests', 'department', 'nationality', 'country_code',
                        'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone', 'email', 'status',
                        'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no', 'arab_position', 'file', 'position',
                        'passport_exist', 'visa_exist', 'created_at',
                    ],
                ],
            ]);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $responseWithAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, ['all' => 'true']);
            $responseWithAll->assertStatus(403);
        }
    }

    public function test_get_paxes_without_all_filter()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAXES_GET');
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
            $responseWithoutAll->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'company', 'lois', 'blood_tests', 'department', 'nationality', 'country_code',
                        'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone', 'email', 'status',
                        'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no', 'arab_position', 'file', 'position',
                        'passport_exist', 'visa_exist', 'created_at',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'type_counts', 'status_counts',
                ],
            ]);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $responseWithoutAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, ['all' => 'false']);
            $responseWithoutAll->assertStatus(403);
        }
    }

    /** @test */
    public function test_get_paxes_without_authentication()
    {
        $response = $this->json('GET', $this->prefix . $this->baseRoute);

        $response->assertStatus(401);
    }

/** @test */
    public function test_show_pax()
    {
        $pax = $this->createNewPax();

        $this->assertNotNull($pax);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAXES_GET');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $pax->id);
            $response->assertOk();

            $response->assertJsonStructure([
                'data' => [
                    'id', 'company', 'passports', 'visas', 'lois', 'blood_tests', 'department', 'nationality', 'country_residence',
                    'country_code', 'latest_loi', 'latest_passport', 'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone',
                    'email', 'status', 'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no', 'arab_position',
                    'file', 'position', 'passport_exist', 'visa_exist', 'created_at',
                ],
            ]);

            $responseData = $response->json('data');
            $this->assertEquals($pax->id, $responseData['id']);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $pax->id);
            $response->assertStatus(403);
        }
    }

    /** @test */
    public function test_show_invalid_pax()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAXES_GET');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . 999999);
            $response->assertStatus(422);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAXES_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . 999999);
            $response->assertStatus(403);
        }
    }

    /** @test */
    public function test_show_paxes_without_authentication()
    {
        $pax = $this->createNewPax();

        $this->assertNotNull($pax);

        $response = $this->json('GET', $this->prefix . $this->baseRoute . '/' . $pax->id);

        $response->assertStatus(401);
    }

    /** @test */
    public function test_store_pax_successfully()
    {
        $statuses = config('pax.pax_statuses');
        $randomStatus = Arr::random($statuses);
        $validData = [
            'nationality' => Country::pluck('id')->random(),
            'pax_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'type' => $this->faker->randomElement(['Local', 'Expat']),
            'company_id' => PLMSCompany::pluck('id')->random(),
            'employee_no' => $this->faker->numberBetween(10000, 99999),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'arab_full_name' => $this->faker->name,
            'department_id' => PLMSDepartment::pluck('id')->random(),
            'position' => $this->faker->jobTitle,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'badge_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'eng_full_name' => $this->faker->name,
            'dob' => $this->faker->date(),
            'status' => $randomStatus,
            'offboard_date' => $this->faker->date(),
            'arab_position' => $this->faker->jobTitle,
            'country_residence' => Country::pluck('id')->random(),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $validData);
            $response->assertStatus(201);
            $response->assertJsonStructure([
                'data' => [
                    'id', 'lois', 'blood_tests', 'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone', 
                    'email', 'status', 'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no', 'arab_position', 
                    'file', 'position', 'passport_exist', 'visa_exist', 'created_at'
                ]
            ]);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PAX_ADD');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $validData);
            $response->assertStatus(403);
        }
    }

    /** @test */
    public function test_store_invalid_pax()
    {
        $invalidData = [
            'nationality' => 1000000 + rand(0, 999999),
            'pax_id' => $this->faker->unique()->numberBetween(100000, 999999),
            'type' => $this->faker->randomElement(['Local', 'Expat']),
            'company_id' => 1000000 + rand(0, 999999),
            'employee_no' => $this->faker->numberBetween(100000, 999999),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'arab_full_name' => $this->faker->name,
            'department_id' => 1000000 + rand(0, 999999),
            'position' => $this->faker->jobTitle,
            'email' => 'invalid' . $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'badge_no' => $this->faker->unique()->numberBetween(100000, 999999),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'eng_full_name' => $this->faker->name,
            'dob' => $this->faker->date(),
            'status' => $this->faker->randomElement([1, 2]),
            'offboard_date' => $this->faker->date(),
            'arab_position' => $this->faker->jobTitle,
            'country_residence' => 1000000 + rand(0, 999999),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $invalidData);
            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_update_pax_successfully()
    {
        $pax = $this->createNewPax();

        $this->assertNotNull($pax);

        $statuses = config('pax.pax_statuses');
        $randomStatus = Arr::random($statuses);

        $validUpdateData = [
            'nationality' => Country::pluck('id')->random(),
            'pax_id' => $this->faker->unique()->numberBetween(1000, 9999),
            'type' => $this->faker->randomElement(['Local', 'Expat']),
            'company_id' => PLMSCompany::pluck('id')->random(),
            'employee_no' => $this->faker->numberBetween(10000, 99999),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'arab_full_name' => $this->faker->name,
            'department_id' => PLMSDepartment::pluck('id')->random(),
            'position' => $this->faker->jobTitle,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'badge_no' => $this->faker->unique()->numberBetween(1000, 9999),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'eng_full_name' => $this->faker->name,
            'dob' => $this->faker->date(),
            'status' => $randomStatus,
            'offboard_date' => $this->faker->date(),
            'arab_position' => $this->faker->jobTitle,
            'country_residence' => Country::pluck('id')->random(),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_EDIT');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $pax->id, $validUpdateData);

            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                        'id', 'company', 'lois', 'blood_tests', 'department', 'nationality', 'country_code',
                        'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone', 'email', 'status',
                        'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no', 'arab_position', 'file', 'position',
                        'passport_exist', 'visa_exist', 'created_at',
                ],
            ]);
        }
    }

    /** @test */
    public function test_update_pax_with_invalid_data()
    {
        $pax = $this->createNewPax();

        $this->assertNotNull($pax);

        $invalidUpdateData = [
            'nationality' => 1000000 + rand(0, 999999),
            'pax_id' => $this->faker->unique()->numberBetween(100000, 999999),
            'type' => $this->faker->randomElement(['Local', 'Expat']),
            'company_id' => 1000000 + rand(0, 999999),
            'employee_no' => $this->faker->numberBetween(100000, 999999),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'arab_full_name' => $this->faker->name,
            'department_id' => 1000000 + rand(0, 999999),
            'position' => $this->faker->jobTitle,
            'email' => 'invalid' . $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'badge_no' => $this->faker->unique()->numberBetween(100000, 999999),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'eng_full_name' => $this->faker->name,
            'dob' => $this->faker->date(),
            'status' => $this->faker->randomElement([1, 2]),
            'offboard_date' => $this->faker->date(),
            'arab_position' => $this->faker->jobTitle,
            'country_residence' => 1000000 + rand(0, 999999),
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_EDIT');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $pax->id, $invalidUpdateData);
            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_update_pax_with_duplicate_unique_field()
    {
        $paxFirst = $this->createNewPax();

        $paxSecond = $this->createNewPax();

        $this->assertNotNull($paxFirst);
        $this->assertNotNull($paxSecond);

        $dataWithDuplicateUniqueField = [
            'email' => $paxSecond->email,
        ];

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_EDIT');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix . $this->baseRoute . '/' . $paxFirst->id, $dataWithDuplicateUniqueField);
            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_delete_pax_successfully()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_DELETE');
        });
        foreach ($permittedUsers as $user) {
            $pax = $this->createNewPax();

            $this->assertNotNull($pax);

            $response = $this->actingAs($this->user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $pax->id);

            $response->assertOk();
            $this->assertDatabaseMissing($pax->getTable(), ['id' => $pax->id]);
        }
    }

    /** @test */
    public function test_delete_non_existent_pax()
    {
        $nonExistentPaxId = 1000000 + rand(0, 999999);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_DELETE');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $nonExistentPaxId);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_delete_pax_with_invalid_id()
    {
        $invalidPaxId = 'invalid-id';

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PAX_DELETE');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $invalidPaxId);

            $response->assertStatus(422);
        }
    }

    /** @test */
    public function test_delete_pax_unauthenticated()
    {
        $pax = $this->createNewPax();

        $this->assertNotNull($pax);

        $response = $this->json('DELETE', $this->prefix . $this->baseRoute . '/' . $pax->id);

        $response->assertStatus(401);
    }

    public function createNewPax()
    {
        $nationalityId = Country::pluck('id')->random();
        $companyId = PLMSCompany::pluck('id')->random();
        $departmentId = PLMSDepartment::pluck('id')->random();

        return PLMSPax::create([
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
            'country_residence' => Country::pluck('id')->random(),
            'image' => $this->faker->imageUrl(),
        ]);
    }

    public function test_get_paxes_without_passport_visa_badge()
    {
        $filters = [
            'pax_without_passport' => 'true',
            'pax_without_visa' => 'true',
            'pax_without_badge' => 'true',
        ];

        $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);

        $response->assertOk();
    }
}
