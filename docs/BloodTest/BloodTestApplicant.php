<?php
/**
 * @OA\Post(
 *     path="/plms/save_blood_applicants",
 *     summary="Save or update blood applicants",
 *     tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Save or update blood applicants details. If 'is_update' is true, it will update an existing applicant otherwise a new blood applicant record will be created. Pass is_update = 1 when updating the record.",
 *     operationId="save_blood_applicants",
 *     @OA\RequestBody(
 *         description="Blood applicant information to save. ",
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/PLMSBloodApplicant")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Blood applicant data saved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="ID of the saved/updated blood applicant")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Validation error messages")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Error message")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Internal server error message")
 *         )
 *     )
 * )
 */


/**
 * @OA\Get(
 *     path="/plms/get_blood_applicants_list",
 *     summary="Fetches list of blood test applicants based on batch number",
 *     description="Retrieves a detailed list of blood test applicants based on a provided batch number.",
 *     operationId="get_blood_applicants_list",
 *     tags={"BloodApplicant"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="batch_no",
 *         in="query",
 *         description="Batch number to filter the results",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Optional search string to filter applicants",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/PLMSBloodApplicant")
 *             ),
 *             @OA\Property(
 *                 property="submission_information",
 *                 type="object",
 *                 ref="#/components/schemas/BloodTestInformation"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="No record found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */


/**
 * @OA\Get(
 *     path="/plms/get_pax_details/{pax_id}",
 *     summary="Retrieve details for a specific Pax",
 *     tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Retrieve detailed Pax information, blood test history, and suggested tests based on nationality and past tests.",
 *     operationId="get_pax_details",
 *     @OA\Parameter(
 *         name="pax_id",
 *         in="path",
 *         description="The Pax ID to fetch details for",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="list", type="object", 
 *                 @OA\Property(property="pax_information", ref="#/components/schemas/PaxInformation"),
 *                 @OA\Property(property="blood_test_history", type="array",
 *                     @OA\Items(ref="#/components/schemas/PLMSBloodApplicant")
 *                 ),
 *                 @OA\Property(property="blood_test_suggestions", type="array",
 *                     @OA\Items(
 *                         @OA\Property(property="label", type="string"),
 *                         @OA\Property(property="default", type="boolean")
 *                     )
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Pax not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Internal server error message")
 *         )
 *     )
 * )
 */


/**
 * @OA\Get(
 *     path="/plms/get_blood_applicant_information/{id}",
 *     summary="Retrieve detailed information for a specific blood applicant",
 *     tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     operationId="get_blood_applicant_information",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="The Blood Applicant ID to fetch details for",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="object",
 *                 @OA\Property(
 *                     property="pax_information",
 *                     type="object",
 *                     @OA\Property(property="full_name", type="string", description="Full name of the applicant"),
 *                     @OA\Property(property="pax_id", type="integer", description="Pax ID"),
 *                     @OA\Property(property="passport_no", type="string", description="Passport number"),
 *                     @OA\Property(property="badge_no", type="string", description="Badge number"),
 *                     @OA\Property(property="position", type="string", description="Position of the applicant"),
 *                     @OA\Property(property="department", type="string", description="Department of the applicant"),
 *                     @OA\Property(property="employer", type="string", description="Employer or company name")
 *                 ),
 *                 @OA\Property(
 *                     property="blood_test_history",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="id", type="integer", description="Blood test ID"),
 *                         @OA\Property(property="submit_date", type="string", format="date", description="Blood test submission date"),
 *                         @OA\Property(property="test_date", type="string", format="date", description="Blood test date"),
 *                         @OA\Property(property="return_date", type="string", format="date", description="Blood test result return date"),
 *                         @OA\Property(property="venue", type="string", description="Venue of the blood test"),
 *                         @OA\Property(property="appoint_time", type="string", format="time", description="Appointment time for blood test")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="penalty",
 *                     type="array",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(property="appoint_date", type="string", format="date", description="Appointment date for the test"),
 *                         @OA\Property(property="penalty_fee", type="string", description="Penalty fee"),
 *                         @OA\Property(property="penalty_remarks", type="string", description="Remarks regarding the penalty")
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="blood_applicant_information",
 *                     type="object",
 *                     description="Detailed information about the blood applicant excluding 'pax'."
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Applicant not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Error message")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Error message")
 *         )
 *     )
 * )
 */


