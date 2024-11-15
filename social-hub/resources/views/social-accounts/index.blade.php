<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Redes Sociales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Sección para conectar nuevas redes sociales -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Conectar Nuevas Redes Sociales') }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Twitter -->
                        @if(!$connectedProviders->contains('twitter'))
                            <div class="border rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold mb-2">Twitter/X</h4>
                                <p class="text-sm text-gray-600 mb-4">Conecta tu cuenta de Twitter/X para programar tweets.</p>
                                <a href="{{ route('social.connect', 'twitter') }}" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                                    Conectar Twitter
                                </a>
                            </div>
                        @endif

                        <!-- Mastodon -->
                        @if(!$connectedProviders->contains('mastodon'))
                            <div class="border rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.268 14.815c-.3 1.536-2.675 3.21-5.403 3.533-1.423.166-2.824.317-4.318.25-2.445-.108-4.368-.634-4.368-.634 0 .26.016.507.048.742.347 2.627 2.61 2.782 4.75 2.857 2.163.075 4.087-.53 4.087-.53l.09 1.975s-1.512.813-4.207.963c-1.485.083-3.33-.037-5.475-.595-4.659-1.124-5.462-5.643-5.582-10.232-.024-.873-.036-1.698-.012-2.468.137-5.174 2.068-8.695 7.706-9.335.933-.106 4.062-.223 7.398.475 3.874.813 4.217 4.208 4.374 5.455l.04.705c0 1.645-.044 3.218-.13 4.84zm-2.792-4.598c0-1.228-.305-2.205-.916-2.93-.63-.748-1.456-1.13-2.478-1.13-1.183 0-2.08.456-2.69 1.37l-.58.972-.582-.97c-.608-.915-1.505-1.372-2.688-1.372-1.022 0-1.848.382-2.478 1.13-.61.725-.915 1.702-.915 2.93v6.17h2.442v-5.993c0-1.228.517-1.85 1.552-1.85 1.144 0 1.716.744 1.716 2.214v3.233h2.428v-3.233c0-1.47.572-2.214 1.716-2.214 1.035 0 1.552.622 1.552 1.85v5.993h2.442v-6.17z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold mb-2">Mastodon</h4>
                                <p class="text-sm text-gray-600 mb-4">Conecta tu cuenta de Mastodon para programar toots.</p>
                                <a href="{{ route('social.connect', 'mastodon') }}" 
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                                    Conectar Mastodon
                                </a>
                            </div>
                        @endif

                        <!-- Reddit -->
                        @if(!$connectedProviders->contains('reddit'))
                            <div class="border rounded-lg p-6 text-center">
                                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 0 0-.231.094.33.33 0 0 0 0 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 0 0 .029-.463.33.33 0 0 0-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 0 0-.232-.095z"/>
                                    </svg>
                                </div>
                                <h4 class="font-semibold mb-2">Reddit</h4>
                                <p class="text-sm text-gray-600 mb-4">Conecta tu cuenta de Reddit para programar posts.</p>
                                <a href="{{ route('social.connect', 'reddit') }}" 
                                    class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700">
                                    Conectar Reddit
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Redes sociales conectadas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ __('Redes Sociales Conectadas') }}
                    </h3>
                    
                    @if($socialAccounts->count() > 0)
                        <div class="space-y-4">
                            @foreach($socialAccounts as $account)
                                <div class="border rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4">
                                            <!-- Iconos específicos para cada red social -->
                                            @if($account->provider === 'twitter')
                                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                    </svg>
                                                </div>
                                            @elseif($account->provider === 'mastodon')
                                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M23.268 14.815c-.3 1.536-2.675 3.21-5.403 3.533-1.423.166-2.824.317-4.318.25-2.445-.108-4.368-.634-4.368-.634 0 .26.016.507.048.742.347 2.627 2.61 2.782 4.75 2.857 2.163.075 4.087-.53 4.087-.53l.09 1.975s-1.512.813-4.207.963c-1.485.083-3.33-.037-5.475-.595-4.659-1.124-5.462-5.643-5.582-10.232-.024-.873-.036-1.698-.012-2.468.137-5.174 2.068-8.695 7.706-9.335.933-.106 4.062-.223 7.398.475 3.874.813 4.217 4.208 4.374 5.455l.04.705c0 1.645-.044 3.218-.13 4.84zm-2.792-4.598c0-1.228-.305-2.205-.916-2.93-.63-.748-1.456-1.13-2.478-1.13-1.183 0-2.08.456-2.69 1.37l-.58.972-.582-.97c-.608-.915-1.505-1.372-2.688-1.372-1.022 0-1.848.382-2.478 1.13-.61.725-.915 1.702-.915 2.93v6.17h2.442v-5.993c0-1.228.517-1.85 1.552-1.85 1.144 0 1.716.744 1.716 2.214v3.233h2.428v-3.233c0-1.47.572-2.214 1.716-2.214 1.035 0 1.552.622 1.552 1.85v5.993h2.442v-6.17z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a .342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            
                                            <div>
                                                <h4 class="font-semibold">{{ ucfirst($account->provider) }}</h4>
                                                <p class="text-sm text-gray-600">
                                                    @if($account->provider === 'mastodon')
                                                        {{ $account->nickname }}@{{ $account->instance }}
                                                    @else
                                                        {{ $account->nickname }}
                                                    @endif
                                                </p>
                                                <p class="text-sm text-gray-600">Conectado el {{ $account->created_at->format('d/m/Y') }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-4">
                                            <!-- Status de la conexión -->
                                            @if($account->isTokenValid())
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Conectado
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Reconexión necesaria
                                                </span>
                                            @endif

                                            <div class="flex space-x-2">
                                                <!-- Botón para reautenticar si es necesario -->
                                                @if(!$account->isTokenValid())
                                                    <a href="{{ route('social.reconnect', $account) }}" 
                                                        class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                                        Reconectar
                                                    </a>
                                                @endif

                                                <!-- Botón de desconexión -->
                                                <form action="{{ route('social.disconnect', $account) }}" method="POST" 
                                                    onsubmit="return confirm('¿Estás seguro de que quieres desconectar esta cuenta?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 font-medium text-sm">
                                                        Desconectar
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Información adicional específica de cada red social -->
                                    <div class="mt-4 pl-14">
                                        @if($account->provider === 'mastodon')
                                            <p class="text-sm text-gray-600">Instancia: {{ $account->instance }}</p>
                                        @elseif($account->provider === 'reddit')
                                            <p class="text-sm text-gray-600">Karma: {{ $account->karma }}</p>
                                        @endif

                                        @if($account->last_used_at)
                                            <p class="text-sm text-gray-600">
                                                Último uso: {{ $account->last_used_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center">No hay redes sociales conectadas.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>