@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        {{ __('Create New Schedule') }}
                    </h2>
                    <a href="{{ route('schedules.index') }}" class="text-blue-600 hover:text-blue-800">
                        {{ __('Back to Schedule') }}
                    </a>
                </div>

                <form action="{{ route('schedules.store') }}" method="POST">
                    @csrf

                    <div class="space-y-6">
                        <!-- Día de la semana -->
                        <div>
                            <label for="day_of_week" class="block text-sm font-medium text-gray-700">
                                {{ __('Day of Week') }}
                            </label>
                            <select id="day_of_week" 
                                    name="day_of_week" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">{{ __('Select a day') }}</option>
                                @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $index => $day)
                                    <option value="{{ $index }}" {{ old('day_of_week') == $index ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hora -->
                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700">
                                {{ __('Time') }}
                            </label>
                            <input type="time" 
                                   id="time" 
                                   name="time" 
                                   value="{{ old('time') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Estado Activo -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_active" class="font-medium text-gray-700">
                                    {{ __('Active') }}
                                </label>
                                <p class="text-gray-500">
                                    {{ __('Enable or disable this schedule') }}
                                </p>
                            </div>
                        </div>

                        <!-- Repetir -->
                        <div>
                            <label for="repeat_type" class="block text-sm font-medium text-gray-700">
                                {{ __('Repeat') }}
                            </label>
                            <select id="repeat_type" 
                                    name="repeat_type" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="weekly" {{ old('repeat_type') == 'weekly' ? 'selected' : '' }}>
                                    {{ __('Weekly') }}
                                </option>
                                <option value="weekdays" {{ old('repeat_type') == 'weekdays' ? 'selected' : '' }}>
                                    {{ __('Weekdays only') }}
                                </option>
                                <option value="custom" {{ old('repeat_type') == 'custom' ? 'selected' : '' }}>
                                    {{ __('Custom') }}
                                </option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 pt-6">
                            <button type="button" 
                                    onclick="window.location.href='{{ route('schedules.index') }}'"
                                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Create Schedule') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Horarios Existentes -->
        <div class="mt-8 bg-white rounded-lg shadow-md">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('Current Schedules') }}
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Day') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Time') }}
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Status') }}
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($schedules ?? [] as $schedule)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$schedule->day_of_week] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $schedule->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $schedule->is_active ? __('Active') : __('Inactive') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    onclick="return confirm('{{ __('Are you sure you want to delete this schedule?') }}')"
                                                    class="text-red-600 hover:text-red-900">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ __('No schedules found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const repeatTypeSelect = document.getElementById('repeat_type');
    const daySelect = document.getElementById('day_of_week');

    repeatTypeSelect.addEventListener('change', function() {
        if (this.value === 'weekdays') {
            const weekendOptions = Array.from(daySelect.options).filter(option => 
                option.value === '0' || option.value === '6'
            );
            weekendOptions.forEach(option => option.disabled = true);
            
            if (daySelect.value === '0' || daySelect.value === '6') {
                daySelect.value = '1'; // Select Monday by default
            }
        } else {
            Array.from(daySelect.options).forEach(option => option.disabled = false);
        }
    });
});
</script>
@endpush
@endsection

// resources/views/schedules/index.blade.php
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">{{ __('Posting Schedule') }}</h2>
            <a href="{{ route('schedules.create') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{ __('Add New Time Slot') }}
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Calendario de Horarios -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div id="schedule-calendar">
                            <x-schedule-calendar :schedules="$schedules" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel Lateral -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Quick Stats') }}</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Active Time Slots') }}</p>
                            <p class="text-2xl font-bold">{{ $schedules->where('is_active', true)->count() }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Posts in Queue') }}</p>
                            <p class="text-2xl font-bold text-blue-600">{{ $queuedPostsCount ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lista de Horarios -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Active Time Slots') }}</h3>
                    <div class="space-y-4">
                        @forelse($schedules->groupBy('day_of_week') as $day => $daySchedules)
                            <div>
                                <h4 class="font-medium text-gray-700">
                                    {{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$day] }}
                                </h4>
                                <div class="ml-4 mt-2 space-y-2">
                                    @foreach($daySchedules->sortBy('time') as $schedule)
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm text-gray-600">
                                                {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                                            </span>
                                            <form action="{{ route('schedules.destroy', $schedule) }}" 
                                                  method="POST" 
                                                  class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('{{ __('Are you sure?') }}')"
                                                        class="text-sm text-red-600 hover:text-red-800">
                                                    {{ __('Remove') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center">
                                {{ __('No time slots configured.') }}
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection