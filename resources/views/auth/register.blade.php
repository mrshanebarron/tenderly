<x-guest-layout>
    @section('title', 'Register')

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-slate-500 hover:text-teal-600 transition" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
