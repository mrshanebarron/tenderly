<x-guest-layout>
    @section('title', 'Sign In')

    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Demo credentials banner --}}
    <div x-data class="mb-5 p-3 bg-violet-600/10 border border-violet-500/20 rounded-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-violet-300">Demo Access</p>
                <p class="text-xs text-zinc-500 mt-0.5">admin@tenderly.com</p>
            </div>
            <button type="button"
                @click="document.getElementById('email').value = 'admin@tenderly.com'; document.getElementById('password').value = '2WBE8QrawPqMtshj'; document.getElementById('email').dispatchEvent(new Event('input')); document.getElementById('password').dispatchEvent(new Event('input'));"
                class="px-3 py-1.5 text-xs font-medium text-violet-300 bg-violet-600/20 hover:bg-violet-600/30 border border-violet-500/30 rounded-md transition">
                Fill credentials
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-zinc-300">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-zinc-300">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-zinc-950/50 border-zinc-700 text-violet-600 shadow-sm focus:ring-violet-500" name="remember">
                <span class="ms-2 text-sm text-zinc-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-zinc-500 hover:text-violet-400 transition rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <button type="submit" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>
</x-guest-layout>
