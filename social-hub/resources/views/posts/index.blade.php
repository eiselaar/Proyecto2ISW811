<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Publicaciones') }}
            </h2>
            <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                Nueva Publicación
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form action="{{ route('posts.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <x-input-label for="status" :value="__('Estado')" />
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300">
                                <option value="">Todos</option>
                                <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="publicado" {{ request('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                                <option value="fallido" {{ request('status') == 'fallido' ? 'selected' : '' }}>Fallido</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="date_from" :value="__('Desde')" />
                            <x-text-input type="date" name="date_from" id="date_from" class="mt-1 block w-full" value="{{ request('date_from') }}" />
                        </div>
                        <div>
                            <x-input-label for="date_to" :value="__('Hasta')" />
                            <x-text-input type="date" name="date_to" id="date_to" class="mt-1 block w-full" value="{{ request('date_to') }}" />
                        </div>
                        <div class="flex items-end">
                            <x-primary-button>Filtrar</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Publicaciones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($posts->count() > 0)
                        <div class="space-y-6">
                            @foreach($posts as $post)
                                <div class="border-b pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-600">
                                                {{ $post->created_at->format('d/m/Y H:i') }}
                                                @if($post->scheduled_at)
                                                    - Programado para: {{ $post->scheduled_at->format('d/m/Y H:i') }}
                                                @endif
                                            </p>
                                            <p class="mt-2">{{ $post->content }}</p>
                                            <div class="mt-3 flex items-center space-x-2">
                                                @foreach($post->socialAccounts as $account)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $account->provider }}
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
                        <div class="mt-6">
                            {{ $posts->links() }}
                        </div>
                    @else
                        <p class="text-gray-500 text-center">No hay publicaciones disponibles.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
