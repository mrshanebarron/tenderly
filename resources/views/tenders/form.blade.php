@extends('layouts.app')
@section('title', isset($tender) ? 'Edit Tender' : 'New Tender')
@section('header')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('tenders.index') }}" class="text-zinc-500 hover:text-zinc-300">Tenders</a>
        <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-white">{{ isset($tender) ? 'Edit' : 'New Tender' }}</span>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ isset($tender) ? route('tenders.update', $tender) : route('tenders.store') }}" class="max-w-3xl">
        @csrf
        @if(isset($tender)) @method('PUT') @endif

        @if($errors->any())
            <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6">
                <ul class="text-sm text-red-400 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="space-y-6">
            <div class="bg-zinc-900/50 border border-white/5 rounded-xl">
                <div class="px-6 py-4 border-b border-white/5">
                    <h2 class="font-semibold text-white">Tender Workspace</h2>
                    <p class="text-xs text-zinc-500 mt-1">Define the tender scope, criteria, and objectives.</p>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-2">Tender Name *</label>
                        <input type="text" name="name" value="{{ old('name', $tender->name ?? '') }}" required
                               class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500"
                               placeholder="e.g. Municipal Digital Transformation Platform">
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-2">Description</label>
                        <textarea name="description" rows="3" class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-zinc-300 text-sm focus:ring-violet-500 focus:border-violet-500" placeholder="Brief overview of the tender scope...">{{ old('description', $tender->description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-2">Objectives</label>
                        <textarea name="objectives" rows="3" class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-zinc-300 text-sm focus:ring-violet-500 focus:border-violet-500" placeholder="Key objectives, one per line...">{{ old('objectives', $tender->objectives ?? '') }}</textarea>
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-2">Focus Themes</label>
                            <input type="text" name="focus_themes" value="{{ old('focus_themes', $tender->focus_themes ?? '') }}"
                                   class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-zinc-300 text-sm focus:ring-violet-500 focus:border-violet-500"
                                   placeholder="e.g. Security, Accessibility, Performance">
                        </div>
                        <div>
                            <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-2">Deadline</label>
                            <input type="date" name="deadline" value="{{ old('deadline', isset($tender) && $tender->deadline ? $tender->deadline->format('Y-m-d') : '') }}"
                                   class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-zinc-300 text-sm focus:ring-violet-500 focus:border-violet-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs text-zinc-500 uppercase tracking-wider mb-2">Context Notes</label>
                        <textarea name="context_notes" rows="2" class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-zinc-300 text-sm focus:ring-violet-500 focus:border-violet-500" placeholder="Additional context for AI question generation...">{{ old('context_notes', $tender->context_notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-lg transition">
                {{ isset($tender) ? 'Update Tender' : 'Create Tender' }}
            </button>
            <a href="{{ route('tenders.index') }}" class="px-6 py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-300 text-sm font-medium rounded-lg transition">Cancel</a>
        </div>
    </form>
@endsection
