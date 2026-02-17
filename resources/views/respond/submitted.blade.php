<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Submitted — Tenderly</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="antialiased bg-zinc-100 min-h-screen flex items-center justify-center" style="font-family: 'Inter', sans-serif;">
    <div class="max-w-md text-center px-4">
        <div class="w-16 h-16 rounded-full bg-emerald-50 border-2 border-emerald-200 flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-zinc-900 mb-2">Responses Submitted</h1>
        <p class="text-zinc-500 mb-6">Thank you, {{ $participant->name }}. Your expert input for <strong class="text-zinc-700">{{ $participant->tender->name }}</strong> has been recorded.</p>
        <p class="text-sm text-zinc-400">Your responses are now locked and will be analyzed by the AI engine.</p>
        <p class="text-xs text-zinc-400 mt-8">Powered by Tenderly</p>
    </div>
</body>
</html>
