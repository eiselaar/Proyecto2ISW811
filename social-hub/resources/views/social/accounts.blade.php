@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">{{ __('Social Media Accounts') }}</h2>

        <div class="bg-white shadow rounded-lg divide-y divide-gray-200">
            @foreach($availableProviders as $provider)
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ ucfirst($provider) }}
                            </h3>
                            @php
                                $account = $socialAccounts->firstWhere('provider', $provider);
                            @endphp
                            @if($account)
                                <p class="text-sm text-green-600">
                                    {{ __('Connected') }}
                                </p>
                            @else
                                <p class="text-sm text-gray-500">
                                    {{ __('Not connected') }}
                                </p>
                            @endif
                        </div>

                        <div>
                            @if($account)
                                <form action="{{ route('social.disconnect', $provider) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn-danger"
                                            onclick="return confirm('Are you sure you want to disconnect this account?')">
                                        {{ __('Disconnect') }}
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('social.redirect', $provider) }}" 
                                   class="btn-primary">
                                    {{ __('Connect') }}
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($account)
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                {{ __('Last updated') }}: 
                                {{ $account->updated_at->diffForHumans() }}
                            </p>
                            @if($account->token_expires_at)
                                <p class="text-sm {{ $account->isTokenExpired() ? 'text-red-600' : 'text-gray-500' }}">
                                    {{ __('Token expires') }}: 
                                    {{ $account->token_expires_at->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6 bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('Important Information') }}
            </h3>
            <div class="prose prose-sm text-gray-500">
                <p>{{ __('Connected accounts allow this application to:') }}</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>{{ __('Post content on your behalf') }}</li>
                    <li>{{ __('Schedule posts for later') }}</li>
                    <li>{{ __('View basic account information') }}</li>
                </ul>
                <p class="mt-4">
                    {{ __('You can disconnect your accounts at any time.') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection