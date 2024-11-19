<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <p class="card-text">{{ $post->content }}</p>
                <small class="text-muted">
                    {{ $formattedDate() }}
                </small>
            </div>
            <span class="badge bg-{{ $statusColor() }}">
                {{ ucfirst($post->status) }}
            </span>
        </div>

        <div class="mb-3">
            @foreach($post->platforms as $platform)
                <span class="badge bg-primary me-1">{{ ucfirst($platform) }}</span>
            @endforeach
        </div>

        @if($post->queuedPost)
            <div class="mb-3">
                <small class="text-muted">
                    @if($post->queuedPost->is_scheduled)
                        Scheduled for: {{ $post->queuedPost->scheduled_for->format('M d, Y H:i') }}
                    @else
                        In queue - Will be posted at next available time slot
                    @endif
                </small>
            </div>
        @endif

        @if($showActions && $post->status === 'queued')
            <div class="mt-3">
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" 
                            onclick="return confirm('Are you sure?')">
                        Cancel
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>