<x-layouts.app-layout> 

<x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </x-slot>
    
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-6">
                <x-ui.card>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-500 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Posts</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalPosts'] }}</p>
                        </div>
                    </div>
                </x-ui.card>
    
                <x-ui.card>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Connected Accounts</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['connectedAccounts'] }}</p>
                        </div>
                    </div>
                </x-ui.card>
    
                <x-ui.card>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Scheduled Posts</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['scheduledPosts'] }}</p>
                        </div>
                    </div>
                </x-ui.card>
    
                <x-ui.card>
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Engagement</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalEngagement'] }}</p>
                        </div>
                    </div>
                </x-ui.card>
            </div>
    
            <!-- Upcoming Posts -->
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Upcoming Posts</h3>
                    <x-forms.button href="{{ route('posts.index') }}">View All</x-forms.button>
                </div>
                
                <div class="space-y-4">
                    @forelse($upcomingPosts as $post)
                        <x-posts.post-card :post="$post" />
                    @empty
                        <p class="text-gray-500 text-center py-4">No upcoming posts scheduled</p>
                    @endforelse
                </div>
            </div>
    
            <!-- Connected Accounts -->
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Connected Accounts</h3>
                    <x-forms.button @click="$dispatch('open-modal', 'connect-account')">
                        Connect New Account
                    </x-forms.button>
                </div>
    
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($socialAccounts as $account)
                        <x-social.account-card :account="$account" />
                    @endforeach
                </div>
            </div>
    
            <!-- Weekly Schedule -->
            <div class="p-6 border-t border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Weekly Schedule</h3>
                    <x-forms.button href="{{ route('schedules.index') }}">Full Calendar</x-button>
                </div>
                
                <x-schedule.week-view />
            </div>
        </div>
    
        <!-- Connect Account Modal -->
        <x-ui.modal name="connect-account" :show="false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Connect Social Media Account
                </h2>
    
                <div class="space-y-4">
                    @foreach($availablePlatforms as $platform)
                        <a href="{{ route('social.connect', $platform) }}" 
                           class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <x-social.platform-icon :platform="$platform" />
                                <span class="ml-3 font-medium text-gray-900">
                                    Connect {{ ucfirst($platform) }}
                                </span>
                            </div>
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        </x-ui.modal>
    
</x-layouts.app-layout>


</x-modal>