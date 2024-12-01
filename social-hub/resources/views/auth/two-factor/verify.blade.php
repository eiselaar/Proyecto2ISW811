@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Verify Two Factor Code</h2>

                <form method="POST" action="{{ route('2fa.verify') }}">
                    @csrf

                    {{-- Campos ocultos para la redirección de redes sociales --}}
                    @if(session('social_callback_platform'))
                        <input type="hidden" name="social_platform" value="{{ session('social_callback_platform') }}">
                    @endif
                    @if(session('social_callback_code'))
                        <input type="hidden" name="social_code" value="{{ session('social_callback_code') }}">
                    @endif

                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700">Authentication Code</label>
                        <input id="code" 
                               type="text" 
                               name="code"
                               required 
                               autofocus
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                               placeholder="Enter your 6-digit code">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Verify Code
                        </button>

                        @if(session('status'))
                            <p class="text-sm text-green-600">{{ session('status') }}</p>
                        @endif
                    </div>
                </form>

                <div class="mt-6">
                    <p class="text-sm text-gray-600">
                        Don't have access to your authenticator app? Contact support for assistance.
                    </p>
                    @if(session('social_callback_platform'))
                        <p class="mt-2 text-sm text-gray-600">
                            Verifying for {{ ucfirst(session('social_callback_platform')) }} connection.
                            <a href="{{ route('dashboard') }}" class="text-indigo-600 hover:text-indigo-500">
                                Cancel
                            </a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>