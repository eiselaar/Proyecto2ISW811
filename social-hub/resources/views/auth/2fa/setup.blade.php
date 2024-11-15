<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurar Autenticación de Dos Factores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if(!auth()->user()->google2fa_enabled)
                        <div class="mb-4">
                            <p class="mb-4">
                                {{ __('La autenticación de dos factores agrega una capa adicional de seguridad a tu cuenta. Para comenzar:') }}
                            </p>
                            <ol class="list-decimal list-inside mb-4 space-y-2">
                                <li>{{ __('Descarga la aplicación Google Authenticator') }}</li>
                                <li>{{ __('Escanea el código QR con la aplicación') }}</li>
                                <li>{{ __('Ingresa el código de verificación generado') }}</li>
                            </ol>
                        </div>

                        <div class="mb-6">
                            {!! $qrCode !!}
                        </div>

                        <form method="POST" action="{{ route('2fa.enable') }}">
                            @csrf
                            <div>
                                <x-input-label for="code" :value="__('Código de Verificación')" />
                                <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" required autofocus />
                                <x-input-error :messages="$errors->get('code')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-primary-button>
                                    {{ __('Activar 2FA') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @else
                        <div class="mb-4">
                            <p class="text-green-600 font-semibold">
                                {{ __('La autenticación de dos factores está activada.') }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('2fa.disable') }}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button onclick="return confirm('¿Estás seguro de que deseas desactivar 2FA?')">
                                {{ __('Desactivar 2FA') }}
                            </x-danger-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>