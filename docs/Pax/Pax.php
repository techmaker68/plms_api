<?php
/**
 * @OA\Post(
 *     path="/plms/save_plms_profile",
 *     tags={"Pax"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Create or update a PLMS pax",
 *     description="This can only be done by the logged in user.pass is_update = 1 when updating the record. While is_update = 1 id field is required.",
 *     operationId="save_plms_profile",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Profile object that needs to be added or updated in the database",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 required={"type","first_name","last_name","company_id"},
 *                 @OA\Property(
 *                     property="type",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="first_name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="last_name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="company_id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="is_update",
 *                     type="boolean"
 *                 ),
 *                @OA\Property(property="dob", type="string", example="1990-01-01"),
 *                 @OA\Property(
 *                     property="eng_full_name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="arab_full_name",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="nationality",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="country_residence",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="arab_position",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="position",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="department_id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="phone",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="country_code",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="gender",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="badge_no",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="image",
 *                     type="file"
 *                 ),
 *     @OA\Property(
 *         property="offboard_date",
 *         type="integer",
 *         format="date",
 *         description="The offbaord date of pax",
 *         example="2033-08-03"
 *     ),
 *             )
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profile created/updated successfully",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="message",
 *                     type="string"
 *                 )
 *             )
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="object"
 *                 )
 *             )
 *         ),
 *     )
 * )
 */


 /**
 * @OA\Get(
 *     path="/plms/get_plms_profile_by_id/{id}",
 *     tags={"Pax"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Get a PLMS Pax Profile by ID",
 *     description="Returns a single pax.",
 *     operationId="get_plms_profile_by_id",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the Pax to retrieve",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operation successful",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="pax_profile", type="object", 
 *                 @OA\Property(property="pax_id", type="integer"),
 *                 @OA\Property(property="type", type="string"),
 *                 @OA\Property(property="company_id", type="integer"),
 *                 @OA\Property(property="employer", type="string"),
 *                 @OA\Property(property="employee_no", type="integer"),
 *                 @OA\Property(property="first_name", type="string"),
 *                 @OA\Property(property="last_name", type="string"),
 *             ),
 *             @OA\Property(property="passports", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="file", type="string"),
 *                     @OA\Property(property="status", type="string"),
 *                     @OA\Property(property="passport_country", type="string"),
 *                 ),
 *             ),
 *             @OA\Property(property="visa", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="file", type="string"),
 *                     @OA\Property(property="status", type="string"),
 *                 ),
 *             ),
 *             @OA\Property(property="blood_test", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="arrival_date", type="date"),
 *                     @OA\Property(property="departure_date", type="date"),
 *                 ),
 *             ),
 *             @OA\Property(property="loi", type="array",
 *                 @OA\Items(
 *                     @OA\Property(property="batch_no", type="string"),
 *                     @OA\Property(property="pax_id", type="integer"),
 *                 ),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Records Not Found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error"
 *     )
 * )
 */



 /**
 * @OA\Post(
 *     path="/plms/delete_plms_profile/{id}",
 *     tags={"Pax"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Delete a PLMS pax profile by ID",
 *     description="Deletes a single pax profile based on the ID provided.",
 *     operationId="delete_plms_profile",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the profile to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="pax deleted",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Successful deletion message",
 *                     example="Pax Removed"
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
 *                     description="Profile not found message",
 *                     example="Pax Does not Exists"
 *                 )
 *             )
 *         ),
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/plms/get_plms_profiles",
 *     summary="Retrieve a list of PLMS profiles",
 *     tags={"Pax"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="This endpoint retrieves a list of PLMS profiles based on the provided search and filter parameters.",
 *     operationId="getPlmsProfiles",
 *     @OA\Parameter(
 *         name="company_id",
 *         in="query",
 *         description="Company ID to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search query to find specific profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="visa_expiry",
 *         in="query",
 *         description="Search query to find specific profiles according to the exipry date of their visas",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="nationality",
 *         in="query",
 *         description="Nationality to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="country_residence",
 *         in="query",
 *         description="Country of residence to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="department_id",
 *         in="query",
 *         description="Department to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="position",
 *         in="query",
 *         description="Position to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="similar_name",
 *         in="query",
 *         description="filter profiles with similiar names",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="pax_without_passport",
 *         in="query",
 *         description="filter profiles without passports",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="pax_without_visa",
 *         in="query",
 *         description="filter profiles without visas",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="pax_without_badge",
 *         in="query",
 *         description="filter profiles without badges",
 *         required=false,
 *         @OA\Schema(
 *             type="boolean"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="type",
 *         in="query",
 *         description="Type to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="status to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="nation_category",
 *         in="query",
 *         description="National category to filter profiles. You can pass single value or multiple values as comma separated",
 *         required=false,
 *         @OA\Schema(
 *             type="string",
 *             example="syrian, arab"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="route",
 *         in="query",
 *         description="Route to filter profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="sort_by",
 *         in="query",
 *         description="Field to sort profiles",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="order",
 *         in="query",
 *         description="Order of sort (asc/desc)",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of profiles per page",
 *         required=false,
 *         @OA\Schema(
 *             type="integer"
 *         )
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
 *     @OA\Response(
 *         response=200,
 *         description="Profiles retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="The ID of the profile"),
 *                     @OA\Property(property="company_id", type="integer", description="The company ID of the profile"),
 *                     @OA\Property(property="pax_id", type="integer", description="The pax ID of the profile"),
 *                     @OA\Property(property="eng_full_name", type="string", description="The full name of the profile in English"),
 *                     @OA\Property(property="first_name", type="string", description="The first name of the profile"),
 *                     @OA\Property(property="arab_full_name", type="string", description="The full name of the profile in Arabic"),
 *                     @OA\Property(property="phone", type="string", description="The phone number of the profile"),
 *                     @OA\Property(property="country_code", type="string", description="The country code of phone number of the profile"),
 *                     @OA\Property(property="email", type="string", description="The email of the profile"),
 *                     @OA\Property(property="status", type="string", description="The status of the profile"),
 *                     @OA\Property(property="gender", type="string", description="The gender of the profile"),
 *                     @OA\Property(property="last_name", type="string", description="The last name of the profile"),
 *                     @OA\Property(property="type", type="string", description="The type of the profile"),
 *                     @OA\Property(property="dob", type="string", example="1990-01-01"),
 *                     @OA\Property(property="badge_no", type="string", description="The badge number of the profile"),
 *                     @OA\Property(property="arab_position", type="string", description="The position of the profile in Arabic"),
 *                     @OA\Property(property="country_residence", type="string", description="The country of residence of the profile"),
 *                     @OA\Property(property="image", type="string", description="The image URL of the profile"),
 *                     @OA\Property(property="position", type="string", description="The position of the profile"),
 *                     @OA\Property(property="department_id", type="string", description="The department of the profile"),
 *                     @OA\Property(property="employer", type="string", description="The employer of the profile"),
 *                     @OA\Property(property="passport_exist", type="boolean", description="If the profile has a passport"),
 *                     @OA\Property(property="visa_exist", type="boolean", description="If the profile has a visa"),
 *                     @OA\Property(property="passport_no", type="string", description="The passport number of the profile"),
 *                     @OA\Property(property="nationality", type="string", description="The nationality of the profile")
 *                 ),
 *             ),
 *             @OA\Property(
 *                 property="pagination",
 *                 type="object",
 *                 @OA\Property(property="current_page", type="integer"),
 *                 @OA\Property(property="per_page", type="integer"),
 *                 @OA\Property(property="total", type="integer"),
 *                 @OA\Property(property="last_page", type="integer"),
 *                 @OA\Property(property="next_page_url", type="string"),
 *                 @OA\Property(property="prev_page_url", type="string"),
 *             ),
 *             @OA\Property(
 *                 property="types_counts",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="type", type="string"),
 *                     @OA\Property(property="count", type="integer"),
 *                 ),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Profiles not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 * )
 */


/**
 * @OA\Post(
 *     path="/plms/excel-import",
 *     tags={"ImportExcel"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Upload and process an Excel file",
 *     description="Upload an Excel file for processing.",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="type",
 *                     type="string",
 *                     description="Type of import (e.g., 'passport' or 'visa' or 'pax')",
 *                     example="pax"
 *                 ),
 *                 @OA\Property(
 *                     property="file",
 *                     type="file",
 *                     description="Excel file to upload (must be in .xlsx or .xls format)"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success response",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Data uploaded successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity - Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="The given data was invalid.")
 *         )
 *     )
 * )
 */

/**
 * @OA\Get(
 *     path="/plms/download-excel",
 *     tags={"ImportExcel"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Download an Excel sample  files",
 *     description="Download an Excel file based on the specified type.",
 *     @OA\Parameter(
 *         name="type",
 *         in="query",
 *         required=true,
 *         description="Type of Excel file to download (e.g., 'pax' or 'passport' or 'visa')",
 *         @OA\Schema(type="string", example="pax")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Success response",
 *         @OA\MediaType(
 *             mediaType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
 *             example="Binary Excel File Content",
 *             @OA\Schema(type="file")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="File not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="File not found")
 *         )
 *     )
 * )
 */