@props(['variant' => 'primary'])

<span {{ $attributes->merge([
    'class' => 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' .
    ($variant === 'primary' ? 'bg-indigo-100 text-indigo-800' : 
     'bg-gray-100 text-gray-800')
]) }}>
    {{ $slot }}
</span