<?php

/**
 * @OA\Post(
 *     path="/plms/save_loi_applicants",
 *     operationId="saveLoiApplicants",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Save LOI Applicant",
 *     description="Creates or updates an LOI Applicant.If 'is_update' is true, it will update an existing visa otherwise a new blood test record will be created. Pass is_update = 1 when updating the record. While is_update = 1 id field is required.",
 *     @OA\RequestBody(
 *         description="LOI Applicant data",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="is_update", type="boolean"),
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="batch_no", type="integer"),
 *             @OA\Property(property="nationality", type="integer"),
 *             @OA\Property(property="pax_id", type="integer"),
 *             @OA\Property(property="passport_no", type="string"),
 *             @OA\Property(property="full_name", type="string"),
 *             @OA\Property(property="arab_full_name", type="string"),
 *             @OA\Property(property="employer", type="string"),
 *             @OA\Property(property="department", type="string"),
 *             @OA\Property(property="position", type="string"),
 *             @OA\Property(property="country_residence", type="string"),
 *             @OA\Property(property="arab_position", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="remarks", type="string"),
 *             @OA\Property(property="phone", type="string"),
 *             @OA\Property(property="deposit_amount", type="string"),
 *             @OA\Property(property="loi_payment_date", type="string"),
 *             @OA\Property(property="loi_payment_receipt_no", type="string"),
 *             @OA\Property(property="pax_type", type="string"),
 *             @OA\Property(property="status", type="string"),
 *             @OA\Property(property="payment_letter_copies", type="string"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer")
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
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Error message")
 *         )
 *     ),
 * )
 *
 */


/**
 * @OA\Post(
 *     path="/loi/applicants/bulk",
 *     operationId="saveLoiApplicantsBulk",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Save LOI Applicants in bulk",
 *     description="Creates new LOI Applicants in bulk",
 *     @OA\RequestBody(
 *         description="LOI Applicants data to save in bulk",
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="batch_no", type="integer", description="Batch number"),
 *             @OA\Property(
 *                 property="applicants_data",
 *                 type="array",
 *                 @OA\Items(
 *                     type="object",
 *                    @OA\Property(property="id", type="integer"),
 *                    @OA\Property(property="batch_no", type="integer"),
 *                    @OA\Property(property="nationality", type="integer"),
 *                    @OA\Property(property="pax_id", type="integer"),
 *                    @OA\Property(property="passport_no", type="string"),
 *                    @OA\Property(property="full_name", type="string"),
 *                    @OA\Property(property="arab_full_name", type="string"),
 *                    @OA\Property(property="employer", type="string"),
 *                    @OA\Property(property="department", type="string"),
 *                    @OA\Property(property="email", type="string"),
 *                    @OA\Property(property="remarks", type="string"),   
 *                    @OA\Property(property="position", type="string"),
 *                    @OA\Property(property="country_residence", type="string"),
 *                    @OA\Property(property="arab_position", type="string"),
 *                    @OA\Property(property="phone", type="string"),
 *                    @OA\Property(property="deposit_amount", type="string"),
 *                    @OA\Property(property="loi_payment_date", type="string"),
 *                    @OA\Property(property="loi_payment_receipt_no", type="string"),
 *                    @OA\Property(property="pax_type", type="string"),
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer")
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
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", description="Error message")
 *         )
 *     ),
 * )
 *
 */


/**
 * @OA\Get(
 *     path="/plms/get_loi_applicants",
 *     operationId="getLoiApplicants",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Fetch LOI Applicants",
 *     description="Returns list of LOI Applicants based on batch number",
 *     @OA\Parameter(
 *         name="batch_no",
 *         description="Batch number to fetch LOI Applicants",
 *         required=true,
 *         in="query",
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *               @OA\Property(
 *                     property="list",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="batch_no", type="integer"),
 *                     @OA\Property(property="pax_id", type="string"),
 *                     @OA\Property(property="passport_no", type="string"),
 *                     @OA\Property(property="loi_payment_date", type="string"),
 *                     @OA\Property(property="full_name", type="string"),
 *                     @OA\Property(property="arab_full_name", type="string"),
 *                     @OA\Property(property="loi_payment_receipt_no", type="string"),
 *                     @OA\Property(property="email", type="string"),
 *                     @OA\Property(property="nationality", type="string"),
 *                     @OA\Property(property="arab_nationality", type="string"),
 *                     @OA\Property(property="department", type="string"),
 *                     @OA\Property(property="position", type="string"),
 *                     @OA\Property(property="country_residence", type="string"),
 *                     @OA\Property(property="arab_position", type="string"),
 *                     @OA\Property(property="status", type="string"),
 *                     @OA\Property(property="remarks", type="string"),
 *                     @OA\Property(property="deposit_amount", type="string"),
 *                     @OA\Property(property="phone", type="string"),
 *                     @OA\Property(property="pax_type", type="string"),
 *                     @OA\Property(property="employer", type="string")
 *                 ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Bad request details")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Not found details")
 *         )
 *     )
 * )
 *
 */


