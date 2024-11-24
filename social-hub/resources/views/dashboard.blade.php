// resources/views/dashboard.blade.php
@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-6">
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Total Posts</h3>
                            <p class="text-3xl font-semibold text-indigo-600">{{ $totalPosts ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Scheduled</h3>
                            <p class="text-3xl font-semibold text-indigo-600">{{ $scheduledPosts ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">Connected Accounts</h3>
                            <p class="text-3xl font-semibold text-indigo-600">{{ $connectedAccounts ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Posts and Schedule -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Posts -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Posts</h3>
                    <div class="space-y-4">
                        @forelse($recentPosts ?? [] as $post)
                            <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                <p class="text-gray-900">{{ $post->content }}</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($post->platforms as $platform)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($platform) }}
                                        </span>
                                    @endforeach
                                </div>
                                <div class="mt-2 text-sm text-gray-500">
                                    {{ $post->created_at->diffForHumans() }}
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No posts yet</p>
                        @endforelse
                    </div>
                </div>

                <!-- Schedule -->
                <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Schedule</h3>
                    <div class="grid grid-cols-8 gap-px bg-gray-200">
                        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Time</div>
                        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">
                                {{ $day }}
                            </div>
                        @endforeach

                        @foreach (range(0, 23) as $hour)
                            <div class="bg-white p-2 text-xs">{{ sprintf('%02d:00', $hour) }}</div>
                            @foreach (range(0, 6) as $day)
                                <div class="bg-white p-2">
                                    @if ($schedules->where('day_of_week', $day)->where('time', sprintf('%02d:00:00', $hour))->count())
                                        <div class="w-4 h-4 rounded-full bg-indigo-600 mx-auto"></div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('posts.create') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create New Post
                    </a>
                    <a href="{{ route('schedules.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Manage Schedule
                    </a>
                    <a href="{{ route('social.accounts') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Connect Accounts
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
@endsection
