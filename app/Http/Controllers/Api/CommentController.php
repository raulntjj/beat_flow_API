<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\CommentService;
use App\Models\Comment;

class CommentController {
    protected $commentService;

    public function __construct(CommentService $commentService) {
        $this->commentService = $commentService;
    }

    public function index(Request $request){
        $params = [
            'getAllData' => $request->getAllData ?? true,
            'perPage' => $request->perPage ?? 10,
            'currentPage' => $request->currentPage ?? 1,
            'search' => $request->search ?? false,
            'filter' => $request->filter ?? false,
        ];
        return $this->commentService->getAllComments($params);
    }

    public function store(StoreCommentRequest $request){
        return $this->commentService->createComment($request->validated());
    }

    public function update(UpdateCommentRequest $request, int $id){
        return $this->commentService->updateComment($request->validated(), $id);
    }

    public function destroy(int $id){
        return $this->commentService->deleteComment($id);
    }
}
