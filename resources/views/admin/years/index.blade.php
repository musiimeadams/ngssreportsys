@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Academic Years & Terms</h2>
        <p class="text-sm text-slate-400 mt-1">Create academic years, add terms, and set which term is currently active system-wide</p>
    </div>

    {{-- Active Term Banner --}}
    @if($activeTerm)
        <div class="flex items-center gap-3 bg-emerald-500/10 border border-emerald-500/25 rounded-2xl px-5 py-4">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
            <div>
                <span class="text-emerald-400 font-bold text-sm">Currently Active Term:</span>
                <span class="text-white font-semibold ml-2">{{ $activeTerm->name }}</span>
                <span class="text-slate-400 text-sm ml-1">— {{ $activeTerm->academicYear->name }}</span>
            </div>
        </div>
    @else
        <div class="flex items-center gap-3 bg-red-500/10 border border-red-500/25 rounded-2xl px-5 py-4">
            <span class="text-red-400 font-bold text-sm">⚠ No active term set. Please activate a term below for the system to function.</span>
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-8">

        {{-- Create Academic Year Form --}}
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20 h-fit space-y-6">

            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Add Academic Year</h3>
                <form action="{{ route('admin.years.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="year_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Year Name</label>
                        <input id="year_name" type="text" name="name" required placeholder="e.g. 2026, 2026/2027"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-600 focus:outline-none transition-all duration-300">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                        Create Academic Year
                    </button>
                </form>
            </div>

            <hr class="border-slate-800">

            <div>
                <h3 class="text-lg font-semibold text-white mb-4">Add Term to Year</h3>
                <form action="{{ route('admin.terms.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="academic_year_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Academic Year</label>
                        <select id="academic_year_id" name="academic_year_id" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            <option value="">Select Year...</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="term_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Term Name</label>
                        <select id="term_name" name="name" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            <option value="Term 1">Term 1</option>
                            <option value="Term 2">Term 2</option>
                            <option value="Term 3">Term 3</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-slate-700 hover:bg-slate-600 text-white font-semibold rounded-xl shadow transition duration-200">
                        Add Term
                    </button>
                </form>
            </div>
        </div>

        {{-- Academic Years & Terms Registry --}}
        <div class="lg:col-span-2 space-y-6">
            @if($years->isEmpty())
                <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 text-slate-500 text-sm">
                    No academic years created yet. Use the form on the left to get started.
                </div>
            @else
                @foreach($years as $year)
                    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-5 shadow-lg shadow-slate-950/20">
                        {{-- Year Header --}}
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <span class="text-lg font-bold text-white">{{ $year->name }}</span>
                                @if($year->is_active)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Active Year
                                    </span>
                                @endif
                            </div>
                            <form action="{{ route('admin.years.destroy', $year->id) }}" method="POST"
                                onsubmit="return confirm('Delete this academic year and ALL its terms? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="px-3 py-1.5 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/20 rounded-lg text-xs font-semibold transition duration-150">
                                    Delete Year
                                </button>
                            </form>
                        </div>

                        {{-- Terms List --}}
                        @if($year->terms->isEmpty())
                            <p class="text-slate-600 text-sm">No terms added yet for this year.</p>
                        @else
                            <div class="divide-y divide-slate-800/60">
                                @foreach($year->terms as $term)
                                    <div class="flex items-center justify-between py-3">
                                        <div class="flex items-center gap-3">
                                            <span class="text-slate-300 font-medium text-sm">{{ $term->name }}</span>
                                            @if($term->is_active)
                                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                                                    ACTIVE
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if(!$term->is_active)
                                                <form action="{{ route('admin.terms.activate', $term->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-emerald-600/10 hover:bg-emerald-600 text-emerald-400 hover:text-white border border-emerald-500/20 rounded-lg text-xs font-semibold transition duration-150">
                                                        Set as Active
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.terms.destroy', $term->id) }}" method="POST"
                                                    onsubmit="return confirm('Delete this term?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-red-600/10 hover:bg-red-600 text-red-400 hover:text-white border border-red-500/20 rounded-lg text-xs font-semibold transition duration-150">
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-slate-600 italic">Cannot delete active term</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>
@endsection
