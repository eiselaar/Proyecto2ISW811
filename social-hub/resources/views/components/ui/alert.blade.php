@props(['type' => 'info'])

<div {{ $attributes->merge([
    'class' => 'rounded-md p-4 ' . ($type === 'error' ? 'bg-red-50 text-red-700' : 
                                   ($type === 'success' ? 'bg-green-50 text-green-700' : 
                                    'bg-blue-50 text-blue-700'))
]) }} role="alert">
    {{ $slot }}
</div>