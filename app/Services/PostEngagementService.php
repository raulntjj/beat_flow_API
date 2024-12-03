<?php

namespace App\Services;

use App\Models\PostEngagement;
use App\Events\PostEngagementEvent;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
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
                $engagements = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
            }
            return response()->json(['status' => 'success', 'response' => $engagements]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getUserPostEngagements(Array $request) {
        try {
            $userAuth = Auth::guard('api')->user();
            $engagements = PostEngagement::where('user_id', $userAuth->id)
		->where('post_id', $request['post_id'])
       		->get()
       		->toArray();
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
            $userAuth = Auth::guard('api')->user();
            $engagement = DB::transaction(function() use ($request) { 
                return PostEngagement::create([
                    'post_id' => $request['post_id'],
                    'user_id' => $request['user_id'],
                    'type' => $request['type'],
                ]);
            });

                $post = \App\Models\Post::find($request['post_id']);
            // Instanciando serviço
            $notificationService = app(NotificationService::class);
            $notificationService->createNotification([
                'user_id' => $post['user_id'],
                'type' => $request['type'],
                'is_read' => false,
                'notifier_name' => $userAuth->user
            ]);

            return response()->json(['status' => 'success', 'response' => $engagement]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    // public function updatePostEngagement(array $request, int $id) {
    //     try {
    //         $engagement = DB::transaction(function() use ($id, $request) {
    //             $engagement = PostEngagement::find($id);
                
    //             if (!$engagement) {
    //                 throw new Exception("PostEngagement not found");
    //             }

    //             $engagement->fill([
    //                 'post_id' => $request['post_id'] ?? $engagement->post_id,
    //                 'user_id' => $request['user_id'] ?? $engagement->user_id,
    //                 'type' => $request['type'] ?? $engagement->type,
    //             ])->save();

    //             return $engagement;
    //         });

    //         return response()->json(['status' => 'success', 'response' => $engagement]);
    //     } catch (Exception $e) {
    //         return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
    //     }
    // }

    public function deletePostEngagement(Array $request) {
        try {
            $engagement = DB::transaction(function() use ($request) {
                $engagement = PostEngagement::where('user_id', $request['user_id'])
                ->where('post_id', $request['post_id'])
                ->where('type', $request['type'])
                ->get();
        
                if (!$engagement) {
                    throw new Exception("PostEngagement not found");
                }

                foreach($engagement as $engagement_unity) {
                    $engagement_unity->delete();
                }

                return $engagement;
            });

            return response()->json(['status' => 'success', 'response' => 'PostEngagement deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
