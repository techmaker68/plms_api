<?php
/**
 * @OA\Get(
 *     path="/api/getCountrys",
 *     summary="Get all countries",
 *     tags={"Countries"},
 *     security={
 *         {"bearerAuth": {}}
 *     },
 *     description="This endpoint retrieves all countries.",
 *     operationId="getCountrys",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="success result"),
 *             @OA\Property(property="code", type="integer", example=200),
 *             @OA\Property(property="result", type="array", @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="country_name_short_en", type="string", example="United States"),
 *                 @OA\Property(property="country_name_full_en", type="string", example="United States of America"),
 *                 @OA\Property(property="country_name_short_ar", type="string", example="الولايات المتحدة")
 *             )),
 *         ),
 *     ),
 * )
 */
