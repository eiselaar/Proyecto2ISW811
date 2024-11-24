@props(['disabled' => false, 'error' => ''])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500' . ($error ? ' border-red-300' : '')]) !!}>
@if($error)
    <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
@endif