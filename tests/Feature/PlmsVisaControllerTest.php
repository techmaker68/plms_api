<?php

namespace Tests\Feature;

use App\Models\PLMSPax;
use App\Models\PLMSVisa;

// class PLMSVisaControllerTest extends PLMSTestCase
// {
//     public function test_save_visa()
//     {
//         $response = $this->actingAs($this->user, 'api')->post('/plms/save_visa', [
//             'pax_id' => PLMSPax::pluck('pax_id')->random(),
//             'full_name' => $this->faker->name,
//             'passport_no' => $this->faker->numerify('########'),
//             'type' => $this->faker->randomElement(['O', 'P', 'D']),
//             'date_of_issue' => $this->faker->date(),
//             'date_of_expiry' => $this->faker->date(),
//             'birthday' => $this->faker->date(),
//             'place_of_issue' => 4,
//             'passport_country' => 4,
//         ]);
//         $response->assertStatus(200);
//     }
//     public function test_get_visa_List()
//     {
//         $response = $this->actingAs($this->user, 'api')->json('GET', '/plms/get_visa_list');
//         $response->assertStatus(200);
//     }
//     public function test_get_visa_by_id()
//     {
//         $visa = PLMSVisa::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->get('/plms/get_visa_detail/' . $visa->id);
//         $response->assertStatus(200);
//     }
//     public function test_delete_visa()
//     {
//         $visa = PLMSVisa::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->post('/plms/delete_visa/' . $visa->id);
//         $response->assertStatus(200);
//     }
// }
