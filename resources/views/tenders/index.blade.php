@extends('layouts.app')
@section('title', 'Tenders')
@section('header')
    <h1 class="text-lg font-semibold text-slate-900">Tenders</h1>
@endsection
@section('actions')
    <a href="{{ route('tenders.create') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">New Tender</a>
@endsection

@section('content')
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($tenders as $tender)
            <a href="{{ route('tenders.show', $tender) }}" class="bg-white border border-slate-200 rounded-xl p-5 hover:border-slate-300 hover:shadow-sm transition group">
                <div class="flex items-start justify-between mb-3">
                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-full border {{ $tender->status_color }}">{{ ucfirst($tender->status) }}</span>
                    @if($tender->deadline)
                        <span class="text-xs {{ $tender->deadline->isPast() ? 'text-red-600' : 'text-slate-400' }}">{{ $tender->deadline->format('M d') }}</span>
                    @endif
                </div>
                <h3 class="font-medium text-slate-900 group-hover:text-teal-700 transition mb-1.5 line-clamp-2">{{ $tender->name }}</h3>
                @if($tender->description)
                    <p class="text-xs text-slate-500 mb-4 line-clamp-2">{{ $tender->description }}</p>
                @endif
                <div class="flex items-center gap-4 pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-500">{{ $tender->criteria_count }} criteria</span>
                    <span class="text-xs text-slate-500">{{ $tender->participants_count }} participants</span>
                </div>
            </a>
        @empty
            <div class="sm:col-span-2 lg:col-span-3 py-16 text-center">
                <div class="w-16 h-16 bg-slate-100 border border-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <p class="text-slate-500 mb-4">No tenders yet.</p>
                <a href="{{ route('tenders.create') }}" class="inline-flex px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-lg transition">Create Your First Tender</a>
            </div>
        @endforelse
    </div>

    @if($tenders->hasPages())
        <div class="mt-6">{{ $tenders->links() }}</div>
    @endif
@endsection
