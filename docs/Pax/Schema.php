<?php

/**
 * @OA\Schema(
 *     schema="PLMSPax",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The pax ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of pax",
 *         example="local , expat , vip etc"
 *     ),
 *     @OA\Property(
 *         property="pax_id",
 *         type="integer",
 *         description="Unique identifier for the pax",
 *         example=1001
 *     ),
 *     @OA\Property(
 *         property="company_id",
 *         type="integer",
 *         description="The ID of the company associated with the pax",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="dob",
 *         type="string",
 *         format="date",
 *         description="Birthday of the pax",
 *         example="1990-01-01"
 *     ),
 *     @OA\Property(
 *         property="first_name",
 *         type="string",
 *         description="The first name of the pax",
 *         example="John"
 *     ),
 *     @OA\Property(
 *         property="last_name",
 *         type="string",
 *         description="The last name of the pax",
 *         example="Doe"
 *     ),
 *     @OA\Property(
 *         property="eng_full_name",
 *         type="string",
 *         description="The full name of the pax in English",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="arab_full_name",
 *         type="string",
 *         description="The full name of the pax in Arabic",
 *         example="جون دو"
 *     ),
 *     @OA\Property(
 *         property="nationality",
 *         type="integer",
 *         description="The nationality of the pax (country ID)",
 *         example=356
 *     ),
 *     @OA\Property(
 *         property="position",
 *         type="string",
 *         description="The position of the pax",
 *         example="Manager"
 *     ),
 *     @OA\Property(
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
 *         property="offboard_date",
 *         type="integer",
 *         format="date",
 *         description="The offbaord date of pax",
 *         example="2033-08-03"
 *     ),
 *     @OA\Property(
 *         property="department_id",
 *         type="integer",
 *         description="The department of the pax",
 *         example="1"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="The phone number of the pax",
 *         example="1234567890"
 *     ),
 *     @OA\Property(
 *         property="country_code",
 *         type="string",
 *         description="The country code for number of the pax",
 *         example="+92"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="The email address of the pax",
 *         example="johndoe@example.com"
 *     ),
 *     @OA\Property(
 *         property="gender",
 *         type="string",
 *         description="The gender of the pax",
 *         example="Male"
 *     ),
 *     @OA\Property(
 *         property="badge_no",
 *         type="integer",
 *         description="The badge number of the pax",
 *         example=123
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer",
 *         description="The status of the pax (1 for onboard, 2 for offboard)",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="image",
 *         type="text",
 *         description="The URL of the pax's image",
 *         example="{app_path}/media/pax/images/5fa2bfc8ad3e7-1604419272.png"
 *     ),
 * )
 */