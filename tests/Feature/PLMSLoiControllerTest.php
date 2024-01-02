<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\PLMSLOI;
use App\Models\PLMSCompany;

// class PLMSLoiControllerTest extends PLMSTestCase
// {

//     /** @test */
//     public function test_save_loi()
//     {
//         $data = $this->getLoiData();

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . '/save_loi', $data);
//         $response->assertStatus(200);
//     }

//     public function test_update_loi()
//     {
//         $loi = PLMSLOI::factory()->create();
//         $data = $this->getLoiData($loi->id);

//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . '/save_loi', $data);
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_renew_loi()
//     {
//         $loi = PLMSLOI::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . '/renew_loi/' . $loi->batch_no);
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_get_loi_applications()
//     {
//         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . '/get_loi_applications');
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_get_loi_application_details()
//     {
//         $loi = PLMSLOI::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->json('GET', $this->prefix . '/get_loi_application_details/' . $loi->id);
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_delete_loi()
//     {
//         $loi = PLMSLOI::factory()->create();
//         $response = $this->actingAs($this->user, 'api')->post($this->prefix . '/delete_loi/' . $loi->id);
//         $response->assertStatus(200);
//     }

//     /** @test */
//     public function test_delete_loi_files()
//     {
//         $loi = PLMSLOI::factory()->create();
//         $data = [];
//         $response = $this->actingAs($this->user, 'api')->json('POST', $this->prefix . '/delete_loi_files/' . $loi->id, $data);
//         $response->assertStatus(200);
//     }

//     private function getLoiData($updateId = null): array
//     {
//         $data = [
//             'is_update' => $updateId !== null,
//             'nation_category' => $this->faker->numberBetween(0, 10),
//             'loi_type' => $this->faker->numberBetween(0, 10),
//             'mfd_date' => $this->faker->date(),
//             'mfd_ref' => $this->faker->word,
//             'moo_date' => $this->faker->date(),
//             'boc_moo_date' => $this->faker->date(),
//             'moi_date' => $this->faker->date(),
//             'moi_2_date' => $this->faker->date(),
//             'majnoon_date' => $this->faker->date(),
//             'moo_ref' => $this->faker->word,
//             'majnoon_ref' => $this->faker->word,
//             'moi_2_ref' => $this->faker->word,
//             'moi_ref' => $this->faker->word,
//             'boc_moo_ref' => $this->faker->word,
//             'hq_date' => $this->faker->date(),
//             'hq_ref' => $this->faker->word,
//             'submission_date' => $this->faker->date(),
//             'moi_payment_date' => $this->faker->date(),
//             'moi_invoice' => $this->faker->word,
//             'moi_deposit' => $this->faker->word,
//             'loi_issue_date' => $this->faker->date(),
//             'loi_no' => $this->faker->randomNumber(),
//             'sent_loi_date' => $this->faker->date(),
//             'close_date' => $this->faker->date(),
//             'company_id' => PLMSCompany::pluck('id')->random(),
//             'company_address_iraq_ar' => PLMSCompany::pluck('address_1')->random(),
//             'entry_purpose' => $this->faker->sentence,
//             'entry_type' => $this->faker->word,
//             'contract_expiry' => $this->faker->date(),
//             'company_address_ar' => PLMSCompany::pluck('address_1')->random(),
//             'country_id' => Country::pluck('id')->random(),
//             'loi_photo_copy' => $this->faker->word,
//             'payment_copy' => $this->faker->word,
//             'mfd_copy' => [$this->faker->word],
//             'hq_copy' => [$this->faker->word],
//             'boc_moo_copy' => [$this->faker->word],
//             'priority' => $this->faker->numberBetween(0, 10),
//             'mfd_submit_date' => $this->faker->date(),
//             'mfd_received_date' => $this->faker->date(),
//             'hq_submit_date' => $this->faker->date(),
//             'hq_received_date' => $this->faker->date(),
//             'boc_moo_submit_date' => $this->faker->date(),
//             'moi_payment_letter_date' => $this->faker->date(),
//             'moi_payment_letter_ref' => $this->faker->word,
//             'expected_issue_date' => $this->faker->date(),
//         ];

//         if ($updateId !== null) {
//             $data['id'] = $updateId;
//         }

//         return $data;
//     }
// }
