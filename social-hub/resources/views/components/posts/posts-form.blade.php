<form method="POST" action="{{ $action }}" {{ $attributes }}>
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-6">
        <!-- Content -->
        <div>
            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
            <textarea
                id="content"
                name="content"
                rows="4"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                required
            >{{ old('content', $post->content ?? '') }}</textarea>
            <p class="mt-1 text-sm text-gray-500">Characters: <span id="charCount">0</span>/280</p>
        </div>

        <!-- Platforms -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Platforms</label>
            <div class="mt-2 space-y-2">
                @foreach($platforms as $platform)
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="platforms[]"
                            value="{{ $platform }}"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            {{ in_array($platform, old('platforms', $post->platforms ?? [])) ? 'checked' : '' }}
                        >
                        <span class="ml-2">{{ ucfirst($platform) }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Schedule -->
        <div>
            <label class="block text-sm font-medium text-gray-700">When to Post</label>
            <div class="mt-2 space-y-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="schedule_type" value="now" class="text-indigo-600" checked>
                    <span class="ml-2">Post Now</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="schedule_type" value="queue" class="text-indigo-600">
                    <span class="ml-2">Add to Queue</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="schedule_type" value="scheduled" class="text-indigo-600">
                    <span class="ml-2">Schedule for Specific Time</span>
                </label>
            </div>
        </div>

        <!-- Scheduled Time -->
        <div id="scheduledTimeContainer" class="hidden">
            <label for="scheduled_for" class="block text-sm font-medium text-gray-700">Schedule For</label>
            <input
                type="datetime-local"
                name="scheduled_for"
                id="scheduled_for"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            {{ $slot }}
        </div>
    </div>
</form>
