@extends('layouts.app')
@section('title', 'Dashboard')
@section('header')
    <h1 class="text-lg font-semibold text-white">Dashboard</h1>
@endsection
@section('actions')
    <a href="{{ route('tenders.create') }}" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">New Tender</a>
@endsection

@section('content')
    {{-- Stats --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-5">
            <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Total Tenders</p>
            <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-5">
            <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Active</p>
            <p class="text-2xl font-bold text-blue-400">{{ $stats['active'] }}</p>
        </div>
        <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-5">
            <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">In Review</p>
            <p class="text-2xl font-bold text-amber-400">{{ $stats['reviewing'] }}</p>
        </div>
        <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-5">
            <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Completed</p>
            <p class="text-2xl font-bold text-emerald-400">{{ $stats['completed'] }}</p>
        </div>
    </div>

    {{-- Recent Tenders --}}
    <div class="bg-zinc-900/50 border border-white/5 rounded-xl">
        <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between">
            <h2 class="font-semibold text-white">Recent Tenders</h2>
            <a href="{{ route('tenders.index') }}" class="text-xs text-violet-400 hover:text-violet-300 transition">View All</a>
        </div>
        <div class="divide-y divide-white/5">
            @forelse($recentTenders as $tender)
                <a href="{{ route('tenders.show', $tender) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-white/[0.02] transition group">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3">
                            <h3 class="font-medium text-white group-hover:text-violet-300 transition truncate">{{ $tender->name }}</h3>
                            <span class="shrink-0 inline-flex px-2 py-0.5 text-xs font-medium rounded-full border {{ $tender->status_color }}">{{ ucfirst($tender->status) }}</span>
                        </div>
                        <p class="text-sm text-zinc-500 mt-0.5 truncate">{{ $tender->description }}</p>
                    </div>
                    <div class="shrink-0 text-right">
                        <p class="text-sm text-zinc-400">{{ $tender->criteria_count }} criteria</p>
                        <p class="text-xs text-zinc-600">{{ $tender->participants_count }} participants</p>
                    </div>
                    @if($tender->deadline)
                        <div class="shrink-0 text-xs {{ $tender->deadline->isPast() ? 'text-red-400' : 'text-zinc-500' }}">
                            {{ $tender->deadline->diffForHumans() }}
                        </div>
                    @endif
                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <p class="text-zinc-500 mb-4">No tenders yet. Create your first tender workspace.</p>
                    <a href="{{ route('tenders.create') }}" class="inline-flex px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">Create Tender</a>
                </div>
            @endforelse
        </div>
    </div>
@endsection
