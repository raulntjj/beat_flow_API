<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Exception;

class CommentService {         
    public function getAllComments(array $params) {
        try {
            $query = Comment::query();
            if ($params['search']){
                $query->where('content', 'like', '%' . $params['search'] . '%');
            }
            
            if ($params['getAllData']) {
                $commentPosts = $query->get();
            } else {
                $commentPosts = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
            return response()->json(['status' => 'success', 'response' => $commentPosts]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getComment(int $id) {
        try {
            $commentPost = Comment::find($id);

            if (!$commentPost) {
                return response()->json(['status' => 'failed', 'response' => 'Comment not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $commentPost]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createComment(array $request) {
        try {
            $commentPost = DB::transaction(function() use ($request) { 
                return Comment::create([
                    'user_id' => $request['user_id'],
                    'content' => $request['content'],
                    'visibility' => $request['visibility'],
                    'media_type' => $request['media_type'],
                    'media_path' => $request['media_path'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $commentPost]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateComment(int $id, array $request) {
        try {
            $commentPost = DB::transaction(function() use ($id, $request) {
                $commentPost = Comment::find($id);
                
                if (!$commentPost) {
                    throw new Exception("Comment not found");
                }

                $commentPost->fill([
                    'user_id' => $request['user_id'] ?? $user_id->user_id,
                    'content' => $request['content'] ?? $commentPost->content,
                    'visibility' => $request['visibility'] ?? $commentPost->visibility,
                    'media_type' => $request['media_type'] ?? $commentPost->media_type,
                    'media_path' => $request['media_path'] ?? $commentPost->media_path,
                ])->save();

                return $commentPost;
            });

            return response()->json(['status' => 'success', 'response' => $commentPost]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteComment(int $id) {
        try {
            $commentPost = DB::transaction(function() use ($id) {
                $commentPost = Comment::find($id);

                if (!$commentPost) {
                    throw new Exception("Comment not found");
                }

                $commentPost->delete();

                return $commentPost;
            });

            return response()->json(['status' => 'success', 'response' => 'Comment deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
