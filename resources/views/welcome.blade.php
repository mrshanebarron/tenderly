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
        .gradient-text {
            background: linear-gradient(135deg, #a78bfa 0%, #818cf8 50%, #6366f1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .card-glow:hover {
            box-shadow: 0 0 40px rgba(139, 92, 246, 0.08), 0 0 80px rgba(99, 102, 241, 0.04);
        }
        .hero-glow {
            background: radial-gradient(ellipse 600px 300px at 50% 0%, rgba(139, 92, 246, 0.12) 0%, transparent 70%);
        }
    </style>
</head>
<body class="antialiased bg-zinc-950 text-zinc-100">

    {{-- Nav --}}
    <nav class="fixed top-0 inset-x-0 z-50 border-b border-white/5 bg-zinc-950/80 backdrop-blur-xl">
        <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                </div>
                <span class="font-bold text-lg text-white tracking-tight">Tenderly</span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm text-zinc-400 hover:text-white transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-zinc-400 hover:text-white transition">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm px-4 py-2 rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-medium hover:from-violet-500 hover:to-indigo-500 transition-all">Get Started</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="relative pt-32 pb-24 overflow-hidden">
        <div class="hero-glow absolute inset-0 pointer-events-none"></div>
        <div class="relative max-w-4xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-violet-500/20 bg-violet-500/5 text-violet-300 text-xs font-medium mb-8 tracking-wide uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-violet-400 animate-pulse"></span>
                AI-Powered Collaboration
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.05] mb-6">
                Tender evaluation,<br>
                <span class="gradient-text">done thoughtfully</span>
            </h1>

            <p class="text-lg sm:text-xl text-zinc-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Structure expert input. Surface consensus and conflict. Prepare decisions with
                AI-driven analysis — all before the first meeting starts.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-semibold text-base hover:from-violet-500 hover:to-indigo-500 transition-all shadow-lg shadow-violet-500/20">
                    Start a Tender Workspace
                </a>
                <a href="{{ route('login') }}" class="px-8 py-3.5 rounded-xl border border-white/10 text-zinc-300 font-medium text-base hover:border-white/20 hover:text-white transition-all">
                    View Demo
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-24 border-t border-white/5">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="text-xs font-medium uppercase tracking-widest text-violet-400 mb-3">How It Works</p>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Three phases. One clear outcome.</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Step 1 --}}
                <div class="relative group">
                    <div class="absolute -top-3 -left-3 w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-violet-500/20">1</div>
                    <div class="pt-10 p-6 rounded-2xl border border-white/5 bg-zinc-900/30 group-hover:border-white/10 transition-all h-full">
                        <h3 class="text-lg font-semibold text-white mb-2">Design</h3>
                        <p class="text-sm text-zinc-400 leading-relaxed">
                            Build your evaluation framework. Define weighted criteria, upload tender documents, and let AI generate targeted questions per criterion.
                        </p>
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative group">
                    <div class="absolute -top-3 -left-3 w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-violet-500/20">2</div>
                    <div class="pt-10 p-6 rounded-2xl border border-white/5 bg-zinc-900/30 group-hover:border-white/10 transition-all h-full">
                        <h3 class="text-lg font-semibold text-white mb-2">Gather</h3>
                        <p class="text-sm text-zinc-400 leading-relaxed">
                            Invite subject-matter experts via secure token links. They respond at their own pace. Track completion and follow up with AI-generated probing questions.
                        </p>
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative group">
                    <div class="absolute -top-3 -left-3 w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-sm font-bold text-white shadow-lg shadow-violet-500/20">3</div>
                    <div class="pt-10 p-6 rounded-2xl border border-white/5 bg-zinc-900/30 group-hover:border-white/10 transition-all h-full">
                        <h3 class="text-lg font-semibold text-white mb-2">Analyze</h3>
                        <p class="text-sm text-zinc-400 leading-relaxed">
                            Generate seven types of AI analysis: consensus points, conflicts, gaps, themes, risks, insights, and a complete session preparation brief.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-24 border-t border-white/5">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <p class="text-xs font-medium uppercase tracking-widest text-violet-400 mb-3">Capabilities</p>
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight">Built for procurement teams<br>who think deeply</h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="p-6 rounded-2xl border border-white/5 bg-zinc-900/30 card-glow transition-all">
                    <div class="w-10 h-10 rounded-lg bg-violet-500/10 border border-violet-500/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6z"/></svg>
                    </div>
                    <h3 class="font-semibold text-white mb-1.5">Weighted Criteria Hierarchy</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Parent and child criteria with customizable weights. Structure your evaluation exactly how your organization thinks.</p>
                </div>

                <div class="p-6 rounded-2xl border border-white/5 bg-zinc-900/30 card-glow transition-all">
                    <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                    </div>
                    <h3 class="font-semibold text-white mb-1.5">AI Question Generation</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Context-aware questions generated per criterion. Technical, risk, quality, and timeline templates adapt to your tender scope.</p>
                </div>

                <div class="p-6 rounded-2xl border border-white/5 bg-zinc-900/30 card-glow transition-all">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                    </div>
                    <h3 class="font-semibold text-white mb-1.5">Secure Token Access</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Participants access their questionnaire via unique token links. No accounts required. Lock access when evaluation closes.</p>
                </div>

                <div class="p-6 rounded-2xl border border-white/5 bg-zinc-900/30 card-glow transition-all">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                    </div>
                    <h3 class="font-semibold text-white mb-1.5">Real-Time Tracking</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Monitor response completeness, participant status, and overall progress. Resend invitations and follow up as needed.</p>
                </div>

                <div class="p-6 rounded-2xl border border-white/5 bg-zinc-900/30 card-glow transition-all">
                    <div class="w-10 h-10 rounded-lg bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                    </div>
                    <h3 class="font-semibold text-white mb-1.5">Seven Analysis Types</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Consensus, conflicts, gaps, themes, risks, insights, and session prep. Each analysis cross-references all participant responses.</p>
                </div>

                <div class="p-6 rounded-2xl border border-white/5 bg-zinc-900/30 card-glow transition-all">
                    <div class="w-10 h-10 rounded-lg bg-rose-500/10 border border-rose-500/20 flex items-center justify-center mb-4">
                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
                    </div>
                    <h3 class="font-semibold text-white mb-1.5">Session Preparation Brief</h3>
                    <p class="text-sm text-zinc-500 leading-relaxed">Walk into evaluation meetings prepared. Discussion topics, clarification questions, and decision points — generated from the data.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-20 border-t border-white/5">
        <div class="max-w-4xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-3xl sm:text-4xl font-bold gradient-text">7</p>
                    <p class="text-sm text-zinc-500 mt-1">Analysis Types</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold gradient-text">100%</p>
                    <p class="text-sm text-zinc-500 mt-1">Test Coverage</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold gradient-text">0</p>
                    <p class="text-sm text-zinc-500 mt-1">Accounts Required</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-bold gradient-text">&infin;</p>
                    <p class="text-sm text-zinc-500 mt-1">Criteria Depth</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-24 border-t border-white/5">
        <div class="max-w-3xl mx-auto px-6 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold tracking-tight mb-4">
                Better evaluations start with<br>
                <span class="gradient-text">better structure</span>
            </h2>
            <p class="text-zinc-400 mb-8 max-w-xl mx-auto">
                Replace scattered emails and conflicting spreadsheets with a single collaborative workspace that surfaces what matters.
            </p>
            <a href="{{ route('register') }}" class="inline-flex px-8 py-3.5 rounded-xl bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-semibold hover:from-violet-500 hover:to-indigo-500 transition-all shadow-lg shadow-violet-500/20">
                Create Your First Tender
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-white/5 py-8">
        <div class="max-w-6xl mx-auto px-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                </div>
                <span class="text-sm font-medium text-zinc-400">Tenderly</span>
            </div>
            <p class="text-xs text-zinc-600">AI-Powered Tender Collaboration Platform</p>
        </div>
    </footer>

</body>
</html>
