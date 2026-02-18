@extends('layouts.app')
@section('title', $tender->name)
@section('header')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('tenders.index') }}" class="text-slate-500 hover:text-slate-700">Tenders</a>
        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-slate-900 truncate max-w-xs">{{ $tender->name }}</span>
        <span class="ml-2 inline-flex px-2 py-0.5 text-xs font-medium rounded-full border {{ $tender->status_color }}">{{ ucfirst($tender->status) }}</span>
    </div>
@endsection
@section('actions')
    <div class="flex items-center gap-2">
        @if($tender->status === 'draft')
            <form method="POST" action="{{ route('tenders.activate', $tender) }}">
                @csrf
                <button class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs font-medium rounded-lg transition">Activate</button>
            </form>
        @elseif($tender->status === 'active')
            <form method="POST" action="{{ route('tenders.review', $tender) }}">
                @csrf
                <button class="px-3 py-1.5 bg-amber-600 hover:bg-amber-500 text-white text-xs font-medium rounded-lg transition">Move to Review</button>
            </form>
        @elseif($tender->status === 'reviewing')
            <a href="{{ route('analysis.show', $tender) }}" class="px-3 py-1.5 bg-teal-600 hover:bg-teal-500 text-white text-xs font-medium rounded-lg transition">View Analysis</a>
            <form method="POST" action="{{ route('tenders.complete', $tender) }}">
                @csrf
                <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-lg transition">Complete</button>
            </form>
        @endif
        <a href="{{ route('tenders.edit', $tender) }}" class="px-3 py-1.5 bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg transition">Edit</a>
    </div>
@endsection

