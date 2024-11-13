<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Models\Follow;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService {    
    public function authenticatedUser(){
       $userAuth = Auth::guard('api')->user();
       $user = User::with([
            'followers',
            'followed',
            'newNotifications'
        ])
       ->where('id', $userAuth->id)
       ->get();
        return response()->json([
            'status' => 'success', 
            'response' => $user
        ]);
    }

    public function getUserFollowers(array $params) {
        try {
            $userAuth = Auth::guard('api')->user();        
            $query = Follow::where('followed_id', $userAuth->id);

            $followers = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            return response()->json(['status' => 'success', 'response' => $followers]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getUserFollowed(array $params) {
        try {
            $userAuth = Auth::guard('api')->user();        
            $query = Follow::where('follower_id', $userAuth->id);

            $followed = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            return response()->json(['status' => 'success', 'response' => $followed]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getUserNotifications(array $params) {
        try {
            $userAuth = Auth::guard('api')->user();        
            $query = Notification::where('user_id', $userAuth->id);

            $notifications = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            return response()->json(['status' => 'success', 'response' => $notifications]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getAllUsers(array $params) {
        try {
            $query = User::query();
            if ($params['search']){
                $query->where('name', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $users = $query->get();
            } else {
                $users = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
            return response()->json(['status' => 'success', 'response' => $users]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getUser(int $id) {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['status' => 'failed', 'response' => 'User not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createUser(array $request) {
        try {
            $user = DB::transaction(function() use ($request) { 
                return User::create([
                    'user' => $request['user'],
                    'name' => $request['name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'profile_photo' => $request['profile_photo'],
                    'bio' => $request['bio'],
                    'is_private' => $request['is_private'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateUser(int $id, array $request) {
        try {
            $user = DB::transaction(function() use ($id, $request) {
                $user = User::find($id);
                
                if (!$user) {
                    throw new Exception("User not found");
                }

                $user->fill([
                    'user' => $request['user'] ?? $user->user,
                    'name' => $request['name'] ?? $user->name,
                    'last_name' => $request['last_name'] ?? $user->last_name,
                    'email' => $request['email'] ?? $user->email,
                    'profile_photo' => $request['profile_photo'] ?? $user->profile_photo,
                    'password' => isset($request['password']) ? bcrypt($request['password']) : $user->password,
                    'bio' => $request['bio'] ?? $user->bio,
                    'is_private' => $request['is_private'] ?? $user->is_private,
                ])->save();

                return $user;
            });

            return response()->json(['status' => 'success', 'response' => $user]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteUser(int $id) {
        try {
            $user = DB::transaction(function() use ($id) {
                $user = User::find($id);

                if (!$user) {
                    throw new Exception("User not found");
                }

                $user->delete();

                return $user;
            });

            return response()->json(['status' => 'success', 'response' => 'User deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
