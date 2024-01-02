<?php
 
namespace Modules\Pax\Tests\Feature;
 
use App\Models\Country;
use Illuminate\Http\UploadedFile;
use Tests\Feature\PLMSTestCase;
use Modules\Pax\Entities\PLMSPax;
use Modules\Company\Entities\PLMSCompany;
use Modules\Department\Entities\PLMSDepartment;
use Modules\Passport\Entities\PLMSPassport;
 
class PLMSPassportControllerTest extends PLMSTestCase
{
    protected $baseRoute;
 
    protected function setUp(): void
    {
        parent::setUp();
        $this->baseRoute = '/passport';
    }
    /** @test */
    public function test_get_passports()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'pax', 'passport_no', 'date_of_issue', 'file',
                        'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                        'type', 'expire_in_days',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'passport_status_counts'
                ],
            ]);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute);
            $response->assertStatus(403);
        }
    }
 
    // /** @test */
    public function test_get_passports_with_valid_filters()
    {
        $filters = [
            'country_residence' => Country::pluck('id')->random(),
            'search' => PLMSPassport::pluck('passport_no')->random(),
            'department_id' => PLMSDepartment::pluck('id')->random(),
            'passport_no' =>  PLMSPassport::pluck('passport_no')->random(),
        ];
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                    '*' => [
                        'id', 'pax', 'passport_no', 'date_of_issue', 'file',
                        'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                        'type', 'expire_in_days',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'passport_status_counts'
                ],
            ]);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $filters);
            $response->assertStatus(403);
        }
    }
 
    // /** @test */
    public function test_get_passports_with_invalid_filters()
    {
        $invalidFilters = [
            'country_residence' => 999,
            'position' => str_repeat('a', 256),
            'department_id' => '23423',
            'passport_no' => 'asdasdasdasd',
        ];
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $invalidFilters);
            $response->assertStatus(422);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, $invalidFilters);
            $response->assertStatus(403);
        } 
    }
 
    // /** @test */
    public function test_get_passports_with_all_filter()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
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
                        'id', 'pax', 'passport_no', 'date_of_issue', 'file',
                        'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                        'type', 'expire_in_days',
                    ],
                ],
            ]);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });
        foreach ($unpermittedUsers as $user) {
            $responseWithAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, ['all' => 'true']);
            $responseWithAll->assertStatus(403);
        } 
    }
 
    public function test_get_passports_without_all_filter()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
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
                        'id', 'pax', 'passport_no', 'date_of_issue', 'file',
                        'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                        'type', 'expire_in_days',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page', 'from', 'last_page', 'links', 'path', 'per_page', 'to', 'total',
                    'passport_status_counts'
                ],
            ]);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });
        foreach ($unpermittedUsers as $user) {
            $responseWithoutAll = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute, ['all' => 'false']);
            $responseWithoutAll->assertStatus(403);
        }
    }

    public function test_get_passports_without_authentication()
    {
        $response = $this->json('GET', $this->prefix . $this->baseRoute);

        $response->assertStatus(401);
    }
    
 
    public function test_create_passport()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $data = $this->generatePassportData();
        // $data['file'] = UploadedFile::fake()->create('document.pdf', 1024); // Size in kilobytes (1MB = 1000KB)
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            // if ($response->status() !== 200) {
            //     dd($response->json()); 
            // }
            $response->assertStatus(201);
            $response->assertJsonStructure([
                'data' => [ 
                    'id', 'passport_no', 'date_of_issue', 'file',
                    'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                    'type', 'expire_in_days',
                ]
            ]);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403);
        }
    }
 
    public function test_validation_for_duplicate_passport_no()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $data = $this->generatePassportData();
        $data['passport_no'] = PLMSPassport::pluck('passport_no')->random();
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(422);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403);
        }
    }
 
    public function test_create_passport_with_invalid_data()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $data = [
            'pax_id' =>  $this->faker->numberBetween(10000, 99999),
            'arab_full_name' => $this->faker->name,
            'full_name' => $this->faker->name,
            'passport_no' => $this->faker->numberBetween(10000, 99999),
            'type' => $this->faker->randomElement(['P', 'O', 'D']),
            'date_of_issue' => $this->faker->date,
            'date_of_expiry' => $this->faker->date,
            'birthday' => $this->faker->date(),
            'place_of_issue' => '3',
            'passport_country' => '3',
            'status' => $this->faker->randomElement([1, 2, 3, 4]),
        ];
        foreach ($permittedUsers as $user) {
    
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(422);
        }
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403);

        }
    }
 
    /** @test */
    public function test_update_passport()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_EDIT');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_EDIT');
        });
        $passport = PLMSPassport::inRandomOrder()->first();
        $data = $this->generatePassportData();
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix .  $this->baseRoute . '/' . $passport->id, $data);
            $response->assertStatus(200);
            $response->assertJsonStructure([
                'data' => [ 
                    'id', 'pax', 'passport_no', 'date_of_issue', 'file',
                    'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                    'type', 'expire_in_days',
                ]
            ]);
        }
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix .  $this->baseRoute . '/' . $passport->id, $data);
            $response->assertStatus(403);

        }

    }
 
    public function test_update_passport_with_invalid_data()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_EDIT');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_EDIT');
        });
        $data = [
            'pax_id' =>  'asdadasd',
            'arab_full_name' => $this->faker->name,
            'full_name' => $this->faker->name,
            'passport_no' => $this->faker->numberBetween(10000, 99999),
            'type' => $this->faker->randomElement(['s', 'T', 'S']),
            'date_of_issue' => $this->faker->date,
            'date_of_expiry' => $this->faker->date,
            'birthday' => $this->faker->date(),
            'place_of_issue' => '3',
        ];
        $passport = PLMSPassport::inRandomOrder()->first();
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix .  $this->baseRoute . '/' . $passport->id, $data);
    
            $response->assertStatus(422);
        }
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('PUT', $this->prefix .  $this->baseRoute . '/' . $passport->id, $data);
            $response->assertStatus(403);

        }
    }
 
    public function test_delete_passport()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_DELETE');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_DELETE');
        });
        foreach ($permittedUsers as $user) {
        $passport = PLMSPassport::inRandomOrder()->first();
        $response = $this->actingAs($user, 'api')->json('Delete', $this->prefix .  $this->baseRoute . '/' . $passport->id);
        $response->assertStatus(200);
        }
        foreach ($unpermittedUsers as $user) {
            $passport = PLMSPassport::inRandomOrder()->first();
            $response = $this->actingAs($user, 'api')->json('Delete', $this->prefix .  $this->baseRoute . '/' . $passport->id);
            $response->assertStatus(403);

        }
    }
 
    public function test_non_existing_passport()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });
        $nonExistingId = 9999;
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->get($this->prefix . $this->baseRoute . '/' . $nonExistingId);
            $response->assertStatus(422);
        }
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->get($this->prefix . $this->baseRoute . '/' . $nonExistingId);
            $response->assertStatus(403);
        }
    }
 
    public function test_missing_required_fields()
    {
        // Test scenario where required fields are missing
        $data = [];
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        foreach ($permittedUsers as $user) {
        $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
        $response->assertStatus(422);
        }
        foreach ($unpermittedUsers as $user) {
        $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
        $response->assertStatus(403);
        }
    }
 
    public function test_invalid_date_format()
    {
        // Test scenario where date fields have an invalid format
        $data = $this->generatePassportData();
        $data['date_of_issue'] = '1/1/2020';
        $data['date_of_expiry'] = '1/1/2020';
        $data['birthday'] = 'invalid-date';
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(422);
        }
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403);
        }
    }
 
    public function test_birthday_in_future()
    {
        // Test scenario where the birthday is in the future
        $data = $this->generatePassportData();
        $data['date_of_issue'] = now()->subDays(10)->format('Y-m-d');
        $data['date_of_expiry'] = now()->addDays(20)->format('Y-m-d');
        $data['birthday'] = now()->addDays(5)->format('Y-m-d');
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(422);
        }
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403);
        }
    }
 
    public function test_invalid_passport_type()
    {
        // Test scenario where an invalid passport type is provided
        $data = $this->generatePassportData();
        $data['type'] = 'Invalid';
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(422);
        }
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403);
        }
    }
 
    public function test_valid_passport_with_File()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
    
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
    
        $data = $this->generatePassportData();
        $data['file'] = UploadedFile::fake()->create('document.pdf', 1024); // Size in kilobytes (1MB = 1000KB)
    
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(201);
            $response->assertJsonStructure([
                'data' => [ 
                        'id', 'passport_no', 'date_of_issue', 'file',
                        'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                        'type', 'expire_in_days',
                ],
            ]);
        }
    
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403); // Assert that the response status is 403 (Forbidden)
        }
    }
    
 
    // public function test_Invalid_Passport_Status()
    // {
    //     // Test scenario where an invalid passport status is provided
    //     $data = $this->generatePassportData();
    //     $data['status'] = 5; // Invalid status
    //     $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
    //     $response->assertStatus(422);
    // }
    public function test_Invalid_pax()
    {
        // Test scenario where an invalid pax_id is provided
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORT_ADD');
        });
        $data['file'] = UploadedFile::fake()->create('document.pdf');
        $data = $this->generatePassportData();
        $data['pax_id'] = 0000000000; // Invalid pax
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(422);
        }
        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('POST', $this->prefix . $this->baseRoute, $data);
            $response->assertStatus(403);
        }
    }

    public function test_show_passport()
    {
        $passport = PLMSPassport::inRandomOrder()->first();

        $this->assertNotNull($passport);

        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $passport->id);
            $response->assertOk();
            $response->assertJsonStructure([
                'data' => [ 
                        'id', 'pax', 'passport_no', 'date_of_issue', 'file',
                        'passport_image', 'date_of_expiry', 'status_id', 'status', 'full_name', 'arab_full_name', 'birthday',
                        'type', 'expire_in_days',
                ]
            ]);
            $responseData = $response->json('data');
            $this->assertEquals($passport->id, $responseData['id']);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $passport->id);
            $response->assertStatus(403);
        }
    }

    public function test_show_invalid_pax()
    {
        $permittedUsers = $this->users->filter(function ($user) {
            return $user->can('PASSPORTS_GET');
        });
        foreach ($permittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . 999999);
            $response->assertStatus(422);
        }

        $unpermittedUsers = $this->users->reject(function ($user) {
            return $user->can('PASSPORTS_GET');
        });

        foreach ($unpermittedUsers as $user) {
            $response = $this->actingAs($user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . 999999);
            $response->assertStatus(403);
        }
    }

    public function test_show_paxes_without_authentication()
    {
        $passport = PLMSPassport::inRandomOrder()->first();

        $this->assertNotNull($passport);

        $response = $this->json('GET', $this->prefix . $this->baseRoute . '/' . $passport->id);

        $response->assertStatus(401);
    }

    private function generatePassportData()
    {
        $pax_id = PLMSPax::pluck('pax_id')->random();
        $place_of_issue = Country::pluck('id')->random();
        $data = [
            'pax_id' => intval($pax_id),
            'arab_full_name' => $this->faker->name,
            'full_name' => $this->faker->name,
            'passport_no' => $this->faker->bothify('??######'),
            'type' => $this->faker->randomElement(['P', 'O', 'D']),
            'date_of_issue' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'date_of_expiry' => $this->faker->dateTimeBetween('-1 month', '-1 day')->format('Y-m-d'),
            'birthday' => $this->faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'place_of_issue' => $place_of_issue,
            'passport_country' => $place_of_issue,
            'status' => $this->faker->randomElement([1, 2, 3, 4]),
        ];
        return $data;
    }
}