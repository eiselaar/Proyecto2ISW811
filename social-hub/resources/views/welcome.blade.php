<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjetas de Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Total de Publicaciones -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Total Publicaciones</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $totalPosts }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publicaciones Pendientes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Pendientes</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $pendingPosts }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Redes Conectadas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Redes Conectadas</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $socialAccountsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Publicaciones Programadas -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">Programadas</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $scheduledPosts }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Últimas Publicaciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Últimas Publicaciones</h3>
                    @if($recentPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentPosts as $post)
                                <div class="border-b pb-4 last:border-b-0 last:pb-0">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-500">{{ $post->created_at->format('d/m/Y H:i') }}</p>
                                            <p class="mt-1">{{ Str::limit($post->content, 150) }}</p>
                                            <div class="mt-2 flex space-x-2">
                                                @foreach($post->socialAccounts as $account)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst($account->provider) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            @if($post->status === 'pendiente')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                            @elseif($post->status === 'publicado')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Publicado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Fallido
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('posts.index') }}" class="text-indigo-600 hover:text-indigo-900">Ver todas las publicaciones →</a>
                        </div>
                    @else
                        <p class="text-gray-500">No hay publicaciones recientes.</p>
                    @endif
                </div>
            </div>

            <!-- Próximas Publicaciones Programadas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Próximas Publicaciones Programadas</h3>
                    @if($scheduledPosts->count() > 0)
                        <div class="space-y-4">
                            @foreach($scheduledPosts as $post)
                                <div class="flex items-center justify-between border-b pb-4 last:border-b-0 last:pb-0">
                                    <div>
                                        <p class="text-sm text-gray-500">Programada para: {{ $post->scheduled_at->format('d/m/Y H:i') }}</p>
                                        <p class="mt-1">{{ Str::limit($post->content, 100) }}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        @foreach($post->socialAccounts as $account)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($account->provider) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No hay publicaciones programadas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

