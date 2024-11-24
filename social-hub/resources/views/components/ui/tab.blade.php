<div x-data="{ tab: '{{ $default ?? 'tab1' }}' }">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            {{ $tabs }}
        </nav>
    </div>
    <div class="mt-4">
        {{ $content }}
    </div>
</div>