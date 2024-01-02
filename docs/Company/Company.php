<?php
/**
 * @OA\Post(
 *     path="/plms/save_plms_company",
 *     summary="Save or Update a PLMS Company",
 *     tags={"PLMSCompany"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="This can either create a new PLMS Company or update an existing one based on the input parameters.Pass is_update = 1 when updating the record. While is_update = 1 id field is required.",
 *     operationId="save_plms_company",
 *     
 *     @OA\RequestBody(
 *         required=true,
 *         description="Pass PLMS Company data",
 *         @OA\JsonContent(ref="#/components/schemas/PLMSCompany")
 *     ),
 *     
 *     @OA\Response(
 *         response=200,
 *         description="PLMS Company data has been successfully saved.",
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request. User has sent an invalid request.",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error.",
 *     ),
 * )
 */

/**
 * @OA\Get(
 *     path="/plms/plms_company_list",
 *     summary="Get the list of PLMS Companies",
 *     tags={"PLMSCompany"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Fetches the list of PLMS Companies. Each company's details include id, name, type, status, short_name, industry, email, phone, website, city, country_id, poc_name, poc_email_or_username, poc_mobile, address_1, created_at, country, and paxes.",
 *     operationId="plms_company_list",
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="list", type="array",
 *                 @OA\Items(ref="#/components/schemas/PLMSCompany")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request. User has sent an invalid request.",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error.",
 *     ),
 * )
 */



/**
* @OA\Get(
*     path="/plms/plms_company_detail/{id}",
*     tags={"PLMSCompany"},
*       security={
*         {"bearerAuth": {}}
*     },
*     summary="Get PLMS Company Details",
*     description="This endpoint retrieves details of a PLMS company by ID.",
*     operationId="plms_company_detail",
*     @OA\Parameter(
*         name="id",
*         in="path",
*         description="ID of the PLMS Company to retrieve",
*         required=true,
*         @OA\Schema(
*           type="integer",
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*         @OA\JsonContent(ref="#/components/schemas/PLMSCompany"),
*     ),
*     @OA\Response(
*         response=404,
*         description="Company not found",
*     ),
* )
*/


/**
* @OA\Post(
*     path="/plms/delete_plms_company/{id}",
*     summary="Delete PLMS Company",
*     tags={"PLMSCompany"},
*       security={
*         {"bearerAuth": {}}
*     },
*     description="This endpoint deletes a PLMS company by ID.",
*     operationId="delete_plms_company",
*     @OA\Parameter(
*         name="id",
*         in="path",
*         description="ID of the PLMS Company to delete",
*         required=true,
*         @OA\Schema(
*           type="integer",
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*     ),
*     @OA\Response(
*         response=404,
*         description="Company not found",
*     ),
* )
*/