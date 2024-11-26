@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white shadow-lg rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ __('Create New Post') }}</h2>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transform transition-all duration-200 ease-in-out hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <span class="mr-2">{{ __('Create Post') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-6">
                    <label for="content" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Content') }}
                    </label>
                    <div class="relative">
                        <textarea id="content" 
                                  name="content" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors duration-200 @error('content') border-red-500 @enderror"
                                  placeholder="Write your post content here..."
                                  required>{{ old('content') }}</textarea>
                        <div class="absolute bottom-3 right-3">
                            <span class="text-sm text-gray-500">
                                <span id="charCount">0</span>/280
                            </span>
                        </div>
                    </div>
                    @error('content')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-3">
                        {{ __('Platforms') }}
                    </label>
                    <div class="space-y-2 bg-gray-50 p-4 rounded-lg">
                        <label class="inline-flex items-center p-2 hover:bg-gray-100 rounded-md transition-colors duration-200">
                            <input type="checkbox" 
                                   name="platforms[]" 
                                   value="linkedin"
                                   class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500"
                                   {{ in_array('linkedin', old('platforms', [])) ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-700">LinkedIn</span>
                        </label>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-3">
                        {{ __('When to Post') }}
                    </label>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <label class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-colors duration-200">
                            <input type="radio" 
                                   name="schedule_type" 
                                   value="now" 
                                   class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500"
                                   {{ old('schedule_type', 'now') === 'now' ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-700">{{ __('Post Now') }}</span>
                        </label>
                        <label class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-colors duration-200">
                            <input type="radio" 
                                   name="schedule_type" 
                                   value="queue" 
                                   class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500"
                                   {{ old('schedule_type') === 'queue' ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-700">{{ __('Add to Queue') }}</span>
                        </label>
                        <label class="flex items-center p-2 hover:bg-gray-100 rounded-md transition-colors duration-200">
                            <input type="radio" 
                                   name="schedule_type" 
                                   value="scheduled" 
                                   class="form-radio h-5 w-5 text-blue-600 focus:ring-blue-500"
                                   {{ old('schedule_type') === 'scheduled' ? 'checked' : '' }}>
                            <span class="ml-3 text-gray-700">{{ __('Schedule for Specific Time') }}</span>
                        </label>
                    </div>
                </div>

                <div id="scheduled-time-container" class="mb-6" style="display: none;">
                    <label for="scheduled_for" class="block text-gray-700 text-sm font-bold mb-2">
                        {{ __('Schedule For') }}
                    </label>
                    <input type="datetime-local" 
                           id="scheduled_for" 
                           name="scheduled_for"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('scheduled_for') border-red-500 @enderror"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           value="{{ old('scheduled_for') }}">
                    @error('scheduled_for')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('content');
    const charCount = document.getElementById('charCount');
    
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 280) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
        }
    });
    
    const scheduleTypeInputs = document.querySelectorAll('input[name="schedule_type"]');
    const scheduledTimeContainer = document.getElementById('scheduled-time-container');
    
    scheduleTypeInputs.forEach(input => {
        input.addEventListener('change', function() {
            scheduledTimeContainer.style.display = 
                this.value === 'scheduled' ? 'block' : 'none';
        });
    });
});
</script>
@endsection
