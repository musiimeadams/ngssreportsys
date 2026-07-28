@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Generate Reports</h2>
            <p class="text-sm text-slate-400 mt-1">Review student performance summaries and generate printable report cards</p>
        </div>

        @if($selectedClass && !$students->isEmpty())
            <a href="{{ route('reports.print_stream', $selectedClass->id) }}" target="_blank"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow transition duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.615 0-1.11-.483-1.12-1.099L6.34 18m11.32 0H6.34M16.5 12h.008v.008H16.5V12Zm-.9 6h.008v.008H15.6V18Z" />
                </svg>
                Print All (Class Bundle)
            </a>
        @endif
    </div>

    @if(!$activeTerm)
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl p-4 text-sm">
            No active academic term is currently set in the database.
        </div>
    @elseif($classes->isEmpty())
        <div class="bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl p-4 text-sm">
            No classes registered in the database.
        </div>
    @else
        <!-- Filter Bar -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form id="filterForm" method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-3">
                <label for="school_class_id" class="text-sm text-slate-400 shrink-0 font-medium">Select Class:</label>
                <select id="school_class_id" name="school_class_id" onchange="document.getElementById('filterForm').submit()"
                    class="bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2 text-white text-sm focus:outline-none transition duration-300">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClass && $selectedClass->id == $c->id ? 'selected' : '' }}>
                            {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        @if($selectedClass)
            <!-- Performance Summary Table -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-6 shadow-lg shadow-slate-950/20">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white">Student Performance Summary</h3>
                    <p class="text-sm text-slate-400">{{ $selectedClass->name }} ({{ $activeTerm->name }})</p>
                </div>

                @if($students->isEmpty())
                    <p class="text-slate-500 text-sm py-4">No active students enrolled in this class.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-350 border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                                    <th class="py-3.5 px-4 w-12 text-center">Rank</th>
                                    <th class="py-3.5 px-4 w-44">Adm Number</th>
                                    <th class="py-3.5 px-4">Learner Name</th>
                                    <th class="py-3.5 px-4 text-center">Subjects Graded</th>
                                    <th class="py-3.5 px-4 text-center">Average Score</th>
                                    <th class="py-3.5 px-4 text-center">Days Present</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($students as $st)
                                    <tr class="hover:bg-slate-950/20 transition duration-150">
                                        <td class="py-3 px-4 text-center">
                                            @if($st->computed_position)
                                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg text-xs font-bold font-mono {{ $st->computed_position == 1 ? 'bg-amber-500/10 text-amber-400 border border-amber-500/30' : ($st->computed_position == 2 ? 'bg-slate-300/15 text-slate-300' : 'bg-slate-800 text-slate-400') }}">
                                                    {{ $st->computed_position }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 font-mono text-white text-xs">{{ $st->admission_number }}</td>
                                        <td class="py-3 px-4 text-white font-semibold">{{ $st->full_name }}</td>
                                        <td class="py-3 px-4 text-center text-slate-400 font-medium">{{ $st->marks_count }}</td>
                                        <td class="py-3 px-4 text-center text-indigo-400 font-bold font-mono">
                                            {{ $st->marks_count > 0 ? number_format($st->average_score, 1) . '%' : '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-center text-slate-400 font-medium">
                                            {{ $st->reportCard?->attendance_present ?? '-' }} / {{ $st->reportCard?->total_attendance ?? '-' }}
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="{{ route('reports.show', $st->id) }}" target="_blank"
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold rounded-lg border border-slate-700 transition">
                                                    View Report
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>
@endsection
