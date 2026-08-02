@extends('layouts.auth')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 shadow-2xl shadow-slate-950/50">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white/5 border border-slate-800 rounded-2xl mb-4 overflow-hidden p-1.5">
            <img src="{{ asset('images/logo.png') }}" alt="School Badge" class="w-full h-full object-contain">
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-white uppercase">Ngarama Girl's Secondary School</h2>
        <p class="text-indigo-400 font-semibold text-sm tracking-wider uppercase mt-1">Report System</p>
        <p class="text-slate-400 mt-3 text-sm">Sign in to your account</p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl p-4 mb-6 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full bg-slate-950/50 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none transition-all duration-300">
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Password</label>
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="w-full bg-slate-950/50 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white placeholder-slate-600 focus:outline-none transition-all duration-300">
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember" type="checkbox" name="remember" 
                class="rounded border-slate-800 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-slate-950 bg-slate-950">
            <label for="remember" class="ml-2 text-sm text-slate-400 cursor-pointer">Remember my device</label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold rounded-xl transition duration-300 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                Sign In
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Footer link -->
    <div class="mt-8 pt-6 border-t border-slate-800/80 text-center text-sm text-slate-400">
        Don't have an account? 
        <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition duration-200">Sign up here</a>
    </div>

    @if(config('app.env') !== 'production')
        <!-- Database Mode Switcher (Local Desktop Only) -->
        <div class="mt-6 p-4 bg-slate-950/40 border border-slate-800/60 rounded-2xl text-center space-y-2">
            <div class="flex items-center justify-center gap-2 text-xs font-semibold uppercase tracking-wider text-slate-400">
                <span class="inline-block w-2 h-2 rounded-full {{ (isset($_COOKIE['db_mode']) && $_COOKIE['db_mode'] === 'online') ? 'bg-emerald-500 animate-pulse' : 'bg-amber-500' }}"></span>
                Database Mode: 
                <span class="{{ (isset($_COOKIE['db_mode']) && $_COOKIE['db_mode'] === 'online') ? 'text-emerald-400 font-bold' : 'text-amber-400 font-bold' }}">
                    {{ (isset($_COOKIE['db_mode']) && $_COOKIE['db_mode'] === 'online') ? 'Cloud (Online)' : 'Local (Offline)' }}
                </span>
            </div>
            
            <button type="button" onclick="toggleDatabaseMode()" 
                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-lg text-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-indigo-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
                Switch to {{ (isset($_COOKIE['db_mode']) && $_COOKIE['db_mode'] === 'online') ? 'Local (Offline)' : 'Cloud (Online)' }}
            </button>
        </div>

        <script>
        function toggleDatabaseMode() {
            const currentMode = document.cookie.split('; ').find(row => row.startsWith('db_mode='))?.split('=')[1] || 'offline';
            const newMode = currentMode === 'online' ? 'offline' : 'online';
            
            // Set cookie for 30 days
            const d = new Date();
            d.setTime(d.getTime() + (30*24*60*60*1000));
            document.cookie = "db_mode=" + newMode + ";expires=" + d.toUTCString() + ";path=/";
            
            window.location.reload();
        }
        </script>
    @endif

    <!-- Developer Credits -->
    <div class="mt-4 text-center text-[10px] text-slate-500 font-bold uppercase tracking-widest">
        Developed by MUSIIME ADAMZ
    </div>
</div>
@endsection
