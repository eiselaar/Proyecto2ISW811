<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <x-social.platform-icon :platform="$platform" class="h-8 w-8" />
            <div class="ml-4">
                <h3 class="text-lg font-medium text-gray-900">{{ ucfirst($platform) }}</h3>
                <x-social.connection-status :connected="$connected" />
            </div>
        </div>
        <div>
            {{ $slot }}
        </div>
    </div>
</div>