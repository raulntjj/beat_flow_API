<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Exception;

class PostService {         
    public function getAllPosts(array $params) {
        try {
            $query = Post::query();
            if ($params['search']){
                $query->where('content', 'like', '%' . $params['search'] . '%');
            }

            // Se passar valor para sortByEngagements
            if ($params['sortByEngagements']) {
                // o valor deverá conter ['asc', 'desc']
                $query->orderBy('id', $params['sortByEngagements']);
            // Se passar valor para sortByDate
            } else if ($params['sortByDate']) {
                // o valor deverá conter ['asc', 'desc']
                $query->orderBy('id', $params['sortByDate']);
            }

            if ($params['getAllData']) {
                $posts = $query->get();
            } else {
                $posts = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
            return response()->json(['status' => 'success', 'response' => $posts]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getPost(int $id) {
        try {
            $post = Post::find($id);

            if (!$post) {
                return response()->json(['status' => 'failed', 'response' => 'Post not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $post]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createPost(array $request) {
        try {
            $post = DB::transaction(function() use ($request) { 
                return Post::create([
                    'user_id' => $request['user_id'],
                    'content' => $request['content'],
                    'visibility' => $request['visibility'],
                    'media_type' => $request['media_type'],
                    'media_path' => $request['media_path'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $post]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updatePost(int $id, array $request) {
        try {
            $post = DB::transaction(function() use ($id, $request) {
                $post = Post::find($id);
                
                if (!$post) {
                    throw new Exception("Post not found");
                }

                $post->fill([
                    'user_id' => $request['user_id'] ?? $user_id->user_id,
                    'content' => $request['content'] ?? $post->content,
                    'visibility' => $request['visibility'] ?? $post->visibility,
                    'media_type' => $request['media_type'] ?? $post->media_type,
                    'media_path' => $request['media_path'] ?? $post->media_path,
                ])->save();

                return $post;
            });

            return response()->json(['status' => 'success', 'response' => $post]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deletePost(int $id) {
        try {
            $post = DB::transaction(function() use ($id) {
                $post = Post::find($id);

                if (!$post) {
                    throw new Exception("Post not found");
                }

                $post->delete();

                return $post;
            });

            return response()->json(['status' => 'success', 'response' => 'Post deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
