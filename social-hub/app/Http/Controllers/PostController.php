<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Events\PostPublished;
use App\Events\PostScheduled;
use App\Http\Requests\PostStoreRequest;
use App\Jobs\PublishPost;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()
            ->latest()
            ->paginate(10);
            
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        $connectedPlatforms = auth()->user()->socialAccounts()
            ->pluck('provider')
            ->unique();
            
        return view('posts.create', compact('connectedPlatforms'));
    }

    public function store(PostStoreRequest $request)
{
    $post = Post::create([
        'user_id' => auth()->id(),
        'content' => $request->content,
        'platforms' => $request->platforms,
        'status' => $request->schedule_type === 'now' ? 'pending' : 'queued',
    ]);

    if ($request->schedule_type === 'now') {
        // Notificar publicación inmediata
        auth()->user()->notify(new PostPublishedNotification($post));
    } else {
        // Notificar programación
        $post->queuedPost()->create([
            'scheduled_for' => $request->scheduled_for,
            'is_scheduled' => $request->schedule_type === 'scheduled'
        ]);
        auth()->user()->notify(new PostScheduledNotification($post));
    }

    return redirect()->route('posts.index')
        ->with('success', 'Post created successfully.');
}
}