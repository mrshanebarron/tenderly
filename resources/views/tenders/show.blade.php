@extends('layouts.app')
@section('title', $tender->name)
@section('header')
    <div class="flex items-center gap-2 text-sm">
        <a href="{{ route('tenders.index') }}" class="text-zinc-500 hover:text-zinc-300">Tenders</a>
        <svg class="w-4 h-4 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="font-semibold text-white truncate max-w-xs">{{ $tender->name }}</span>
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
            <a href="{{ route('analysis.show', $tender) }}" class="px-3 py-1.5 bg-violet-600 hover:bg-violet-500 text-white text-xs font-medium rounded-lg transition">View Analysis</a>
            <form method="POST" action="{{ route('tenders.complete', $tender) }}">
                @csrf
                <button class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-medium rounded-lg transition">Complete</button>
            </form>
        @endif
        <a href="{{ route('tenders.edit', $tender) }}" class="px-3 py-1.5 bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-300 text-xs font-medium rounded-lg transition">Edit</a>
    </div>
@endsection

@section('content')
<div x-data="{ activeTab: 'overview' }" class="space-y-6">
    {{-- Tabs --}}
    <div class="flex gap-1 border-b border-white/5 -mt-2">
        <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'text-violet-400 border-violet-400' : 'text-zinc-500 border-transparent hover:text-zinc-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Overview</button>
        <button @click="activeTab = 'criteria'" :class="activeTab === 'criteria' ? 'text-violet-400 border-violet-400' : 'text-zinc-500 border-transparent hover:text-zinc-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Criteria & Questions <span class="text-xs text-zinc-600">({{ $totalQuestions }})</span></button>
        <button @click="activeTab = 'participants'" :class="activeTab === 'participants' ? 'text-violet-400 border-violet-400' : 'text-zinc-500 border-transparent hover:text-zinc-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Participants <span class="text-xs text-zinc-600">({{ $tender->participants->count() }})</span></button>
        <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'text-violet-400 border-violet-400' : 'text-zinc-500 border-transparent hover:text-zinc-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition">Documents <span class="text-xs text-zinc-600">({{ $tender->documents->count() }})</span></button>
        <button @click="activeTab = 'analysis'" :class="activeTab === 'analysis' ? 'text-violet-400 border-violet-400' : 'text-zinc-500 border-transparent hover:text-zinc-300'" class="px-4 py-3 text-sm font-medium border-b-2 transition">AI Analysis <span class="text-xs text-zinc-600">({{ $tender->analyses->count() }})</span></button>
    </div>

    {{-- Overview Tab --}}
    <div x-show="activeTab === 'overview'" x-cloak>
        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6">
                    <h3 class="font-semibold text-white mb-3">Description</h3>
                    <p class="text-sm text-zinc-400 leading-relaxed">{{ $tender->description ?: 'No description provided.' }}</p>
                </div>
                @if($tender->objectives)
                    <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6">
                        <h3 class="font-semibold text-white mb-3">Objectives</h3>
                        <ul class="space-y-2">
                            @foreach(explode("\n", $tender->objectives) as $obj)
                                @if(trim($obj))
                                    <li class="flex items-start gap-2 text-sm text-zinc-400">
                                        <svg class="w-4 h-4 text-violet-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                        {{ trim($obj) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="space-y-6">
                <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6 space-y-4">
                    <h3 class="font-semibold text-white">Details</h3>
                    @if($tender->deadline)
                        <div>
                            <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Deadline</p>
                            <p class="text-sm font-medium {{ $tender->deadline->isPast() ? 'text-red-400' : 'text-white' }}">{{ $tender->deadline->format('F d, Y') }}</p>
                        </div>
                    @endif
                    @if($tender->focus_themes)
                        <div>
                            <p class="text-xs text-zinc-500 uppercase tracking-wider mb-2">Focus Themes</p>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach(explode(',', $tender->focus_themes) as $theme)
                                    <span class="px-2 py-0.5 bg-violet-500/10 text-violet-400 text-xs rounded-full border border-violet-500/20">{{ trim($theme) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <div>
                        <p class="text-xs text-zinc-500 uppercase tracking-wider mb-1">Progress</p>
                        <div class="flex items-center gap-3">
                            <div class="flex-1 bg-zinc-800 rounded-full h-2 overflow-hidden">
                                <div class="bg-violet-500 h-full rounded-full transition-all" style="width: {{ $tender->progress }}%"></div>
                            </div>
                            <span class="text-xs text-zinc-400 font-medium">{{ $tender->progress }}%</span>
                        </div>
                    </div>
                </div>
                @if($tender->context_notes)
                    <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6">
                        <h3 class="font-semibold text-white mb-2">Context Notes</h3>
                        <p class="text-xs text-zinc-500 leading-relaxed">{{ $tender->context_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Criteria & Questions Tab --}}
    <div x-show="activeTab === 'criteria'" x-cloak>
        <div class="space-y-4">
            @foreach($tender->criteria as $criterion)
                <div class="bg-zinc-900/50 border border-white/5 rounded-xl overflow-hidden" x-data="{ open: true }">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center justify-between cursor-pointer" @click="open = !open">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 text-zinc-500 transition-transform" :class="open && 'rotate-90'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            <h3 class="font-semibold text-white">{{ $criterion->name }}</h3>
                            <span class="text-xs text-zinc-500">Weight: {{ $criterion->weight }}%</span>
                            <span class="text-xs text-zinc-600">{{ $criterion->questions->count() }} questions</span>
                        </div>
                        <form method="POST" action="{{ route('questions.generate', $criterion) }}" class="flex items-center gap-2" onclick="event.stopPropagation()">
                            @csrf
                            <button class="px-2.5 py-1 bg-violet-600/20 border border-violet-500/30 hover:bg-violet-600/30 text-violet-400 text-xs font-medium rounded-lg transition flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                                Generate AI Questions
                            </button>
                        </form>
                    </div>
                    <div x-show="open" x-transition>
                        @if($criterion->description)
                            <div class="px-6 py-3 bg-zinc-900/30 border-b border-white/5">
                                <p class="text-xs text-zinc-500">{{ $criterion->description }}</p>
                            </div>
                        @endif
                        <div class="divide-y divide-white/5">
                            @foreach($criterion->questions as $question)
                                <div class="px-6 py-3 flex items-start gap-3 group">
                                    <span class="shrink-0 mt-1 px-1.5 py-0.5 text-[10px] font-bold rounded {{ $question->priority_color }}">{{ strtoupper(substr($question->priority, 0, 1)) }}</span>
                                    <div class="flex-1">
                                        <p class="text-sm text-zinc-300">{{ $question->question_text }}</p>
                                        <div class="flex items-center gap-3 mt-1">
                                            <span class="text-[10px] text-zinc-600">{{ $question->source === 'ai_generated' ? 'AI Generated' : ($question->source === 'seed' ? 'Seed' : 'Manual') }}</span>
                                            <span class="text-[10px] text-zinc-600">{{ $question->responses->count() }} responses</span>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('questions.destroy', $question) }}" class="opacity-0 group-hover:opacity-100 transition">
                                        @csrf @method('DELETE')
                                        <button class="text-zinc-700 hover:text-red-400 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                        {{-- Add question form --}}
                        <div class="px-6 py-3 border-t border-white/5" x-data="{ adding: false }">
                            <button @click="adding = true" x-show="!adding" class="text-xs text-violet-400 hover:text-violet-300 transition">+ Add Question</button>
                            <form method="POST" action="{{ route('questions.store', $criterion) }}" x-show="adding" x-cloak class="flex gap-2">
                                @csrf
                                <input type="text" name="question_text" required placeholder="Enter question..." class="flex-1 bg-zinc-950/50 border-zinc-800 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                                <select name="priority" class="bg-zinc-950/50 border-zinc-800 rounded-lg text-zinc-300 text-xs focus:ring-violet-500 focus:border-violet-500">
                                    <option value="normal">Normal</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                                <button type="submit" class="px-3 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-medium rounded-lg transition">Add</button>
                                <button type="button" @click="adding = false" class="px-3 py-2 text-zinc-500 hover:text-zinc-300 text-xs transition">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Add Criterion --}}
            <div class="bg-zinc-900/30 border border-dashed border-white/10 rounded-xl p-6" x-data="{ adding: false }">
                <button @click="adding = true" x-show="!adding" class="w-full text-center text-sm text-zinc-500 hover:text-violet-400 transition">+ Add Criterion</button>
                <form method="POST" action="{{ route('criteria.store', $tender) }}" x-show="adding" x-cloak class="space-y-3">
                    @csrf
                    <div class="grid md:grid-cols-3 gap-3">
                        <div class="md:col-span-2">
                            <input type="text" name="name" required placeholder="Criterion name..." class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                        </div>
                        <div>
                            <input type="number" name="weight" placeholder="Weight %" min="1" max="100" class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                        </div>
                    </div>
                    <textarea name="description" rows="2" placeholder="Description (optional)..." class="w-full bg-zinc-950/50 border-zinc-800 rounded-lg text-zinc-300 text-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-medium rounded-lg transition">Add Criterion</button>
                        <button type="button" @click="adding = false" class="px-4 py-2 text-zinc-500 hover:text-zinc-300 text-xs transition">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Participants Tab --}}
    <div x-show="activeTab === 'participants'" x-cloak>
        <div class="space-y-4">
            <div class="bg-zinc-900/50 border border-white/5 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5">
                    <h3 class="font-semibold text-white">Expert Participants</h3>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($tender->participants as $participant)
                        <div class="px-6 py-4 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-500/20 to-indigo-600/20 border border-violet-500/30 flex items-center justify-center text-sm font-bold text-violet-400">
                                {{ substr($participant->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-white">{{ $participant->name }}</p>
                                    <span class="inline-flex px-2 py-0.5 text-[10px] font-medium rounded-full border {{ $participant->status_color }}">{{ ucfirst($participant->status) }}</span>
                                </div>
                                <div class="flex items-center gap-3 mt-0.5">
                                    <span class="text-xs text-zinc-500">{{ $participant->email }}</span>
                                    @if($participant->role)
                                        <span class="text-xs text-zinc-600">{{ $participant->role }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <p class="text-sm font-medium text-zinc-300">{{ $participant->completion_percent }}%</p>
                                <p class="text-[10px] text-zinc-600">{{ $participant->last_active_at ? 'Active ' . $participant->last_active_at->diffForHumans() : 'Not started' }}</p>
                            </div>
                            <div class="shrink-0 flex items-center gap-1" x-data="{ copied: false }">
                                <button @click="navigator.clipboard.writeText('{{ url('/respond/' . $participant->token) }}'); copied = true; setTimeout(() => copied = false, 2000)" class="p-1.5 text-zinc-600 hover:text-violet-400 transition" title="Copy invite link">
                                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-3.374a4.5 4.5 0 00-1.242-7.244l-4.5-4.5a4.5 4.5 0 00-6.364 6.364L4.343 8.69" transform="translate(0,2)"/></svg>
                                    <svg x-show="copied" x-cloak class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-zinc-500">No participants invited yet.</div>
                    @endforelse
                </div>
            </div>

            {{-- Invite Participant --}}
            <div class="bg-zinc-900/50 border border-white/5 rounded-xl">
                <div class="px-6 py-4 border-b border-white/5">
                    <h3 class="font-semibold text-white">Invite Expert</h3>
                </div>
                <form method="POST" action="{{ route('participants.store', $tender) }}" class="p-6">
                    @csrf
                    <div class="grid md:grid-cols-3 gap-3">
                        <input type="text" name="name" required placeholder="Full name" class="bg-zinc-950/50 border-zinc-800 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                        <input type="email" name="email" required placeholder="Email address" class="bg-zinc-950/50 border-zinc-800 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                        <input type="text" name="role" placeholder="Role (optional)" class="bg-zinc-950/50 border-zinc-800 rounded-lg text-white text-sm focus:ring-violet-500 focus:border-violet-500">
                    </div>
                    <button type="submit" class="mt-3 px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white text-xs font-medium rounded-lg transition">Send Invitation</button>
                </form>
            </div>
        </div>
    </div>

    {{-- Documents Tab --}}
    <div x-show="activeTab === 'documents'" x-cloak>
        <div class="space-y-4">
            <div class="bg-zinc-900/50 border border-white/5 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5">
                    <h3 class="font-semibold text-white">Uploaded Documents</h3>
                </div>
                <div class="divide-y divide-white/5">
                    @forelse($tender->documents as $doc)
                        <div class="px-6 py-3 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center">
                                <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $doc->original_name }}</p>
                                <p class="text-xs text-zinc-500">{{ $doc->human_size }} &middot; {{ ucfirst($doc->processing_status) }}</p>
                            </div>
                            @if($doc->processing_status === 'completed')
                                <span class="shrink-0 px-2 py-0.5 text-[10px] text-emerald-400 bg-emerald-400/10 rounded-full border border-emerald-400/20">Processed</span>
                            @endif
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-zinc-500">No documents uploaded.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6">
                <h3 class="font-semibold text-white mb-3">Upload Document</h3>
                <form method="POST" action="{{ route('documents.store', $tender) }}" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <input type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx" required class="text-sm text-zinc-400 file:mr-3 file:px-4 file:py-2 file:rounded-lg file:border-0 file:bg-violet-600 file:text-white file:text-xs file:font-medium file:cursor-pointer hover:file:bg-violet-500">
                    <button type="submit" class="px-4 py-2 bg-white/5 border border-white/10 hover:bg-white/10 text-zinc-300 text-xs font-medium rounded-lg transition">Upload & Process</button>
                </form>
                <p class="text-[10px] text-zinc-600 mt-2">Supported: PDF, Word, Excel. Max 10MB. Documents are processed by AI for question generation.</p>
            </div>
        </div>
    </div>

    {{-- Analysis Tab --}}
    <div x-show="activeTab === 'analysis'" x-cloak>
        <div class="space-y-4">
            {{-- Generate Analysis --}}
            <div class="bg-zinc-900/50 border border-white/5 rounded-xl p-6">
                <h3 class="font-semibold text-white mb-3">Generate AI Analysis</h3>
                <p class="text-xs text-zinc-500 mb-4">Run AI analysis on participant responses to identify patterns, conflicts, and insights.</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['consensus', 'conflicts', 'gaps', 'themes', 'risks', 'insights', 'session_prep'] as $type)
                        <form method="POST" action="{{ route('analysis.generate', $tender) }}">
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}">
                            <button class="px-3 py-1.5 bg-violet-600/20 border border-violet-500/30 hover:bg-violet-600/30 text-violet-400 text-xs font-medium rounded-lg transition">
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            {{-- Existing Analyses --}}
            @foreach($tender->analyses as $analysis)
                <div class="bg-zinc-900/50 border border-white/5 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $analysis->type_color }}">{{ strtoupper(str_replace('_', ' ', $analysis->type)) }}</span>
                        <h3 class="font-semibold text-white">{{ $analysis->title }}</h3>
                        <span class="ml-auto text-xs text-zinc-600">{{ number_format($analysis->confidence * 100) }}% confidence</span>
                    </div>
                    <div class="p-6">
                        @if(isset($analysis->content['summary']))
                            <p class="text-sm text-zinc-400 mb-4">{{ $analysis->content['summary'] }}</p>
                        @endif

                        @if(isset($analysis->content['points']))
                            <div class="space-y-3">
                                @foreach($analysis->content['points'] as $point)
                                    <div class="flex items-start gap-3">
                                        <div class="shrink-0 w-12 h-12 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center">
                                            <span class="text-sm font-bold text-emerald-400">{{ $point['agreement_level'] }}%</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $point['area'] }}</p>
                                            <p class="text-xs text-zinc-500 mt-0.5">{{ $point['detail'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(isset($analysis->content['gaps']))
                            <div class="space-y-3">
                                @foreach($analysis->content['gaps'] as $gap)
                                    <div class="flex items-start gap-3">
                                        <span class="shrink-0 mt-0.5 px-2 py-0.5 text-[10px] font-bold rounded {{ $gap['severity'] === 'critical' ? 'text-red-400 bg-red-400/10' : ($gap['severity'] === 'high' ? 'text-amber-400 bg-amber-400/10' : 'text-zinc-400 bg-zinc-400/10') }}">{{ strtoupper($gap['severity']) }}</span>
                                        <div>
                                            <p class="text-sm font-medium text-white">{{ $gap['area'] }}</p>
                                            <p class="text-xs text-zinc-500 mt-0.5">{{ $gap['detail'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(isset($analysis->content['conflicts']))
                            <div class="space-y-3">
                                @foreach($analysis->content['conflicts'] as $conflict)
                                    <div class="border border-white/5 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <p class="text-sm font-medium text-white">{{ $conflict['topic'] }}</p>
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded {{ $conflict['severity'] === 'high' ? 'text-red-400 bg-red-400/10' : 'text-amber-400 bg-amber-400/10' }}">{{ strtoupper($conflict['severity']) }}</span>
                                        </div>
                                        <ul class="space-y-1">
                                            @foreach($conflict['positions'] as $pos)
                                                <li class="text-xs text-zinc-400 flex items-start gap-2">
                                                    <span class="text-zinc-600">&bull;</span> {{ $pos }}
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
