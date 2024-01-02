<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\User\Contracts\PLMSUserServiceContract;
use Modules\User\Http\Requests\LoginRequest;

class PLMSUserLoginController extends BaseController
{
    protected PLMSUserServiceContract $service;

    public function __construct(PLMSUserServiceContract $service)
    {
        $this->service = $service;
    }

    public function login(LoginRequest $request)
    {
        return $this->tryCatch(function () use ($request) {
            $validatedData = $request->validated();
            $user = $this->service->authenticate($validatedData);
            return response()->json($user);
        });
    }
    public function logout(Request $request)
    {
        return $this->tryCatch(function () use ($request) {
            $message = $this->service->logout($request->user());
            return response()->json($message);
        });
    }

    public function getCurrentUser()
    {
        return $this->tryCatch(function () {
            $user = $this->service->getCurrentUser();
            return response()->json($user);
        });
    }
}
