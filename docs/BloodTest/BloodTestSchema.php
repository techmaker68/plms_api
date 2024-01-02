<?php

/**
 * @OA\Schema(
 *     schema="PLMSBloodTest",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The blood test ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="batch_no",
 *         type="integer",
 *         description="The unique batch number",
 *         example=1001
 *     ),
 *     @OA\Property(
 *         property="submit_date",
 *         type="string",
 *         format="date",
 *         description="The date when the test is submitted",
 *         example="2023-01-01"
 *     ),
 *     @OA\Property(
 *         property="test_date",
 *         type="string",
 *         format="date",
 *         description="The date when the test is performed",
 *         example="2023-01-02"
 *     ),
 *     @OA\Property(
 *         property="return_date",
 *         type="string",
 *         format="date",
 *         description="The date when the test result is returned",
 *         example="2023-01-03"
 *     ),
 *     @OA\Property(
 *         property="venue",
 *         type="string",
 *         description="The venue where the test is performed",
 *         example="Venue A"
 *     ),
 * )
 */