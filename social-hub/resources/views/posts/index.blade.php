@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">{{ __('Posts') }}</h2>
        <a href="{{ route('posts.create') }}" class="btn-primary">
            {{ __('Create New Post') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            @forelse($posts as $post)
                <x-post-card :post="$post" />
            @empty
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-gray-500 text-center">
                        {{ __('No posts found. Create your first post!') }}
                    </p>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-4">
                <h3 class="text-lg font-semibold mb-4">{{ __('Quick Stats') }}</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Total Posts') }}</p>
                        <p class="text-2xl font-bold">{{ $posts->total() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Published') }}</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ $posts->where('status', 'published')->count() }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">{{ __('Queued') }}</p>
                        <p class="text-2xl font-bold text-blue-600">
                            {{ $posts->where('status', 'queued')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
