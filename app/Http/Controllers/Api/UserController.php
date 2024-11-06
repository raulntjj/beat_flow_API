<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController {
    protected $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    public function index(Request $request) {
        $params = [
            'getAllData' => $request->getAllData ?? false,
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->userService->getAllUsers($params);
    }

    public function show(int $id) {
        return $this->userService->getUser($id);
    }

    public function store(StoreUserRequest $request) {
        return $this->userService->createUser($request);
    }

    public function update(UpdateUserRequest $request, int $id) {
        return $this->userService->updateUser($request, $id);
    }

    public function destroy(int $id) {
        return $this->userService->deleteUser($id);
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['status' => 'failed', 'response' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function me() {
        return response()->json(['status' => 'success', 'response' => Auth::guard('api')->user()]);
    }

    public function logout() {
        Auth::guard('api')->logout();
        return response()->json(['status' => 'success', 'response' => 'Successfully logged out']);
    }

    public function refresh() {
        return $this->respondWithToken(Auth::guard('api')->refresh());
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
