<?php
/**
 * @OA\Post(
 *     path="/plms/save_passport",
 *     tags={"Passport"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Save a passport",
 *     description="Creates or updates a passport depending on the is_update field in the request. pass is_update = 1 when updating the record. While is_update = 1 id field is required.",
 *     operationId="save_passport",
 *     @OA\RequestBody(
 *         description="Passport data that needs to be saved",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 required={"passport_no"},
 *                 @OA\Property(property="pax_id", type="integer", example=1),
 *                 @OA\Property(property="passport_no", type="string", example="123456789"),
 *                 @OA\Property(property="date_of_issue", type="string", example="2023-08-03"),
 *                 @OA\Property(property="date_of_expiry", type="string", example="2023-10-03"),
 *                 @OA\Property(property="full_name", type="string", example="John Doe"),
 *                 @OA\Property(property="arab_full_name", type="string", example="جون دو"),
 *                 @OA\Property(property="passport_country", type="integer", example=1),
 *                 @OA\Property(property="birthday", type="string", example="1990-01-01"),
 *                 @OA\Property(property="type", type="string", example="Regular"),
 *                 @OA\Property(property="place_of_issue", type="integer", example=1),
 *                 @OA\Property(property="status", type="integer", example=1),
 *                 @OA\Property(property="file", type="string", example="path_to_file"),
 *                 @OA\Property(property="is_update", type="boolean", example=false)
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Passport data saved",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="message", type="string", example="Passport data submitted.")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Invalid input",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="message", type="string", example="Invalid data provided.")
 *             )
 *         )
 *     )
 * )
 */


 /**
 * @OA\Get(
 *     path="/plms/get_passport_list",
 *     tags={"Passport"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Get list of passports",
 *     description="Returns a list of passports with various filter, sorting and pagination options.",
 *     operationId="get_passport_list",
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search term for a general search across multiple fields.",
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
 *         name="status",
 *         in="query",
 *         description="Filter by status. Multiple values can be passed, separated by commas.",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="full_name",
 *         in="query",
 *         description="Filter by full name",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pax_id",
 *         in="query",
 *         description="Filter by pax ID",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="date_of_expiry",
 *         in="query",
 *         description="Search query to find specific profiles according to the exipry date of their passports",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="sort_by",
 *         in="query",
 *         description="Sort by a specific field",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="order",
 *         in="query",
 *         description="Sort order (asc or desc)",
 *         required=false,
 *         @OA\Schema(type="string", enum={"asc", "desc"})
 *     ),
 *     @OA\Parameter(
 *         name="company_id",
 *         in="query",
 *         description="Filter by company ID",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of records to return per page",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="excel_export",
 *         in="query",
 *         description="If true, exports all profiles to excel",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *      @OA\Parameter(
 *         name="passport_image",
 *         in="query",
 *         description="If true,  all passport have file",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of passports",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="list", type="array", @OA\Items(ref="#/components/schemas/PLMSPassport")),
 *             @OA\Property(
 *                 property="pagination",
 *                 type="object",
 *                 @OA\Property(property="current_page", type="integer", description="Current page of the result set"),
 *                 @OA\Property(property="per_page", type="integer", description="Number of records per page"),
 *                 @OA\Property(property="total", type="integer", description="Total number of records"),
 *                 @OA\Property(property="last_page", type="integer", description="Last page number of the result set"),
 *                 @OA\Property(property="next_page_url", type="string", description="URL for the next page"),
 *                 @OA\Property(property="prev_page_url", type="string", description="URL for the previous page"),
 *             ),
 *             @OA\Property(
 *                 property="passport_status_counts",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="status", type="string", description="Status of the passport"),
 *                     @OA\Property(property="count", type="integer", description="Number of passports with this status")
 *                 )
 *             )
 *         )
 *     )
 * )
 */


 /**
 * @OA\Get(
 *     path="/plms/get_passport_detail/{id}",
 *     summary="Get passport detail by id",
 *     description="This can only be done by the logged in user.",
 *     operationId="getPassportDetail",
 *     tags={"Passport"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         description="ID of passport to return",
 *         in="path",
 *         name="id",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Passport details returned",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="Passport ID"),
 *                     @OA\Property(property="passport_no", type="string", description="Passport number"),
 *                     @OA\Property(property="date_of_issue", type="string", format="date", description="Date of issue"),
 *                     @OA\Property(property="type", type="string", description="Type of passport"),
 *                     @OA\Property(property="date_of_expiry", type="string", format="date", description="Date of expiry"),
 *                     @OA\Property(property="status", type="string", description="Passport status"),
 *                     @OA\Property(property="full_name", type="string", description="Full name on passport"),
 *                     @OA\Property(property="arab_full_name", type="string", description="Full name in Arabic"),
 *                     @OA\Property(property="passport_country", type="integer", description="Country of passport"),
 *                     @OA\Property(property="birthday", type="string", format="date", description="Birthday"),
 *                     @OA\Property(property="pax_id", type="integer", description="Passenger ID"),
 *                     @OA\Property(property="file", type="text", description="File name"),
 *                     @OA\Property(property="place_of_issue", type="integer", description="Place of issue")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record Not Found",
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/plms/delete_passport/{id}",
 *     tags={"Passport"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Delete a passport by ID",
 *     description="Deletes a single passport based on the ID provided.",
 *     operationId="delete_passport",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the passport to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="passport deleted",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Successful deletion message",
 *                     example="passport Removed"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="passport not found",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="passport not found message",
 *                     example="passport Does not Exists"
 *                 )
 *             )
 *         ),
 *     )
 * )
 */
