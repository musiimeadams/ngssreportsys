@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white font-sans">Class Teacher Remarks & Attendance</h2>
        <p class="text-sm text-slate-400 mt-1">Record student attendance records, conduct reports, and end of term evaluation remarks</p>
    </div>

    @if(!$activeTerm)
        <div class="bg-red-500/10 border border-red-500/25 text-red-400 rounded-2xl p-5 text-sm">
            No active academic term is currently set in the database.
        </div>
    @elseif($classes->isEmpty())
        <div class="bg-amber-500/10 border border-amber-500/25 text-amber-400 rounded-2xl p-5 text-sm">
            No school classes registered in the database.
        </div>
    @else
        <!-- Filter Bar -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form id="filterForm" method="GET" action="{{ route('classteacher.comments.index') }}" class="flex items-center gap-3">
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
            <!-- Comments Table -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-6 shadow-lg shadow-slate-950/20">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-white">{{ $selectedClass->name }}</h3>
                    <p class="text-sm text-slate-400 mt-0.5">Term: {{ $activeTerm->name }} ({{ $activeTerm->academicYear->name }})</p>
                </div>

                <form action="{{ route('classteacher.comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="school_class_id" value="{{ $selectedClass->id }}">
                    <input type="hidden" name="term_id" value="{{ $activeTerm->id }}">

                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="w-full text-left text-sm text-slate-350 border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                                    <th class="py-3.5 px-4 w-44">Adm Number</th>
                                    <th class="py-3.5 px-4 w-52">Learner Name</th>
                                    <th class="py-3.5 px-4 w-28">Days Present</th>
                                    <th class="py-3.5 px-4 w-28">Total Days</th>
                                    <th class="py-3.5 px-4 w-64">Conduct Comments</th>
                                    <th class="py-3.5 px-4">Class Teacher's Comments</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($students as $index => $student)
                                    <tr class="hover:bg-slate-950/10 transition">
                                        <td class="py-4 px-4 font-mono text-white text-xs">
                                            {{ $student->admission_number }}
                                            <input type="hidden" name="reports[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        <td class="py-4 px-4 text-white font-medium">
                                            {{ $student->full_name }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <input type="number" min="0" name="reports[{{ $index }}][attendance_present]"
                                                value="{{ old('reports.'.$index.'.attendance_present', $student->reportCard?->attendance_present ?? 0) }}"
                                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-lg px-2.5 py-1.5 text-white focus:outline-none text-center">
                                        </td>
                                        <td class="py-4 px-4">
                                            <input type="number" min="0" name="reports[{{ $index }}][total_attendance]"
                                                value="{{ old('reports.'.$index.'.total_attendance', $student->reportCard?->total_attendance ?? 0) }}"
                                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-lg px-2.5 py-1.5 text-white focus:outline-none text-center">
                                        </td>
                                        <td class="py-4 px-4">
                                            <input type="text" name="reports[{{ $index }}][conduct_comment]"
                                                value="{{ old('reports.'.$index.'.conduct_comment', $student->reportCard?->conduct_comment) }}"
                                                placeholder="e.g. Respectful and cooperative"
                                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-lg px-3 py-1.5 text-white placeholder-slate-655 focus:outline-none text-xs">
                                        </td>
                                        <td class="py-4 px-4">
                                            <input type="text" name="reports[{{ $index }}][class_teacher_comment]"
                                                value="{{ old('reports.'.$index.'.class_teacher_comment', $student->reportCard?->class_teacher_comment) }}"
                                                placeholder="e.g. Excellent performance, keep it up"
                                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-lg px-3 py-1.5 text-white placeholder-slate-655 focus:outline-none text-xs">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/20 transition duration-200">
                            Save Remarks & Attendance
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endif
</div>
@endsection
