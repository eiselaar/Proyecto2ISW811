@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <!-- Estadísticas Generales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-indigo-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-indigo-700">Posts Programados</h3>
                        <p class="text-3xl font-bold">{{ $scheduledPosts ?? 0 }}</p>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-blue-700">Posts Publicados</h3>
                        <p class="text-3xl font-bold">{{ $publishedPosts ?? 0 }}</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-green-700">Cuentas Conectadas</h3>
                        <p class="text-3xl font-bold">{{ $connectedAccounts ?? 0 }}</p>
                    </div>
                </div>

                <!-- Posts Recientes y Cola -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Posts Recientes -->
                    <div class="bg-white rounded-lg border p-6">
                        <h2 class="text-lg font-semibold mb-4">Posts Recientes</h2>
                        @if(isset($recentPosts) && count($recentPosts) > 0)
                            <div class="space-y-4">
                                @foreach($recentPosts as $post)
                                    <div class="border-b pb-4">
                                        <p class="text-sm text-gray-600">{{ Str::limit($post->content, 100) }}</p>
                                        <div class="mt-2 flex justify-between items-center">
                                            <div class="flex space-x-2">
                                                @foreach($post->platforms as $platform)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst($platform) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                {{ $post->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No hay posts recientes.</p>
                        @endif
                    </div>

                    <!-- Cola de Publicación -->
                    <div class="bg-white rounded-lg border p-6">
                        <h2 class="text-lg font-semibold mb-4">Cola de Publicación</h2>
                        @if(isset($queuedPosts) && count($queuedPosts) > 0)
                            <div class="space-y-4">
                                @foreach($queuedPosts as $post)
                                    <div class="border-b pb-4">
                                        <p class="text-sm text-gray-600">{{ Str::limit($post->content, 100) }}</p>
                                        <div class="mt-2 flex justify-between items-center">
                                            <span class="text-xs text-blue-600">
                                                Programado para: {{ $post->queuedPost->scheduled_for->format('d M, H:i') }}
                                            </span>
                                            <div class="flex space-x-2">
                                                @foreach($post->platforms as $platform)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        {{ ucfirst($platform) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No hay posts en cola.</p>
                        @endif
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">Acciones Rápidas</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('posts.create') }}" 
                           class="flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Crear Nuevo Post
                        </a>
                        <a href="{{ route('schedules.index') }}" 
                           class="flex items-center justify-center px-4 py-2 border border-indigo-600 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">
                            Gestionar Horarios
                        </a>
                        <a href="{{ route('social.accounts') }}" 
                           class="flex items-center justify-center px-4 py-2 border border-indigo-600 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">
                            Conectar Cuentas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
