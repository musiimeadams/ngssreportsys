<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NGSS ReportSys') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
        }
    </style>

    <!-- Scripts and styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex flex-col md:flex-row relative overflow-hidden bg-slate-950">
    <!-- Background accents -->
    <div class="absolute top-0 -left-4 w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-3xl -z-10 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-purple-500/5 rounded-full blur-3xl -z-10 animate-pulse" style="animation-delay: 3s;"></div>

    <!-- Sidebar navigation -->
    <aside class="w-full md:w-64 bg-slate-900/60 backdrop-blur-xl border-r border-slate-800/80 flex flex-col shrink-0">
        <!-- Brand Header -->
        <div class="p-6 border-b border-slate-800/80 flex items-center gap-3">
            <div class="w-10 h-10 bg-white/5 border border-slate-800 rounded-xl flex items-center justify-center overflow-hidden p-1">
                <img src="{{ asset('images/logo.png') }}" alt="School Badge" class="w-full h-full object-contain">
            </div>
            <div>
                <h1 class="text-lg font-bold tracking-tight text-white leading-none">NGSS Reports</h1>
                <span class="text-xs text-slate-500">Report Generator</span>
            </div>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                Dashboard
            </a>

            <!-- Teacher / Mark Entry Section -->
            @if(auth()->user()->isTeacher())
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Teacher Area</div>
                <a href="{{ route('teacher.marks.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('teacher.marks.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                    Enter Marks
                </a>
            @endif

            <!-- Class Teacher Section -->
            @if(auth()->user()->isClassTeacher())
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Class Teacher Area</div>
                <a href="{{ route('classteacher.comments.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('classteacher.comments.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379L12 21l3.12-3.142c1.153-.086 2.294-.213 3.423-.379 1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v5.518Z" />
                    </svg>
                    Remarks & Attendance
                </a>
            @endif

            <!-- Output Reports Section -->
            @if(auth()->user()->role !== 'teacher')
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Reports Area</div>
                <a href="{{ route('reports.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('reports.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    Generate Reports
                </a>
            @endif

            <!-- Admin Setup Links -->
            @if(auth()->user()->isAdmin())
                <div class="pt-4 pb-2 px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Admin Settings</div>
                <a href="{{ route('admin.users.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                    Teacher Approvals
                </a>
                <a href="{{ route('admin.settings.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457-3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.827m11.488-8.27 1.15-.827M8.14 5.106l.827 1.15m8.27 11.488.827 1.15M12 3v1.5m0 15V21m-3.077-2.457.513-1.41m5.13-14.095.513-1.41" />
                    </svg>
                    School Settings
                </a>
                <a href="{{ route('admin.classes.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.classes.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3a1.5 1.5 0 0 1 1.5-1.5h3a1.5 1.5 0 0 1 1.5 1.5v3" />
                    </svg>
                    Classes
                </a>
                <a href="{{ route('admin.subjects.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.subjects.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    Subjects
                </a>
                <a href="{{ route('admin.students.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.students.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.9c2.79 0 5.437-.472 7.893-1.33A60.43 60.43 0 0 0 19.4 13.25m-17.14 0a48.11 48.11 0 0 1 3.476-8.59 4.89 4.89 0 0 1 4.023-2.74c1.043-.086 2.11-.127 3.18-.127 1.07 0 2.137.041 3.18.127a4.89 4.89 0 0 1 4.023 2.74c1.31 2.42 2.48 4.965 3.477 8.59m-17.14 0a48.108 48.108 0 0 0 3.478 8.59m10.186-20.08c-.468-.008-.94-.013-1.414-.013-.474 0-.946.005-1.414.013m0 0a1.5 1.5 0 1 0-2.828.403m8.47 20.08c-.468.008-.94.013-1.414.013-.475 0-.947-.005-1.414-.013m0 0a1.5 1.5 0 1 0-2.828.403M9.083 20.505a1.5 1.5 0 1 1-2.828-.403m10.187-20.08a1.5 1.5 0 1 0 2.828-.403M12 3v18" />
                    </svg>
                    Students
                </a>
                <a href="{{ route('admin.allocations.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.allocations.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                    Allocations
                </a>
                <a href="{{ route('admin.optional_subjects.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.optional_subjects.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.67 2.67 0 0 0 21 17.25l-5.83-5.83m0 0a2.67 2.67 0 0 1-3.75-3.75L17.25 3A2.67 2.67 0 0 0 13.5 6.75l-5.83 5.83m0 0a2.67 2.67 0 0 1-3.75-3.75L9.75 3A2.67 2.67 0 0 0 6 6.75L3 9.75a2.67 2.67 0 0 0 0 3.75Z" />
                    </svg>
                    Optional Subjects
                </a>
                <a href="{{ route('admin.years.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition duration-200 {{ request()->routeIs('admin.years.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/10' : 'text-slate-400 hover:bg-slate-800/50 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    Years &amp; Terms
                </a>
            @endif
        </nav>

        <!-- User profile section -->
        <div class="p-4 border-t border-slate-800/80 bg-slate-950/40">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-indigo-600 flex items-center justify-center font-bold text-white uppercase text-sm">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500 capitalize truncate">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
                    </div>
                <div class="flex items-center gap-1 shrink-0">
                    <a href="{{ route('profile.password') }}" class="text-slate-500 hover:text-indigo-400 p-1.5 rounded-lg hover:bg-slate-800/50 transition duration-200" title="Change Password">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-500 hover:text-red-400 p-1.5 rounded-lg hover:bg-slate-800/50 transition duration-200" title="Log Out">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Developer Credits -->
        <div class="py-2.5 px-4 text-center border-t border-slate-800/20 bg-slate-950/60">
            <span class="text-[9px] font-bold text-slate-500 tracking-widest uppercase block">Developed by MUSIIME ADAMZ</span>
        </div>
    </aside>

    <!-- Main Workspace -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Top bar -->
        <header class="border-b border-slate-800/80 bg-slate-900/40 backdrop-blur-xl px-6 py-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shrink-0">
            <div class="flex items-center gap-4">
                <img src="/images/logo.png" alt="School Logo" class="w-16 h-16 object-contain">
                <div>
                    <h2 class="text-xl md:text-2xl font-black text-white tracking-wide uppercase leading-none">NGARAMA GIRL'S SECONDARY SCHOOL</h2>
                    <p class="text-xs text-red-500 font-semibold italic mt-1.5">Develop a girl, Develop a nation.</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-xs font-semibold px-3 py-1 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-full">
                    Term: {{ \App\Models\Term::where('is_active', true)->first()?->name ?? 'None' }} ({{ \App\Models\AcademicYear::where('is_active', true)->first()?->name ?? 'None' }})
                </span>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 p-6 md:p-8">
            <!-- Flash messages -->
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-xl p-4 mb-6 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl p-4 mb-6 text-sm flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
