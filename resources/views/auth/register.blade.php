@extends('layouts.auth')

@section('content')
<div class="bg-slate-900/60 backdrop-blur-xl border border-slate-800/80 rounded-3xl p-8 shadow-2xl shadow-slate-950/50">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-14 h-14 bg-indigo-500/10 border border-indigo-500/30 rounded-2xl mb-4 text-indigo-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold tracking-tight text-white uppercase">Ngarama Secondary School</h2>
        <p class="text-indigo-400 font-semibold text-sm tracking-wider uppercase mt-1">Report System</p>
        <p class="text-slate-400 mt-3 text-sm">Register as a teacher</p>
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

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="e.g. Mr. Okello Joseph"
                class="w-full bg-slate-950/50 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. joseph@school.com"
                class="w-full bg-slate-950/50 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Phone Number</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}" required placeholder="+2567..."
                    class="w-full bg-slate-950/50 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
            </div>

            <!-- Role selection -->
            <div>
                <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Role Type</label>
                <select id="role" name="role" required
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Subject Teacher</option>
                    <option value="class_teacher" {{ old('role') == 'class_teacher' ? 'selected' : '' }}>Class Teacher</option>
                    <option value="headteacher" {{ old('role') == 'headteacher' ? 'selected' : '' }}>Head Teacher</option>
                </select>
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters"
                class="w-full bg-slate-950/50 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Repeat password"
                class="w-full bg-slate-950/50 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" 
                class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold rounded-xl transition duration-300 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30 flex items-center justify-center gap-2">
                Register Account
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </button>
        </div>
    </form>

    <!-- Footer link -->
    <div class="mt-6 pt-5 border-t border-slate-800/80 text-center text-sm text-slate-400">
        Already have an account? 
        <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition duration-200">Sign in instead</a>
    </div>

    <!-- Developer Credits -->
    <div class="mt-4 text-center text-[10px] text-slate-500 font-bold uppercase tracking-widest">
        Developed by MUSIIME ADAMZ
    </div>
</div>
@endsection
