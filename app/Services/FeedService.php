<?php

namespace App\Services;

use App\Models\Feed;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class FeedService {         
    public function getAllFeeds(array $params) {
        try {
            $query = Feed::query();
            if ($params['search']){
                $query->where('name', 'like', '%' . $params['search'] . '%');
            }

            if ($params['getAllData']) {
                $feeds = $query->get();
            } else {
                $feeds = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
            }
            return response()->json(['status' => 'success', 'response' => $feeds]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function getUserFeed(array $params) {
        try {
            $userAuth = Auth::guard('api')->user();
    
            // Busca os posts e compartilhamentos relacionados às pessoas que o usuário segue
            $query = Feed::query()
                ->with(['post.user', 'sharedPost.post.user'])
		->select('feeds.*')
                ->where(function ($query) use ($userAuth) {
                    $query->whereHas('post.user', function ($queryUser) use ($userAuth) {
                        $queryUser->whereIn('id', function ($q) use ($userAuth) {
                            $q->select('followed_id')
                                ->from('follows')
                                ->where('follower_id', $userAuth->id);
                        });
                    })
                    ->orWhereHas('sharedPost.post.user', function ($queryUser) use ($userAuth) {
                        $queryUser->whereIn('id', function ($q) use ($userAuth) {
                            $q->select('followed_id')
                                ->from('follows')
                                ->where('follower_id', $userAuth->id);
                        });
                    });
                })
	    	->leftJoin('posts', 'feeds.post_id', '=', 'posts.id') // Faz join com posts
		->leftJoin('posts as shared_posts', 'feeds.shared_post_id', '=', 'shared_posts.id') // Faz join com shared posts
		->orderByRaw('GREATEST(
        		COALESCE(posts.created_at, 0),
			COALESCE(shared_posts.created_at, 0)
    		) DESC'); // Ordena pelo mais recente entre posts e shared posts
    
            // Paginação
            $feeds = $query->paginate($params['perPage'], ['*'], 'page', $params['page']);
    
            return response()->json(['status' => 'success', 'response' => $feeds]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }    

    public function getFeed(int $id) {
        try {
            $feed = Feed::find($id);

            if (!$feed) {
                return response()->json(['status' => 'failed', 'response' => 'Feed not found'], 404);
            }

            return response()->json(['status' => 'success', 'response' => $feed]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function createFeed(array $request) {
        try {
            $feed = DB::transaction(function() use ($request) { 
                return Feed::create([
                    'post_id' => $request['post_id'],
                    'shared_post_id' => $request['user_id'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $feed]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateFeed(array $request, int $id) {
        try {
            $feed = DB::transaction(function() use ($id, $request) {
                $feed = Feed::find($id);
                
                if (!$feed) {
                    throw new Exception("Feed not found");
                }

                $feed->fill([
                    'post_id' => $request['post_id'] ?? $feed->post_id,
                    'shared_post_id' => $request['shared_post_id'] ?? $feed->shared_post_id,
                    ])->save();

                return $feed;
            });

            return response()->json(['status' => 'success', 'response' => $feed]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteFeed(int $id) {
        try {
            $feed = DB::transaction(function() use ($id) {
                $feed = Feed::find($id);

                if (!$feed) {
                    throw new Exception("Feed not found");
                }

                $feed->delete();

                return $feed;
            });

            return response()->json(['status' => 'success', 'response' => 'Feed deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}