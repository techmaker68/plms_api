<?php

/**
 * @OA\Post(
 *     path="/plms/download-zip",
 *     summary="Generate and send ZIP files",
 *     tags={"ZIP Controller"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         description="Request body containing batch_no and file",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 required={"batch_no", "file"},
 *                 @OA\Property(property="batch_no", type="string", description="Batch number", example="348"),
 *                 @OA\Property(property="file", type="string", format="binary", description="File to upload"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="ZIP files sent successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Zip files sent to the LOI admin emails")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation failed",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Validation failed"),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *                 @OA\Property(property="batch_no", type="array", @OA\Items(type="string")),
 *                 @OA\Property(property="file", type="array", @OA\Items(type="string")),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="LOI Admin emails are empty in Database",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Please add at least one LOI admin email in settings")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="An error occurred",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="An error occurred: Internal Server Error")
 *         )
 *     ),
 * )
 */
