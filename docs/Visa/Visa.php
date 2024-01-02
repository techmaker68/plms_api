<?php
/**
 * @OA\Post(
 *     path="/plms/save_visa",
 *     summary="Save visa details",
 *     description="Save visa details. If 'is_update' is true, it will update an existing visa otherwise a new visa will be created. Pass is_update = 1 when updating the record. While is_update = 1 id field is required.",
 *     operationId="save_visa",
 *     tags={"Visa"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         description="Visa data to be saved",
 *         @OA\JsonContent(
 *             type="object",
 *             required={"passport_no","pax_id"},
 *             @OA\Property(property="passport_no", type="string", description="Passport number"),
 *             @OA\Property(property="pax_id", type="integer", description="Passenger ID"),
 *             @OA\Property(property="is_update", type="boolean", description="Is update or not"),
 *             @OA\Property(property="id", type="integer", description="Visa ID (required if is_update is true)"),
 *             @OA\Property(property="type", type="string", description="Type of visa"),
 *             @OA\Property(property="loi_no", type="string", description="LOI number"),
 *             @OA\Property(property="full_name", type="string", description="Full name on visa"),
 *             @OA\Property(property="date_of_issue", type="string", format="date", description="Date of issue"),
 *             @OA\Property(property="date_of_expiry", type="string", format="date", description="Date of expiry"),
 *             @OA\Property(property="visa_no", type="string", description="Visa number"),
 *             @OA\Property(property="ref_no", type="string", description="Reference number"),
 *             @OA\Property(property="file", type="string", format="binary", description="File to be uploaded")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Visa data saved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="id", type="integer", description="Visa ID")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *     ),
 * )
 */


 /**
 * @OA\Get(
 *     path="/plms/get_visa_list",
 *     summary="Get visa list",
 *     description="Get visa list with optional search, sorting, filtering, and pagination.",
 *     operationId="get_visa_list",
 *     tags={"Visa"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="company_id",
 *         in="query",
 *         description="The company ID for visa filtering",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="A string for searching across multiple fields (pax_id, full_name, type, loi_no, visa_no, passport_no)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Filter by visa status. Multiple status can be passed as comma separated values",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pax_id",
 *         in="query",
 *         description="Filter by Passenger ID",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="passport_no",
 *         in="query",
 *         description="Filter by passport number",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="loi_no",
 *         in="query",
 *         description="Filter by LOI number",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="date_of_expiry",
 *         in="query",
 *         description="Search query to find specific profiles according to the exipry date of their visas",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="sort_by",
 *         in="query",
 *         description="Sort by column",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="order",
 *         in="query",
 *         description="Order by 'asc' or 'desc'",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of records per page",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *      @OA\Parameter(
 *         name="excel_export",
 *         in="query",
 *         description="If true, exports all visas to excel",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *      @OA\Parameter(
 *         name="visa_image",
 *         in="query",
 *         description="If true,  all visas have file",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Visa list retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="id", type="integer", description="Visa ID"),
 *                     @OA\Property(property="pax_id", type="string", description="Passenger ID"),
 *                     @OA\Property(property="full_name", type="string", description="Full name on visa"),
 *                     @OA\Property(property="type", type="string", description="Type of visa"),
 *                     @OA\Property(property="passport_no", type="string", description="Passport number"),
 *                     @OA\Property(property="image", type="boolean", description="Does the passenger have an image"),
 *                     @OA\Property(property="loi_no", type="string", description="LOI number"),
 *                     @OA\Property(property="employer", type="string", description="Employer name"),
 *                     @OA\Property(property="department", type="string", description="Department name"),
 *                     @OA\Property(property="date_of_issue", type="string", format="date", description="Date of issue",format="date"),
 *                     @OA\Property(property="date_of_expiry", type="string", format="date", description="Date of expiry",format="date"),
 *                     @OA\Property(property="expire_in_days", type="integer", description="Number of days remaining until expiry"),
 *                     @OA\Property(property="status", type="string", description="Status of visa"),
 *                 )
 *             ),
 *             @OA\Property(
 *                 property="pagination",
 *                 type="object",
 *                 @OA\Property(property="current_page", type="integer", description="Current page number"),
 *                 @OA\Property(property="per_page", type="integer", description="Number of records per page"),
 *                 @OA\Property(property="total", type="integer", description="Total number of records"),
 *                 @OA\Property(property="last_page", type="integer", description="Last page number"),
 *                 @OA\Property(property="next_page_url", type="string", description="URL of the next page"),
 *                 @OA\Property(property="prev_page_url", type="string", description="URL of the previous page"),
 *             ),
 *             @OA\Property(
 *                 property="visa_types_counts",
 *                 type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="status", type="string", description="Status of visa"),
 *                     @OA\Property(property="count", type="integer", description="Count of visa for the status"),
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Visa list not found",
 *     ),
 * )
 */


/**
 * @OA\Get(
 *     path="/plms/get_visa_detail/{id}",
 *     summary="Retrieve specific visa details",
 *     operationId="get_visa_detail",
 *     tags={"Visa"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the visa to return",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Visa retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="pax_id", type="integer"),
 *                 @OA\Property(property="full_name", type="string"),
 *                 @OA\Property(property="type", type="string"),
 *                 @OA\Property(property="status", type="string"),
 *                 @OA\Property(property="loi_no", type="string"),
 *                 @OA\Property(property="ref_no", type="string"),
 *                 @OA\Property(property="visa_no", type="string"),
 *                 @OA\Property(property="pax_profile", type="string"),
 *                 @OA\Property(property="date_of_issue", type="string",format="date"),
 *                 @OA\Property(property="file", type="text"),
 *                 @OA\Property(property="date_of_expiry", type="string",format="date"),
 *                 @OA\Property(property="passport_no", type="string"),
 *             ),
 *             @OA\Property(
 *                 property="historical_visas",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/PLMSVisa")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Visa not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 * )
 */

 /**
 * @OA\Post(
 *     path="/plms/delete_visa/{id}",
 *     tags={"Visa"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Delete a Visa  by ID",
 *     description="Deletes a single visa based on the ID provided.",
 *     operationId="delete_visa",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the visa to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="visa deleted",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Successful deletion message",
 *                     example="visa Removed"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Pax not found",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="visa not found message",
 *                     example="visa Does not Exists"
 *                 )
 *             )
 *         ),
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/plms/cancel_visa",
 *     tags={"Visa"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Cancel a Visa",
 *     description="Cancel a visa by providing the visa ID and reason for cancellation.",
 *     operationId="cancelVisa",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="visa_id",
 *                     type="integer",
 *                     description="ID of the visa to be cancelled",
 *                     example=1
 *                 ),
 *                 @OA\Property(
 *                     property="reason",
 *                     type="string",
 *                     description="Reason for cancelling the visa",
 *                     example="Incomplete documentation"
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/PLMSVisa"),
 *             @OA\Property(property="status", type="integer", example=1),
 *             @OA\Property(property="message", type="string", example="Visa Cancelled Successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object", example={}),
 *             @OA\Property(property="code", type="integer", example=422),
 *             @OA\Property(property="message", type="string", example="Validation error details.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Record Does Not Exists")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Internal Server Error")
 *         )
 *     )
 * )
 */