<?php

namespace App\Services;

use App\Models\SharedPost;
use Illuminate\Support\Facades\DB;
use Exception;

class SharedPostService {         
    public function getAllSharedPosts(array $params) {
        try {
            $query = SharedPost::query();
            if ($params['search']){
                $query->where('content', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $sharedPosts = $query->get();
            } else {
                $sharedPosts = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
            return response()->json(['status' => 'success', 'response' => $sharedPosts]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getSharedPost(int $id) {
        try {
            $sharedPost = SharedPost::find($id);

            if (!$sharedPost) {
                return response()->json(['status' => 'failed', 'response' => 'SharedPost not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $sharedPost]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createSharedPost(array $request) {
        try {
            $sharedPost = DB::transaction(function() use ($request) { 
                return SharedPost::create([
                    'user_id' => $request['user_id'],
                    'content' => $request['content'],
                    'visibility' => $request['visibility'],
                    'media_type' => $request['media_type'],
                    'media_path' => $request['media_path'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $sharedPost]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateSharedPost(int $id, array $request) {
        try {
            $sharedPost = DB::transaction(function() use ($id, $request) {
                $sharedPost = SharedPost::find($id);
                
                if (!$sharedPost) {
                    throw new Exception("SharedPost not found");
                }

                $sharedPost->fill([
                    'user_id' => $request['user_id'] ?? $user_id->user_id,
                    'content' => $request['content'] ?? $sharedPost->content,
                    'visibility' => $request['visibility'] ?? $sharedPost->visibility,
                    'media_type' => $request['media_type'] ?? $sharedPost->media_type,
                    'media_path' => $request['media_path'] ?? $sharedPost->media_path,
                ])->save();

                return $sharedPost;
            });

            return response()->json(['status' => 'success', 'response' => $sharedPost]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteSharedPost(int $id) {
        try {
            $sharedPost = DB::transaction(function() use ($id) {
                $sharedPost = SharedPost::find($id);

                if (!$sharedPost) {
                    throw new Exception("SharedPost not found");
                }

                $sharedPost->delete();

                return $sharedPost;
            });

            return response()->json(['status' => 'success', 'response' => 'SharedPost deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
