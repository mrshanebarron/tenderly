<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tenderly — AI-Powered Tender Collaboration</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .glow-ring { box-shadow: 0 0 80px rgba(139, 92, 246, 0.08), 0 0 160px rgba(139, 92, 246, 0.04); }
        .mockup-shadow { box-shadow: 0 25px 60px -12px rgba(0,0,0,0.5), 0 0 0 1px rgba(255,255,255,0.05); }
        .subtle-grid { background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.03) 1px, transparent 0); background-size: 32px 32px; }
    </style>
</head>
<body class="antialiased bg-zinc-950 text-zinc-100">

    {{-- Nav — minimal, same as the app --}}
    <nav class="border-b border-white/5">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                </div>
                <span class="font-bold text-lg text-white tracking-tight">Tenderly</span>
            </div>
            <div class="flex items-center gap-5">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-zinc-400 hover:text-white transition">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm px-4 py-1.5 rounded-lg bg-violet-600 hover:bg-violet-500 text-white font-medium transition">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero — one line, then show the product --}}
    <section class="pt-24 pb-16 max-w-6xl mx-auto px-6">
        <div class="max-w-3xl">
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-violet-400/80 mb-5">Tender Collaboration Platform</p>
            <h1 class="text-5xl sm:text-6xl font-extrabold tracking-tight leading-[1.05] text-white mb-6">
                Structure expert input.<br>
                <span class="bg-gradient-to-r from-violet-400 to-indigo-400 bg-clip-text text-transparent">Surface what matters.</span>
            </h1>
            <p class="text-lg text-zinc-400 leading-relaxed max-w-xl">
                Build evaluation criteria, gather responses from subject-matter experts via secure links, then run AI analysis to find consensus, conflicts, and gaps — before the first meeting starts.
            </p>
            <div class="flex items-center gap-4 mt-8">
                <a href="{{ route('register') }}" class="px-6 py-3 rounded-lg bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold transition shadow-lg shadow-violet-600/20">Get Started</a>
                <a href="#how-it-works" class="text-sm text-zinc-400 hover:text-white transition flex items-center gap-1.5">
                    See how it works
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Product Preview: The actual workspace, rendered as a live mockup --}}
    <section id="how-it-works" class="pb-24 max-w-6xl mx-auto px-6">
        <div class="rounded-xl border border-white/[0.08] bg-zinc-900/60 overflow-hidden mockup-shadow glow-ring">
            {{-- Fake app chrome --}}
            <div class="flex items-center gap-2 px-4 py-3 border-b border-white/5 bg-zinc-900/80">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-zinc-700"></div>
                    <div class="w-3 h-3 rounded-full bg-zinc-700"></div>
                    <div class="w-3 h-3 rounded-full bg-zinc-700"></div>
                </div>
                <div class="flex-1 flex justify-center">
                    <div class="px-4 py-1 rounded-md bg-zinc-800 text-xs text-zinc-500 font-mono">tenderly.app/tenders/cloud-migration-rfp</div>
                </div>
            </div>

            <div class="flex min-h-[520px]">
                {{-- Mini sidebar --}}
                <div class="w-48 shrink-0 bg-zinc-900/50 border-r border-white/5 hidden lg:block">
                    <div class="px-4 py-4 border-b border-white/5">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-md bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                            </div>
                            <span class="font-bold text-sm text-white">Tenderly</span>
                        </div>
                    </div>
                    <div class="px-2 py-3 space-y-0.5">
                        <div class="flex items-center gap-2 px-3 py-2 rounded-md text-xs text-zinc-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6z"/></svg>
                            Dashboard
                        </div>
                        <div class="flex items-center gap-2 px-3 py-2 rounded-md text-xs bg-white/10 text-white font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            Tenders
                        </div>
                    </div>
                </div>

                {{-- Main content area --}}
                <div class="flex-1 p-6 overflow-hidden">
                    {{-- Breadcrumb + status --}}
                    <div class="flex items-center gap-2 text-xs mb-5">
                        <span class="text-zinc-500">Tenders</span>
                        <svg class="w-3 h-3 text-zinc-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        <span class="font-medium text-white">Cloud Infrastructure Migration RFP</span>
                        <span class="ml-1 px-2 py-0.5 text-[10px] font-medium rounded-full border text-blue-400 bg-blue-400/10 border-blue-400/20">Active</span>
                    </div>

                    {{-- Tabs --}}
                    <div class="flex gap-1 border-b border-white/5 mb-5 -mx-1">
                        <span class="px-3 py-2 text-xs font-medium text-zinc-500 border-b-2 border-transparent">Overview</span>
                        <span class="px-3 py-2 text-xs font-medium text-violet-400 border-b-2 border-violet-400">Criteria & Questions</span>
                        <span class="px-3 py-2 text-xs font-medium text-zinc-500 border-b-2 border-transparent">Participants <span class="text-zinc-700">4</span></span>
                        <span class="px-3 py-2 text-xs font-medium text-zinc-500 border-b-2 border-transparent">AI Analysis <span class="text-zinc-700">6</span></span>
                    </div>

                    {{-- Criteria tree --}}
                    <div class="space-y-3">
                        {{-- Criterion 1: expanded --}}
                        <div class="bg-zinc-900/50 border border-white/5 rounded-lg overflow-hidden">
                            <div class="px-4 py-3 border-b border-white/5 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-zinc-500 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="text-sm font-semibold text-white">Technical Architecture</span>
                                <span class="text-[10px] text-zinc-600">Weight: 30%</span>
                                <span class="text-[10px] text-zinc-700">5 questions</span>
                                <span class="ml-auto px-2 py-0.5 text-[10px] font-medium text-violet-400 bg-violet-600/20 border border-violet-500/30 rounded-md">Generate AI Questions</span>
                            </div>
                            <div class="divide-y divide-white/5">
                                <div class="px-4 py-2.5 flex items-start gap-2">
                                    <span class="shrink-0 mt-0.5 px-1 py-0.5 text-[9px] font-bold rounded text-red-400 bg-red-400/10">C</span>
                                    <span class="text-xs text-zinc-300">What is your approach to ensuring data security and GDPR compliance throughout the system?</span>
                                    <span class="ml-auto shrink-0 text-[10px] text-zinc-700">4 responses</span>
                                </div>
                                <div class="px-4 py-2.5 flex items-start gap-2">
                                    <span class="shrink-0 mt-0.5 px-1 py-0.5 text-[9px] font-bold rounded text-amber-400 bg-amber-400/10">H</span>
                                    <span class="text-xs text-zinc-300">What specific technologies and frameworks do you propose, and why are they the best fit?</span>
                                    <span class="ml-auto shrink-0 text-[10px] text-zinc-700">4 responses</span>
                                </div>
                                <div class="px-4 py-2.5 flex items-start gap-2">
                                    <span class="shrink-0 mt-0.5 px-1 py-0.5 text-[9px] font-bold rounded text-zinc-400 bg-zinc-400/10">N</span>
                                    <span class="text-xs text-zinc-300">How will your proposed architecture handle scalability requirements as usage grows?</span>
                                    <span class="ml-auto shrink-0 text-[10px] text-zinc-700">3 responses</span>
                                </div>
                            </div>
                        </div>

                        {{-- Criterion 2: collapsed --}}
                        <div class="bg-zinc-900/50 border border-white/5 rounded-lg">
                            <div class="px-4 py-3 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="text-sm font-semibold text-white">Risk Management & Mitigation</span>
                                <span class="text-[10px] text-zinc-600">Weight: 25%</span>
                                <span class="text-[10px] text-zinc-700">4 questions</span>
                            </div>
                        </div>

                        {{-- Criterion 3: collapsed --}}
                        <div class="bg-zinc-900/50 border border-white/5 rounded-lg">
                            <div class="px-4 py-3 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="text-sm font-semibold text-white">Team & Organization</span>
                                <span class="text-[10px] text-zinc-600">Weight: 20%</span>
                                <span class="text-[10px] text-zinc-700">4 questions</span>
                            </div>
                        </div>

                        {{-- Criterion 4: collapsed --}}
                        <div class="bg-zinc-900/50 border border-white/5 rounded-lg">
                            <div class="px-4 py-3 flex items-center gap-2">
                                <svg class="w-3.5 h-3.5 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span class="text-sm font-semibold text-white">Timeline & Planning</span>
                                <span class="text-[10px] text-zinc-600">Weight: 15%</span>
                                <span class="text-[10px] text-zinc-700">4 questions</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- The two sides — how it works, shown as actual product states --}}
    <section class="py-24 border-t border-white/5 subtle-grid">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid lg:grid-cols-2 gap-16 items-start">

                {{-- Left: Analysis output --}}
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-violet-400/80 mb-3">What you get</p>
                    <h2 class="text-2xl font-bold text-white mb-8">Seven types of AI analysis</h2>

                    <div class="space-y-3">
                        <div class="bg-zinc-900/50 border border-white/5 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded text-emerald-400 bg-emerald-400/10">CONSENSUS</span>
                                <span class="text-xs text-zinc-600">87% confidence</span>
                            </div>
                            <p class="text-sm text-zinc-400 leading-relaxed">Strong alignment on microservices architecture and API-first design across all four participants.</p>
                            <div class="mt-3 flex items-center gap-3 p-2 bg-zinc-900/50 border border-white/5 rounded-md">
                                <span class="text-sm font-bold text-emerald-400">92%</span>
                                <span class="text-xs text-zinc-500">Technical Architecture — all favor modular approach</span>
                            </div>
                        </div>

                        <div class="bg-zinc-900/50 border border-white/5 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded text-red-400 bg-red-400/10">CONFLICTS</span>
                                <span class="text-xs text-zinc-600">79% confidence</span>
                            </div>
                            <p class="text-sm text-zinc-400 leading-relaxed">Cloud provider selection has three competing positions that need resolution.</p>
                            <div class="mt-3 space-y-1">
                                <div class="flex items-start gap-2 text-xs text-zinc-500"><span class="text-zinc-600 mt-0.5">&bull;</span> AWS preferred for enterprise features</div>
                                <div class="flex items-start gap-2 text-xs text-zinc-500"><span class="text-zinc-600 mt-0.5">&bull;</span> Azure preferred for existing integrations</div>
                                <div class="flex items-start gap-2 text-xs text-zinc-500"><span class="text-zinc-600 mt-0.5">&bull;</span> Multi-cloud strategy recommended</div>
                            </div>
                        </div>

                        <div class="bg-zinc-900/50 border border-white/5 rounded-lg p-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded text-amber-400 bg-amber-400/10">GAPS</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="shrink-0 mt-0.5 px-1.5 py-0.5 text-[9px] font-bold rounded text-red-400 bg-red-400/10">CRITICAL</span>
                                <span class="text-xs text-zinc-400">No participant provided detailed RPO/RTO targets or failover procedures for disaster recovery.</span>
                            </div>
                        </div>

                        <div class="flex gap-2 flex-wrap">
                            <span class="px-2.5 py-1 text-[10px] font-medium rounded text-blue-400 bg-blue-400/10 border border-blue-400/20">Themes</span>
                            <span class="px-2.5 py-1 text-[10px] font-medium rounded text-orange-400 bg-orange-400/10 border border-orange-400/20">Risks</span>
                            <span class="px-2.5 py-1 text-[10px] font-medium rounded text-violet-400 bg-violet-400/10 border border-violet-400/20">Insights</span>
                            <span class="px-2.5 py-1 text-[10px] font-medium rounded text-cyan-400 bg-cyan-400/10 border border-cyan-400/20">Session Prep</span>
                        </div>
                    </div>
                </div>

                {{-- Right: Participant view (light side) --}}
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-violet-400/80 mb-3">What participants see</p>
                    <h2 class="text-2xl font-bold text-white mb-8">Clean response forms, no account required</h2>

                    <div class="rounded-lg border border-zinc-700 bg-zinc-100 overflow-hidden shadow-xl shadow-black/30">
                        {{-- Light mode participant preview --}}
                        <div class="px-5 py-4 border-b border-zinc-200">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-5 h-5 rounded-md bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08"/></svg>
                                </div>
                                <span class="font-bold text-xs text-zinc-900">Tenderly</span>
                            </div>
                            <p class="text-sm font-bold text-zinc-900">Cloud Infrastructure Migration RFP</p>
                            <p class="text-xs text-zinc-500 mt-0.5">Welcome, <strong class="text-zinc-700">Willem van der Berg</strong> (Solution Architect)</p>
                        </div>
                        <div class="p-5 space-y-4">
                            <div>
                                <p class="text-xs font-semibold text-zinc-700 mb-2">Technical Architecture <span class="text-zinc-400 font-normal">Weight: 30%</span></p>
                                <div class="bg-white rounded-lg border border-zinc-200 overflow-hidden">
                                    <div class="px-3 py-2 bg-zinc-50 border-b border-zinc-100 flex items-start gap-1.5">
                                        <span class="shrink-0 mt-0.5 px-1 py-0.5 text-[8px] font-bold rounded text-red-600 bg-red-50">CRITICAL</span>
                                        <p class="text-[11px] text-zinc-700">What is your approach to ensuring data security and GDPR compliance?</p>
                                    </div>
                                    <div class="p-3">
                                        <div class="w-full h-16 bg-zinc-50 rounded border border-zinc-200 p-2">
                                            <p class="text-[10px] text-zinc-400">Our approach centers on privacy by design principles. We implement data classification at the ingestion layer, ensuring PII is identified and tagged before it enters the processing pipeline. For GDPR Article 17 compliance, we maintain a deletion propagation system that...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1.5 bg-violet-600 text-white text-[10px] font-semibold rounded-md">Save Progress</span>
                                <span class="px-3 py-1.5 bg-zinc-900 text-white text-[10px] font-semibold rounded-md">Submit Final</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-zinc-600 mt-3">Participants receive a unique token link. No signup, no password. Responses auto-save and can be submitted when ready.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Session Prep — the payoff --}}
    <section class="py-24 border-t border-white/5">
        <div class="max-w-4xl mx-auto px-6">
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-violet-400/80 mb-4">The outcome</p>
            <h2 class="text-3xl font-bold text-white mb-10">Walk into the evaluation meeting prepared</h2>

            <div class="bg-zinc-900/50 border border-white/5 rounded-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-white/5 flex items-center gap-3">
                    <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                    <div>
                        <h3 class="font-semibold text-white text-sm">Session Preparation Brief</h3>
                        <p class="text-[10px] text-zinc-600">Auto-generated from all participant responses</p>
                    </div>
                    <span class="ml-auto px-2 py-0.5 text-[10px] font-bold rounded text-cyan-400 bg-cyan-400/10">SESSION PREP</span>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <p class="text-xs font-semibold text-zinc-300 uppercase tracking-wider mb-3">Discussion Topics</p>
                        <div class="space-y-2">
                            <div class="flex items-start gap-3">
                                <span class="shrink-0 mt-0.5 px-1.5 py-0.5 text-[9px] font-bold rounded text-red-400 bg-red-400/10">CRITICAL</span>
                                <div>
                                    <p class="text-sm text-white">Define disaster recovery requirements</p>
                                    <p class="text-xs text-zinc-600">15 min &middot; Gap identified — no RPO/RTO targets established</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="shrink-0 mt-0.5 px-1.5 py-0.5 text-[9px] font-bold rounded text-amber-400 bg-amber-400/10">HIGH</span>
                                <div>
                                    <p class="text-sm text-white">Resolve cloud provider strategy</p>
                                    <p class="text-xs text-zinc-600">20 min &middot; Three competing positions need resolution</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-300 uppercase tracking-wider mb-3">Decision Points</p>
                        <div class="space-y-1.5">
                            <p class="text-sm text-zinc-400">Cloud provider commitment (AWS vs Azure vs Multi-cloud)</p>
                            <p class="text-sm text-zinc-400">Data residency policy (EU-only vs distributed)</p>
                            <p class="text-sm text-zinc-400">Go/No-Go criteria for Phase 1 release</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 border-t border-white/5">
        <div class="max-w-2xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-violet-500/20 bg-violet-500/5 mb-8">
                <div class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></div>
                <span class="text-xs font-medium text-violet-300">Built for evaluation teams in regulated industries</span>
            </div>
            <h2 class="text-3xl font-bold text-white mb-4">Replace spreadsheets with structured insight</h2>
            <p class="text-zinc-500 mb-8 max-w-md mx-auto">Design your evaluation, invite your experts, and let AI surface what your team needs to discuss — in hours, not weeks.</p>
            <div class="flex items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-lg bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold transition shadow-lg shadow-violet-600/20">Create Your First Tender</a>
                <a href="{{ route('login') }}" class="px-6 py-3.5 rounded-lg border border-white/10 hover:border-white/20 text-zinc-300 text-sm font-medium transition">Log in</a>
            </div>
        </div>
    </section>

    <footer class="border-t border-white/5 py-8">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-5 h-5 rounded bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08"/></svg>
                </div>
                <span class="text-xs text-zinc-600">Tenderly</span>
            </div>
            <p class="text-[10px] text-zinc-700">AI-Powered Tender Collaboration</p>
        </div>
    </footer>

</body>
</html>
