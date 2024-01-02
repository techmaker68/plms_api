<?php
/**
 * @OA\Post(
 *     path="/plms/save_loi",
 *     summary="Save or update a Letter of Intent (LOI)",
 *     description="Save loi details. If 'is_update' is true, it will update an existing visa otherwise a new blood test record will be created. Pass is_update = 1 when updating the record. While is_update = 1 id field is required.",
 *     operationId="save_loi",
 *     tags={"LOI"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Save or Update LOI Data",
 *     description="Saves a new LOI or updates an existing one based on the provided details.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="LOI data to save/update",
 *        @OA\JsonContent(
 *           @OA\Property(property="is_update", type="boolean", description="Flag to check if data should be updated or saved"),
 *           @OA\Property(property="id", type="integer", description="ID of the LOI if updating"),
 *           @OA\Property(property="nation_category", type="integer", description="Nationality category of the LOI"),
 *           @OA\Property(property="loi_type", type="integer", description="Type of LOI"),
 *           @OA\Property(property="mfd_date", type="string", format="date", description="MFD Date"),
 *           @OA\Property(property="mfd_ref", type="string", description="MFD Reference"),
 *           @OA\Property(property="moo_date", type="string", format="date", description="MOO Date"),
 *           @OA\Property(property="boc_moo_date", type="string", format="date", description="BOC MOO Date"),
 *           @OA\Property(property="moi_date", type="string", format="date", description="MOI Date"),
 *           @OA\Property(property="moi_2_date", type="string", format="date", description="MOI 2 Date"),
 *           @OA\Property(property="majnoon_date", type="string", format="date", description="Majnoon Date"),
 *           @OA\Property(property="moo_ref", type="string", description="MOO Reference"),
 *           @OA\Property(property="majnoon_ref", type="string", description="Majnoon Reference"),
 *           @OA\Property(property="moi_2_ref", type="string", description="MOI 2 Reference"),
 *           @OA\Property(property="moi_ref", type="string", description="MOI Reference"),
 *           @OA\Property(property="boc_moo_ref", type="string", description="BOC MOO Reference"),
 *           @OA\Property(property="hq_date", type="string", format="date", description="HQ Date"),
 *           @OA\Property(property="hq_ref", type="string", description="HQ Reference"),
 *           @OA\Property(property="submission_date", type="string", format="date", description="Submission Date"),
 *           @OA\Property(property="moi_payment_date", type="string", format="date", description="MOI Payment Date"),
 *           @OA\Property(property="moi_invoice", type="string", description="MOI Invoice"),
 *           @OA\Property(property="moi_deposit", type="string", description="MOI Deposit"),
 *           @OA\Property(property="loi_issue_date", type="string", format="date", description="LOI Issue Date"),
 *           @OA\Property(property="loi_no", type="integer", description="LOI Number"),
 *           @OA\Property(property="sent_loi_date", type="string", format="date", description="Sent LOI Date"),
 *           @OA\Property(property="close_date", type="string", format="date", description="Close Date"),
 *           @OA\Property(property="company_id", type="integer", description="Company ID", example=1),
 *           @OA\Property(property="company_address_iraq_ar", type="string", description="Company Address in Iraq"),
 *           @OA\Property(property="entry_purpose", type="string", description="Entry Purpose"),
 *           @OA\Property(property="entry_type", type="string", description="Entry Type"),
 *           @OA\Property(property="contract_expiry", type="string", format="date", description="Contract Expiry Date"),
 *           @OA\Property(property="company_address_ar", type="string", description="Company Address"),
 *           @OA\Property(property="loi_photo_copy", type="string", format="binary", description="LOI Photo Copy"),
 *           @OA\Property(property="payment_copy", type="string", format="binary", description="Payment Copy"),
 *           @OA\Property(property="mfd_copy", type="array", @OA\Items(type="string", format="binary"), description="MFD Copy"),
 *           @OA\Property(property="hq_copy", type="array", @OA\Items(type="string", format="binary"), description="HQ Copy"),
 *           @OA\Property(property="boc_moo_copy", type="array", @OA\Items(type="string", format="binary"), description="BOC MOO Copy"),
 *           @OA\Property(property="priority", type="integer", description="Priority of LOI"),
 *           @OA\Property(property="mfd_submit_date", type="string", format="date", description="MFD Submission Date"),
 *           @OA\Property(property="mfd_received_date", type="string", format="date", description="MFD Received Date"),
 *           @OA\Property(property="hq_submit_date", type="string", format="date", description="HQ Submission Date"),
 *           @OA\Property(property="hq_received_date", type="string", format="date", description="HQ Received Date"),
 *           @OA\Property(property="boc_moo_submit_date", type="string", format="date", description="BOC MOO Submission Date"),
 *           @OA\Property(property="moi_payment_letter_date", type="string", format="date", description="MOI Payment Letter Date"),
 *           @OA\Property(property="moi_payment_letter_ref", type="string", description="MOI Payment Letter Reference"),
 *           @OA\Property(property="expected_issue_date", type="string", format="date", description="Expected LOI Issue Date"),
 *       ),
 * ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="data", type="object", ref="#/components/schemas/PLMSLOI"),
 *             @OA\Property(property="message", type="string", description="Response message")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="errors", type="object", description="Errors during validation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error"
 *     )
 * )
 */

