<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSharedPostRequest;
use App\Http\Requests\UpdateSharedPostRequest;
use App\Services\SharedPostService;
use App\Models\SharedPost;

class SharedPostController {
    protected $sharedPostService;

    public function __construct(SharedPostService $sharedPostService) {
        $this->sharedPostService = $sharedPostService;
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? true,
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->sharedPostService->getAllSharedPosts($params);
    }

    public function store(StoreSharedPostRequest $request){
        return $this->sharedPostService->createSharedPost($request->validated());
    }

    public function update(UpdateSharedPostRequest $request, int $id){
        return $this->sharedPostService->updateSharedPost($request->validated(), $id);
    }

    public function destroy(int $id){
        return $this->sharedPostService->deleteSharedPost($id);
    }
}
