<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Exception;

class RoleService {         
    public function getAllRoles(array $params) {
        try {
            $query = Role::query();
            if ($params['search']){
                $query->where('name', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $roles = $query->get();
            } else {
                $roles = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
            return response()->json(['status' => 'success', 'response' => $roles]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getRole(int $id) {
        try {
            $role = Role::find($id);

            if (!$role) {
                return response()->json(['status' => 'failed', 'response' => 'Role not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $role]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createRole(array $request) {
        try {
            $role = DB::transaction(function() use ($request) { 
                return Role::create([
                    'name' => $request['name'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $role]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateRole(int $id, array $request) {
        try {
            $role = DB::transaction(function() use ($id, $request) {
                $role = Role::find($id);
                
                if (!$role) {
                    throw new Exception("Role not found");
                }

                $role->fill([
                    'name' => $request['name'] ?? $role->name,
                ])->save();

                return $role;
            });

            return response()->json(['status' => 'success', 'response' => $role]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteRole(int $id) {
        try {
            $role = DB::transaction(function() use ($id) {
                $role = Role::find($id);

                if (!$role) {
                    throw new Exception("Role not found");
                }

                $role->delete();

                return $role;
            });

            return response()->json(['status' => 'success', 'response' => 'Role deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
