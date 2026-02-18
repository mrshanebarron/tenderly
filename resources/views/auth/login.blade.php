<x-guest-layout>
    @section('title', 'Sign In')

    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Demo credentials banner --}}
    <div x-data class="mb-5 p-3 bg-teal-50 border border-teal-200 rounded-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-teal-700">Demo Access</p>
                <p class="text-xs text-slate-500 mt-0.5">admin@tenderly.com</p>
            </div>
            <button type="button"
                @click="document.getElementById('email').value = 'admin@tenderly.com'; document.getElementById('password').value = '2WBE8QrawPqMtshj'; document.getElementById('email').dispatchEvent(new Event('input')); document.getElementById('password').dispatchEvent(new Event('input'));"
                class="px-3 py-1.5 text-xs font-medium text-teal-700 bg-teal-100 hover:bg-teal-200 border border-teal-300 rounded-md transition">
                Fill credentials
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-slate-500 hover:text-teal-600 transition rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>
</x-guest-layout>
