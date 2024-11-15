@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm']) !!}>

<!-- resources/views/components/dropdown-link.blade.php -->
<a {{ $attributes->merge(['class' => 'block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out']) }}>
    {{ $slot }}
</a>