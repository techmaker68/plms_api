<?php

namespace Tests\Feature;

use Modules\User\Entities\User;

// class PLMSUserControllerTest extends PLMSTestCase
// {
//     public function test_create_user()
//     {
//         $postData =$this->create_user();
//         $response = $this->actingAs($this->user, 'api')->post('/api/add_user', $postData);
//         $response->assertStatus(201);
//     }
//     public function test_update_user()
//     {
//         $password = $this->faker->password . $this->faker->date();
//         $userData =$this->create_user();
//         $user = User::create($userData);
//         $postData = [
//             'name' => $this->faker->name,
//             'email' =>  $this->faker->email,
//             'password' => $password,
//             'password_confirmation' => $password,
//         ];
//         $response = $this->actingAs($this->user, 'api')->json('Post', '/api/update_user/' . $user->id, $postData);
//         $response->assertStatus(200);
//     }
//     public function test_get_users_list()
//     {
//         $response = $this->actingAs($this->user, 'api')->get('/api/users');
//         $response->assertStatus(200);
//     }
//     public function test_delete_user()
//     {
//         $userData =$this->create_user();
//         $user = User::create($userData);
//         $response = $this->actingAs($this->user, 'api')->post('/api/delete_user/' . $user->id);
//         $response->assertStatus(200);
//     }
//     public function test_show_user()
//     {
//         $userData =$this->create_user();
//         $user = User::create($userData);
//         $response = $this->actingAs($this->user, 'api')->get('/api/show_user/' . $user->id);
//         $response->assertStatus(200);
//     }
    

//     public function create_user()
//     {
//         $password = $this->faker->password . $this->faker->date();
//         $postData = [
//             'name' => $this->faker->name,
//             'email' => $this->faker->email,
//             'password' => $password,
//             'password_confirmation' => $password,
//         ];
//         return  $postData;
//     }
// }
