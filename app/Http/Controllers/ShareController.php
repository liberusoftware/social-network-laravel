<?php

namespace App\Http\Controllers;

use App\Events\PostShared;
use App\Events\PostUnshared;
use App\Models\Post;
use App\Models\Share;

class ShareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle($postId)
    {
        $post = Post::findOrFail($postId);
        $this->authorize('view', $post);

        $share = Share::where('post_id', $post->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($share) {
            $share->delete();
            $shared = false;
            $sharesCount = $post->shares()->count();

            event(new PostUnshared($post, auth()->user(), $sharesCount));
        } else {
            Share::create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ]);
            $shared = true;
            $sharesCount = $post->shares()->count();

            event(new PostShared($post, auth()->user(), $sharesCount));
        }

        return response()->json([
            'shared' => $shared,
            'shares_count' => $sharesCount,
        ]);
    }

    public function index($postId)
    {
        $post = Post::findOrFail($postId);
        $this->authorize('view', $post);

        $shares = Share::where('post_id', $postId)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($shares);
    }
}
