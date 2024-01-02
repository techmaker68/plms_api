<?php

/**
 * @OA\Schema(
 *     schema="PLMSPassport",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The unique identifier of a passport in the system",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="pax_id",
 *         type="integer",
 *         description="The unique identifier of a pax associated with the passport",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="full_name",
 *         type="string",
 *         description="Full name of the passport holder",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="arab_full_name",
 *         type="string",
 *         description="Full name of the passport holder in Arabic",
 *         example="جون دو"
 *     ),
 *     @OA\Property(
 *         property="passport_no",
 *         type="string",
 *         description="Passport number",
 *         example="E123456789"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="Type of the passport",
 *         example="Regular"
 *     ),
 *     @OA\Property(
 *         property="date_of_issue",
 *         type="string",
 *         format="date",
 *         description="Date of issue of the passport",
 *         example="2023-08-03"
 *     ),
 *     @OA\Property(
 *         property="date_of_expiry",
 *         type="string",
 *         format="date",
 *         description="Expiry date of the passport",
 *         example="2033-08-03"
 *     ),
 *     @OA\Property(
 *         property="birthday",
 *         type="string",
 *         format="date",
 *         description="Birthday of the passport holder",
 *         example="1990-01-01"
 *     ),
 *     @OA\Property(
 *         property="file",
 *         type="text",
 *         description="Image associated with the passport",
 *         example="{app_path}/media/passports/images/5fa2bfc8ad3e7-1604419272.png"
 *     ),
 *     @OA\Property(
 *         property="passport_country",
 *         type="integer",
 *         description="The unique identifier of the country associated with the passport",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="place_of_issue",
 *         type="integer",
 *         description="The unique identifier of the place of issue of the passport",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer",
 *         description="Status of the passport, where 1 is active , 2 is expired , 3 to be renewd & 4 is cancelled",
 *         example=1
 *     ),
 * )
 */