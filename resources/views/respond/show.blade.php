<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Respond — {{ $tender->name }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:300,400,500,600,700&family=dm-serif-display:400,400i&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .font-serif { font-family: 'DM Serif Display', Georgia, serif; }
    </style>
</head>
<body class="antialiased bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-teal-700 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                </div>
                <span class="font-bold text-lg text-slate-900">Tenderly</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">{{ $tender->name }}</h1>
            <p class="text-slate-500 mt-1">Welcome, <strong class="text-slate-700">{{ $participant->name }}</strong>{{ $participant->role ? ' (' . $participant->role . ')' : '' }}</p>
            @if($tender->deadline)
                <p class="text-sm text-slate-400 mt-2">Deadline: <span class="{{ $tender->deadline->isPast() ? 'text-red-600 font-medium' : 'text-slate-600' }}">{{ $tender->deadline->format('F d, Y') }}</span></p>
            @endif
        </div>

        @if(session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-lg text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('respond.save', $participant->token) }}">
            @csrf

            @foreach($criteria as $criterion)
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <h2 class="text-lg font-semibold text-slate-800">{{ $criterion->name }}</h2>
                        @if($criterion->weight)
                            <span class="text-xs text-slate-400">Weight: {{ $criterion->weight }}%</span>
                        @endif
                    </div>
                    @if($criterion->description)
                        <p class="text-sm text-slate-500 mb-4">{{ $criterion->description }}</p>
                    @endif

                    <div class="space-y-4">
                        @foreach($criterion->questions as $question)
                            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                                <div class="px-5 py-3 bg-slate-50 border-b border-slate-100 flex items-start gap-2">
                                    @if($question->priority !== 'normal')
                                        <span class="shrink-0 mt-0.5 px-1.5 py-0.5 text-[10px] font-bold rounded {{ $question->priority === 'critical' ? 'text-red-700 bg-red-50' : 'text-amber-700 bg-amber-50' }}">{{ strtoupper($question->priority) }}</span>
                                    @endif
                                    <p class="text-sm font-medium text-slate-700">{{ $question->question_text }}</p>
                                </div>
                                <div class="p-5">
                                    <textarea name="answers[{{ $question->id }}]" rows="4" class="w-full border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400" placeholder="Type your response...">{{ $responses[$question->id] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Sub-criteria --}}
                    @foreach($criterion->children as $child)
                        <div class="mt-6 ml-6">
                            <h3 class="text-sm font-semibold text-slate-700 mb-3">{{ $child->name }}</h3>
                            <div class="space-y-4">
                                @foreach($child->questions as $question)
                                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                                        <div class="px-5 py-3 bg-slate-50 border-b border-slate-100">
                                            <p class="text-sm font-medium text-slate-700">{{ $question->question_text }}</p>
                                        </div>
                                        <div class="p-5">
                                            <textarea name="answers[{{ $question->id }}]" rows="3" class="w-full border-slate-200 rounded-lg text-sm text-slate-800 focus:ring-teal-500 focus:border-teal-500 placeholder-slate-400" placeholder="Type your response...">{{ $responses[$question->id] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="flex items-center gap-3 py-6 border-t border-slate-200">
                <button type="submit" class="px-6 py-3 bg-teal-600 hover:bg-teal-500 text-white text-sm font-semibold rounded-lg transition">Save Progress</button>
                <button type="button" onclick="if(confirm('Submit your responses? You won\'t be able to edit them after submission.')) document.getElementById('submit-form').submit()" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg transition">Submit Final Responses</button>
            </div>
        </form>

        <form id="submit-form" method="POST" action="{{ route('respond.submit', $participant->token) }}" class="hidden">@csrf</form>

        <p class="text-center text-slate-400 text-xs mt-8">Powered by Tenderly</p>
    </div>
</body>
</html>
