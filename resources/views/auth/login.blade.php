@extends('layouts.guest')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-primary-dark">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-lg">
        <!-- <h2 class="text-3xl font-bold text-center text-primary-dark">{{ __('Login') }}</h2> -->

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Input Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-primary-dark">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="block w-full px-4 py-2 mt-2 text-primary-dark bg-gray-100 border border-gray-300 rounded focus:ring focus:ring-primary-light focus:border-primary-dark @error('email') border-red-500 @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="text-sm text-danger mt-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Input Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-primary-dark">{{ __('Password') }}</label>
                <input id="password" type="password" class="block w-full px-4 py-2 mt-2 text-primary-dark bg-gray-100 border border-gray-300 rounded focus:ring focus:ring-primary-light focus:border-primary-dark @error('password') border-red-500 @enderror" name="password" required autocomplete="current-password">
                @error('password')
                    <span class="text-sm text-danger mt-1" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Checkbox Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="w-4 h-4 text-primary-dark border-gray-300 rounded focus:ring-primary-light" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="ml-2 text-sm text-primary-dark">{{ __('Remember Me') }}</label>
            </div>

            <!-- Login Button -->
            <div>
                <button type="submit" class="w-full px-4 py-2 font-semibold text-white bg-primary-dark rounded-md hover:bg-primary focus:ring-2 focus:ring-primary-light">
                    {{ __('Login') }}
                </button>
            </div>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 mt-4">
                Copyright © 2024 Digitalinkuy. All Rights Reserved.<br>
                Made by <a href="https://www.digitalinkuy.com/" class="font-bold text-primary hover:underline">Digitalinkuy</a>.
            </div>
        </form>
    </div>
</div>
@endsection
