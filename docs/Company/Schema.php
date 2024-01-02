<?php

/**
 * @OA\Schema(
 *     schema="PLMSCompany",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The company ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the company",
 *         example="Company A"
 *     ),
 *     @OA\Property(
 *         property="type",
 *         type="string",
 *         description="The type of the company (owner , contractor etc)",
 *         example="owner"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="integer",
 *         description="The status of the company (1= approved , 2= pending)",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="short_name",
 *         type="string",
 *         description="The short name of the company",
 *         example="CmpA"
 *     ),
 *     @OA\Property(
 *         property="industry",
 *         type="string",
 *         description="The industry the company belongs to",
 *         example="IT"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         description="The email address of the company",
 *         example="info@companyA.com"
 *     ),
 *     @OA\Property(
 *         property="phone",
 *         type="string",
 *         description="The phone number of the company",
 *         example="+1234567890"
 *     ),
 *     @OA\Property(
 *         property="website",
 *         type="string",
 *         description="The website of the company",
 *         example="www.companyA.com"
 *     ),
 *     @OA\Property(
 *         property="address_1",
 *         type="string",
 *         description="The address of the company",
 *         example="123 Street, City"
 *     ),
 *     @OA\Property(
 *         property="city",
 *         type="string",
 *         description="The city where the company is located",
 *         example="City A"
 *     ),
 *     @OA\Property(
 *         property="country_id",
 *         type="integer",
 *         description="The country where the company is located",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="poc_name",
 *         type="string",
 *         description="The name of the point of contact at the company",
 *         example="John Doe"
 *     ),
 *     @OA\Property(
 *         property="poc_email_or_username",
 *         type="string",
 *         description="The email or username of the point of contact at the company",
 *         example="john.doe@companyA.com"
 *     ),
 *     @OA\Property(
 *         property="poc_mobile",
 *         type="string",
 *         description="The mobile number of the point of contact at the company",
 *         example="+1234567891"
 *     )
 * )
 */