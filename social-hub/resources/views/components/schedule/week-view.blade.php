
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="grid grid-cols-7 gap-px">
        @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $index => $day)
            <div class="p-4">
                <h3 class="font-medium text-gray-900">{{ $day }}</h3>
                <div class="mt-2 space-y-1">
                    @foreach($schedules->where('day_of_week', $index) as $schedule)
                        <div class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>