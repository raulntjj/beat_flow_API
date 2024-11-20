<?php

namespace App\Services;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Exception;

class PermissionService {         
    public function getAllPermissions(array $params) {
        try {
            $query = Permission::query();
            if ($params['search']){
                $query->where('name', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $permissions = $query->get();
            } else {
                $permissions = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
            return response()->json(['status' => 'success', 'response' => $permissions]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getPermission(int $id) {
        try {
            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json(['status' => 'failed', 'response' => 'Permission not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $permission]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createPermission(array $request) {
        try {
            $permission = DB::transaction(function() use ($request) { 
                return Permission::create([
                    'name' => $request['name'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $permission]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updatePermission(array $request, int $id) {
        try {
            $permission = DB::transaction(function() use ($id, $request) {
                $permission = Permission::find($id);
                
                if (!$permission) {
                    throw new Exception("Permission not found");
                }

                $permission->fill([
                    'name' => $request['name'] ?? $permission->name,
                ])->save();

                return $permission;
            });

            return response()->json(['status' => 'success', 'response' => $permission]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deletePermission(int $id) {
        try {
            $permission = DB::transaction(function() use ($id) {
                $permission = Permission::find($id);

                if (!$permission) {
                    throw new Exception("Permission not found");
                }

                $permission->delete();

                return $permission;
            });

            return response()->json(['status' => 'success', 'response' => 'Permission deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
