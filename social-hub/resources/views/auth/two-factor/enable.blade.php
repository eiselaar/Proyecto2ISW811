@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-center">
        <div class="w-full md:w-1/2 lg:w-1/3">
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">{{ __('Enable Two Factor Authentication') }}</h2>

                <p class="mb-4 text-gray-600">
                    Scan this QR code with your Google Authenticator app or enter the code manually:
                </p>

                <div class="mb-4 flex justify-center">
                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                </div>

                <p class="mb-4 text-sm text-gray-500 text-center">
                    Manual entry code: {{ $secret }}
                </p>

                <form method="POST" action="{{ route('2fa.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="code" class="block text-gray-700 text-sm font-bold mb-2">
                            {{ __('Verification Code') }}
                        </label>
                        <input id="code" type="text" name="code" required autofocus
                               class="form-input @error('code') border-red-500 @enderror">
                        @error('code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        {{ __('Enable 2FA') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection