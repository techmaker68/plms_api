<?php
/**
 * @OA\Schema(
 *     schema="Role",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The role ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the role",
 *         example="admin"
 *     ),
 *     @OA\Property(
 *         property="display_name",
 *         type="string",
 *         description="The description of the role",
 *         example="Admin role with all permissions",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="guard_name",
 *         type="string",
 *         description="The guard name for the role",
 *         example="web"
 *     )
 * )
 */

 /**
 *  @OA\Schema(
 *     schema="Pagination",
 *     type="object",
 *     @OA\Property(property="current_page", type="integer", description="Current page number"),
 *     @OA\Property(property="per_page", type="integer", description="Number of records per page"),
 *     @OA\Property(property="total", type="integer", description="Total number of records"),
 *     @OA\Property(property="last_page", type="integer", description="Last page number"),
 *     @OA\Property(property="next_page_url", type="string", description="URL for the next page"),
 *     @OA\Property(property="prev_page_url", type="string", description="URL for the previous page"),
 * )
  */