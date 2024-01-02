
<?php
/**
* @OA\Get(
*     path="/roles/permission",
*     summary="Get all permissions",
*     tags={"Permissions"},
*       security={
*         {"bearerAuth": {}}
*     },
*     description="This endpoint retrieves all permissions.",
*     operationId="index",
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(ref="#/components/schemas/Permission")
*         ),
*     ),
* )
*/

/**
* @OA\Get(
*     path="/roles/user_permissions/{user}",
*     summary="Get a user's permissions",
*     tags={"Permissions"},
*       security={
*         {"bearerAuth": {}}
*     },
*     description="This endpoint retrieves all permissions of a specific user.",
*     operationId="UserPermissions",
*     @OA\Parameter(
*         name="id",
*         in="path",
*         description="ID of the user to get permissions for",
*         required=true,
*         @OA\Schema(
*           type="integer",
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*         @OA\JsonContent(
*             type="object",
*             @OA\Property(property="permissions", type="array", @OA\Items(type="string"))
*         ),
*     ),
*     @OA\Response(
*         response=404,
*         description="User not found",
*     ),
* )
*/

/**
* @OA\Post(
*     path="/roles/check_permission",
*     summary="Check if a user has a specific permission",
*     tags={"Permissions"},
*       security={
*         {"bearerAuth": {}}
*     },
*     description="This endpoint checks if a specific permission is assigned to a user.",
*     operationId="checkPermission",
*     @OA\RequestBody(
*         required=true,
*         description="User ID and Permission ID",
*         @OA\JsonContent(
*             required={"user_id", "permission_id"},
*             @OA\Property(property="user_id", type="integer", example=1),
*             @OA\Property(property="permission_id", type="integer", example=1),
*         ),
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*         @OA\JsonContent(
*             @OA\Property(property="status", type="boolean", example=true),
*         ),
*     ),
*     @OA\Response(
*         response=400,
*         description="Permission doesn't exist",
*     ),
* )
*/

/**
* @OA\Post(
*     path="/roles/permission",
*     summary="Create a new permission",
*     tags={"Permissions"},
*       security={
*         {"bearerAuth": {}}
*     },
*     description="This endpoint creates a new permission.",
*     operationId="store",
*     @OA\RequestBody(
*         required=true,
*         description="Data for the new permission",
*         @OA\JsonContent(
*             required={"name", "description"},
*             @OA\Property(property="name", type="string", example="PermissionName"),
*             @OA\Property(property="description", type="string", example="Permission description"),
*         ),
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(ref="#/components/schemas/Permission")
*         ),
*     ),
*     @OA\Response(
*         response=400,
*         description="Bad request",
*     ),
* )
*/

/**
* @OA\Post(
*     path="/roles/permission/{id}",
*     summary="Delete a permission",
*     tags={"Permissions"},
*       security={
*         {"bearerAuth": {}}
*     },
*     description="This endpoint deletes a specific permission by ID.",
*     operationId="delete",
*     @OA\Parameter(
*         name="id",
*         in="path",
*         description="ID of the permission to delete",
*         required=true,
*         @OA\Schema(
*           type="integer",
*         )
*     ),
*     @OA\Response(
*         response=200,
*         description="Successful operation",
*         @OA\JsonContent(
*             type="array",
*             @OA\Items(ref="#/components/schemas/Permission")
*         ),
*     ),
*     @OA\Response(
*         response=400,
*         description="Permission not found",
*     ),
* )
*/

/**
 * @OA\Post(
 *     path="/roles/update_permission",
 *     summary="Update a permission",
 *     description="Update the details of a permission by its ID.",
 *     tags={"Permissions"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="ID of the permission to update."),
 *             @OA\Property(property="name", type="string", description="New name for the permission."),
 *             @OA\Property(property="description", type="string", description="New description for the permission."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Permission updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="permission", ref="#/components/schemas/Permission"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Permission Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */
