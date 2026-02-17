@extends('layouts.app')
@section('title', 'Session Prep — ' . $tender->name)
@section('header')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('tenders.show', $tender) }}" class="text-zinc-500 hover:text-zinc-300">{{ $tender->name }}</a>
        <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-white">Session Preparation</span>
    </div>
@endsection

@section('content')
    @php
        $sessionPrep = $tender->analyses->where('type', 'session_prep')->last();
    @endphp

    @if($sessionPrep && isset($sessionPrep->content['discussion_topics']))
        <div class="max-w-4xl space-y-8">
            {{-- Discussion Topics --}}
            <div class="bg-zinc-900/50 border border-white/5 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5">
                    <h2 class="font-semibold text-white">Discussion Topics</h2>
                    <p class="text-xs text-zinc-500 mt-1">Prioritized agenda items for the working session.</p>
                </div>
                <div class="divide-y divide-white/5">
                    @foreach($sessionPrep->content['discussion_topics'] as $topic)
                        <div class="px-6 py-4 flex items-start gap-4">
                            <span class="shrink-0 mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded {{ $topic['priority'] === 'critical' ? 'text-red-400 bg-red-400/10' : ($topic['priority'] === 'high' ? 'text-amber-400 bg-amber-400/10' : 'text-blue-400 bg-blue-400/10') }}">{{ strtoupper($topic['priority']) }}</span>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-white">{{ $topic['topic'] }}</p>
                                <p class="text-xs text-zinc-500 mt-1">{{ $topic['context'] }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-zinc-600">{{ $topic['time_estimate'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Clarification Questions --}}
                <div class="bg-zinc-900/50 border border-white/5 rounded-xl">
                    <div class="px-6 py-4 border-b border-white/5">
                        <h2 class="font-semibold text-white">Clarification Questions</h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            @foreach($sessionPrep->content['clarification_questions'] as $q)
                                <li class="flex items-start gap-2.5 text-sm text-zinc-400">
                                    <svg class="w-4 h-4 text-violet-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $q }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Decision Points --}}
                <div class="bg-zinc-900/50 border border-white/5 rounded-xl">
                    <div class="px-6 py-4 border-b border-white/5">
                        <h2 class="font-semibold text-white">Decision Points</h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            @foreach($sessionPrep->content['decision_points'] as $dp)
                                <li class="flex items-start gap-2.5 text-sm text-zinc-400">
                                    <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    {{ $dp }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="max-w-4xl bg-zinc-900/50 border border-white/5 rounded-xl p-12 text-center">
            <p class="text-zinc-500 mb-4">No session preparation has been generated yet.</p>
            <form method="POST" action="{{ route('analysis.generate', $tender) }}">
                @csrf
                <input type="hidden" name="type" value="session_prep">
                <button class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium rounded-lg transition">Generate Session Preparation</button>
            </form>
        </div>
    @endif
@endsection
