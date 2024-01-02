<?php

/**
 * @OA\Schema(
 *     schema="PLMSLOIApplicant",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The LOI Applicant ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="pax_id",
 *         type="integer",
 *         description="The Pax ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="batch_no",
 *         type="integer",
 *         description="The unique batch number",
 *         example=1001
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer",
 *         description="The status of the LOI applicant 0 = approved , 1 rejected , 2 cancelled , 3 give up ",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="loi_payment_date",
 *         type="string",
 *         format="date",
 *         description="The date when the LOI payment was made",
 *         example="2023-01-01"
 *     ),
 *      @OA\Property(
 *         property="country_residence",
 *         type="integer",
 *         description="The country_residence of the pax (country ID)",
 *         example=356
 *     ),
 *     @OA\Property(
 *         property="arab_position",
 *         type="string",
 *         description="The arab_position of the pax",
 *         example="Manager"
 *     ),
 *     @OA\Property(
 *         property="position",
 *         type="string",
 *         description="The arab_position of the pax",
 *         example="Manager"
 *     ),
 *     @OA\Property(
 *         property="deposit_amount",
 *         type="integer",
 *         description="The amount of the deposit",
 *         example=500
 *     ),
 *     @OA\Property(
 *         property="loi_payment_receipt_no",
 *         type="integer",
 *         description="The receipt number of the LOI payment",
 *         example=10001
 *     ),
 *     @OA\Property(
 *         property="remarks",
 *         type="string",
 *         description="Remarks regarding the LOI applicant",
 *         example="Additional info..."
 *     ),
 *     @OA\Property(
 *         property="full_name",
 *         type="string",
 *         description="The full name of the LOI applicant",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="arab_full_name",
 *         type="string",
 *         description="The full name of the LOI applicant in Arabic",
 *         example="جون دو"
 *     ),
 *     @OA\Property(
 *         property="passport_no",
 *         type="string",
 *         description="The passport number of the LOI applicant",
 *         example="P1234567"
 *     ),
 *     @OA\Property(
 *         property="nationality",
 *         type="integer",
 *         description="The nationality of the LOI applicant",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="department",
 *         type="string",
 *         description="The department of the LOI applicant",
 *         example="IT"
 *     ),
 *     @OA\Property(
 *         property="employer",
 *         type="string",
 *         description="The employer of the LOI applicant",
 *         example="Company A"
 *     ),
 *     @OA\Property(
 *         property="pax_type",
 *         type="string",
 *         description="The type of the PAX",
 *         example="Type A"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="The email address of the LOI applicant",
 *         example="john@example.com"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="The phone number of the LOI applicant",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="payment_letter_copy",
 *         type="text",
 *         description="The copy of the payment letter",
 *         example="{app_path}/media/loiApplicants/images/payment_letter_copy.png"
 *     )
 * )
 */