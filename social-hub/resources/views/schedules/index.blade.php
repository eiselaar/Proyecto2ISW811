@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Schedule Settings</h2>
                    <button type="button" 
                            onclick="toggleModal()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        Add New Time Slot
                    </button>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Weekly Schedule -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="border-t border-gray-200">
                        <div class="divide-y divide-gray-200">
                            @php
                                $days = [
                                    0 => 'Sunday',
                                    1 => 'Monday',
                                    2 => 'Tuesday',
                                    3 => 'Wednesday',
                                    4 => 'Thursday',
                                    5 => 'Friday',
                                    6 => 'Saturday'
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
                                                        <form action="{{ route('schedules.destroy', $schedule) }}" 
                                                              method="POST" 
                                                              class="inline ml-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-500 hover:text-red-700"
                                                                    onclick="return confirm('Are you sure you want to delete this time slot?')">
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

<!-- Modal for adding new schedule -->
<div id="scheduleModal" 
     class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden"
     x-data>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add New Time Slot</h3>
                <button type="button" onclick="toggleModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route('schedules.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700">Day of Week</label>
                    <select name="day_of_week" 
                            id="day_of_week" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required>
                        <option value="">Select a day</option>
                        <option value="0">Sunday</option>
                        <option value="1">Monday</option>
                        <option value="2">Tuesday</option>
                        <option value="3">Wednesday</option>
                        <option value="4">Thursday</option>
                        <option value="5">Friday</option>
                        <option value="6">Saturday</option>
                    </select>
                    @error('day_of_week')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
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
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">
                        Add Time Slot
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
</script>
@endsection