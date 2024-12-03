@props(['post'])

<div class="bg-white rounded-lg shadow p-6 mb-4">
    <div class="flex justify-between items-start">
        <div class="flex-1">
            <p class="text-gray-900">{{ $post->content }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
                @foreach($post->platforms as $platform)
                <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <img 
                        src="{{ asset('storage/images/' . $platform . '.svg') }}"
                        alt="{{ $platform }}" 
                        class="w-4 h-4 mr-9"
                    >
                    {{ ucfirst($platform) }}
                </div>
                @endforeach
            </div>
        </div>
        <x-posts.post-status :status="$post->status" />
    </div>
    
    @if($post->queuedPost && $post->queuedPost->scheduled_for)
        <div class="mt-4 text-sm text-gray-500">
            Scheduled for: {{ $post->queuedPost->scheduled_for->format('M d, Y H:i') }}
        </div>
    @endif

    @if($slot->isNotEmpty())
        <div class="mt-4 flex justify-end space-x-2">
            {{ $slot }}
        </div>
    @endif
</div>