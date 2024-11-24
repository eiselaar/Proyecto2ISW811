@props(['hour', 'day', 'hasSchedule'])

<div class="h-full w-full flex items-center justify-center">
    @if($hasSchedule)
        <div class="w-4 h-4 rounded-full bg-indigo-600"></div>
    @endif
</div>

