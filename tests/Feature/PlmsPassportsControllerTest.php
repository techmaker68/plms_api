<?php

namespace Tests\Feature;

use App\Models\PLMSPassport;
use App\Models\PLMSPax;

// class PLMSPassportsControllerTest extends PLMSTestCase
// {
//     public function test_save_passport()
//     {
//         $pax = PLMSPax::factory()->create();
//         $postData = [
//             'pax_id' => $pax->pax_id,
//             'full_name' => $pax->eng_full_name,
//             'passport_no' => $this->faker->numerify('########'),
//             'type' => $this->faker->randomElement(['O', 'P', 'D']),
//             'date_of_issue' => $this->faker->date(),
//             'date_of_expiry' => $this->faker->date(),
//             'birthday' => $this->faker->date(),
//             'place_of_issue' => 4,
//             'passport_country' => 4,
//             'is_update' => false,
//         ];
//         $response = $this->actingAs($this->user, 'api')->post('/plms/save_passport', $postData);
//         $response->assertStatus(200);
//     }
//     public function test_get_passport_by_id()
//     {
//         $passport = PLMSPassport::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->get('/plms/get_passport_detail/' . $passport->id);
//         $response->assertStatus(200);
//     }

//     public function test_get_passport_list()
//     {
//         $response = $this->actingAs($this->user, 'api')->json('GET', '/plms/get_passport_list');
//         $response->assertStatus(200);
//     }
//     public function test_delete_passport_by_id()
//     {
//         $passport = PLMSPassport::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->get('/plms/get_passport_detail/' . $passport->id);
//         $response->assertStatus(200);
//     }
// }