/**
 * @OA\Get(
 *     path="/plms/get_loi_applicant_details",
 *     operationId="getLoiApplicantDetails",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Fetch LOI Applicant Details",
 *     description="Returns list of LOI Applicant Details",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="list",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/PLMSLOIApplicant")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Not found details")
 *         )
 *     )
 * )
 *
 */


/**
 * @OA\Get(
 *     path="/plms/get_loi_applicant_full_details/{id}",
 *     operationId="getLoiApplicantFullDetails",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Fetch LOI Applicant Full Details",
 *     description="Returns details of the specified LOI Applicant",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the LOI Applicant to return",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *               @OA\Property(
 *                     property="pax_profile",
 *                     type="object",
 *                     @OA\Property(property="pax_id", type="string"),
 *                     @OA\Property(property="passport_no", type="string"),
 *                     @OA\Property(property="full_name", type="string"),
 *                     @OA\Property(property="badge_no", type="string"),
 *                     @OA\Property(property="department", type="string"),
 *                     @OA\Property(property="position", type="string"),
 *                     @OA\Property(property="employer", type="string")
 *                 ),
 *             @OA\Property(
 *                 property="last_loi_application",
 *                 type="object",
 *                 @OA\Property(property="loi_payment_date", type="string"),
 *                 @OA\Property(property="batch_no", type="string"),
 *                 @OA\Property(property="loi_no", type="string"),
 *                 @OA\Property(property="loi_payment_receipt_no", type="string"),
 *                 @OA\Property(property="remarks", type="string"),
 *                 @OA\Property(property="deposit_amount", type="string"),
 *                 @OA\Property(property="payment_letter_copy", type="string")
 *             ),
 *             @OA\Property(
 *                 property="current_loi_application",
 *                 type="object",
 *                 ref="#/components/schemas/PLMSLOIApplicant"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Not found details")
 *         )
 *     )
 * )
 *
 */


/**
 * @OA\Post(
 *     path="/plms/remove_applicant_from_list",
 *     summary="Remove specific loi applicants",
 *     description="This can only be done by the logged in user.",
 *     operationId="remove_applicant_from_list",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="loi_applicant",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 description="The IDs of the blood applicants to remove"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Applicant removed successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 * )
 */


