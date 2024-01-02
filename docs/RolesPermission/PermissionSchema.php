<?php
/**
 * @OA\Schema(
 *     schema="Permission",
 *     type="object",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="The permission ID",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="The name of the permission",
 *         example="view_users"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="The description of the permission",
 *         example="Permission to view users",
 *         nullable=true
 *     ),
 *     @OA\Property(
 *         property="guard_name",
 *         type="string",
 *         description="The guard name for the permission",
 *         example="web"
 *     )
 * )
 */