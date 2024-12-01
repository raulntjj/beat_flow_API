<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Services\FeedService;
use App\Models\Feed;

class FeedController {
    protected $feedService;

    public function __construct(FeedService $feedService) {
        $this->feedService = $feedService;
    }
    
    public function myFeed(Request $params) {
        $params = [
            'perPage' => $request->perPage ?? 10,
            'page' => $request->page ?? 1,
        ];
        return $this->feedService->getUserFeed($params);
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? false,
            'perPage' => $request->perPage ?? 10,
            'page' => $request->page ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->feedService->getAllFeeds($params);
    }


    public function store(StoreFeedRequest $request){
        return $this->feedService->createFeed($request->validated());
    }

    public function update(UpdateFeedRequest $request, int $id){
        return $this->feedService->updateFeed($request->validated(), $id);
    }

    public function destroy(int $id){
        return $this->feedService->deleteFeed($id);
    }
}
