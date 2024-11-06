<?php

namespace App\Services;

use App\Models\Follow;
use Illuminate\Support\Facades\DB;
use Exception;

class FollowService {
    public function createFollow(array $request) {
        try {
            $follow = DB::transaction(function() use ($request) { 
                return Follow::create([
                    'followed_id' => $request['followed_id'],
                    'follower_id' => $request['follower_id'],
                ]);     
            });

            return response()->json(['status' => 'success', 'response' => $follow]);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }

    public function deleteFollow(int $id) {
        try {
            $follow = DB::transaction(function() use ($id) {
                $follow = Follow::find($id);

                if (!$follow) {
                    throw new Exception("Follow not found");
                }

                $follow->delete();

                return $follow;
            });

            return response()->json(['status' => 'success', 'response' => 'Follow deleted successfully']);
        } catch (Exception $e) {
            return response()->json(['status' => 'failed', 'response' => $e->getMessage()]);
        }
    }
}
