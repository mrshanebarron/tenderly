@extends('layouts.app')
@section('title', 'Profile')
@section('header')
    <h1 class="text-lg font-semibold text-white">Profile Settings</h1>
@endsection

@section('content')
<div class="max-w-2xl space-y-6">
    {{-- Update Profile Information --}}
    <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6">
        <h3 class="font-semibold text-white mb-1">Profile Information</h3>
        <p class="text-sm text-zinc-500 mb-5">Update your account's name and email address.</p>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="block text-sm font-medium text-zinc-300">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                    class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-zinc-300">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                    class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">Save</button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-400">Saved.</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6">
        <h3 class="font-semibold text-white mb-1">Update Password</h3>
        <p class="text-sm text-zinc-500 mb-5">Ensure your account is using a long, random password to stay secure.</p>

        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-zinc-300">Current Password</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                    class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password" class="block text-sm font-medium text-zinc-300">New Password</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                    class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-zinc-300">Confirm Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                    class="mt-1 block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">Update Password</button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-400">Saved.</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6" x-data="{ confirming: false }">
        <h3 class="font-semibold text-white mb-1">Delete Account</h3>
        <p class="text-sm text-zinc-500 mb-5">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

        <button @click="confirming = true" x-show="!confirming" class="px-4 py-2 bg-red-600/20 border border-red-500/30 hover:bg-red-600/30 text-red-400 text-sm font-medium rounded-lg transition">
            Delete Account
        </button>

        <form method="post" action="{{ route('profile.destroy') }}" x-show="confirming" x-cloak class="space-y-4">
            @csrf
            @method('delete')

            <p class="text-sm text-zinc-400">Please enter your password to confirm you would like to permanently delete your account.</p>

            <div>
                <input name="password" type="password" placeholder="Password" required
                    class="block w-full bg-zinc-950/50 border-zinc-700 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500 placeholder-zinc-600">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-medium rounded-lg transition">Confirm Delete</button>
                <button type="button" @click="confirming = false" class="px-4 py-2 text-zinc-500 hover:text-zinc-300 text-sm transition">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
