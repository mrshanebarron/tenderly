<x-guest-layout>
    @section('title', 'Register')

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-zinc-300">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <label for="email" class="block text-sm font-medium text-zinc-300">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-zinc-300">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <label for="password_confirmation" class="block text-sm font-medium text-zinc-300">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="text-sm text-zinc-500 hover:text-violet-400 transition" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">
                {{ __('Register') }}
            </button>
        </div>
    </form>
</x-guest-layout>
