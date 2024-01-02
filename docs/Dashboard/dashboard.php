<?php
/**
 * @OA\Get(
 *     path="/plms/dashboard_stats", 
 *     tags={"Dashboard Stats"},
 *        security={
 *         {"bearerAuth": {}}
 *     },
 *     summary="Retrieve dashboard statistics",
 *     description="Fetches and returns various dashboard statistics.",
 *     operationId="dashboard_stats",
 *     @OA\Response(
 *         response=200,
 *         description="Records Found",
 *         @OA\JsonContent(
 *            type="object",
 *            @OA\Property(property="paxes_stats", ref="#/components/schemas/PaxesStats"),
 *            @OA\Property(property="passports_stats", ref="#/components/schemas/PassportsStats"),
 *            @OA\Property(property="visas_stats", ref="#/components/schemas/VisasStats"),
 *            @OA\Property(property="blood_test_stats", ref="#/components/schemas/BloodTestStats"),
 *            @OA\Property(property="lois_stats", ref="#/components/schemas/LoisStats")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *            type="object",
 *            @OA\Property(property="message", type="string", example="An error occurred!")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="PaxesStats",
 *     type="object",
 *     @OA\Property(property="total", type="integer"),
 *     @OA\Property(property="onboard", type="integer"),
 *     @OA\Property(property="offboard", type="integer"),
 *     @OA\Property(property="expat", type="integer"),
 *     @OA\Property(property="paxes_without_passports", type="integer"),
 *     @OA\Property(property="paxes_without_visas", type="integer")
 * )
 *
 * @OA\Schema(
 *     schema="PassportsStats",
 *     type="object",
 *     @OA\Property(property="total", type="integer"),
 *     @OA\Property(property="active", type="integer"),
 *     @OA\Property(property="expired", type="integer"),
 *     @OA\Property(property="to_be_renewed", type="integer"),
 *     @OA\Property(property="cancelled", type="integer")
 * )
 *
 * @OA\Schema(
 *     schema="VisasStats",
 *     type="object",
 *     @OA\Property(property="total", type="integer"),
 *     @OA\Property(property="active", type="integer"),
 *     @OA\Property(property="expired", type="integer"),
 *     @OA\Property(property="to_be_renewed", type="integer"),
 *     @OA\Property(property="cancelled", type="integer")
 * )
 *
 * @OA\Schema(
 *     schema="BloodTestStats",
 *     type="object",
 *     @OA\Property(property="total_records", type="integer"),
 *     @OA\Property(property="submitted", type="integer"),
 *     @OA\Property(property="tested", type="integer"),
 *     @OA\Property(property="returned", type="integer"),
 *     @OA\Property(property="scheduled", type="integer")
 * )
 *
 * @OA\Schema(
 *     schema="LoisStats",
 *     type="object",
 *     @OA\Property(property="lastest_batch", type="integer"),
 *     @OA\Property(property="closed_loi", type="integer"),
 *     @OA\Property(property="open_loi", type="integer"),
 *     @OA\Property(property="total_applicants", type="integer"),
 *     @OA\Property(property="approved", type="integer"),
 *     @OA\Property(property="rejected", type="integer"),
 *     @OA\Property(property="cancelled", type="integer"),
 *     @OA\Property(property="give_up", type="integer")
 * )
 */
