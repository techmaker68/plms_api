<?php
/**
 * @OA\Post(
 *     path="/plms/save_blood_test",
 *      operationId="save_blood_test",
 *      tags={"BloodTest"},
 *      summary="Save or update blood test data",
 *      description="Saves a new blood test data or updates existing one based on the is_update flag.",
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              @OA\Property(property="is_update", type="boolean", description="Flag to check if data should be updated. True for update, false for save new."),
 *              @OA\Property(property="id", type="integer", description="ID of the blood test to update. Required if is_update is true."),
 *              @OA\Property(property="submit_date", type="string", format="date", description="Submission date of the blood test."),
 *              @OA\Property(property="test_date", type="string", format="date", description="Date of the blood test."),
 *              @OA\Property(property="return_date", type="string", format="date", description="Return date for the blood test."),
 *              @OA\Property(property="venue", type="string", description="Venue of the blood test."),
 *              @OA\Property(property="start_time", type="string", description="Start time for the blood test."),
 *              @OA\Property(property="end_time", type="string", description="End time for the blood test."),
 *              @OA\Property(property="interval", type="string", description="Intervals for the blood test."),
 *              @OA\Property(property="applicants_interval", type="string", description="Intervals set for applicants.")
 *          ),
 *      ),
 * 
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", description="Success message"),
 *              @OA\Property(property="data", type="integer", description="ID of the saved/updated blood test data.")
 *          )
 *      ),
 * 
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Validation error messages")
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
 * @OA\Get(
 *     path="/plms/get_blood_test_records",
 *     summary="Get Blood Test Records",
 *     description="Fetches a list of blood test records based on search and sorting criteria. Returns paginated results.",
 *     operationId="getBloodTestRecords",
 *      security={
 *         {"bearerAuth": {}}
 *     },
 *     tags={"BloodTest"},
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Filter the results based on passport number, full name, or batch number",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="sort_by",
 *         in="query",
 *         description="Specify the column name for sorting",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="order",
 *         in="query",
 *         description="Specify the order of sorting (asc or desc)",
 *         required=false,
 *         @OA\Schema(type="string", enum={"asc", "desc"}, default="asc")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Specify the number of results per page",
 *         required=false,
 *         @OA\Schema(type="integer", default="25")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="passport_no", type="string"),
 *                     @OA\Property(property="full_name", type="string"),
 *                     @OA\Property(property="nationality", type="string"),
 *                     @OA\Property(property="employer", type="string"),
 *                     @OA\Property(property="batch_no", type="integer"),
 *                     @OA\Property(property="submission_date", type="string", format="date")
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="pagination",
 *                 type="object",
 *                 @OA\Property(property="current_page", type="integer"),
 *                 @OA\Property(property="per_page", type="integer"),
 *                 @OA\Property(property="total", type="integer"),
 *                 @OA\Property(property="last_page", type="integer"),
 *                 @OA\Property(property="next_page_url", type="string"),
 *                 @OA\Property(property="prev_page_url", type="string")
 *             )
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
 *     ),
 * )
 */


/**
 * @OA\Get(
 *     path="/plms/get_blood_test_list",
 *     summary="Get list of blood tests",
 *     description="Fetches a list of blood tests based on provided filters. Returns paginated results or sorted results based on the month of the test date.",
 *     operationId="get_blood_test_list",
 *     tags={"BloodTest"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="batch_no",
 *         in="query",
 *         description="Filter the results based on batch number",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="month",
 *         in="query",
 *         description="Filter the results based on test month in the format 'MM-YYYY'",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Specify the number of results per page",
 *         required=false,
 *         @OA\Schema(type="integer", default="25")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/PLMSBloodTest"),
 *                 description="List of blood tests"
 *             ),
 *             @OA\Property(
 *                 property="pagination",
 *                 type="object",
 *                 @OA\Property(property="current_page", type="integer"),
 *                 @OA\Property(property="per_page", type="integer"),
 *                 @OA\Property(property="total", type="integer"),
 *                 @OA\Property(property="last_page", type="integer"),
 *                 @OA\Property(property="next_page_url", type="string"),
 *                 @OA\Property(property="prev_page_url", type="string")
 *             )
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
 *     ),
 * )
 */


 /**
 * @OA\Post(
 *     path="/plms/delete_blood_test/{id}",
 *     summary="Delete a specific blood test record",
 *     description="Deletes a blood test record identified by its ID. Also deletes associated blood_applicant data if present.",
 *     operationId="delete_blood_test",
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     tags={"BloodTest"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the blood test record to be deleted",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Blood test data deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="ID of the deleted blood test")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Record Does not Exists")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Unexpected error during the delete operation")
 *         )
 *     ),
 * )
 */
