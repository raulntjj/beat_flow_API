<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Services\PermissionService;
use App\Models\Permission;

class PermissionController {
    protected $permissionService;

    public function __construct(PermissionService $permissionService) {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? true,
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->permissionService->getAllPermissions($params);
    }

    public function store(StorePermissionRequest $request){
        return $this->permissionService->createPermission($request);
    }

    public function update(UpdatePermissionRequest $request, int $id){
        return $this->permissionService->updatePermission($request, $id);
    }

    public function destroy(int $id){
        return $this->permissionService->deletePermission($id);
    }
}
