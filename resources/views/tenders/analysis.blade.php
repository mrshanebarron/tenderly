@extends('layouts.app')
@section('title', 'Analysis — ' . $tender->name)
@section('header')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('tenders.show', $tender) }}" class="text-slate-500 hover:text-slate-700">{{ $tender->name }}</a>
        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-slate-900">AI Analysis</span>
    </div>
@endsection
@section('actions')
    <a href="{{ route('analysis.session-prep', $tender) }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">Session Preparation</a>
@endsection

@section('content')
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            @forelse($tender->analyses->sortByDesc('created_at') as $analysis)
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $analysis->type_icon }}"/></svg>
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-900">{{ $analysis->title }}</h3>
                            <p class="text-[10px] text-slate-400">Generated {{ $analysis->created_at->diffForHumans() }} &middot; {{ number_format($analysis->confidence * 100) }}% confidence</p>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $analysis->type_color }}">{{ strtoupper(str_replace('_', ' ', $analysis->type)) }}</span>
                    </div>
                    <div class="p-6">
                        @if(isset($analysis->content['summary']))
                            <p class="text-sm text-slate-600 mb-4 leading-relaxed">{{ $analysis->content['summary'] }}</p>
                        @endif

                        @if(isset($analysis->content['points']))
                            <div class="space-y-3">
                                @foreach($analysis->content['points'] as $point)
                                    <div class="flex items-center gap-4 p-3 bg-slate-50 border border-slate-100 rounded-lg">
                                        <div class="shrink-0 w-14 text-center">
                                            <span class="text-lg font-bold text-emerald-600">{{ $point['agreement_level'] }}%</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $point['area'] }}</p>
                                            <p class="text-xs text-slate-500">{{ $point['detail'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(isset($analysis->content['gaps']))
                            <div class="space-y-2">
                                @foreach($analysis->content['gaps'] as $gap)
                                    <div class="flex items-start gap-3 p-3 bg-slate-50 border border-slate-100 rounded-lg">
                                        <span class="shrink-0 mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded {{ $gap['severity'] === 'critical' ? 'text-red-700 bg-red-50' : ($gap['severity'] === 'high' ? 'text-amber-700 bg-amber-50' : 'text-slate-600 bg-slate-100') }}">{{ strtoupper($gap['severity']) }}</span>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $gap['area'] }}</p>
                                            <p class="text-xs text-slate-500">{{ $gap['detail'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white border border-slate-200 rounded-xl p-12 text-center">
                    <p class="text-slate-500 mb-4">No analyses generated yet.</p>
                    <a href="{{ route('tenders.show', $tender) }}" class="text-teal-600 hover:text-teal-700 text-sm transition">Go to tender workspace to generate analyses</a>
                </div>
            @endforelse
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <h3 class="font-semibold text-slate-900 mb-4">Generate Analysis</h3>
                <div class="space-y-2">
                    @foreach(['consensus', 'conflicts', 'gaps', 'themes', 'risks', 'insights'] as $type)
                        <form method="POST" action="{{ route('analysis.generate', $tender) }}">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button class="w-full text-left px-3 py-2 bg-slate-50 border border-slate-200 hover:bg-slate-100 text-slate-700 text-sm rounded-lg transition">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <h3 class="font-semibold text-slate-900 mb-3">Participant Status</h3>
                <div class="space-y-3">
                    @foreach($tender->participants as $p)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-teal-50 border border-teal-200 flex items-center justify-center text-xs font-bold text-teal-700">
                                {{ substr($p->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-900 truncate">{{ $p->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $p->role }}</p>
                            </div>
                            <span class="inline-flex px-2 py-0.5 text-[10px] font-medium rounded-full border {{ $p->status_color }}">{{ ucfirst($p->status) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
