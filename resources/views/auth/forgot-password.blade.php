<x-guest-layout>
    @section('title', 'Forgot Password')

    <div class="mb-4 text-sm text-slate-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-slate-500 hover:text-teal-600 transition" href="{{ route('login') }}">Back to sign in</a>
            <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">
                {{ __('Send Reset Link') }}
            </button>
        </div>
    </form>
</x-guest-layout>
