@props(['status'])

@php
$classes = [
    'published' => 'bg-green-100 text-green-800',
    'queued' => 'bg-yellow-100 text-yellow-800',
    'failed' => 'bg-red-100 text-red-800',
    'draft' => 'bg-gray-100 text-gray-800'
][$status] ?? 'bg-gray-100 text-gray-800';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $classes }}">
    {{ ucfirst($status) }}
</span>