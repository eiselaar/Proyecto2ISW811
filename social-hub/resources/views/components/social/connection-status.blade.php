@props(['connected'])

<p class="text-sm {{ $connected ? 'text-green-600' : 'text-gray-500' }}">
    {{ $connected ? 'Connected' : 'Not connected' }}
</p>