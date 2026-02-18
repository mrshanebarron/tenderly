@extends('layouts.app')
@section('title', 'Session Prep — ' . $tender->name)
@section('header')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('tenders.show', $tender) }}" class="text-slate-500 hover:text-slate-700">{{ $tender->name }}</a>
        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-slate-900">Session Preparation</span>
    </div>
@endsection

@section('content')
    @php
        $sessionPrep = $tender->analyses->where('type', 'session_prep')->last();
    @endphp

    @if($sessionPrep && isset($sessionPrep->content['discussion_topics']))
        <div class="max-w-4xl space-y-8">
            {{-- Discussion Topics --}}
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h2 class="font-semibold text-slate-900">Discussion Topics</h2>
                    <p class="text-xs text-slate-500 mt-1">Prioritized agenda items for the working session.</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($sessionPrep->content['discussion_topics'] as $topic)
                        <div class="px-6 py-4 flex items-start gap-4">
                            <span class="shrink-0 mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded {{ $topic['priority'] === 'critical' ? 'text-red-700 bg-red-50' : ($topic['priority'] === 'high' ? 'text-amber-700 bg-amber-50' : 'text-blue-700 bg-blue-50') }}">{{ strtoupper($topic['priority']) }}</span>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-900">{{ $topic['topic'] }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $topic['context'] }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-slate-400">{{ $topic['time_estimate'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                {{-- Clarification Questions --}}
                <div class="bg-white border border-slate-200 rounded-xl">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="font-semibold text-slate-900">Clarification Questions</h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            @foreach($sessionPrep->content['clarification_questions'] as $q)
                                <li class="flex items-start gap-2.5 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-teal-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $q }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Decision Points --}}
                <div class="bg-white border border-slate-200 rounded-xl">
                    <div class="px-6 py-4 border-b border-slate-200">
                        <h2 class="font-semibold text-slate-900">Decision Points</h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            @foreach($sessionPrep->content['decision_points'] as $dp)
                                <li class="flex items-start gap-2.5 text-sm text-slate-600">
                                    <svg class="w-4 h-4 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                    {{ $dp }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="max-w-4xl bg-white border border-slate-200 rounded-xl p-12 text-center">
            <p class="text-slate-500 mb-4">No session preparation has been generated yet.</p>
            <form method="POST" action="{{ route('analysis.generate', $tender) }}">
                @csrf
                <input type="hidden" name="type" value="session_prep">
                <button class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">Generate Session Preparation</button>
            </form>
        </div>
    @endif
@endsection
