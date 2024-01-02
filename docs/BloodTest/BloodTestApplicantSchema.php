<?php

/**
 * @OA\Schema(
 *     schema="PLMSBloodApplicant",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The applicant ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="pax_id",
 *         type="integer",
 *         description="The associated pax ID",
 *         example=1001
 *     ),
 *     @OA\Property(
 *         property="batch_no",
 *         type="integer",
 *         description="The associated batch number",
 *         example=2001
 *     ),
 *     @OA\Property(
 *         property="arrival_date",
 *         type="string",
 *         format="date",
 *         description="The applicant's arrival date",
 *         example="2023-01-01"
 *     ),
 *     @OA\Property(
 *         property="departure_date",
 *         type="string",
 *         format="date",
 *         description="The applicant's departure date",
 *         example="2023-01-02"
 *     ),
 *     @OA\Property(
 *         property="passport_submit_date",
 *         type="string",
 *         format="date",
 *         description="The date the applicant's passport was submitted",
 *         example="2023-01-03"
 *     ),
 *     @OA\Property(
 *         property="passport_return_date",
 *         type="string",
 *         format="date",
 *         description="The date the applicant's passport was returned",
 *         example="2023-01-04"
 *     ),
 *     @OA\Property(
 *         property="hiv_expire_date",
 *         type="string",
 *         format="date",
 *         description="The hiv_expire_date of blood applicants",
 *         example="2023-01-03"
 *     ),
 *     @OA\Property(
 *         property="hbs_expire_date",
 *         type="string",
 *         format="date",
 *         description="The hbs_expire_date of blood applicants",
 *         example="2023-01-04"
 *     ),
 *     @OA\Property(
 *         property="appoint_date",
 *         type="string",
 *         format="date",
 *         description="The date of the applicant's appointment",
 *         example="2023-01-05"
 *     ),
 *     @OA\Property(
 *         property="appoint_time",
 *         type="string",
 *         format="time",
 *         description="The time of the applicant's appointment",
 *         example="12:00:00"
 *     ),
 *     @OA\Property(
 *         property="attendance",
 *         type="integer",
 *         description="The applicant's attendance status (1= tested , 2=no show , 3=no need to test)",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="test_purposes",
 *         type="string",
 *         description="The purpose of the test",
 *         example="Bloods"
 *     ),
 *     @OA\Property(
 *         property="blood_test_types",
 *         type="string",
 *         description="The types of blood tests (Malaria , HIV ,HBS)",
 *         example="Malaria, HIV"
 *     ),
 *     @OA\Property(
 *         property="remarks",
 *         type="string",
 *         description="Any additional remarks",
 *         example="N/A"
 *     ),
 *     @OA\Property(
 *         property="passport_no",
 *         type="string",
 *         description="The applicant's passport number",
 *         example="EB3456789"
 *     ),
 *     @OA\Property(
 *         property="full_name",
 *         type="string",
 *         description="The applicant's full name",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="passport_country",
 *         type="integer",
 *         description="The ID of the applicant's passport country",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="birthday",
 *         type="string",
 *         format="date",
 *         description="The applicant's date of birth",
 *         example="1990-01-01"
 *     ),
 *     @OA\Property(
 *         property="passport_issue_date",
 *         type="string",
 *         format="date",
 *         description="The date of issue of the applicant's passport",
 *         example="2015-01-01"
 *     ),
 *     @OA\Property(
 *         property="passport_expiry_date",
 *         type="string",
 *         format="date",
 *         description="The expiry date of the applicant's passport",
 *         example="2025-01-01"
 *     ),
 *     @OA\Property(
 *         property="employer",
 *         type="string",
 *         description="The applicant's employer",
 *         example="Company A"
 *     ),
 *     @OA\Property(
 *         property="badge_no",
 *         type="integer",
 *         description="The applicant's badge number",
 *         example=101
 *     ),
 *     @OA\Property(
 *         property="position",
 *         type="string",
 *         description="The applicant's position",
 *         example="Manager"
 *     ),
 *     @OA\Property(
 *         property="department",
 *         type="string",
 *         description="The applicant's department",
 *         example="Department A"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="The applicant's email address",
 *         example="johndoe@example.com"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="The applicant's phone number",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="nationality",
 *         type="integer",
 *         description="The ID of the applicant's nationality",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="scheduled_status",
 *         type="integer",
 *         description="applicant scheduled status",
 *         example=1
 *     ),
 * )
 */

 /**
 * @OA\Schema(
 *     schema="BloodTestInformation",
 *     description="Information related to a specific blood test batch",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The ID of the blood test."
 *     ),
 *     @OA\Property(
 *         property="batch_no",
 *         type="integer",
 *         description="The batch number of the blood test."
 *     ),
 *     @OA\Property(
 *         property="submit_date",
 *         type="string",
 *         format="date",
 *         description="The date the blood test was submitted."
 *     ),
 *     @OA\Property(
 *         property="test_date",
 *         type="string",
 *         format="date",
 *         description="The date the blood test was taken."
 *     ),
 *     @OA\Property(
 *         property="return_date",
 *         type="string",
 *         format="date",
 *         description="The date the blood test results were returned."
 *     ),
 *     @OA\Property(
 *         property="venue",
 *         type="string",
 *         description="The location/venue where the blood test was conducted."
 *     ),
 *     @OA\Property(
 *         property="submitted",
 *         type="boolean",
 *         description="Flag indicating if the blood test was submitted."
 *     ),
 *     @OA\Property(
 *         property="tested",
 *         type="boolean",
 *         description="Flag indicating if the blood test was taken/performed."
 *     ),
 *     @OA\Property(
 *         property="returned",
 *         type="boolean",
 *         description="Flag indicating if the blood test results were returned."
 *     ),
 *     @OA\Property(
 *         property="enabled",
 *         type="boolean",
 *         description="Flag indicating if the test date is in the current week."
 *     )
 * )
 */
/**
 * @OA\Schema(
 *     schema="PaxInformation",
 *     required={"full_name", "pax_id", "passport_no", "badge_no", "position", "department", "employer"},
 *     @OA\Property(property="full_name", type="string", description="Full name of the Pax"),
 *     @OA\Property(property="pax_id", type="integer", description="ID of the Pax"),
 *     @OA\Property(property="passport_no", type="string", description="Passport number of the Pax"),
 *     @OA\Property(property="badge_no", type="integer", description="Badge number of the Pax"),
 *     @OA\Property(property="position", type="string", description="Position of the Pax"),
 *     @OA\Property(property="department", type="string", description="Department of the Pax"),
 *     @OA\Property(property="employer", type="string", description="Employer of the Pax")
 * )
 */

/**
 * @OA\Schema(
 *     schema="BloodTestHistory",
 *     required={"id", "submit_date", "test_date", "return_date", "venue", "appoint_time"},
 *     @OA\Property(property="id", type="integer", description="Blood test ID"),
 *     @OA\Property(property="submit_date", type="string", format="date", description="Date the blood test was submitted"),
 *     @OA\Property(property="test_date", type="string", format="date", description="Date the blood test was taken"),
 *     @OA\Property(property="return_date", type="string", format="date", description="Date the blood test was returned"),
 *     @OA\Property(property="venue", type="string", description="Venue where the test was conducted"),
 *     @OA\Property(property="appoint_time", type="string", description="Time the appointment was scheduled")
 * )
 */
