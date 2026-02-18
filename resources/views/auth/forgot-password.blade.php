<x-guest-layout>
    @section('title', 'Forgot Password')

    <div class="mb-4 text-sm text-zinc-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-zinc-300">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-zinc-500 hover:text-violet-400 transition" href="{{ route('login') }}">Back to sign in</a>
            <button type="submit" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">
                {{ __('Send Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
