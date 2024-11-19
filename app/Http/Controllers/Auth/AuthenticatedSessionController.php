<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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

    public function myFollowers(Request $params) {
        $params = [
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
        ];
        return $this->userService->getUserFollowers($params);
    }

    public function myFollowed(Request $params) {
        $params = [
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
        ];
        return $this->userService->getUserFollowed($params);
    }

    public function myNotifications(Request $params) {
        $params = [

            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
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
