<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\PostService;
use App\Models\Post;

class PostController {
    protected $postService;

    public function __construct(PostService $postService) {
        $this->postService = $postService;
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? false,
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
            'sortByDate' => $request->sortByDate ?? false,
            'sortByEngagements' => $request->sortByDate ?? 'ASC',
        ];
        return $this->postService->getAllPosts($params);
    }

    public function show(int $id){
        return $this->postService->getPost($id);
    }

    public function store(StorePostRequest $request){
        return $this->postService->createPost($request->validated());
    }

    public function update(UpdatePostRequest $request, int $id){
        return $this->postService->updatePost($request->validated(), $id);
    }

    public function destroy(int $id){
        return $this->postService->deletePost($id);
    }
}
