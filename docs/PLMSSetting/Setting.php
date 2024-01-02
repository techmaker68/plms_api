<?php 
/**
 * @OA\Post(
 *     path="/plms/test_emails", 
 *     tags={"Settings"}, 
 *       security={
 *         {"bearerAuth": {}}
 *     }, 
 *     summary="Send a test email",
 *     description="This endpoint sends a test email to the specified address with batch number and applicants' details.",
 *     operationId="testEmails",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"emails","batch_no"},
 *             @OA\Property(property="emails", type="string", format="string", description="Comma-separated email addresses", example="email1@example.com,email2@example.com"),
 *             @OA\Property(property="batch_no", type="string", example="B12345"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Test Email Sent Successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Test Email Sent Successfully!"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Validation error message here"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/plms/get_settings",
 *     tags={"Settings"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Retrieve settings",
 *     description="Fetches all settings from the PLMSSetting table.",
 *     operationId="getSettings",
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Records found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="blood_test_emails", type="string", example="00:00:00"),
 *             @OA\Property(property="blood_test_email_admins", type="string", example="admin1@example.com,admin2@example.com"),
 *             @OA\Property(property="blood_test_email_status", type="integer", example=1),
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Records Found!"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/plms/save_settings",  
 *     tags={"Settings"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Save Settings",
 *     description="Updates various settings in the PLMSSetting table.",
 *     operationId="saveSettings",
 *     
 *     @OA\Parameter(
 *         name="blood_test_emails",
 *         in="query",
 *         @OA\Schema(
 *             type="string",
 *         ),
 *         description="Blood test email time setting."
 *     ),
 *     @OA\Parameter(
 *         name="blood_test_email_status",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         ),
 *         description="Status for blood test email."
 *     ),
 *     @OA\Parameter(
 *         name="blood_test_email_admins",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *         description="Admin emails for blood test."
 *     ),
 *     @OA\Parameter(
 *         name="loi_admin_emails",
 *         in="query",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         ),
 *         description="LOI admin emails setting."
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Settings updated successfully.",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="blood_test_emails", type="string", description="Blood test email time setting."),
 *             @OA\Property(property="blood_test_email_admins", type="string", description="Admin emails for blood test."),
 *             @OA\Property(property="blood_test_email_status", type="integer", description="Status for blood test email."),
 *             @OA\Property(property="loi_admin_emails", type="string", description="LOI admin emails setting."),
 *             @OA\Property(property="status", type="string", example="success", description="Status of the operation."),
 *             @OA\Property(property="code", type="integer", example=200, description="HTTP status code."),
 *             @OA\Property(property="message", type="string", example="Settings updated Successfully!", description="Response message.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Description of the validation error.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Description of the server error.")
 *         )
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/plms/send_email_to_applicants/{batch_no}",  
 *     tags={"Settings"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Send email to applicants",
 *     description="Send email to applicants based on batch number",
 *     @OA\Parameter(
 *         name="batch_no",
 *         in="path",
 *         description="Batch number of the applicants",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="code", type="integer", example=200),
 *                 @OA\Property(property="message", type="string", example="Email Sent Successfully!")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="code", type="integer", example=500)
 *             )
 *         )
 *     )
 * )
 */

 /**
 * @OA\Get(
 *      path="/plms/get_venues",
 *      operationId="getVenues",
 *      tags={"Settings"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *      summary="Get list of venues",
 *      description="Returns list of venues",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  type="object",
 *                  required={"id", "label"},
 *                  @OA\Property(property="id", type="integer", format="int64", description="ID of the venue", example=1),
 *                  @OA\Property(property="label", type="string", description="Label of the venue", example="J Block")
 *              )
 *          )
 *       ),
 *      @OA\Response(
 *          response=400,
 *          description="Bad Request",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Not Found",
 *      )
 * )
 */

/**
 * @OA\Post(
 *     path="/plms/sort_applicants_by_sequence",
 *     operationId="sortApplicants",
 *     tags={"Settings"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *      summary="Sort applicants based on sequence number",
 *      description="Sorts LOI and Blood applicants based on sequence number",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="applicants", type="array", 
 *                  @OA\Items(type="integer"),
 *                  description="List of LOI applicant IDs"
 *              ),
 *              @OA\Property(property="blood_applicants", type="array", 
 *                  @OA\Items(type="integer"),
 *                  description="List of Blood applicant IDs"
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="data sorted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error"
 *      )
 * )
 */