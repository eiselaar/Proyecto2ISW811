@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-6">{{ __('Create New Post') }}</h2>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Content') }}
                    </label>
                    <textarea id="content" name="content" rows="4"
                              class="form-input @error('content') border-red-500 @enderror"
                              required>{{ old('content') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">
                        <span id="charCount">0</span>/280 characters
                    </p>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Platforms') }}
                    </label>
                    @foreach($connectedPlatforms as $platform)
                        <label class="inline-flex items-center mt-2 mr-4">
                            <input type="checkbox" name="platforms[]" value="{{ $platform }}"
                                   class="form-checkbox" {{ in_array($platform, old('platforms', [])) ? 'checked' : '' }}>
                            <span class="ml-2">{{ ucfirst($platform) }}</span>
                        </label>
                    @endforeach
                    @error('platforms')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('When to Post') }}
                    </label>
                    <div class="space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="schedule_type" value="now" class="form-radio"
                                   {{ old('schedule_type', 'now') === 'now' ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('Post Now') }}</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="schedule_type" value="queue" class="form-radio"
                                   {{ old('schedule_type') === 'queue' ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('Add to Queue') }}</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="schedule_type" value="scheduled" class="form-radio"
                                   {{ old('schedule_type') === 'scheduled' ? 'checked' : '' }}>
                            <span class="ml-2">{{ __('Schedule for Specific Time') }}</span>
                        </label>
                    </div>
                </div>

                <div id="scheduled-time-container" class="mb-4" style="display: none;">
                    <label for="scheduled_for" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Schedule For') }}
                    </label>
                    <input type="datetime-local" id="scheduled_for" name="scheduled_for"
                           class="form-input @error('scheduled_for') border-red-500 @enderror"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           value="{{ old('scheduled_for') }}">
                    @error('scheduled_for')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="btn-primary">
                        {{ __('Create Post') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const content = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    const scheduleType = document.getElementsByName('schedule_type');
    const scheduledTimeContainer = document.getElementById('scheduled-time-container');

    content.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });

    scheduleType.forEach(input => {
        input.addEventListener('change', function() {
            scheduledTimeContainer.style.display = 
                this.value === 'scheduled' ? 'block' : 'none';
        });
    });

    // Inicializar estado
    const selectedType = document.querySelector('input[name="schedule_type"]:checked');
    if (selectedType && selectedType.value === 'scheduled') {
        scheduledTimeContainer.style.display = 'block';
    }
});
</script>
@endpush