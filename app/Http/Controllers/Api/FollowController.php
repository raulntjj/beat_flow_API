<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFollowRequest;
use App\Http\Requests\UpdateFollowRequest;
use App\Services\FollowService;
use App\Models\Follow;

class FollowController {
    protected $followService;

    public function __construct(FollowService $followService) {
        $this->followService = $followService;
    }
    
    public function store(StoreFollowRequest $request){
        return $this->followService->createFollow($request->validated());
    }

    public function destroy(Request $request){
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'follower_id' => 'required',
            'followed_id' => 'required',
        ], [
            'follower_id.required' => 'The follower_id field is required.',
            'followed_id.required' => 'The followed_id field is required.',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'response' => $validator->errors(),
            ], 200);
        }
    
        return $this->followService->deleteFollow($validated);
    }
}
