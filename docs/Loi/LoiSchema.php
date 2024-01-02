<?php

/**
 * @OA\Schema(
 *     schema="PLMSLOI",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The LOI ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nation_category",
 *         type="integer",
 *         description="The nation category",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="batch_no",
 *         type="integer",
 *         description="The unique batch number",
 *         example=1001
 *     ),
 *     @OA\Property(
 *         property="loi_type",
 *         type="integer",
 *         description="The type of LOI (1= 3 Months , 2 = 6 Months , 3 = 12 Months)",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="entry_purpose",
 *         type="string",
 *         description="entry purpose of loi",
 *         example="purpose"
 *     ),
 *     @OA\Property(
 *         property="entry_type",
 *         type="string",
 *         description="entry type of loi",
 *         example="type"
 *     ),
 *     @OA\Property(
 *         property="company_address_iraq_ar",
 *         type="string",
 *         description="company address of iraq",
 *         example="example"
 *     ),
 *     @OA\Property(
 *         property="company_address_ar",
 *         type="string",
 *         description="company address",
 *         example="example"
 *     ),
 *     @OA\Property(
 *         property="company_id",
 *         type="integer",
 *         description="company of loi",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="contract_expiry",
 *         type="string",
 *         format="date",
 *         description="The submission date of the LOI",
 *         example="2023-01-01"
 *     ),
 *     @OA\Property(
 *         property="submission_date",
 *         type="string",
 *         format="date",
 *         description="The submission date of the LOI",
 *         example="2023-01-01"
 *     ),
 *     @OA\Property(
 *         property="mfd_date",
 *         type="string",
 *         format="date",
 *         description="The MFD date",
 *         example="2023-01-02"
 *     ),
 *     @OA\Property(
 *         property="mfd_ref",
 *         type="string",
 *         description="The MFD reference",
 *         example="MFD123"
 *     ),
 *     @OA\Property(
 *         property="hq_date",
 *         type="string",
 *         format="date",
 *         description="The HQ date",
 *         example="2023-01-03"
 *     ),
 *     @OA\Property(
 *         property="hq_ref",
 *         type="string",
 *         description="The HQ reference",
 *         example="HQ123"
 *     ),
 *     @OA\Property(
 *         property="moo_date",
 *         type="string",
 *         format="date",
 *         description="The MOO date",
 *         example="2023-01-04"
 *     ),
 *     @OA\Property(
 *         property="moo_ref",
 *         type="string",
 *         description="The MOO reference",
 *         example="MOO123"
 *     ),
 *     @OA\Property(
 *         property="moi_payment_date",
 *         type="string",
 *         format="date",
 *         description="The MOI payment date",
 *         example="2023-01-05"
 *     ),
 *     @OA\Property(
 *         property="moi_invoice",
 *         type="string",
 *         description="The MOI invoice",
 *         example="INV123"
 *     ),
 *     @OA\Property(
 *         property="moi_deposit",
 *         type="string",
 *         description="The MOI deposit",
 *         example="DEP123"
 *     ),
 *     @OA\Property(
 *         property="loi_issue_date",
 *         type="string",
 *         format="date",
 *         description="The LOI issue date",
 *         example="2023-01-06"
 *     ),
 *     @OA\Property(
 *         property="loi_no",
 *         type="integer",
 *         description="The LOI number",
 *         example=1002
 *     ),
 *     @OA\Property(
 *         property="sent_loi_date",
 *         type="string",
 *         format="date",
 *         description="The date when the LOI is sent",
 *         example="2023-01-07"
 *     ),
 *     @OA\Property(
 *         property="close_date",
 *         type="string",
 *         format="date",
 *         description="The closing date of the LOI",
 *         example="2023-01-08"
 *     ),
 *     @OA\Property(
 *         property="loi_photo_copy",
 *         type="text",
 *         description="The LOI photocopy",
 *         example="{app_path}/media/loi/images/loi_photo_copy.png"
 *     ),
 *     @OA\Property(
 *         property="payment_copy",
 *         type="text",
 *         description="The payment copy",
 *         example="{app_path}/media/loi/images/payment_copy.png"
 *     ),
 *     @OA\Property(
 *         property="mfd_copy",
 *         type="text",
 *         description="The mfd copy",
 *         example="{app_path}/media/loi/images/mfd_copy.png"
 *     ),
 *     @OA\Property(
 *         property="boc_moo_copy",
 *         type="text",
 *         description="The boc moo copy",
 *         example="{app_path}/media/loi/images/boc_moo_copy.png"
 *     ),
 *     @OA\Property(
 *         property="hq_copy",
 *         type="text",
 *         description="The hq copy",
 *         example="{app_path}/media/loi/images/hq_copy.png"
 *     ),
 * )
 */