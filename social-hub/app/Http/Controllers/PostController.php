<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\QueuedPost;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            ->toArray();

        return view('posts.create', compact('connectedPlatforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:280',
            'platforms' => 'required|array',
            'platforms.*' => 'required|string|in:twitter,reddit,mastodon',
            'schedule_type' => 'required|in:now,queue,scheduled',
            'scheduled_for' => 'required_if:schedule_type,scheduled|nullable|date|after:now',
        ]);

        $post = auth()->user()->posts()->create([
            'content' => $validated['content'],
            'platforms' => $validated['platforms'],
            'status' => $validated['schedule_type'] === 'now' ? 'pending' : 'queued',
        ]);

        if ($validated['schedule_type'] !== 'now') {
            QueuedPost::create([
                'post_id' => $post->id,
                'scheduled_for' => $validated['scheduled_for'] ?? null,
                'is_scheduled' => $validated['schedule_type'] === 'scheduled',
            ]);
        } else {
            // Dispatch job to publish immediately
            // PublishPost::dispatch($post);
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    public function queue()
    {
        $queuedPosts = auth()->user()->posts()
            ->whereHas('queuedPost')
            ->with('queuedPost')
            ->latest()
            ->paginate(10);

        return view('posts.queue', compact('queuedPosts'));
    }
}