@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white font-sans">Account Security</h2>
        <p class="text-sm text-slate-400 mt-1">Change your password to keep your report system account secure.</p>
    </div>

    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
        <h3 class="text-lg font-semibold text-white mb-6">Update Password</h3>
        
        <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label for="current_password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Current Password</label>
                <input id="current_password" type="password" name="current_password" required placeholder="••••••••"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white placeholder-slate-700 focus:outline-none transition-all duration-300">
                @error('current_password')
                    <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <hr class="border-slate-850">

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label for="new_password" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">New Password</label>
                    <input id="new_password" type="password" name="new_password" required placeholder="••••••••"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white placeholder-slate-700 focus:outline-none transition-all duration-300">
                    @error('new_password')
                        <span class="text-xs text-red-400 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="new_password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Confirm New Password</label>
                    <input id="new_password_confirmation" type="password" name="new_password_confirmation" required placeholder="••••••••"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white placeholder-slate-700 focus:outline-none transition-all duration-300">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-850">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-slate-600 text-slate-300 font-semibold rounded-xl text-sm transition duration-150">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl text-sm shadow transition duration-200">
                    Save New Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