/**
 * @OA\Post(
 *      path="/loi/renew_loi/{batch_no}",
 *      operationId="renewLoi",
 *      tags={"LOI"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *      summary="Renew an LOI",
 *      description="Creates a new LOI and its applicants based on the existing one, using the batch_no as a reference",
 *      @OA\Parameter(
 *          name="batch_no",
 *          description="Batch number of LOI to renew",
 *          required=true,
 *          in="path",
 *          @OA\Schema(
 *              type="integer"
 *          )
 *      ),
 *      @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="object",
 *                 @OA\Property(
 *                     property="loi_application",
 *                     ref="#/components/schemas/PLMSLOI"
 *                 ),
 *                 @OA\Property(
 *                     property="loi_applicants",
 *                     type="array",
 *                     @OA\Items(ref="#/components/schemas/PLMSLOIApplicant")
 *                 )
 *             )
 *         )
 *      ),
 *       @OA\Response(
 *          response=400,
 *          description="Bad Request",
 *           @OA\JsonContent( type="object", @OA\Property(property="message", type="string", description="Error message"))
 *       ),
 * )
*/


/**
 * @OA\Get(
 *     path="/plms/get_loi_applications",
 *     operationId="getLoiApplications",
 *     tags={"LOI"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Fetch LOI Applications",
 *     description="Returns a list of LOI Applications with pagination and the ability to search and sort",
 *     @OA\Parameter(
 *         name="loi_management",
 *         in="query",
 *         description="Filter applications based on loi_management",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Parameter(
 *         name="search_by_applicant",
 *         in="query",
 *         description="Search applications by applicant",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search applications by batch_no or loi_no",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Filter applications based on status",
 *         required=false,
 *         @OA\Schema(type="integer", enum={0,1})
 *     ),
 *     @OA\Parameter(
 *         name="company_id",
 *         in="query",
 *         description="Filter applications by company id",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="sort_by",
 *         in="query",
 *         description="Sort applications by a specific column",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="order",
 *         in="query",
 *         description="Ordering direction for sorting (asc or desc)",
 *         required=false,
 *         @OA\Schema(type="string", enum={"asc", "desc"})
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Define the number of results per page",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *      @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="return records against  the status",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *      @OA\Parameter(
 *         name="priority",
 *         in="query",
 *         description="return records against  the priority",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *      @OA\Parameter(
 *         name="issued",
 *         in="query",
 *         description="return records against  the issued",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *  *      @OA\Parameter(
 *         name="excel_export",
 *         in="query",
 *         description="return excel",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *   @OA\Parameter(
 *         name="batch_nos",
 *         in="query",
 *         description="send lois batch nos for excel",
 *         required=false,
 *           @OA\Schema(
*      type="array",
*     @OA\Items(type="integer")
*)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of LOI Applications",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 description="List of LOI applications",
 *                 @OA\Items(
 *                     ref="#/components/schemas/PLMSLOI"
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
 *             @OA\Property(property="message", type="string", example="Result Not Found")
 *         )
 *     ),
 * )
 *
 */



 /**
 * @OA\Get(
 *     path="/plms/get_loi_application_details/{id}",
 *     operationId="getLoiApplicationDetails",
 *     tags={"LOI"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Fetch LOI Application Details",
 *     description="Returns details of a specific LOI Application",
 *     @OA\Parameter(
 *         name="id",
 *         description="LOI Application ID",
 *         required=true,
 *         in="path",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 description="result found",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="applicants ", type="string"),
 *                     @OA\Property(property="nation_category", type="string"),
 *                     @OA\Property(property="batch_no", type="string"),
 *                     @OA\Property(property="loi_type", type="string"),
 *                     @OA\Property(property="mfd_date", type="string"),
 *                     @OA\Property(property="mfd_ref", type="string"),
 *                     @OA\Property(property="moo_date", type="string"),
 *                     @OA\Property(property="moo_ref", type="string"),
 *                     @OA\Property(property="hq_date", type="string"),
 *                     @OA\Property(property="hq_ref", type="string"),
 *                     @OA\Property(property="majnoon_date", type="string"),
 *                     @OA\Property(property="majnoon_ref", type="string"),
 *                     @OA\Property(property="moi_2_date", type="string"),
 *                     @OA\Property(property="moi_2_ref", type="string"),
 *                     @OA\Property(property="moi_date", type="string"),
 *                     @OA\Property(property="moi_ref", type="string"),
 *                     @OA\Property(property="boc_moo_ref", type="string"),
 *                     @OA\Property(property="boc_moo_date", type="string"),
 *                     @OA\Property(property="moi_payment_date", type="string"),
 *                     @OA\Property(property="moi_invoice", type="string"),
 *                     @OA\Property(property="moi_deposit", type="string"),
 *                     @OA\Property(property="loi_issue_date", type="string"),
 *                     @OA\Property(property="loi_no", type="string"),
 *                     @OA\Property(property="sent_loi_date", type="string"),
 *                     @OA\Property(property="close_date", type="string"),
 *                     @OA\Property(property="submission_date", type="string", format="date"),
 *                     @OA\Property(property="entry_purpose", type="string", format="string"),
 *                     @OA\Property(property="entry_type", type="string", format="string"),
 *                     @OA\Property(property="contract_expiry", type="string", format="date"),
 *                     @OA\Property(property="company_id", type="string", ),
 *                     @OA\Property(property="company_country", type="string", ),
 *                     @OA\Property(property="company_address_ar", type="string",),
 *                     @OA\Property(property="company_address_iraq_ar", type="string",),
 *                 )
 *             ),
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
 * )
 *
 */

 /**
 * @OA\Post(
 *     path="/plms/delete_loi/{id}",
 *     tags={"LOI"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Delete a loi by ID",
 *     description="Deletes a single loi based on the ID provided.",
 *     operationId="delete_loi",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the loi to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="loi deleted",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="Successful deletion message",
 *                     example="loi Removed"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="loi not found",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="message",
 *                     type="string",
 *                     description="loi not found message",
 *                     example="loi Does not Exists"
 *                 )
 *             )
 *         ),
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/plms/delete_loi_files/{loi}",
 *     tags={"LOI"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Delete LOI Files",
 *     description="Deletes the LOI files based on the provided indices and LOI ID.",
 *     
 *     @OA\Parameter(
 *         name="loi",
 *         in="path",
 *         required=true,
 *         description="ID of the LOI record",
 *         @OA\Schema(type="integer")
 *     ),
 *     
 *     @OA\RequestBody(
 *         description="Indices of the LOI files to be deleted",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="boc_moo_index", type="string", example="0", description="Index of the BOC MOO copy to be deleted"),
 *             @OA\Property(property="mfd_index", type="string", example="0", description="Index of the MFD copy to be deleted"),
 *             @OA\Property(property="hq_index", type="string", example="0", description="Index of the HQ copy to be deleted"),
 *         )
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Copy Removed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Copy Does not Exists")
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
 *     path="/plms/export-loi",
 *     summary="Export LOI data to Excel",
 *     description="Export LOI data to Excel based on batch numbers.",
 *     operationId="exportLoiExcel",
 *     tags={"LOI"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="batch_no",
 *                 type="array",
 *                 description="Array of batch numbers to export.",
 *                 @OA\Items(type="integer")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Excel file download successful.",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error. 'batch_no' must be an array of integers.",
 *     ),
 * )
 */