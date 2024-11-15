<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nueva Publicación') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <x-input-label for="content" :value="__('Contenido')" />
                            <textarea id="content" name="content" rows="4" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>{{ old('content') }}</textarea>
                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label :value="__('Redes Sociales')" />
                            <div class="mt-2 space-y-2">
                                @foreach($socialAccounts as $account)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="social_accounts[]" value="{{ $account->id }}" 
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <span class="ml-2">{{ $account->provider }} ({{ $account->provider_id }})</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('social_accounts')" class="mt-2" />
                        </div>

                        <div class="mb-6">
                            <x-input-label :value="__('Tipo de Publicación')" />
                            <div class="mt-2 space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="publish_type" value="now" 
                                        class="border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        checked>
                                    <span class="ml-2">Publicar ahora</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="publish_type" value="queue" 
                                        class="border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2">Enviar a cola</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="publish_type" value="scheduled" 
                                        class="border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <span class="ml-2">Programar</span>
                                </label>
                            </div>
                        </div>

                        <div id="scheduleFields" class="mb-6 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="schedule_date" :value="__('Fecha')" />
                                    <x-text-input type="date" name="schedule_date" id="schedule_date" class="mt-1 block w-full" />
                                </div>
                                <div>
                                    <x-input-label for="schedule_time" :value="__('Hora')" />
                                    <x-text-input type="time" name="schedule_time" id="schedule_time" class="mt-1 block w-full" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>
                                {{ __('Crear Publicación') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const publishTypeInputs = document.querySelectorAll('input[name="publish_type"]');
            const scheduleFields = document.getElementById('scheduleFields');

            publishTypeInputs.forEach(input => {
                input.addEventListener('change', function() {
                    if (this.value === 'scheduled') {
                        scheduleFields.classList.remove('hidden');
                    } else {
                        scheduleFields.classList.add('hidden');
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>