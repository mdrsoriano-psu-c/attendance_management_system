<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Attendance System') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans bg-[#070316] text-white">
    <main class="min-h-screen flex items-center justify-center px-6 bg-[radial-gradient(circle_at_top_left,_rgba(147,51,234,.28),_transparent_35%),linear-gradient(135deg,#070316_0%,#11031f_55%,#200323_100%)]">
        <section class="w-full max-w-2xl rounded-[28px] border border-violet-500/30 bg-[#120822]/80 shadow-2xl shadow-purple-950/70 px-8 py-12 sm:px-14 sm:py-16 text-center backdrop-blur-xl">
            <div class="mx-auto mb-10 flex h-16 w-16 items-center justify-center rounded-2xl border border-violet-400/40 bg-violet-500/10 text-3xl shadow-lg shadow-purple-900/50">
                🔮
            </div>

            <h1 class="text-5xl sm:text-6xl font-black leading-tight tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-white via-fuchsia-200 to-pink-300">
                Attendance<br>Management<br>System
            </h1>

            <p class="mt-6 text-purple-200/70 font-semibold">
                Track classes, manage students, record attendance, and generate reports in one workspace.
            </p>

            @auth
                <a href="{{ route('dashboard') }}" class="mt-10 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-4 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-purple-900/40 hover:scale-[1.02] transition">
                    Enter
                </a>
            @else
                <a href="{{ route('login') }}" class="mt-10 inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-8 py-4 text-sm font-black uppercase tracking-widest text-white shadow-lg shadow-purple-900/40 hover:scale-[1.02] transition">
                    Enter
                </a>
            @endauth
        </section>
    </main>
</body>
</html>
