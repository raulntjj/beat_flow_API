<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Services\RoleService;
use App\Models\Role;

class RoleController {
    protected $roleService;

    public function __construct(RoleService $roleService) {
        $this->roleService = $roleService;
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? true,
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->roleService->getAllRoles($params);
    }

    public function store(StoreRoleRequest $request){
        return $this->roleService->createRole($request);
    }

    public function update(UpdateRoleRequest $request, int $id){
        return $this->roleService->updateRole($request, $id);
    }

    public function destroy(int $id){
        return $this->roleService->deleteRole($id);
    }
}
