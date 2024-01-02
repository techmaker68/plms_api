<?php
/**
* @OA\Schema(
*     schema="User",
*     required={"id", "name", "email", "roles"},
*     @OA\Property(property="id", type="integer", format="int64", description="User's unique identifier."),
*     @OA\Property(property="name", type="string", description="User's name."),
*     @OA\Property(property="email", type="string", description="User's email."),
*     @OA\Property(property="roles", type="array", @OA\Items(type="string"), description="User's roles."),
* )
*/