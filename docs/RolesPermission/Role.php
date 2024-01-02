<?php
/**
 * @OA\Get(
 *     path="/roles/list",
 *     summary="Get a list of roles with associated users and permissions",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Retrieve a list of roles along with their associated users and permissions.",
 *     operationId="getRoles",
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="roles",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Role")
 *             ),
 *             @OA\Property(
 *                 property="role_users",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/User")
 *             ),
 *             @OA\Property(
 *                 property="role_permissions",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Permission")
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true,
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Internal Server Error",
 *             ),
 *         ),
 *     ),
 * )
 */

/**
 * @OA\Post(
 *     path="/roles/add_role",
 *     summary="Create a new role with selected permissions",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Create a new role with a specified name, an optional display name, and selected permissions.",
 *     operationId="createRole",
 *     @OA\RequestBody(
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="The name of the role (required)."
 *             ),
 *             @OA\Property(
 *                 property="display_name",
 *                 type="string",
 *                 description="An optional display name for the role."
 *             ),
 *             @OA\Property(
 *                 property="selectedPermissions",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 description="An array of selected permission IDs (required)."
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             ref="#/components/schemas/Role"
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="object",
 *                 description="Validation error messages"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="error",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Internal Server Error"
 *             )
 *         )
 *     )
 * )
 **/

/**
 * @OA\Post(
 *     path="/roles/update_role",
 *     summary="Update a role",
 *     description="Update the details of a role by its ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="ID of the role to update."),
 *             @OA\Property(property="name", type="string", description="New name for the role."),
 *             @OA\Property(property="display_name", type="string", description="New display name for the role."),
 *             @OA\Property(property="selectedPermissions", type="array", @OA\Items(type="integer"), description="An array of permission IDs to assign to this role."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="role", ref="#/components/schemas/Role"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Role Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */


 /**
 * @OA\Get(
 *     path="/roles/roles_list",
 *     summary="List roles",
 *     description="Retrieve a list of roles with optional search and pagination.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search term for filtering roles by name or display name.",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of records to return per page (default is 25).",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="all",
 *         in="query",
 *         description="all records without pagination.",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of roles",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="list", type="array", @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="Role ID"),
 *                 @OA\Property(property="name", type="string", description="Role name"),
 *                 @OA\Property(property="display_name", type="string", description="Role display name"),
 *                 @OA\Property(property="created_at", type="string", description="Creation date"),
 *             )),
 *             @OA\Property(property="pagination", ref="#/components/schemas/Pagination"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/roles/roles_permission_list",
 *     summary="List permissions",
 *     description="Retrieve a list of permissions with optional search and pagination.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search term for filtering permissions by name or description.",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of records to return per page (default is 25).",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="all",
 *         in="query",
 *         description="all records without pagination.",
 *         required=false,
 *         @OA\Schema(type="boolean")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of permissions",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="list", type="array", @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="Permission ID"),
 *                 @OA\Property(property="name", type="string", description="Permission name"),
 *                 @OA\Property(property="description", type="string", description="Permission description"),
 *                 @OA\Property(property="permissions", type="array", @OA\Items(type="string"), description="Additional permissions"),
 *             )),
 *             @OA\Property(property="pagination", ref="#/components/schemas/Pagination"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/roles/delete/{id}",
 *     summary="Delete a role",
 *     description="Delete a role by its ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the role to delete.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="Deleted Role ID"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/roles/role/{id}",
 *     summary="Get role details by ID",
 *     description="Retrieve role details, including the users assigned to the role, by its ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the role to retrieve details for.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role details retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="role", type="object",
 *                 @OA\Property(property="id", type="integer", description="Role ID"),
 *                 @OA\Property(property="name", type="string", description="Role name"),
 *                 @OA\Property(property="users", type="array", @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", description="User ID"),
 *                     @OA\Property(property="name", type="string", description="User name"),
 *                     @OA\Property(property="email", type="string", description="User email"),
 *                 ), description="Users assigned to the role"),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/roles/user_by_role/{id}",
 *     summary="Get users by role",
 *     description="Retrieve users assigned to a role by the role's ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the role to retrieve users for.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Users assigned to the role retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="role_users", type="array", @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", description="User ID"),
 *                 @OA\Property(property="name", type="string", description="User name"),
 *                 @OA\Property(property="email", type="string", description="User email"),
 *             ), description="Users assigned to the role"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/users/get_user_with_role/{id}",
 *     summary="Get user with roles",
 *     description="Retrieve a user's details along with their assigned roles by the user's ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the user to retrieve along with their assigned roles.",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User with roles retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", description="User ID"),
 *                 @OA\Property(property="name", type="string", description="User name"),
 *                 @OA\Property(property="email", type="string", description="User email"),
 *                 @OA\Property(property="roles", type="array", @OA\Items(type="string"), description="User's assigned roles"),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Record Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Get(
 *     path="/roles/all_users",
 *     summary="Get a list of all users with roles",
 *     description="Retrieve a list of all users along with their assigned roles. Supports searching and pagination.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search term for filtering users and roles.",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of records to return per page",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="List of users with roles retrieved successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="list", type="array",
 *                 @OA\Items(type="object",
 *                     @OA\Property(property="id", type="integer", description="User ID"),
 *                     @OA\Property(property="name", type="string", description="User name"),
 *                     @OA\Property(property="user_name", type="string", description="User username"),
 *                     @OA\Property(property="roles", type="string", description="User's assigned roles"),
 *                 ),
 *             ),
 *             @OA\Property(property="pagination", type="object",
 *                 @OA\Property(property="current_page", type="integer", description="Current page number"),
 *                 @OA\Property(property="per_page", type="integer", description="Number of records per page"),
 *                 @OA\Property(property="total", type="integer", description="Total number of records"),
 *                 @OA\Property(property="last_page", type="integer", description="Last page number"),
 *                 @OA\Property(property="next_page_url", type="string", description="URL to the next page"),
 *                 @OA\Property(property="prev_page_url", type="string", description="URL to the previous page"),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Result Not Found",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/roles/remove_user_role",
 *     summary="Remove a role from a user",
 *     description="Remove a specific role from a user by their user ID and role ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user_id", type="integer", description="The ID of the user from whom to remove the role."),
 *             @OA\Property(property="role_id", type="integer", description="The ID of the role to be removed from the user."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role removed from the user successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", description="User ID"),
 *                 @OA\Property(property="name", type="string", description="User's name"),
 *                 @OA\Property(property="email", type="string", description="User's email"),
 *                 @OA\Property(property="roles", type="array", @OA\Items(type="string"), description="User's updated roles"),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/roles/assign_role_to_user",
 *     summary="Assign a role to a user",
 *     description="Assign a specific role to a user by their user ID and role ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="id", type="integer", description="The ID of the role to assign to the user."),
 *             @OA\Property(property="user_id", type="integer", description="The IDs of the users to whom the role will be assigned."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role assigned to the user successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="user", type="object",
 *                 @OA\Property(property="id", type="integer", description="User ID"),
 *                 @OA\Property(property="name", type="string", description="User's name"),
 *                 @OA\Property(property="email", type="string", description="User's email"),
 *                 @OA\Property(property="roles", type="array", @OA\Items(type="string"), description="User's updated roles"),
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation Error",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */

 /**
 * @OA\Post(
 *     path="/roles/update_roles_permission/{role}",
 *     summary="Update a role's permissions",
 *     description="Update the permissions of a role by the role's ID.",
 *     tags={"Roles"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Parameter(
 *         description="ID of the role to update",
 *         in="path",
 *         name="role",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", description="New name for the role."),
 *             @OA\Property(property="display_name", type="string", description="New display name for the role."),
 *             @OA\Property(property="selectedPermissions", type="array", @OA\Items(type="integer"), description="An array of permission IDs to assign to the role."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Role updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="role", ref="#/components/schemas/Role"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *     )
 * )
 */
