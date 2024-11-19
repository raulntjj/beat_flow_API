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
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckOwnership;

Route::fallback(function () {
    return response()->json(['status' => 'failed', 'details' => 'Route not found'], 404);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    // Rotas para obter dados do usuário logado
    Route::prefix('me')->group(function () {
        Route::get('/', [AuthenticatedSessionController::class, 'me']);
        Route::put('/', [AuthenticatedSessionController::class, 'changeUserData']);
        Route::delete('/', [AuthenticatedSessionController::class, 'deleteUserAccount']);
        Route::patch('/change-password', [AuthenticatedSessionController::class, 'changePassword']);
        Route::patch('/change-email', [AuthenticatedSessionController::class, 'changeEmail']);
    
        Route::get('/feed', [FeedController::class, 'myFeed']);
        Route::get('/followers', [AuthenticatedSessionController::class, 'myFollowers']);
        Route::get('/followed', [AuthenticatedSessionController::class, 'myFollowed']);
        Route::get('/notifications', [AuthenticatedSessionController::class, 'myNotifications']);
    });

    // Permissões para usuários comuns (Limited acess)
    Route::middleware([CheckPermission::class . ':User'])->group(function () {
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
    Route::middleware([CheckPermission::class . ':Admin'])->group(function () {
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
