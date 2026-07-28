@extends('layouts.auth')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 shadow-2xl shadow-slate-950/50">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-500/10 border border-indigo-500/30 rounded-2xl mb-4 text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Welcome Back</h2>
        <p class="text-slate-400 mt-2 text-sm">Sign in to your secondary school report system account</p>
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

    <!-- Developer Credits -->
    <div class="mt-4 text-center text-[10px] text-slate-500 font-bold uppercase tracking-widest">
        Developed by MUSIIME ADAMZ
    </div>
</div>
@endsection
