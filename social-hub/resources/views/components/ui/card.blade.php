<div {{ $attributes->merge(['class' => 'bg-white overflow-hidden shadow-sm sm:rounded-lg']) }}>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>