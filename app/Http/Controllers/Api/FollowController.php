<?php

namespace App\Http\Controllers;

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
        return $this->followService->createFollow($request);
    }

    public function destroy(int $id){
        return $this->followService->deleteFollow($id);
    }
}
