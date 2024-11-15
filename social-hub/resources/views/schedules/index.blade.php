<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Horarios de Publicación') }}
            </h2>
            <button @click="$dispatch('open-modal', 'add-schedule')" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Agregar Horario
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Vista de Calendario -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-7 gap-4">
                        @foreach(['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $index => $day)
                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-lg mb-4 text-center text-gray-700">{{ $day }}</h3>
                                <div class="space-y-2">
                                    @foreach($schedules->where('day_of_week', $index) as $schedule)
                                        <div class="bg-gray-50 rounded-lg p-3 relative group">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="text-sm font-medium">
                                                        {{ \Carbon\Carbon::parse($schedule->posting_time)->format('H:i') }}
                                                    </span>
                                                </div>
                                                
                                                <!-- Status del horario -->
                                                <div class="flex items-center">
                                                    @if($schedule->is_active)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            Activo
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                            Inactivo
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Redes sociales asociadas -->
                                            @if($schedule->socialAccounts->count() > 0)
                                                <div class="mt-2 flex flex-wrap gap-1">
                                                    @foreach($schedule->socialAccounts as $account)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ ucfirst($account->provider) }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <!-- Acciones (visibles al hover) -->
                                            <div class="absolute top-2 right-2 hidden group-hover:flex space-x-2">
                                                <button @click="$dispatch('open-modal', 'edit-schedule-{{ $schedule->id }}')" 
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                </button>

                                                <form action="{{ route('schedules.toggle', $schedule) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="{{ $schedule->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                        </svg>
                                                    </button>
                                                </form>

                                                <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" 
                                                    class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este horario?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($schedules->where('day_of_week', $index)->count() === 0)
                                        <div class="text-center py-4 text-gray-500 text-sm">
                                            No hay horarios configurados
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para agregar horario -->
    <x-modal name="add-schedule" focusable>
        <form method="POST" action="{{ route('schedules.store') }}" class="p-6">
            @csrf

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Agregar Nuevo Horario') }}
            </h2>

            <div class="mt-6">
                <x-input-label for="day_of_week" :value="__('Día de la Semana')" />
                <select name="day_of_week" id="day_of_week" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Selecciona un día</option>
                    <option value="0">Domingo</option>
                    <option value="1">Lunes</option>
                    <option value="2">Martes</option>
                    <option value="3">Miércoles</option>
                    <option value="4">Jueves</option>
                    <option value="5">Viernes</option>
                    <option value="6">Sábado</option>
                </select>
                <x-input-error :messages="$errors->get('day_of_week')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label for="posting_time" :value="__('Hora')" />
                <x-text-input type="time" name="posting_time" id="posting_time" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->get('posting_time')" class="mt-2" />
            </div>

            <div class="mt-6">
                <x-input-label :value="__('Redes Sociales')" />
                <div class="mt-2 space-y-2">
                    @foreach($socialAccounts as $account)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="social_accounts[]" value="{{ $account->id }}" 
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2">{{ ucfirst($account->provider) }} - {{ $account->nickname }}</span>
                        </label>
                    @endforeach
                </div>
                <x-input-error :messages="$errors->get('social_accounts')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancelar') }}
                </x-secondary-button>

                <x-primary-button class="ml-3">
                    {{ __('Guardar') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Modal para editar horario (se genera uno por cada horario) -->
    @foreach($schedules as $schedule)
        <x-modal name="edit-schedule-{{ $schedule->id }}" focusable>
            <form method="POST" action="{{ route('schedules.update', $schedule) }}" class="p-6">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Editar Horario') }}
                </h2>

                <div class="mt-6">
                    <x-input-label for="day_of_week_{{ $schedule->id }}" :value="__('Día de la Semana')" />
                    <select name="day_of_week" id="day_of_week_{{ $schedule->id }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="0" {{ $schedule->day_of_week == 0 ? 'selected' : '' }}>Domingo</option>
                        <option value="1" {{ $schedule->day_of_week == 1 ? 'selected' : '' }}>Lunes</option>
                        <option value="2" {{ $schedule->day_of_week == 2 ? 'selected' : '' }}>Martes</option>
                        <option value="3" {{ $schedule->day_of_week == 3 ? 'selected' : '' }}>Miércoles</option>
                        <option value="4" {{ $schedule->day_of_week == 4 ? 'selected' : '' }}>Jueves</option>
                        <option value="5" {{ $schedule->day_of_week == 5 ? 'selected' : '' }}>Viernes</option>
                        <option value="6" {{ $schedule->day_of_week == 6 ? 'selected' : '' }}>Sábado</option>
                    </select>
                </div>

                <div class="mt-6">
                    <x-input-label for="posting_time_{{ $schedule->id }}" :value="__('Hora')" />
                    <x-text-input type="time" name="posting_time" id="posting_time_{{ $schedule->id }}" 
                        class="mt-1 block w-full" 
                        value="{{ \Carbon\Carbon::parse($schedule->posting_time)->format('H:i') }}" 
                        required />
                </div>

                <div class="mt-6">
                    <x-input-label :value="__('Redes Sociales')" />
                    <div class="mt-2 space-y-2">
                        @foreach($socialAccounts as $account)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="social_accounts[]" value="{{ $account->id }}" 
                                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    {{ $schedule->socialAccounts->contains($account->id) ? 'checked' : '' }}>
                                <span class="ml-2">{{ ucfirst($account->provider) }} - {{ $account->nickname }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            {{ $schedule->is_active ? 'checked' : '' }}>
                        <span class="ml-2">{{ __('Activo') }}</span>
                    </label>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancelar') }}
                    </x-secondary-button>

                    <x-primary-button class="ml-3">
                        {{ __('Actualizar') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    @endforeach
</x-app-layout>