/**
 * @OA\Post(
 *     path="/plms/remove_blood_applicant",
 *     tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Remove a list of blood applicants",
 *     description="Deletes blood applicants based on the provided list of IDs.",
 *     @OA\RequestBody(
 *         description="List of blood applicants to remove",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="blood_applicants", type="array", @OA\Items(type="integer"), description="List of blood applicant IDs")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Applicant Removed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="An error occurred while processing the request")
 *         )
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/plms/update_multiple_blood_applicants",
 *     tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Update multiple blood applicants",
 *     description="Updates multiple blood applicants based on the provided IDs and fields.",
 *     
 *     @OA\RequestBody(
 *         description="Details of the blood applicants to be updated",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="ids", type="string", example="1,2,3,4", description="Comma-separated list of blood applicant IDs"),
 *             @OA\Property(property="blood_test_types", type="string", description="Types of blood tests"),
 *             @OA\Property(property="arrival_date", type="date", description="Arrival date"),
 *             @OA\Property(property="departure_date", type="date", description="Departure date"),
 *             @OA\Property(property="remarks", type="string", description="Remarks about the blood applicant"),
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Blood Applicants data updated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Applicants Does not Exists")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="An error occurred while processing the request")
 *         )
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/plms/blood_applicants_reschedule_time",
 *     tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Reschedule time for multiple blood applicants",
 *     description="Toggles the scheduled_status of multiple blood applicants based on the provided IDs.",
 *     
 *     @OA\RequestBody(
 *         description="IDs of the blood applicants to be rescheduled",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="ids", type="string", example="1,2,3,4", description="Comma-separated list of blood applicant IDs"),
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Blood Applicants data updated.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Applicants Does not Exists")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="An error occurred while processing the request")
 *         )
 *     )
 * )
 */

/**
 * @OA\Post(
 *      path="/plms/renew_blood_applicant_appointment/{id}",
 *      operationId="renew_blood_applicant_appointment",
 *      tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *      summary="Renew blood applicant appointment",
 *      description="Renew the appointment date and remarks for the given blood applicant.",
 *      @OA\Parameter(
 *          name="id",
 *          description="ID of the blood applicant to renew appointment for",
 *          in="path",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 * 
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"batch_no", "new_appoint_date"},
 *              @OA\Property(property="batch_no", type="string", description="Batch number"),
 *              @OA\Property(property="new_appoint_date", type="string", format="date", description="New appointment date"),
 *              @OA\Property(property="new_remarks", type="string", description="New remarks for the applicant appointment")
 *          ),
 *      ),
 * 
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", description="Success message"),
 *          )
 *      ),
 * 
 *      @OA\Response(
 *          response=422,
 *          description="Validation Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="array", 
 *                  @OA\Items(type="string")
 *              )
 *          )
 *      ),
 * 
 *      @OA\Response(
 *          response=500,
 *          description="Server error"
 *      ),
 * )
 */

 /**
 * @OA\Post(
 *      path="/plms/add_blood_applicant_penalty/{id}",
 *      operationId="add_blood_applicant_penalty",
 *      tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *      summary="Add penalty to a blood applicant",
 *      description="Adds a penalty fee and remarks for the given blood applicant.",
 * 
 *      @OA\Parameter(
 *          name="id",
 *          description="ID of the blood applicant to add penalty for",
 *          in="path",
 *          required=true,
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 * 
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"batch_no", "penalty_fee"},
 *              @OA\Property(property="batch_no", type="string", description="Batch number"),
 *              @OA\Property(property="penalty_fee", type="number", description="Penalty fee"),
 *              @OA\Property(property="penalty_remarks", type="string", description="Remarks for the penalty")
 *          ),
 *      ),
 * 
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", description="Success message"),
 *          )
 *      ),
 * 
 *      @OA\Response(
 *          response=422,
 *          description="Validation Error",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(property="message", type="array", 
 *                  @OA\Items(type="string")
 *              )
 *          )
 *      ),
 * 
 *      @OA\Response(
 *          response=500,
 *          description="Server error"
 *      ),
 * )
 */

 /**
 * @OA\Post(
 *      path="/plms/send_blood_applicants_report",
 *      tags={"BloodApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *      summary="Send blood applicants report",
 *      description="This endpoint sends a blood applicants report based on provided applicant IDs.",
 *      operationId="sendBloodApplicantsReport",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="blood_applicants", type="array", @OA\Items(type="integer"), description="List of blood applicant IDs"),
 *              @OA\Property(property="notes", type="string", description="Notes for the report"),
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal Server Error",
 *      )
 * )
 */