@section('content')
<div x-data="{ activeTab: 'overview' }" class="space-y-6">
    {{-- Tabs --}}
    <div class="flex gap-1 border-b border-slate-200 -mt-2">
        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'text-teal-700 border-teal-600' : 'text-slate-500 border-transparent hover:text-slate-700'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Overview</button>
        <button @click="activeTab = 'criteria'" :class="activeTab === 'criteria' ? 'text-teal-700 border-teal-600' : 'text-slate-500 border-transparent hover:text-slate-700'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Criteria & Questions <span class="text-xs text-slate-400">({{ $totalQuestions }})</span></button>
        <button @click="activeTab = 'participants'" :class="activeTab === 'participants' ? 'text-teal-700 border-teal-600' : 'text-slate-500 border-transparent hover:text-slate-700'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Participants <span class="text-xs text-slate-400">({{ $tender->participants->count() }})</span></button>
        <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'text-teal-700 border-teal-600' : 'text-slate-500 border-transparent hover:text-slate-700'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Documents <span class="text-xs text-slate-400">({{ $tender->documents->count() }})</span></button>
        <button @click="activeTab = 'analysis'" :class="activeTab === 'analysis' ? 'text-teal-700 border-teal-600' : 'text-slate-500 border-transparent hover:text-slate-700'" class="px-4 py-3 text-sm font-medium border-b-2 transition">AI Analysis <span class="text-xs text-slate-400">({{ $tender->analyses->count() }})</span></button>
    </div>

    {{-- Overview Tab --}}
    <div x-show="activeTab === 'overview'" x-cloak>
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-slate-200 rounded-xl p-6">
                    <h3 class="font-semibold text-slate-900 mb-3">Description</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">{{ $tender->description ?: 'No description provided.' }}</p>
                </div>
                @if($tender->objectives)
                    <div class="bg-white border border-slate-200 rounded-xl p-6">
                        <h3 class="font-semibold text-slate-900 mb-3">Objectives</h3>
                        <ul class="space-y-2">
                            @foreach(explode("\n", $tender->objectives) as $obj)
                                @if(trim($obj))
                                    <li class="flex items-start gap-2 text-sm text-slate-600">
                                        <svg class="w-4 h-4 text-teal-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                        {{ trim($obj) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="space-y-6">
                <div class="bg-white border border-slate-200 rounded-xl p-6 space-y-4">
                    <h3 class="font-semibold text-slate-900">Details</h3>
                    @if($tender->deadline)
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Deadline</p>
                            <p class="text-sm font-medium {{ $tender->deadline->isPast() ? 'text-red-600' : 'text-slate-900' }}">{{ $tender->deadline->format('F d, Y') }}</p>
                        </div>
                    @endif
                    @if($tender->focus_themes)
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wider mb-2">Focus Themes</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(explode(',', $tender->focus_themes) as $theme)
                                    <span class="px-2 py-0.5 bg-teal-50 text-teal-700 text-xs rounded-full border border-teal-200">{{ trim($theme) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Progress</p>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-slate-200 rounded-full h-2 overflow-hidden">
                                <div class="bg-teal-500 h-full rounded-full transition-all" style="width: {{ $tender->progress }}%"></div>
                            </div>
                            <span class="text-xs text-slate-500 font-medium">{{ $tender->progress }}%</span>
                        </div>
                    </div>
                </div>
                @if($tender->context_notes)
                    <div class="bg-white border border-slate-200 rounded-xl p-6">
                        <h3 class="font-semibold text-slate-900 mb-2">Context Notes</h3>
                        <p class="text-xs text-slate-500 leading-relaxed">{{ $tender->context_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Criteria & Questions Tab --}}
    <div x-show="activeTab === 'criteria'" x-cloak>
        <div class="space-y-4">
            @foreach($tender->criteria as $criterion)
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden" x-data="{ open: true }">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between cursor-pointer" @click="open = !open">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-slate-400 transition-transform" :class="open && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <h3 class="font-semibold text-slate-900">{{ $criterion->name }}</h3>
                            <span class="text-xs text-slate-500">Weight: {{ $criterion->weight }}%</span>
                            <span class="text-xs text-slate-400">{{ $criterion->questions->count() }} questions</span>
                        </div>
                        <form method="POST" action="{{ route('questions.generate', $criterion) }}" class="flex items-center gap-2" onclick="event.stopPropagation()">
                            @csrf
                            <button class="px-2.5 py-1 bg-teal-50 border border-teal-200 hover:bg-teal-100 text-teal-700 text-xs font-medium rounded-lg transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                                Generate AI Questions
                            </button>
                        </form>
                    </div>
                    <div x-show="open" x-transition>
                        @if($criterion->description)
                            <div class="px-6 py-3 bg-slate-50 border-b border-slate-100">
                                <p class="text-xs text-slate-500">{{ $criterion->description }}</p>
                            </div>
                        @endif
                        <div class="divide-y divide-slate-100">
                            @foreach($criterion->questions as $question)
                                <div class="px-6 py-3 flex items-start gap-3 group">
                                    <span class="shrink-0 mt-1 px-1.5 py-0.5 text-[10px] font-bold rounded {{ $question->priority_color }}">{{ strtoupper(substr($question->priority, 0, 1)) }}</span>
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700">{{ $question->question_text }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[10px] text-slate-400">{{ $question->source === 'ai_generated' ? 'AI Generated' : ($question->source === 'seed' ? 'Seed' : 'Manual') }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $question->responses->count() }} responses</span>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('questions.destroy', $question) }}" class="opacity-0 group-hover:opacity-100 transition">
                                        @csrf @method('DELETE')
                                        <button class="text-slate-300 hover:text-red-500 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        {{-- Add question form --}}
                        <div class="px-6 py-3 border-t border-slate-100" x-data="{ adding: false }">
                            <button @click="adding = true" x-show="!adding" class="text-xs text-teal-600 hover:text-teal-700 transition">+ Add Question</button>
                            <form method="POST" action="{{ route('questions.store', $criterion) }}" x-show="adding" x-cloak class="flex gap-2">
                                @csrf
                                <input type="text" name="question_text" required placeholder="Enter question..." class="flex-1 bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                                <select name="priority" class="bg-white border-slate-300 rounded-lg text-slate-700 text-xs focus:ring-teal-500 focus:border-teal-500">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">Add</button>
                                <button type="button" @click="adding = false" class="px-3 py-2 text-slate-500 hover:text-slate-700 text-xs transition">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Add Criterion --}}
            <div class="bg-white border border-dashed border-slate-300 rounded-xl p-6" x-data="{ adding: false }">
                <button @click="adding = true" x-show="!adding" class="w-full text-center text-sm text-slate-500 hover:text-teal-600 transition">+ Add Criterion</button>
                <form method="POST" action="{{ route('criteria.store', $tender) }}" x-show="adding" x-cloak class="space-y-3">
                    @csrf
                    <div class="grid md:grid-cols-3 gap-3">
                        <div class="md:col-span-2">
                            <input type="text" name="name" required placeholder="Criterion name..." class="w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                        <div>
                            <input type="number" name="weight" placeholder="Weight %" min="1" max="100" class="w-full bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>
                    <textarea name="description" rows="2" placeholder="Description (optional)..." class="w-full bg-white border-slate-300 rounded-lg text-slate-700 text-sm focus:ring-teal-500 focus:border-teal-500"></textarea>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">Add Criterion</button>
                        <button type="button" @click="adding = false" class="px-4 py-2 text-slate-500 hover:text-slate-700 text-xs transition">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Participants Tab --}}
    <div x-show="activeTab === 'participants'" x-cloak>
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-900">Expert Participants</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($tender->participants as $participant)
                        <div class="px-6 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-teal-50 border border-teal-200 flex items-center justify-center text-sm font-bold text-teal-700">
                                {{ substr($participant->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-slate-900">{{ $participant->name }}</p>
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-medium rounded-full border {{ $participant->status_color }}">{{ ucfirst($participant->status) }}</span>
                                </div>
                                <div class="flex items-center gap-3 mt-0.5">
                                    <span class="text-xs text-slate-500">{{ $participant->email }}</span>
                                    @if($participant->role)
                                        <span class="text-xs text-slate-400">{{ $participant->role }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-sm font-medium text-slate-700">{{ $participant->completion_percent }}%</p>
                                <p class="text-[10px] text-slate-400">{{ $participant->last_active_at ? 'Active ' . $participant->last_active_at->diffForHumans() : 'Not started' }}</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-1" x-data="{ copied: false }">
                                <button @click="navigator.clipboard.writeText('{{ url('/respond/' . $participant->token) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="p-1.5 text-slate-400 hover:text-teal-600 transition" title="Copy invite link">
                                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-3.374a4.5 4.5 0 00-1.242-7.244l-4.5-4.5a4.5 4.5 0 00-6.364 6.364L4.343 8.69" transform="translate(0,2)"/></svg>
                                    <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-500">No participants invited yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Invite Participant --}}
            <div class="bg-white border border-slate-200 rounded-xl">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-900">Invite Expert</h3>
                </div>
                <form method="POST" action="{{ route('participants.store', $tender) }}" class="p-6">
                    @csrf
                    <div class="grid md:grid-cols-3 gap-3">
                        <input type="text" name="name" required placeholder="Full name" class="bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                        <input type="email" name="email" required placeholder="Email address" class="bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                        <input type="text" name="role" placeholder="Role (optional)" class="bg-white border-slate-300 rounded-lg text-slate-800 text-sm focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    <button type="submit" class="mt-3 px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-medium rounded-lg transition">Send Invitation</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Documents Tab --}}
    <div x-show="activeTab === 'documents'" x-cloak>
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200">
                    <h3 class="font-semibold text-slate-900">Uploaded Documents</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($tender->documents as $doc)
                        <div class="px-6 py-3 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-teal-50 border border-teal-200 flex items-center justify-center">
                                <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $doc->original_name }}</p>
                                <p class="text-xs text-slate-500">{{ $doc->human_size }} &middot; {{ ucfirst($doc->processing_status) }}</p>
                            </div>
                            @if($doc->processing_status === 'completed')
                                <span class="shrink-0 px-2 py-0.5 text-[10px] text-emerald-700 bg-emerald-50 rounded-full border border-emerald-200">Processed</span>
                            @endif
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-slate-500">No documents uploaded.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <h3 class="font-semibold text-slate-900 mb-3">Upload Document</h3>
                <form method="POST" action="{{ route('documents.store', $tender) }}" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <input type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx" required class="text-sm text-slate-500 file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-slate-900 file:text-white file:text-xs file:font-medium file:cursor-pointer hover:file:bg-slate-800">
                    <button type="submit" class="px-4 py-2 bg-slate-100 border border-slate-200 hover:bg-slate-200 text-slate-700 text-xs font-medium rounded-lg transition">Upload & Process</button>
                </form>
                <p class="text-[10px] text-slate-400 mt-2">Supported: PDF, Word, Excel. Max 10MB. Documents are processed by AI for question generation.</p>
            </div>
        </div>
    </div>

    {{-- Analysis Tab --}}
    <div x-show="activeTab === 'analysis'" x-cloak>
        <div class="space-y-4">
            {{-- Generate Analysis --}}
            <div class="bg-white border border-slate-200 rounded-xl p-6">
                <h3 class="font-semibold text-slate-900 mb-3">Generate AI Analysis</h3>
                <p class="text-xs text-slate-500 mb-4">Run AI analysis on participant responses to identify patterns, conflicts, and insights.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['consensus', 'conflicts', 'gaps', 'themes', 'risks', 'insights', 'session_prep'] as $type)
                        <form method="POST" action="{{ route('analysis.generate', $tender) }}">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button class="px-3 py-1.5 bg-teal-50 border border-teal-200 hover:bg-teal-100 text-teal-700 text-xs font-medium rounded-lg transition">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            {{-- Existing Analyses --}}
            @foreach($tender->analyses as $analysis)
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 flex items-center gap-3">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $analysis->type_color }}">{{ strtoupper(str_replace('_', ' ', $analysis->type)) }}</span>
                        <h3 class="font-semibold text-slate-900">{{ $analysis->title }}</h3>
                        <span class="ml-auto text-xs text-slate-400">{{ number_format($analysis->confidence * 100) }}% confidence</span>
                    </div>
                    <div class="p-6">
                        @if(isset($analysis->content['summary']))
                            <p class="text-sm text-slate-600 mb-4">{{ $analysis->content['summary'] }}</p>
                        @endif

                        @if(isset($analysis->content['points']))
                            <div class="space-y-3">
                                @foreach($analysis->content['points'] as $point)
                                    <div class="flex items-start gap-3">
                                        <div class="shrink-0 w-12 h-12 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-center">
                                            <span class="text-sm font-bold text-emerald-600">{{ $point['agreement_level'] }}%</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $point['area'] }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $point['detail'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(isset($analysis->content['gaps']))
                            <div class="space-y-3">
                                @foreach($analysis->content['gaps'] as $gap)
                                    <div class="flex items-start gap-3">
                                        <span class="shrink-0 mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded {{ $gap['severity'] === 'critical' ? 'text-red-700 bg-red-50' : ($gap['severity'] === 'high' ? 'text-amber-700 bg-amber-50' : 'text-slate-600 bg-slate-100') }}">{{ strtoupper($gap['severity']) }}</span>
                                        <div>
                                            <p class="text-sm font-medium text-slate-900">{{ $gap['area'] }}</p>
                                            <p class="text-xs text-slate-500 mt-0.5">{{ $gap['detail'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(isset($analysis->content['conflicts']))
                            <div class="space-y-3">
                                @foreach($analysis->content['conflicts'] as $conflict)
                                    <div class="border border-slate-200 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <p class="text-sm font-medium text-slate-900">{{ $conflict['topic'] }}</p>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $conflict['severity'] === 'high' ? 'text-red-700 bg-red-50' : 'text-amber-700 bg-amber-50' }}">{{ strtoupper($conflict['severity']) }}</span>
                                        </div>
                                        <ul class="space-y-1">
                                            @foreach($conflict['positions'] as $pos)
                                                <li class="text-xs text-slate-600 flex items-start gap-2">
                                                    <span class="text-slate-400">&bull;</span> {{ $pos }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
