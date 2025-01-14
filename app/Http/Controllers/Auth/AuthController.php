<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController {
    public function login(Request $request) {
        $credentials = $request->only('identifier', 'password');
        $identifier = $credentials['identifier'];
        $password = $credentials['password'];
    
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'user';
    
        $credentials = [
            $field => $identifier,
            'password' => $password,
        ];
    
        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['status' => 'failed', 'response' => 'Unauthorized'], 401);
        }
    
        return $this->respondWithToken($token);
    }
    
    
    public function logout() {
        Auth::guard('api')->logout();
        return response()->json(['status' => 'success', 'response' => 'Successfully logged out']);
    }

    public function register(StoreUserRequest $request) {
        $userService = app(UserService::class);
        $user = $userService->createUser($request->validated());
        return response()->json(['status' => 'success', 'response' => $user]);
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
