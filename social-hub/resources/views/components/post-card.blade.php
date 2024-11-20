<div class="bg-white shadow rounded-lg p-6 mb-4">
    <div class="flex justify-between items-start mb-4">
        <div class="flex-1">
            <p class="text-gray-900">{{ $post->content }}</p>
            <p class="text-sm text-gray-500 mt-2">
                {{ $post->created_at->diffForHumans() }}
            </p>
        </div>
        <span class="px-2 py-1 text-xs rounded-full 
            {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 
               ($post->status === 'queued' ? 'bg-blue-100 text-blue-800' : 
               'bg-yellow-100 text-yellow-800') }}">
            {{ ucfirst($post->status) }}
        </span>
    </div>

    <div class="flex flex-wrap gap-2 mb-4">
        @foreach($post->platforms as $platform)
            <span class="px-2 py-1 text-xs bg-gray-100 rounded-full">
                {{ ucfirst($platform) }}
            </span>
        @endforeach
    </div>

    @if($post->queuedPost)
        <div class="text-sm text-gray-600">
            @if($post->queuedPost->is_scheduled)
                Scheduled for: {{ $post->queuedPost->scheduled_for->format('M d, Y H:i') }}
            @else
                In queue - Will be posted at next available time slot
            @endif
        </div>
    @endif

    @if($showActions && $post->status === 'queued')
        <div class="mt-4 flex gap-2">
            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                  onsubmit="return confirm('Are you sure you want to cancel this post?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">Cancel</button>
            </form>
        </div>
    @endif
</div>