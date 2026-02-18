@extends('layouts.app')
@section('title', 'Profile')
@section('header')
    <h1 class="text-lg font-semibold text-slate-900">Profile Settings</h1>
@endsection

@section('content')
<div class="max-w-2xl space-y-6">
    {{-- Update Profile Information --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <h3 class="font-semibold text-slate-900 mb-1">Profile Information</h3>
        <p class="text-sm text-slate-500 mb-5">Update your account's name and email address.</p>

        <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf
            @method('patch')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                    class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                    class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">Save</button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600">Saved.</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6">
        <h3 class="font-semibold text-slate-900 mb-1">Update Password</h3>
        <p class="text-sm text-slate-500 mb-5">Ensure your account is using a long, random password to stay secure.</p>

        <form method="post" action="{{ route('password.update') }}" class="space-y-4">
            @csrf
            @method('put')

            <div>
                <label for="update_password_current_password" class="block text-sm font-medium text-slate-700">Current Password</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                    class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password" class="block text-sm font-medium text-slate-700">New Password</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                    class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </div>

            <div>
                <label for="update_password_password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                    class="mt-1 block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">Update Password</button>
                @if (session('status') === 'password-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-emerald-600">Saved.</p>
                @endif
            </div>
        </form>
    </div>

    {{-- Delete Account --}}
    <div class="bg-white border border-slate-200 rounded-xl p-6" x-data="{ confirming: false }">
        <h3 class="font-semibold text-slate-900 mb-1">Delete Account</h3>
        <p class="text-sm text-slate-500 mb-5">Once your account is deleted, all of its resources and data will be permanently deleted.</p>

        <button @click="confirming = true" x-show="!confirming" class="px-4 py-2 bg-red-50 border border-red-200 hover:bg-red-100 text-red-700 text-sm font-medium rounded-lg transition">
            Delete Account
        </button>

        <form method="post" action="{{ route('profile.destroy') }}" x-show="confirming" x-cloak class="space-y-4">
            @csrf
            @method('delete')

            <p class="text-sm text-slate-600">Please enter your password to confirm you would like to permanently delete your account.</p>

            <div>
                <input name="password" type="password" placeholder="Password" required
                    class="block w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-medium rounded-lg transition">Confirm Delete</button>
                <button type="button" @click="confirming = false" class="px-4 py-2 text-slate-500 hover:text-slate-700 text-sm transition">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
