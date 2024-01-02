<?php

namespace Tests\Feature;

use App\Models\PLMSBloodTest;

// class PLMSBloodTestControllerTest extends PLMSTestCase
// {
//     /** @test */
//     public function test_get_test_records()
//     {
//         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . '/get_blood_test_records');
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_get_blood_test_list()
//     {
//         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . '/get_blood_test_list');
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_save_blood_test()
//     {
//         $data = $this->getBloodTestData();

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . '/save_blood_test', $data);
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_update_blood_test()
//     {
//         $bloodTest = PLMSBloodTest::factory()->create();
//         $data = $this->getBloodTestData($bloodTest->id);

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . '/save_blood_test', $data);
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_delete_blood_test()
//     {
//         $bloodTest = PLMSBloodTest::factory()->create();

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . '/delete_blood_test/' . $bloodTest->id);
//         $response->assertStatus(200);
//     }

//     private function getBloodTestData($updateId = null): array
//     {
//         $data = [
//             'is_update' => $updateId !== null,
//             'submit_date' => $this->faker->date('Y-m-d'),
//             'test_date' => $this->faker->date('Y-m-d'),
//             'return_date' => $this->faker->date('Y-m-d'),
//             'venue' => $this->faker->word,
//             'start_time' => $this->faker->time('H:i:s'),
//             'end_time' => $this->faker->time('H:i:s'),
//             'interval' => $this->faker->randomNumber(),
//             'applicants_interval' => $this->faker->randomNumber(),
//         ];

//         if ($updateId !== null) {
//             $data['id'] = $updateId;
//         }

//         return $data;
//     }
// }
