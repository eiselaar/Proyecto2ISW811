@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Configuración de Horarios</h2>
                    <button type="button" 
                            onclick="toggleModal()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Agregar Nuevo Horario
                    </button>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Horarios Semanales -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="border-t border-gray-200">
                        <div class="divide-y divide-gray-200">
                            @php
                                $days = [
                                    0 => 'Domingo',
                                    1 => 'Lunes',
                                    2 => 'Martes',
                                    3 => 'Miércoles',
                                    4 => 'Jueves',
                                    5 => 'Viernes',
                                    6 => 'Sábado'
                                ];
                            @endphp

                            @foreach($days as $dayIndex => $dayName)
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-medium text-gray-900 w-32">{{ $dayName }}</h4>
                                        <div class="flex-1 ml-4">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($schedules->where('day_of_week', $dayIndex) as $schedule)
                                                    <div class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1 text-sm">
                                                        {{ \Carbon\Carbon::parse($schedule->time)->format('g:i A') }}
                                                        
                                                        <!-- Botón de editar -->
                                                        <button onclick="prepareEdit('{{ $schedule->id }}', '{{ $schedule->day_of_week }}', '{{ $schedule->time }}')"
                                                                class="ml-2 text-blue-600 hover:text-blue-800">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Formulario y botón de eliminar -->
                                                        <form id="deleteForm_{{ $schedule->id }}" 
                                                              action="{{ route('schedules.destroy', $schedule) }}" 
                                                              method="POST" 
                                                              class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" 
                                                                    onclick="confirmDelete('{{ $schedule->id }}')"
                                                                    class="ml-2 text-red-600 hover:text-red-800">
                                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar nuevo horario -->
<div id="scheduleModal" 
     class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Agregar Nuevo Horario</h3>
                <button type="button" onclick="toggleModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700">Día de la Semana</label>
                    <select name="day_of_week" 
                            id="day_of_week" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>
                        <option value="">Selecciona un día</option>
                        <option value="0">Domingo</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                        <option value="6">Sábado</option>
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="time" class="block text-sm font-medium text-gray-700">Hora</label>
                    <input type="time" 
                           name="time" 
                           id="time" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           required>
                    @error('time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" 
                            onclick="toggleModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        Agregar Horario
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar horario -->
<div id="editScheduleModal" 
     class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Horario</h3>
                <button type="button" onclick="toggleEditModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editForm" action="" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="edit_day_of_week" class="block text-sm font-medium text-gray-700">Día de la Semana</label>
                    <select name="day_of_week" 
                            id="edit_day_of_week" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>
                        <option value="0">Domingo</option>
                        <option value="1">Lunes</option>
                        <option value="2">Martes</option>
                        <option value="3">Miércoles</option>
                        <option value="4">Jueves</option>
                        <option value="5">Viernes</option>
                        <option value="6">Sábado</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label for="edit_time" class="block text-sm font-medium text-gray-700">Hora</label>
                    <input type="time" 
                           name="time" 
                           id="edit_time" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                           required>
                </div>

                <div class="flex justify-end gap-4">
                    <button type="button" 
                            onclick="toggleEditModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleModal() {
    const modal = document.getElementById('scheduleModal');
    modal.classList.toggle('hidden');
}

function toggleEditModal() {
    const modal = document.getElementById('editScheduleModal');
    modal.classList.toggle('hidden');
}

function prepareEdit(scheduleId, dayOfWeek, time) {
    // Actualiza la URL del formulario
    const form = document.getElementById('editForm');
    form.action = `/schedules/${scheduleId}`;
    
    // Establece los valores actuales
    document.getElementById('edit_day_of_week').value = dayOfWeek;
    document.getElementById('edit_time').value = time;
    
    // Muestra el modal
    toggleEditModal();
}

function confirmDelete(scheduleId) {
    if (confirm('¿Estás seguro de que deseas eliminar este horario?')) {
        document.getElementById('deleteForm_' + scheduleId).submit();
    }
}
</script>
@endsection