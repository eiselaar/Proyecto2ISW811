<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Events\PostPublished;
use App\Events\PostScheduled;
use App\Http\Requests\PostStoreRequest;
use App\Jobs\PublishPost;
use App\Notifications\PostPublishedNotification;
use Illuminate\Support\Facades\Log;
use Exception;

class PostController extends Controller
{
    private function logError($message, $exception = null, $context = []) {
        $logContext = array_merge([
            'user_id' => auth()->id(),
            'exception' => $exception ? [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ] : null
        ], $context);

        Log::error($message, $logContext);
    }

    private function logInfo($message, $context = []) {
        $logContext = array_merge([
            'user_id' => auth()->id(),
            'timestamp' => now()->toDateTimeString()
        ], $context);

        Log::info($message, $logContext);
    }

    public function index()
    {
        try {
            $this->logInfo('Fetching posts for user');
            
            $posts = auth()->user()->posts()
                ->latest()
                ->paginate(10);

            $this->logInfo('Posts fetched successfully', [
                'count' => $posts->count(),
                'total' => $posts->total()
            ]);

            return view('posts.index', compact('posts'));
        } catch (Exception $e) {
            $this->logError('Error fetching posts', $e);
            throw $e;
        }
    }

    public function create()
    {
        try {
            $this->logInfo('Fetching connected platforms');
            
            $connectedPlatforms = auth()->user()->socialAccounts()
                ->pluck('provider')
                ->unique();

            $this->logInfo('Platforms fetched successfully', [
                'platforms' => $connectedPlatforms->toArray()
            ]);

            return view('posts.create', compact('connectedPlatforms'));
        } catch (Exception $e) {
            $this->logError('Error fetching connected platforms', $e);
            throw $e;
        }
    }

    public function store(PostStoreRequest $request)
{
    try {
        $this->logInfo('Starting post creation process', [
            'content_length' => strlen($request->content),
            'platforms' => $request->platforms,
            'schedule_type' => $request->schedule_type,
            'has_scheduled_for' => isset($request->scheduled_for)
        ]);

        // Create post - Cambiamos 'pending' por 'draft' que sí está en el enum
        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $request->content,
            'platforms' => $request->platforms,
            'status' => $request->schedule_type === 'now' ? 'draft' : 'queued', // Cambiado 'pending' por 'draft'
        ]);

        $this->logInfo('Post created successfully', [
            'post_id' => $post->id,
            'status' => $post->status
        ]);

        if ($request->schedule_type === 'now') {
            try {
                auth()->user()->notify(new PostPublishedNotification($post));
                $this->logInfo('Immediate publication notification sent', [
                    'post_id' => $post->id
                ]);
            } catch (Exception $e) {
                $this->logError('Failed to send immediate publication notification', $e, [
                    'post_id' => $post->id
                ]);
            }
        } else {
            try {
                $queuedPost = $post->queuedPost()->create([
                    'scheduled_for' => $request->scheduled_for,
                    'is_scheduled' => $request->schedule_type === 'scheduled'
                ]);

                $this->logInfo('Post scheduled successfully', [
                    'post_id' => $post->id,
                    'queued_post_id' => $queuedPost->id,
                    'scheduled_for' => $request->scheduled_for
                ]);
            } catch (Exception $e) {
                $this->logError('Failed to schedule post', $e, [
                    'post_id' => $post->id,
                    'scheduled_for' => $request->scheduled_for
                ]);
                throw $e;
            }
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');

    } catch (Exception $e) {
        $this->logError('Post creation failed', $e, [
            'request_data' => $request->except(['content'])
        ]);
        
        return redirect()->back()
            ->with('error', 'Failed to create post. Please try again.')
            ->withInput();
    }
}
}