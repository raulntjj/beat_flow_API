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
                    'post_id' => $request['post_id'],
                    'user_id' => $request['user_id'],
                    'content' => $request['content'],
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
                    'post_id' => $request['post_id'] ?? $commentPost->post_id,
                    'user_id' => $request['user_id'] ?? $commentPost->user_id,
                    'content' => $request['content'] ?? $commentPost->content,
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
