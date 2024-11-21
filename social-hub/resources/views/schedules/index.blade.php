@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">{{ __('Posting Schedule') }}</h2>
            <button
                type="button"
                class="btn-primary"
                onclick="window.location.href='{{ route('posts.create') }}'"
            >
                {{ __('Create New Post') }}
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg p-6">
                    <div id="schedule-calendar"></div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Quick Stats') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Active Time Slots') }}</p>
                            <p class="text-2xl font-bold">{{ $schedules->where('is_active', true)->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Posts in Queue') }}</p>
                            <p class="text-2xl font-bold text-blue-600">
                                {{ $queuedPostsCount }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Next Scheduled Post') }}</p>
                            @if($nextScheduledPost)
                                <p class="text-sm font-medium">
                                    {{ $nextScheduledPost->scheduled_for->format('M d, Y H:i') }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500">{{ __('No scheduled posts') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <h4 class="text-sm font-semibold mb-2">{{ __('Quick Settings') }}</h4>
                        <form action="{{ route('schedules.bulk-update') }}" method="POST">
                            @csrf
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="weekend_posts" class="form-checkbox"
                                           {{ $weekendPostsEnabled ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm">{{ __('Enable weekend posts') }}</span>
                                </label>
                                <button type="submit" class="btn-primary w-full mt-4">
                                    {{ __('Save Settings') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection