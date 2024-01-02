<?php

namespace Tests\Feature;

use App\Models\PLMSCompany;

// class PLMSCompanyControllerTest extends PLMSTestCase
// {
//     public function test_save_company()
//     {
//         $response = $this->actingAs($this->user, 'api')->post('/plms/save_plms_company', [
//             'name' => $this->faker->word,
//             'type' => $this->faker->randomElement(['Partner', 'Owner', 'Contractor','Operator']),
//             'status' => $this->faker->numberBetween(1, 2),
//             'short_name' => $this->faker->word,
//             'industry' => $this->faker->word,
//             'email' => $this->faker->email,
//             'phone' => $this->faker->phoneNumber,
//             'website' => $this->faker->url,
//             'address_1' => $this->faker->address,
//             'city' => $this->faker->city,
//             'country_id' => $this->faker->numberBetween(1, 250),
//             'poc_name' => $this->faker->name,
//             'poc_email_or_username' => $this->faker->userName,
//             'poc_mobile' => $this->faker->phoneNumber,
//             'id' => null,
//         ]);
      
//         $response->assertStatus(200);
//     }
//     public function test_get_company_list()
//     {
//         $response = $this->actingAs($this->user, 'api')->json('GET', '/plms/plms_company_list');
//         $response->assertStatus(200);
//     }
//     public function test_get_company_by_id()
//     {
//         $company = PLMSCompany::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->get('/plms/plms_company_detail/' . $company->id);
//         $response->assertStatus(200);
//     }
//     public function test_delete_company()
//     {
//         $company = PLMSCompany::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->post('/plms/delete_plms_company/' . $company->id);
//         $response->assertStatus(200);
//     }
// }
