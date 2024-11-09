<?php

namespace App\Services;

use App\Models\Feed;
use Illuminate\Support\Facades\DB;
use Exception;

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
                $feeds = $query->paginate($params['perPage'], ['*'], 'page', $params['currentPage']);
            }
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
                    'name' => $request['name'],
                    'slug' => $request['slug'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $feed]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function updateFeed(int $id, array $request) {
        try {
            $feed = DB::transaction(function() use ($id, $request) {
                $feed = Feed::find($id);
                
                if (!$feed) {
                    throw new Exception("Feed not found");
                }

                $feed->fill([
                    'name' => $request['name'] ?? $feed->name,
                    'slug' => $request['slug'] ?? $feed->slug,
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
