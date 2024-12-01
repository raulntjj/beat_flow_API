<?php

namespace App\Services;

use App\Models\SharedPost;
use App\Models\Feed;
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
                $sharedPosts = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
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
                    'post_id' => $request['post_id'],
                    'user_id' => $request['user_id'],
                    'comment' => $request['comment'],
                ]);     
            });

            // Criando um feed para o compartilhamento criado
            Feed::create([
                'post_id' => null,
                'shared_post_id' => $sharedPost->id,
            ]);

            return response()->json(['status' => 'success', 'response' => $sharedPost]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateSharedPost(array $request, int $id) {
        try {
            $sharedPost = DB::transaction(function() use ($id, $request) {
                $sharedPost = SharedPost::find($id);
                
                if (!$sharedPost) {
                    throw new Exception("SharedPost not found");
                }

                $sharedPost->fill([
                    'post_id' => $request['post_id'] ?? $sharedPost->post_id,
                    'user_id' => $request['user_id'] ?? $sharedPost->user_id,
                    'comment' => $request['comment'] ?? $sharedPost->comment,
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
