@props(['variant' => 'primary'])

<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 ' . 
    ($variant === 'primary' 
        ? 'bg-indigo-600 hover:bg-indigo-700 text-white'
        : 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300')
]) }}>
    {{ $slot }}
</button>