<?php

namespace App\Services;

use App\Models\PostEngagement;
use Illuminate\Support\Facades\DB;
use Exception;

class PostEngagementService {         
    public function getAllPostEngagements(array $params) {
        try {
            $query = PostEngagement::query();
            if ($params['search']){
                $query->where('content', 'like', '%' . $params['search'] . '%');
            }
            
            if ($params['getAllData']) {
                $engagements = $query->get();
            } else {
                $engagements = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
            return response()->json(['status' => 'success', 'response' => $engagements]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getPostEngagement(int $id) {
        try {
            $engagement = PostEngagement::find($id);

            if (!$engagement) {
                return response()->json(['status' => 'failed', 'response' => 'PostEngagement not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $engagement]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createPostEngagement(array $request) {
        try {
            $engagement = DB::transaction(function() use ($request) { 
                return PostEngagement::create([
                    'user_id' => $request['user_id'],
                    'content' => $request['content'],
                    'visibility' => $request['visibility'],
                    'media_type' => $request['media_type'],
                    'media_path' => $request['media_path'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $engagement]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updatePostEngagement(int $id, array $request) {
        try {
            $engagement = DB::transaction(function() use ($id, $request) {
                $engagement = PostEngagement::find($id);
                
                if (!$engagement) {
                    throw new Exception("PostEngagement not found");
                }

                $engagement->fill([
                    'user_id' => $request['user_id'] ?? $user_id->user_id,
                    'content' => $request['content'] ?? $engagement->content,
                    'visibility' => $request['visibility'] ?? $engagement->visibility,
                    'media_type' => $request['media_type'] ?? $engagement->media_type,
                    'media_path' => $request['media_path'] ?? $engagement->media_path,
                ])->save();

                return $engagement;
            });

            return response()->json(['status' => 'success', 'response' => $engagement]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deletePostEngagement(int $id) {
        try {
            $engagement = DB::transaction(function() use ($id) {
                $engagement = PostEngagement::find($id);

                if (!$engagement) {
                    throw new Exception("PostEngagement not found");
                }

                $engagement->delete();

                return $engagement;
            });

            return response()->json(['status' => 'success', 'response' => 'PostEngagement deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
