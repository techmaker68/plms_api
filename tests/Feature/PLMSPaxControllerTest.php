<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\PLMSPax;
use App\Models\PLMSCompany;
use App\Models\PLMSDepartment;

// class PLMSPaxControllerTest extends PLMSTestCase
// {
//     /** @test */
//     public function test_get_paxes()
//     {
//         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.'/get_plms_profiles');

//         $response->assertOk();
//         $this->assert_valid_pax_Response_structure($response);
//     }

//     /** @test */
//     public function test_get_pax_by_id()
//     {
//         $pax = PLMSPax::factory()->create();

//         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.'/get_plms_profile_by_id/' . $pax->id);
//         $response->assertOk()->assertJsonStructure($this->getPaxProfileJsonStructure());

//         $nonExistingId = 99999;
//         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix.'/get_plms_profile_by_id/' . $nonExistingId);
//         $response->assertOk()->assertJson(['result' => '', 'total' => '', 'status' => 'success', 'message' => 'Records Not Found', 'code' => 200]);
//     }

//     /** @test */
//     public function test_create_pax()
//     {
//         $data = $this->generate_pax_data();

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix.'/save_plms_profile', $data);
//         $response->assertStatus(200)->assertJson(['status' => 'success', 'message' => 'PAX submitted.', 'code' => 200]);
//     }

//     /** @test */
//     public function test_update_pax()
//     {
//         $pax = PLMSPax::factory()->create();
//         $data = $this->generate_pax_data(['id' => $pax->id, 'is_update' => true, 'badge_no' => $pax->badge_no]);

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix.'/save_plms_profile', $data);
//         $response->assertStatus(200)->assertJson(['status' => 'success', 'message' => 'PAX updated.', 'code' => 200]);
//     }

//     /** @test */
//     public function test_validation_for_duplicate_badge_no()
//     {
//         $existingPax = PLMSPax::factory()->create(['badge_no' => '12345']);
//         $data = $this->generate_pax_data(['badge_no' => '12345']);

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix.'/save_plms_profile', $data);
//         $response->assertStatus(422)->assertJson(['status' => 'failed', 'message' => ['badge_no' => ['The badge no has already been taken.']], 'code' => 422]);
//     }

//     /** @test */
//     public function test_delete_pax()
//     {
//         $pax = PLMSPax::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->post($this->prefix.'/delete_plms_profile/' . $pax->id);
//         $response->assertOk()->assertJson(['result' => $pax->id, 'status' => 'success', 'message' => 'Pax Removed', 'code' => 200]);
//         $this->assertDatabaseMissing('plms_paxes', ['id' => $pax->id]);
//     }

//     /** @test */
//     public function test_non_existing_pax()
//     {
//         $nonExistingId = 9999;
//         $response = $this->actingAs($this->user, 'api')->post($this->prefix.'/delete_plms_profile/' . $nonExistingId);
//         $response->assertOk()->assertJson(['result' => '', 'status' => 'success', 'message' => 'Pax Does not Exists', 'code' => 200]);
//     }

//     protected function generate_pax_data($overrides = [])
//     {
//         return array_merge([
//             'type' => 'Local',
//             'first_name' => $this->faker->firstName,
//             'last_name' => $this->faker->lastName,
//             'company_id' => PLMSCompany::pluck('id')->random(),
//             'badge_no' => $this->faker->unique()->numberBetween(1000, 9999),
//             'eng_full_name' => $this->faker->name,
//             'arab_full_name' => 'جون دو',
//             'nationality' => Country::pluck('id')->random(),
//             'position' => $this->faker->jobTitle,
//             'country_residence' => $this->faker->numberBetween(1, 150),
//             'arab_position' => 'الموقع العربي',
//             'department_id' => PLMSDepartment::pluck('id')->random(),
//             'email' => $this->faker->safeEmail,
//             'dob' => $this->faker->date(),
//             'phone' => $this->faker->phoneNumber,
//             'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
//             'status' => $this->faker->randomElement([1, 2]),
//             'offboard_date' => $this->faker->date()
//         ], $overrides);
//     }

//     protected function assert_valid_pax_Response_structure($response)
//     {
//         $response->assertJsonStructure([
//             'result' => [
//                 'list' => [
//                     '*' => [
//                         'id', 'company_id', 'pax_id', 'eng_full_name', 'first_name', 'arab_full_name', 'phone',
//                         'email', 'status', 'offboard_date', 'gender', 'dob', 'last_name', 'type', 'badge_no',
//                         'arab_position', 'country_residence', 'image', 'position', 'department', 'employer',
//                         'passport_exist', 'visa_exist', 'visa_expire_date', 'passport_no', 'nationality',
//                     ]
//                 ],
//                 'pagination', 'types_counts', 'status_count'
//             ],
//             'total', 'status', 'message', 'code'
//         ]);
//     }

//     protected function getPaxProfileJsonStructure()
//     {
//         return [
//             'result' => [
//                 'pax_profile' => [
//                     'pax_id', 'type', 'company_id', 'employer', 'employee_no', 'first_name', 'last_name',
//                     'nationality', 'nationality_label', 'arab_full_name', 'department_label', 'department',
//                     'position', 'email', 'phone', 'badge_no', 'gender', 'id', 'eng_full_name', 'dob', 'status',
//                     'offboard_date', 'arab_position', 'country_residence', 'image',
//                 ],
//                 'passports', 'visa', 'blood_test', 'loi'
//             ],
//             'total', 'status', 'message', 'code'
//         ];
//     }
// }