/**
 * @OA\Post(
 *     path="/plms/update_applicants_by_loi",
 *     operationId="updateApplicantsByLoi",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Update LOI Applicant details",
 *     description="Updates the details of the specified LOI Applicant",
 *     @OA\RequestBody(
 *         description="Applicant data to be updated",
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="id",
 *                     description="The ID of the Applicant to update. this field is required",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="remarks",
 *                     description="The new remarks of the Applicant",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="deposit_amount",
 *                     description="The new deposit amount of the Applicant",
 *                     type="number"
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     description="The new status of the Applicant",
 *                     type="integer"
 *                 ),
 *                 @OA\Property(
 *                     property="loi_payment_date",
 *                     description="The new LOI payment date of the Applicant",
 *                     type="string",
 *                     format="date-time"
 *                 ),
 *                 @OA\Property(
 *                     property="loi_payment_receipt_no",
 *                     description="The new LOI payment receipt number of the Applicant",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="payment_letter_copies",
 *                     description="The new payment letter copy of the Applicant",
 *                     type="string",
 *                     format="binary"
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer"),
 *             @OA\Property(property="batch_no", type="integer"),
 *             @OA\Property(property="pax_id", type="string"),
 *             @OA\Property(property="passport_no", type="string"),
 *             @OA\Property(property="full_name", type="string"),
 *             @OA\Property(property="arab_full_name", type="string"),
 *             @OA\Property(property="loi_payment_receipt_no", type="string"),
 *             @OA\Property(property="department", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="nationality", type="string"),
 *             @OA\Property(property="status", type="integer"),
 *             @OA\Property(property="remarks", type="string"),
 *             @OA\Property(property="deposit_amount", type="number"),
 *             @OA\Property(property="phone", type="string"),
 *             @OA\Property(property="pax_type", type="string"),
 *             @OA\Property(property="payment_letter_copy", type="string"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Validation details")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Not Found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="error", type="string", example="Not found details")
 *         )
 *     )
 * )
 *
 */

 /**
 * @OA\Post(
 *     path="/plms/remove_loi_applicant",
 *     summary="Remove specific loi applicants",
 *     description="This can only be done by the logged in user.",
 *     operationId="remove_loi_applicant",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="loi_applicants",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 description="The IDs of the loi applicants to remove"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Applicant removed successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string")
 *         )
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/plms/delete_payment_letter_copy/{applicant}/{index}",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Delete a specific payment letter copy",
 *     description="Delete a specific payment letter copy associated with an applicant based on provided index",
 *     operationId="deletePaymentLetterCopy",
 *     @OA\Parameter(
 *         name="applicant",
 *         in="path",
 *         description="ID of the applicant",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Parameter(
 *         name="index",
 *         in="path",
 *         description="Index of the payment letter copy to delete",
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
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Payment letter copy not found"
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/plms/update_multiple_loi_applicants",
 *     tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Update multiple LOI applicants",
 *     description="Update payment letter details and other information for multiple applicants based on provided IDs",
 *     operationId="updateMultipleLOIApplicants",
 *     
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="ids",
 *                     type="string",
 *                     description="Comma-separated list of applicant IDs to be updated"
 *                 ),
 *                 @OA\Property(
 *                     property="payment_letter_copies",
 *                     type="array",
 *                     description="Uploaded payment letter files",
 *                     @OA\Items(type="string", format="binary")
 *                 ),
 *                 @OA\Property(
 *                     property="deposit_amount",
 *                     type="number",
 *                     description="Deposit amount"
 *                 ),
 *                 @OA\Property(
 *                     property="loi_payment_date",
 *                     type="string",
 *                     description="Payment date for LOI",
 *                     format="date"
 *                 ),
 *                 @OA\Property(
 *                     property="loi_payment_receipt_no",
 *                     type="string",
 *                     description="Receipt number for LOI payment"
 *                 ),
 *                 @OA\Property(
 *                     property="remarks",
 *                     type="string",
 *                     description="Remarks"
 *                 ),
 *                 @OA\Property(
 *                     property="status",
 *                     type="integer",
 *                     description="Status of the applicants' LOI"
 *                 ),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input"
 *     )
 * )
 */

 /**
 * @OA\Get(
 *      path="/plms/loi_passport_base64",
 *      operationId="loiPassportBase64",
 *      tags={"LoiApplicant"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *      summary="Get LOI Applicants Passport in Base64 format",
 *      description="Returns base64 encoded passport and other details of the LOI applicants",
 *      
 *      @OA\Parameter(
 *          name="batch_no",
 *          description="Batch number of the LOI Applicant",
 *          required=true,
 *          in="query",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              type="array",
 *              @OA\Items(
 *                  type="object",
 *                  @OA\Property(property="name", type="string", description="Formatted name"),
 *                  @OA\Property(property="passport", type="string", format="base64", description="Base64 encoded passport"),
 *                  @OA\Property(property="extension", type="string", description="File extension of the passport")
 *              )
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
 *          description="Server Error"
 *      )
 * )
 */

 /**
 * @OA\Post(
 *     path="/plms/send_loi_to_applicants",
 *     summary="Send LOI to Applicants",
 *     description="Sends an email containing the Letter of Intent to the specified recipients.",
 *     operationId="sendLoiToApplicants",
 *     tags={"LoiApplicant"},
 *        security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="batch_no", type="string", description="The batch number."),
 *             @OA\Property(property="to", type="string", description="Comma-separated list of recipient email addresses."),
 *             @OA\Property(property="bcc", type="string", description="Comma-separated list of BCC email addresses."),
 *             @OA\Property(property="cc", type="string", description="Comma-separated list of CC email addresses."),
 *             @OA\Property(property="subject", type="string", description="The subject of the email."),
 *             @OA\Property(property="content", type="string", description="The content/body of the email."),
 *             @OA\Property(property="attachment", type="array", @OA\Items(type="file"), description="Array of file attachments."),
 *             @OA\Property(property="all_applicants", type="boolean", description="Whether to send to all applicants in the batch.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Email Sent Successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="string", example=""),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Email Sent Successfully.")
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
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="data", type="object", example={}),
 *             @OA\Property(property="code", type="integer", example=500),
 *             @OA\Property(property="message", type="string", example="Internal Server Error.")
 *         )
 *     )
 * )
 */
