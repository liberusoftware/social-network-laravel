<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\FriendshipController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\GroupPostController;
use App\Http\Controllers\GroupSearchController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\UserSearchController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Messages
    Route::get('/messages', [MessageController::class, 'index']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount']);
    Route::get('/messages/conversation/{user}', [MessageController::class, 'conversation']);
    Route::get('/messages/{message}', [MessageController::class, 'show']);
    Route::get('/messages/{message}/attachments/{attachment}', [MessageController::class, 'downloadAttachment']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);

    // Message Reactions
    Route::post('/messages/{message}/reactions', [MessageController::class, 'addReaction']);
    Route::delete('/messages/{message}/reactions/{emoji}', [MessageController::class, 'removeReaction']);

    // Typing Indicator
    Route::post('/messages/typing', [MessageController::class, 'typing']);

    // Conversations
    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations', [ConversationController::class, 'store']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
    Route::get('/conversations/{conversation}/messages', [MessageController::class, 'conversationMessages']);
    Route::post('/conversations/{conversation}/participants', [ConversationController::class, 'addParticipants']);
    Route::delete('/conversations/{conversation}/participants/{user}', [ConversationController::class, 'removeParticipant']);

    // Feed routes
    Route::get('/feed', [FeedController::class, 'index']);
    Route::get('/timeline/{userId}', [FeedController::class, 'timeline']);

    // Post routes
    Route::get('/posts', [PostController::class, 'index']);
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/posts/{id}', [PostController::class, 'show']);
    Route::put('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    // Comment routes
    Route::get('/posts/{postId}/comments', [CommentController::class, 'index']);
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{commentId}', [CommentController::class, 'update']);
    Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']);

    // Like routes
    Route::post('/posts/{postId}/like', [LikeController::class, 'toggle']);
    Route::get('/posts/{postId}/likes', [LikeController::class, 'index']);

    // Share routes
    Route::post('/posts/{postId}/share', [ShareController::class, 'toggle']);
    Route::get('/posts/{postId}/shares', [ShareController::class, 'index']);
});

// Friend request routes
Route::middleware('auth:sanctum')->prefix('friendships')->group(function () {
    Route::get('/', [FriendshipController::class, 'index']);
    Route::post('/send', [FriendshipController::class, 'send']);
    Route::post('/accept', [FriendshipController::class, 'accept']);
    Route::post('/reject', [FriendshipController::class, 'reject']);
});

// Follower routes
Route::middleware('auth:sanctum')->prefix('followers')->group(function () {
    Route::get('/', [FollowerController::class, 'index']);
    Route::post('/follow', [FollowerController::class, 'follow']);
    Route::post('/unfollow', [FollowerController::class, 'unfollow']);
});

// User search routes
Route::middleware(['auth:sanctum', 'throttle:user-search'])->get('/users/search', [UserSearchController::class, 'search']);

// Media routes
Route::middleware('auth:sanctum')->prefix('media')->group(function () {
    Route::get('/', [MediaController::class, 'index']);
    Route::post('/', [MediaController::class, 'store']);
    Route::get('/feed', [MediaController::class, 'feed']);
    Route::get('/gallery/{userId}', [MediaController::class, 'gallery']);
    Route::get('/{id}', [MediaController::class, 'show']);
    Route::put('/{id}', [MediaController::class, 'update']);
    Route::delete('/{id}', [MediaController::class, 'destroy']);
});

// Album routes
Route::middleware('auth:sanctum')->prefix('albums')->group(function () {
    Route::get('/', [AlbumController::class, 'index']);
    Route::post('/', [AlbumController::class, 'store']);
    Route::get('/public', [AlbumController::class, 'publicAlbums']);
    Route::get('/{id}', [AlbumController::class, 'show']);
    Route::put('/{id}', [AlbumController::class, 'update']);
    Route::delete('/{id}', [AlbumController::class, 'destroy']);
});

// Group routes
Route::middleware('auth:sanctum')->prefix('groups')->group(function () {
    // Group search and discovery (must be before /{id} routes)
    Route::get('/search/query', [GroupSearchController::class, 'search']);
    Route::get('/search/suggestions', [GroupSearchController::class, 'suggestions']);
    Route::get('/search/popular', [GroupSearchController::class, 'popular']);

    // Group CRUD
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
    Route::get('/{id}', [GroupController::class, 'show']);
    Route::put('/{id}', [GroupController::class, 'update']);
    Route::delete('/{id}', [GroupController::class, 'destroy']);

    // Group members
    Route::get('/{id}/members', [GroupController::class, 'members']);
    Route::get('/{id}/pending-members', [GroupController::class, 'pendingMembers']);

    // Group membership management
    Route::post('/{groupId}/join', [GroupMemberController::class, 'join']);
    Route::post('/{groupId}/leave', [GroupMemberController::class, 'leave']);
    Route::post('/{groupId}/members/{userId}/approve', [GroupMemberController::class, 'approve']);
    Route::post('/{groupId}/members/{userId}/reject', [GroupMemberController::class, 'reject']);
    Route::delete('/{groupId}/members/{userId}', [GroupMemberController::class, 'removeMember']);
    Route::put('/{groupId}/members/{userId}/role', [GroupMemberController::class, 'updateRole']);

    // Group posts
    Route::get('/{groupId}/posts', [GroupPostController::class, 'index']);
    Route::post('/{groupId}/posts', [GroupPostController::class, 'store']);
    Route::put('/{groupId}/posts/{postId}', [GroupPostController::class, 'update']);
    Route::delete('/{groupId}/posts/{postId}', [GroupPostController::class, 'destroy']);
});
