<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="grid grid-cols-8 gap-px bg-gray-200">
        <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">Time</div>
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
            <div class="bg-gray-50 p-2 text-center text-xs font-medium text-gray-500">{{ $day }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-8 gap-px bg-gray-200">
        @foreach(range(0, 23) as $hour)
            <div class="bg-white p-2 text-xs">{{ sprintf('%02d:00', $hour) }}</div>
            @foreach(range(0, 6) as $day)
                <div class="bg-white p-2">
                    <x-schedule.time-slot 
                        :hour="$hour" 
                        :day="$day" 
                        :has-schedule="$schedules->contains('day_of_week', $day) && 
                                     $schedules->contains('time', sprintf('%02d:00:00', $hour))" 
                    />
                </div>
            @endforeach
        @endforeach
    </div>
</div>