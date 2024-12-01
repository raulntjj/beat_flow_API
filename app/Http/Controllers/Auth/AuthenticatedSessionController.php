<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeEmailRequest;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticatedSessionController {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function me() {
        return $this->userService->authenticatedUser();
    }

    public function changePassword(ChangePasswordRequest $request) {
        return $this->userService->changePassword($request->validated());
    }

    public function changeEmail(ChangeEmailRequest $request) {
        return $this->userService->changeEmail($request->validated());
    }

    public function changeUserData(UpdateUserRequest $request) {
        $userAuth = Auth::guard('api')->user();
        return $this->userService->updateUser($request->validated(), $userAuth->id);
    }

    public function deleteUserAccount() {
        $userAuth = Auth::guard('api')->user();
        return $this->userService->deleteUser($userAuth->id);
    }

    public function myFollowers(Request $params) {
        $params = [
            'perPage' => $params->perPage ?? 10,
            'page' => $params->page ?? 1,
        ];
        return $this->userService->getUserFollowers($params);
    }

    public function myFollowed(Request $params) {
        $params = [
            'perPage' => $params->perPage ?? 10,
            'page' => $params->page ?? 1,
        ];
        return $this->userService->getUserFollowed($params);
    }

    public function myNotifications(Request $params) {
        $params = [
            'perPage' => $params->perPage ?? 10,
            'page' => $params->page ?? 1,
        ];
        return $this->userService->getUserNotifications($params);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'status' => 'success',
            'response' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ],
        ]);
    }
}
