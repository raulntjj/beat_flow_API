<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\Api\GenreController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\PostEngagementController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SharedPostController;
use App\Http\Controllers\Api\UserController;
use App\Http\Middleware;

Route::fallback(function () {
    return response()->json(['status' => 'failed', 'details' => 'Route not found'], 404);
});

Route::post('login', [UserController::class, 'login']);
Route::post('logout', [UserController::class, 'logout']);
Route::post('refresh', [UserController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    // Rotas para obter dados do usuário logado
    Route::get('me', [UserController::class, 'me']);
    Route::get('me/feed', [FeedController::class, 'myFeed']);
    Route::get('me/followers', [UserController::class, 'myFollowers']);
    Route::get('me/followed', [UserController::class, 'myFollowed']);
    Route::get('me/notifications', [UserController::class, 'myNotifications']);

    // Permissões para usuários comuns (Limited acess)
    Route::middleware([CheckPermission::class . ':user'])->group(function () {
        Route::apiResource('comments', CommentController::class)
        ->middleware(CheckOwnership::class . ':comment');

        Route::apiResource('posts', PostController::class)
        ->middleware(CheckOwnership::class . ':post');

        Route::apiResource('post-engagements', PostEngagementController::class)
        ->middleware(CheckOwnership::class . ':post_engagement');

        Route::apiResource('follows', FollowController::class)->only(['store', 'destroy'])
        ->middleware(CheckOwnership::class . ':follow');

        Route::apiResource('shared-posts', SharedPostController::class)
        ->middleware(CheckOwnership::class . ':shared_post');

        Route::apiResource('notifications', NotificationController::class)
        ->middleware(CheckOwnership::class . ':notification');
    });

    // Permissões para admins (Full access)
    Route::middleware([CheckPermission::class . ':admin'])->group(function () {
        Route::apiResource('comments', CommentController::class);
        Route::apiResource('feeds', FeedController::class);
        Route::apiResource('follows', FollowController::class);
        Route::apiResource('genres', GenreController::class);
        Route::apiResource('notifications', NotificationController::class);
        Route::apiResource('permissions', PermissionController::class);
        Route::apiResource('posts', PostController::class);
        Route::apiResource('post-engagements', PostEngagementController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('shared-posts', SharedPostController::class);
        Route::apiResource('users', UserController::class);
    });
});
