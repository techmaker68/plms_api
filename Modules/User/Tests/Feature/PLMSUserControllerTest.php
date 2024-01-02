<?php

namespace Modules\User\Tests\Feature;

use Tests\Feature\PLMSTestCase;
use Modules\User\Entities\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PLMSUserControllerTest extends PLMSTestCase
{
    protected $baseRoute;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseRoute = '/auth/users';
        $this->plms_user = User::inRandomOrder()->where('name','!=' ,'Super Admin')->first();
    }

    public function test_get_users()
    {
        $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.$this->baseRoute);
        $response->assertOk();
    }

    public function test_visas_with_valid_filters()
    {
        $filters = [
            'name' => User::pluck('name')->random(),
            'email' => User::pluck('email')->random(),
        ];

        $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.$this->baseRoute, $filters);
        // if ($response->status() !== 200) {
        //     dd($response->json()); 
        // }
        $response->assertOk();
    }

    /** invalid filter */
    public function test_users_with_invalid_filters()
    {
        $invalidFilters = [
           'email' => str_repeat('a', 256),
        ];

        $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.$this->baseRoute, $invalidFilters);

        $response->assertStatus(422);
    }

    public function test_users_with_all_filter()
    {
        $responseWithAll = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.$this->baseRoute, ['all' => 'true']);

        $responseWithAll->assertOk();
        $responseDataWithAll = $responseWithAll->json();
        $this->assertIsArray($responseDataWithAll);
        $this->assertArrayHasKey('data', $responseDataWithAll);
    }

    /** tests with all check */
    public function test_users_without_all_filter()
    {
        $responseWithoutAll = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.$this->baseRoute, ['all' => 'false']);

        $responseWithoutAll->assertOk();
        $responseDataWithoutAll = $responseWithoutAll->json();
        $this->assertArrayHasKey('data', $responseDataWithoutAll);
        $this->assertArrayHasKey('links', $responseDataWithoutAll);
        $this->assertArrayHasKey('meta', $responseDataWithoutAll);
        $this->assertIsArray($responseDataWithoutAll['data']);
        $this->assertIsArray($responseDataWithoutAll['links']);
        $this->assertIsArray($responseDataWithoutAll['meta']);
    }

     /** show  */
     public function test_show_user()
     { 
         $this->assertNotNull($this->plms_user);
 
         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $this->plms_user->id);
 
         $response->assertOk();
 
         $responseData = $response->json('data');
         $this->assertEquals($this->plms_user->id, $responseData['id']);
     }

     /** show invalid visa */
    public function test_show_user_with_invalid_id()
    {
        $invalidId = 9999;

        $this->assertNull(User::find($invalidId));

        $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $invalidId);

        $response->assertStatus(422);

        $responseData = $response->json();
        $this->assertArrayHasKey('error', $responseData);
    }

    public function test_show_user_with_invalid_type()
    {
        $invalidId = 'invalid_id';

        $this->assertNull(User::find($invalidId));

        $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . $this->baseRoute . '/' . $invalidId);

        $response->assertStatus(422);

        $responseData = $response->json();
        $this->assertArrayHasKey('error', $responseData);

        $this->assertNull($response->json('data'));
    }

    public function test_delete_user()
    {
        $response = $this->actingAs($this->user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $this->plms_user->id);

        $response->assertOk();

        $this->assertNull(User::find($this->plms_user->id));
    }

    public function test_delete_user_with_invalid_id()
    {
        $invalidId = 9999;

        $this->assertNull(User::find($invalidId));

        $response = $this->actingAs($this->user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $invalidId);

        $response->assertStatus(422);

        $responseData = $response->json();
        $this->assertArrayHasKey('error', $responseData);

        $this->assertNull(User::find($invalidId));
    }

    public function test_delete_user_with_invalid_id_type()
    {
        $invalidId = 'invalid_id';

        $this->assertNull(User::find($invalidId));

        $response = $this->actingAs($this->user, 'api')->json('DELETE', $this->prefix . $this->baseRoute . '/' . $invalidId);

        $response->assertStatus(422);

        $responseData = $response->json();
        $this->assertArrayHasKey('error', $responseData);

        $this->assertNull(User::find($invalidId));
    }

    public function test_create_new_user()
    {
        // Call the visaData function to get visa data
        $user = $this->userData();
        $this->assertNotNull($user);
    
        $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . $this->baseRoute, $user);
        // if ($response->status() !== 200) {
        //     dd($response->json()); 
        // }
        $response->assertStatus(201);
    
        $responseData = $response->json('data');
    
    }

    public function test_create_new_user_with_assigning_role()
    {
        // Call the userData function to get user data
        $user = $this->userData();
        $this->assertNotNull($user);

        // Assign a random role to the user
        $roles = Role::pluck('id')->where('name','!=','Super Admin')->random(2)->implode(',');
        $user['role_ids'] = $roles;
        
        // Create a new user and assign roles in a single request
        $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . $this->baseRoute, $user);

        $response->assertStatus(201);

        $responseData = $response->json('data');
        $this->assertArrayHasKey('id', $responseData);
    }

    public function userData()
    {    
        $fakerPassword = Str::random(12); // Generate a random 12-character password
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => $fakerPassword,
            'password_confirmation' => $fakerPassword, // Ensure both passwords are the same
        ];
        return $data;
    }
}
