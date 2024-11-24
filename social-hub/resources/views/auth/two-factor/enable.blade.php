@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">Enable Two Factor Authentication</h2>
                
                <div class="mb-4">
                    <p class="text-gray-600">
                        Two factor authentication adds an additional layer of security to your account.
                        When enabled, you'll need to provide a 6 digit code when logging in.
                    </p>
                </div>

                <div class="flex justify-start">
                    <form method="POST" action="{{ route('two-factor.enable') }}">
                        @csrf
                        <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Enable 2FA
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
