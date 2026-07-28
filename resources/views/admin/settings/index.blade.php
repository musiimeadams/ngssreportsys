@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">School Configuration Settings</h2>
        <p class="text-sm text-slate-400 mt-1">Configure official school details, next term details, and parameters shown on report cards</p>
    </div>

    <div class="max-w-2xl bg-slate-900/60 border border-slate-800/80 rounded-3xl p-8 shadow-lg shadow-slate-950/20">
        <h3 class="text-lg font-semibold text-white mb-6">School Profile & Report Parameters</h3>
        
        <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
            @csrf

            <!-- School Name -->
            <div>
                <label for="school_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">School Name</label>
                <input id="school_name" type="text" name="school_name" value="{{ old('school_name', $settings->school_name) }}" required
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
            </div>

            <!-- Motto -->
            <div>
                <label for="school_motto" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">School Motto</label>
                <input id="school_motto" type="text" name="school_motto" value="{{ old('school_motto', $settings->school_motto) }}" required
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Address / P.O. Box</label>
                <input id="address" type="text" name="address" value="{{ old('address', $settings->address) }}" required
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Telephone Contacts</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $settings->phone) }}" required
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Official Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $settings->email) }}" required
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <!-- Next Term Begins -->
                <div>
                    <label for="next_term_begins" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Next Term Begins On</label>
                    <input id="next_term_begins" type="date" name="next_term_begins" 
                        value="{{ old('next_term_begins', $settings->next_term_begins ? $settings->next_term_begins->format('Y-m-d') : '') }}"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
                </div>

                <!-- Next Term Ends -->
                <div>
                    <label for="next_term_ends" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Next Term Ends On</label>
                    <input id="next_term_ends" type="date" name="next_term_ends" 
                        value="{{ old('next_term_ends', $settings->next_term_ends ? $settings->next_term_ends->format('Y-m-d') : '') }}"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
                </div>
            </div>

            <!-- Next Term Fees -->
            <div>
                <label for="next_term_fees" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Next Term Fees Structure</label>
                <input id="next_term_fees" type="text" name="next_term_fees" value="{{ old('next_term_fees', $settings->next_term_fees) }}" required
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition duration-300">
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                    Save Config Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
