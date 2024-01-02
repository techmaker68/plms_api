<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([\App\Http\Middleware\SanitizeInput::class, 'auth:api'])
    ->group(function () {
        Route::apiResource('roles', RoleController::class);
        Route::controller(RoleController::class)->prefix('/roles')->group(function () {
            Route::post('/assign/role/user', 'assignRoleToUser');
            Route::post('/remove/user/role', 'removeRoleFromUser');
            Route::get('/all/users', 'usersWithRoles');
            Route::get('/get_user_with_role/{id}', 'getUserWithRole');
            Route::get('/user_by_role/{role}', 'userByRole');
            Route::get('roles/list', 'roleList');
            Route::post('/update/users/roles', 'updateUserRole');
        });

        //api resource for permissions CRUD
        Route::apiResource('permissions', PermissionController::class);

        Route::controller(PermissionController::class)->prefix('/permissions')->group(function () {
            Route::get('/user/permissions/{user}', 'user_permissions');
            Route::post('/check/permission', 'check_permission');
        });
    });
