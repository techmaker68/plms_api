<?php

/**
* @OA\Schema(
*     schema="PLMSVisa",
*     type="object",
*     @OA\Property(
*         property="id",
*         type="integer",
*         description="The visa ID",
*         example=1
*     ),
*     @OA\Property(
*         property="full_name",
*         type="string",
*         description="The full name in visa",
*         example="John Doe"
*     ),
*     @OA\Property(
*         property="pax_id",
*         type="integer",
*         description="Unique identifier for the pax",
*         example=1001
*     ),
*     @OA\Property(
*         property="type",
*         type="string",
*         description="The type of visa (visitor , 3 months , 6 months , 12 months)",
*         example="3 months"
*     ),
*     @OA\Property(
*         property="loi_no",
*         type="integer",
*         description="The Letter of Invitation number",
*         example=12345
*     ),
*     @OA\Property(
*         property="date_of_issue",
*         type="string",
*         format="date",
*         description="The date of visa issue",
*         example="2023-01-01"
*     ),
*     @OA\Property(
*         property="date_of_expiry",
*         type="string",
*         format="date",
*         description="The date of visa expiry",
*         example="2023-12-31"
*     ),
*     @OA\Property(
*         property="visa_no",
*         type="string",
*         description="The visa sticker number ",
*         example="VS123"
*     ),
*     @OA\Property(
*         property="ref_no",
*         type="string",
*         description="The reference number",
*         example="REF123"
*     ),
*     @OA\Property(
*         property="passport_no",
*         type="string",
*         description="The passport number",
*         example="PASS123"
*     ),
*     @OA\Property(
*         property="reason",
*         type="text",
*         description="The reason of cancelation",
*     ),
*     @OA\Property(
*         property="status",
*         type="integer",
*         description="The status of the visa (1= active, 2= expired , 3=to be renewed , 4=cancelled )",
*         example=1
*     ),
*     @OA\Property(
*         property="file",
*         type="text",
*         description="The URL of the visa's file",
*         example="{app_path}/media/visas/files/5fa2bfc8ad3e7-1604419272.pdf"
*     ),
* )
*/