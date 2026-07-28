@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Student Marks Entry</h2>
        <p class="text-sm text-slate-400 mt-1">Input continuous assessment (formative) and end-of-term exam (summative) scores</p>
    </div>

    @if(!$activeTerm)
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl p-4 text-sm">
            No active academic term is currently set in the database.
        </div>
    @elseif($allocations->isEmpty())
        <div class="bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-xl p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 mx-auto text-amber-500 mb-3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
            </svg>
            <h4 class="font-bold text-lg text-white">No Allocations Assigned</h4>
            <p class="text-sm text-slate-400 mt-1">You are not allocated to teach any subjects/classes for {{ $activeTerm->name }}.</p>
        </div>
    @else
        <!-- Filter Bar -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form id="filterForm" method="GET" action="{{ route('teacher.marks.index') }}" class="flex items-center gap-3">
                <label for="allocation_id" class="text-sm text-slate-400 shrink-0 font-medium">Select Subject/Class:</label>
                <select id="allocation_id" name="allocation_id" onchange="document.getElementById('filterForm').submit()"
                    class="bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2 text-white text-sm focus:outline-none transition duration-300">
                    @foreach($allocations as $alloc)
                        <option value="{{ $alloc->id }}" {{ $selectedAllocation && $selectedAllocation->id == $alloc->id ? 'selected' : '' }}>
                            {{ $alloc->subject->name }} ({{ $alloc->subject->code }}) - {{ $alloc->schoolClass->name }}
                        </option>
                    @endforeach
                </select>
            </form>

            @if($selectedAllocation)
                <div class="text-xs text-slate-400">
                    <span class="font-semibold text-white">Curriculum Info:</span> Formative max is 20, Summative max is 80. Total (100) auto-calculated.
                </div>
            @endif
        </div>

        @if($selectedAllocation)
            <!-- Marks Entry Table -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-6 shadow-lg shadow-slate-950/20">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-white">{{ $selectedAllocation->subject->name }}</h3>
                        <p class="text-sm text-slate-400">{{ $selectedAllocation->schoolClass->name }} ({{ $students->count() }} enrolled learners)</p>
                    </div>
                </div>

                <form action="{{ route('teacher.marks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="allocation_id" value="{{ $selectedAllocation->id }}">

                    <div class="overflow-x-auto -mx-6 px-6">
                        <table class="w-full text-left text-sm text-slate-350 border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                                    <th class="py-3.5 px-4 w-40">Adm Number</th>
                                    <th class="py-3.5 px-4 w-24 text-center">Photo</th>
                                    <th class="py-3.5 px-4">Learner Name</th>
                                    <th class="py-3.5 px-4 w-32 text-center">Course Work (20)</th>
                                    <th class="py-3.5 px-4 w-32 text-center">Summative (80)</th>
                                    <th class="py-3.5 px-4 w-28 text-center">Total (100)</th>
                                    <th class="py-3.5 px-4 w-32 text-center">Achievement / 3</th>
                                    <th class="py-3.5 px-4 w-24 text-center">Grade</th>
                                    <th class="py-3.5 px-4">Comments / Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($students as $index => $student)
                                    <tr class="hover:bg-slate-950/10 transition">
                                        <td class="py-4 px-4 font-mono text-white text-xs">
                                            {{ $student->admission_number }}
                                            <input type="hidden" name="scores[{{ $index }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <div class="flex flex-col items-center gap-1">
                                                @if($student->photo_path)
                                                    <img src="{{ asset($student->photo_path) }}" alt="{{ $student->full_name }}" class="w-10 h-10 object-cover rounded-full border border-slate-850 shadow-md">
                                                @else
                                                    <div class="w-10 h-10 rounded-full border border-slate-800 bg-slate-950 flex items-center justify-center text-slate-500 text-xs font-bold font-mono">
                                                        {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                @if(auth()->user()->isAdmin())
                                                    <button type="button" onclick="document.getElementById('photo-input-{{ $student->id }}').click()"
                                                        class="text-[10px] text-indigo-400 hover:text-indigo-300 font-semibold underline transition duration-150 select-none">
                                                        Upload
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-white font-medium">
                                            {{ $student->full_name }}
                                        </td>
                                        <td class="py-4 px-4">
                                            <input type="number" step="0.1" min="0" max="20" name="scores[{{ $index }}][formative_score]"
                                                value="{{ old('scores.'.$index.'.formative_score', $student->mark?->formative_score) }}"
                                                data-index="{{ $index }}"
                                                class="formative-input w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-lg px-2.5 py-1.5 text-white focus:outline-none text-center">
                                        </td>
                                        <td class="py-4 px-4">
                                            <input type="number" step="0.1" min="0" max="80" name="scores[{{ $index }}][summative_score]"
                                                value="{{ old('scores.'.$index.'.summative_score', $student->mark?->summative_score) }}"
                                                data-index="{{ $index }}"
                                                class="summative-input w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-lg px-2.5 py-1.5 text-white focus:outline-none text-center">
                                        </td>
                                        <td class="py-4 px-4 text-center text-white font-bold font-mono">
                                            <span id="total-{{ $index }}">
                                                {{ $student->mark?->total_score !== null ? number_format($student->mark->total_score, 1) . '%' : '-' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center text-indigo-400 font-bold font-mono">
                                            <span id="achievement-{{ $index }}">
                                                {{ $student->mark?->level_of_achievement !== null ? number_format($student->mark->level_of_achievement, 1) : '-' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-center font-bold text-slate-300 font-mono">
                                            <span id="grade-{{ $index }}">
                                                {{ $student->mark?->grade ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <input type="text" name="scores[{{ $index }}][teacher_comment]"
                                                value="{{ old('scores.'.$index.'.teacher_comment', $student->mark?->teacher_comment) }}"
                                                placeholder="Enter student competency comment"
                                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 rounded-lg px-3 py-1.5 text-white placeholder-slate-650 focus:outline-none text-xs">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/20 transition duration-200">
                            Save Student Marks
                        </button>
                    </div>
                </form>
            </div>
        @endif
    @endif
</div>

@if(auth()->user()->isAdmin() && $selectedAllocation && !$students->isEmpty())
    <!-- Hidden Student Photo Upload Forms -->
    @foreach($students as $student)
        <form id="photo-form-{{ $student->id }}" action="{{ route('students.photo', $student->id) }}" method="POST" enctype="multipart/form-data" class="hidden">
            @csrf
            <input type="file" id="photo-input-{{ $student->id }}" name="photo" accept="image/*" onchange="document.getElementById('photo-form-{{ $student->id }}').submit()">
        </form>
    @endforeach
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formativeInputs = document.querySelectorAll('.formative-input');
    const summativeInputs = document.querySelectorAll('.summative-input');

    function calculateRow(index) {
        const formativeInput = document.querySelector(`.formative-input[data-index="${index}"]`);
        const summativeInput = document.querySelector(`.summative-input[data-index="${index}"]`);
        const totalSpan = document.getElementById(`total-${index}`);
        const achievementSpan = document.getElementById(`achievement-${index}`);
        const gradeSpan = document.getElementById(`grade-${index}`);

        const f = parseFloat(formativeInput.value) || 0;
        const s = parseFloat(summativeInput.value) || 0;
        const total = f + s;

        totalSpan.textContent = total.toFixed(1) + '%';

        const achievement = (total / 100) * 3;
        achievementSpan.textContent = achievement.toFixed(1);

        let grade = 'G';
        if (total >= 90) grade = 'A*';
        else if (total >= 80) grade = 'A';
        else if (total >= 70) grade = 'B';
        else if (total >= 60) grade = 'C';
        else if (total >= 50) grade = 'D';
        else if (total >= 40) grade = 'E';
        else if (total >= 30) grade = 'F';

        gradeSpan.textContent = grade;
    }

    formativeInputs.forEach(input => {
        const handleFormative = function() {
            let val = parseFloat(this.value);
            if (val > 20) {
                this.value = 20;
            } else if (val < 0) {
                this.value = 0;
            }
            calculateRow(this.dataset.index);
        };
        input.addEventListener('input', handleFormative);
        input.addEventListener('change', handleFormative);
        input.addEventListener('blur', handleFormative);
    });

    summativeInputs.forEach(input => {
        const handleSummative = function() {
            let val = parseFloat(this.value);
            if (val > 80) {
                this.value = 80;
            } else if (val < 0) {
                this.value = 0;
            }
            calculateRow(this.dataset.index);
        };
        input.addEventListener('input', handleSummative);
        input.addEventListener('change', handleSummative);
        input.addEventListener('blur', handleSummative);
    });
});
</script>
@endsection
