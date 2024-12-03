@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                {{-- Stats Cards --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Posts</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['total_posts'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Queued Posts</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['queued_posts'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Scheduled Posts</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['scheduled_posts'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Published Posts</div>
                                <div class="text-lg font-semibold text-gray-900">{{ $stats['published_posts'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                {{-- Connected Platforms --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Connected Platforms</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    @foreach (['linkedin', 'mastodon', 'reddit'] as $platform)
                                        <div class="flex items-center space-x-2">
                                            <img src="{{ Storage::url('images/' . $platform . '.svg') }}"
                                                alt="{{ $platform }}" class="w-6 h-6">
                                            @if (in_array($platform, $connectedPlatforms))
                                                <form action="{{ route('social.disconnect', $platform) }}" method="POST"
                                                    class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        Disconnect
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('social.connect', $platform) }}"
                                                    class="text-blue-600 hover:text-blue-800">
                                                    Connect
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Post --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Quick Post</h2>
                        @if (count($connectedPlatforms) > 0)
                            <form action="{{ route('posts.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="schedule_type" value="now">

                                @if ($errors->any())
                                    <div
                                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-4">
                                    <textarea name="content" rows="3" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 @error('content') border-red-500 @enderror"
                                        placeholder="What's on your mind?">{{ old('content') }}</textarea>
                                    @error('content')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Post to: <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-x-4">
                                        @foreach ($connectedPlatforms as $platform)
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="platforms[]" value="{{ $platform }}"
                                                    {{ in_array($platform, old('platforms', [])) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 @error('platforms') border-red-500 @enderror">
                                                <span class="ml-2 text-sm text-gray-600">
                                                    {{ ucfirst($platform) }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('platforms')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Post Now
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center py-4">
                                <p class="text-gray-600">No social platforms connected.
                                    <a href="{{ route('social.connect', 'linkedin') }}"
                                        class="text-indigo-600 hover:text-indigo-500">
                                        Connect a platform
                                    </a>
                                    to start posting.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                {{-- Recent Posts --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Recent Posts</h2>
                        <div class="space-y-4">
                            @forelse($recentPosts as $post)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <p class="text-sm text-gray-500">
                                            Posted {{ $post->created_at->diffForHumans() }}
                                        </p>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full {{ match ($post->status) {
                                                'published' => 'bg-green-100 text-green-800',
                                                'scheduled' => 'bg-blue-100 text-blue-800',
                                                'queued' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            } }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-700">{{ $post->content }}</p>
                                    <div class="mt-2 flex gap-2">
                                        @foreach ($post->platforms as $platform)
                                        <img 
                                        src="{{ asset('storage/images/' . $platform . '.svg') }}"
                                        alt="{{ $platform }}" 
                                        class="w-4 h-4 mr-9"
                                    >
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">No recent posts.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Upcoming Posts --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Upcoming Posts</h2>
                        <div class="space-y-4">
                            @forelse($upcomingPosts as $post)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <p class="text-sm text-gray-500">
                                                Scheduled for {{ $post->scheduled_at->format('M j, Y g:i A') }}
                                            </p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            Scheduled
                                        </span>
                                    </div>
                                    <p class="text-gray-700">{{ $post->content }}</p>
                                    <div class="mt-2 flex gap-2">
                                        @foreach ($post->platforms as $platform)
                                            <img src="{{ Storage::url('images/' . $platform . '.svg') }}"
                                                alt="{{ $platform }}" class="w-6 h-6">
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500">No upcoming posts scheduled.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
