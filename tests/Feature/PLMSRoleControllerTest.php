<?php

namespace Tests\Feature;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// class PLMSRoleControllerTest extends PLMSTestCase
// {
//     public function test_create_role()
//     {
//         $permissions = Permission::where('guard_name', 'api')->get();
//         $postData = [
//             'name' => $this->faker->name,
//             'display_name' => $this->faker->name,
//             'selectedPermissions' => $permissions->pluck('id'),
//             'guard_name' => 'api'
//         ];
//         $response = $this->actingAs($this->user, 'api')->post('/roles/add_role', $postData);
//         $response->assertStatus(200);
//     }
//     public function test_update_role()
//     {
//         $role = Role::create([
//             'name' => $this->faker->name,
//             'display_name' => $this->faker->name,
//         ]);
//         $postData = [
//             'id' => $role->id,
//             'name' => $this->faker->name,
//             'display_name' =>  $this->faker->name,
//             'guard_name' => 'api'
//         ];
//         $response = $this->actingAs($this->user, 'api')->json('Post', '/roles/update_role', $postData);
//         $response->assertStatus(200);
//     }
//     public function test_get_roles_list()
//     {
//         $response = $this->actingAs($this->user, 'api')->get('/roles/roles_list');
//         $response->assertStatus(200);
//     }
//     public function test_delete_role()
//     {
//         $role = Role::create([
//             'name' => $this->faker->name,
//             'display_name' => $this->faker->name,
//         ]);
//         $response = $this->actingAs($this->user, 'api')->post('/roles/delete/' . $role->id);
//         $response->assertStatus(200);
//     }
// }
