@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">{{ __('Queue') }}</h2>
        <a href="{{ route('posts.create') }}" class="btn-primary">
            {{ __('Create New Post') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Scheduled Posts') }}</h3>
                @forelse($queuedPosts->where('queuedPost.is_scheduled', true) as $post)
                    <div class="border-b border-gray-200 last:border-0 py-4">
                        <x-post-card :post="$post" />
                    </div>
                @empty
                    <p class="text-gray-500 text-center">
                        {{ __('No scheduled posts.') }}
                    </p>
                @endforelse
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">{{ __('Queue') }}</h3>
                @forelse($queuedPosts->where('queuedPost.is_scheduled', false) as $post)
                    <div class="border-b border-gray-200 last:border-0 py-4">
                        <x-post-card :post="$post" />
                    </div>
                @empty
                    <p class="text-gray-500 text-center">
                        {{ __('Queue is empty.') }}
                    </p>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $queuedPosts->links() }}
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-4">
                <h3 class="text-lg font-semibold mb-4">{{ __('Posting Schedule') }}</h3>
                <x-schedule-calendar :schedules="$schedules" />
                
                <div class="mt-4">
                    <a href="{{ route('schedules.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        {{ __('Manage Schedule') }} →
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection