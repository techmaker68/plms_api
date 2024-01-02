<?php
 /**
 * @OA\Post(
 *     path="/api/auth/login",
 *     summary="User Login",
 *     tags={"Authentication"},
 *     description="Authenticate a user and return an access token.",
 *     operationId="login",
 *     security={},
 *     @OA\RequestBody(
 *         required=true,
 *         description="User credentials",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *                 @OA\Property(property="password", type="string", description="User's password")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="Successful login",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", description="Access token")
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Unauthorized")
 *         )
 *     )
 * )
 */

/**
 * @OA\Post(
 *     path="/api/add_user",
 *     summary="User Registration",
 *     tags={"Authentication"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Register a new user and return an access token.",
 *     operationId="register",
 *     @OA\RequestBody(
 *         required=true,
 *         description="User registration data",
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", description="User's name"),
 *             @OA\Property(property="email", type="string", format="email", description="User's email address"),
 *             @OA\Property(property="password", type="string", description="User's password (min. 6 characters)")
 *         )
 *     ),
 *     @OA\Response(
 *         response="201",
 *         description="User registration successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", description="Access token")
 *         )
 *     )
 * )
*/

 /**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="User Logout",
 *     tags={"Authentication"},
 *     description="Revoke the user's access token to log them out.",
 *     operationId="logout",
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     @OA\Response(
 *         response="200",
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Successfully logged out")
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", description="Unauthorized")
 *         )
 *     )
 * )
*/
/**
 * @OA\Get(
 *     path="/api/users",
 *     summary="Get a list of users",
 *     tags={"Authentication"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Retrieve a list of users with optional search, sorting, and role/permission filtering.",
 *     operationId="getUsers",
 *     @OA\Parameter(
 *         name="search",
 *         in="query",
 *         description="Search term to filter users (optional)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="sort_by",
 *         in="query",
 *         description="Column to sort by (optional)",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="order",
 *         in="query",
 *         description="Sort order ('asc' or 'desc') (optional)",
 *         required=false,
 *         @OA\Schema(type="string", enum={"asc", "desc"})
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Number of items per page (optional)",
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
 *     @OA\Parameter(
 *         name="role_ids",
 *         in="query",
 *         description="Array of role IDs to filter users (optional)",
 *         required=false,
 *     ),
 *     @OA\Parameter(
 *         name="permission_ids",
 *         in="query",
 *         description="Array of permission IDs to filter users (optional)",
 *         required=false,
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="List of users found",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/User")
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized"
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Forbidden"
 *     ),
 * )
 */

 /**
 * @OA\Post(
 *     path="/api/delete_user/{id}",
 *     summary="Delete a User",
 *     tags={"Authentication"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Delete a user by ID.",
 *     operationId="deleteUser",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID to be deleted",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User deleted successfully."
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="User not found."
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized. User not authenticated."
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Forbidden. User doesn't have the necessary permissions."
 *     ),
 * )
 */

 /**
 * @OA\Get(
 *     path="/api/show_user/{id}",
 *     summary="Get User Details",
 *     tags={"Authentication"},
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     description="Retrieve details of a user by ID.",
 *     operationId="getUserDetails",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID to retrieve details",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User details retrieved successfully.",
 *         @OA\JsonContent(
 *             type="object",
 *             ref="#/components/schemas/User" 
 *         )
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="User not found."
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized. User not authenticated."
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Forbidden. User doesn't have the necessary permissions."
 *     ),
 * )
 */

 /**
 * @OA\Get(
 *     path="/api/current_user",
 *     summary="Get Current User",
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     tags={"Authentication"},
 *     description="Retrieve details of the current authenticated user.",
 *     operationId="getCurrentUser",
 *     @OA\Response(
 *         response="200",
 *         description="User details retrieved successfully.",
 *         @OA\JsonContent(
 *             type="object",
 *             ref="#/components/schemas/User"
 *         )
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="User not authenticated."
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Forbidden. User doesn't have the necessary permissions."
 *     ),
 * )
 */

 /**
 * @OA\Post(
 *     path="/api/update_user/{id}",
 *     summary="Update User",
 *       security={
 *         {"bearerAuth": {}}
 *     },
 *     tags={"Authentication"},
 *     description="Update a user's information by ID.",
 *     operationId="updateUser",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID to update",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Updated user data",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="User updated successfully."
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="User not found."
 *     ),
 *     @OA\Response(
 *         response="400",
 *         description="Validation error. Invalid data."
 *     ),
 *     @OA\Response(
 *         response="401",
 *         description="Unauthorized. User not authenticated."
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Forbidden. User doesn't have the necessary permissions."
 *     ),
 * )
 */
