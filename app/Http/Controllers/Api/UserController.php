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
            'page' => $request->page ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->userService->getAllUsers($params);
    }

    public function show(int $id) {
        return $this->userService->getUser($id);
    }

    public function store(StoreUserRequest $request) {
        return $this->userService->createUser($request->validated());
    }

    public function update(UpdateUserRequest $request, int $id) {
        return $this->userService->updateUser($request->validated(), $id);
    }

    public function destroy(int $id) {
        return $this->userService->deleteUser($id);
    }
}
