@props(['title', 'value', 'icon'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <x-dynamic-component :component="'heroicon-o-' . $icon" 
                                  class="h-6 w-6 text-gray-400"/>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-500">
                    {{ $title }}
                </div>
                <div class="text-lg font-semibold text-gray-900">
                    {{ $value }}
                </div>
            </div>
        </div>
    </div>
</div>