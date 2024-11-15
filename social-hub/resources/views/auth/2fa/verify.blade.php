<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Verificación de Dos Factores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-4">
                        <p>
                            {{ __('Por favor, ingresa el código de verificación generado por tu aplicación Google Authenticator.') }}
                        </p>
                    </div>

                    <form method="POST" action="{{ route('2fa.validate') }}">
                        @csrf
                        <div>
                            <x-input-label for="code" :value="__('Código de Verificación')" />
                            <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" required autofocus />
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Verificar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>