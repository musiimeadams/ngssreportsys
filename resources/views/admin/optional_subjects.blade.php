@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Optional Subjects Registrations</h2>
            <p class="text-sm text-slate-400 mt-1">Register Senior 3 (S3) and Senior 4 (S4) students for elective/optional subjects</p>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
        <form method="GET" id="filterForm" action="{{ route('admin.optional_subjects.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <label for="class_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Class (S3 / S4):</label>
                <select id="class_id" name="class_id" required onchange="document.getElementById('student_id').value=''; document.getElementById('subject_id').value=''; this.form.submit()"
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white text-sm focus:outline-none transition duration-300">
                    <option value="" disabled selected>-- Select S3/S4 Class --</option>
                    @foreach($classes as $cls)
                        <option value="{{ $cls->id }}" {{ $selectedClassId == $cls->id ? 'selected' : '' }}>
                            {{ $cls->name }} ({{ $cls->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="student_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Student (Manage by Student):</label>
                <select id="student_id" name="student_id" {{ empty($selectedClassId) ? 'disabled' : '' }}
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white text-sm focus:outline-none transition duration-300">
                    <option value="">-- All Students (Manage by Subject) --</option>
                    @if(!empty($selectedClassId))
                        @foreach($classStudents as $st)
                            <option value="{{ $st->id }}" {{ $selectedStudentId == $st->id ? 'selected' : '' }}>
                                {{ $st->full_name }} ({{ $st->admission_number }})
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label for="subject_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-2">Optional Subject:</label>
                <select id="subject_id" name="subject_id" {{ empty($selectedClassId) ? 'disabled' : '' }}
                    class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-3 text-white text-sm focus:outline-none transition duration-300">
                    <option value="" disabled {{ empty($selectedStudentId) && empty($selectedSubjectId) ? 'selected' : '' }}>-- Select Optional Subject --</option>
                    <option value="all" {{ $selectedSubjectId == 'all' ? 'selected' : '' }}>-- All Optional Subjects (Class Grid) --</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ $selectedSubjectId == $subj->id ? 'selected' : '' }}>
                            {{ $subj->name }} ({{ $subj->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-3 px-4 bg-indigo-650 hover:bg-indigo-550 text-white font-semibold rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-lg shadow-indigo-650/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                    </svg>
                    Load Grid
                </button>
            </div>
        </form>
    </div>

    @if($selectedClass)
        @if($selectedStudent)
            <!-- Registration Grid for Single Student -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-6 shadow-lg shadow-slate-950/20">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-slate-800 pb-5">
                    <div>
                        <h3 class="text-lg font-bold text-white">Manage Student Electives</h3>
                        <p class="text-sm text-slate-400">
                            Learner: <span class="text-indigo-400 font-semibold">{{ $selectedStudent->full_name }}</span> | Class: <span class="text-indigo-400 font-semibold">{{ $selectedClass->name }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="selectAllBtn" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-lg text-slate-300 transition">
                            Select All
                        </button>
                        <button type="button" id="deselectAllBtn" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-lg text-slate-300 transition">
                            Deselect All
                        </button>
                    </div>
                </div>

                <form action="{{ route('admin.optional_subjects.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                    <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($subjects as $subj)
                            @php
                                $isRegistered = in_array($subj->id, $studentRegisteredSubjectIds);
                            @endphp
                            <label class="flex items-center gap-3 p-4 bg-slate-950/30 hover:bg-slate-950/50 border border-slate-800/60 hover:border-slate-750 rounded-2xl cursor-pointer transition duration-200">
                                <input type="checkbox" name="subject_ids[]" value="{{ $subj->id }}" {{ $isRegistered ? 'checked' : '' }}
                                    class="student-checkbox rounded border-slate-800 text-indigo-650 focus:ring-indigo-650 focus:ring-offset-slate-950 bg-slate-950 w-4 h-4">
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-white leading-tight">{{ $subj->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono mt-0.5">{{ $subj->code }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-end pt-4 border-t border-slate-800/80">
                        <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30 transition duration-200">
                            Save Registrations
                        </button>
                    </div>
                </form>
            </div>
        @elseif($selectedSubject)
            <!-- Registration Grid for Subjects -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-6 shadow-lg shadow-slate-950/20">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-slate-800 pb-5">
                    <div>
                        <h3 class="text-lg font-bold text-white">Student Registration Grid</h3>
                        <p class="text-sm text-slate-400">
                            Subject: <span class="text-indigo-400 font-semibold">{{ $selectedSubject->name }}</span> | Class: <span class="text-indigo-400 font-semibold">{{ $selectedClass->name }}</span>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="selectAllBtn" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-lg text-slate-300 transition">
                            Select All
                        </button>
                        <button type="button" id="deselectAllBtn" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-semibold rounded-lg text-slate-300 transition">
                            Deselect All
                        </button>
                    </div>
                </div>

                @if($students->isEmpty())
                    <p class="text-slate-500 text-sm py-4 text-center">No active learners registered in this class.</p>
                @else
                    @if($selectedSubjectId === 'all')
                        <!-- Class Matrix Grid Form -->
                        <form action="{{ route('admin.optional_subjects.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                            <input type="hidden" name="subject_id" value="all">

                            <div class="overflow-x-auto border border-slate-800/60 rounded-2xl bg-slate-950/40">
                                <table class="w-full border-collapse text-left">
                                    <thead>
                                        <tr class="border-b border-slate-800 bg-slate-950/85">
                                            <th class="p-4 text-xs font-semibold uppercase tracking-wider text-slate-400">Learner Name</th>
                                            @foreach($subjects as $subj)
                                                <th class="p-4 text-xs font-semibold uppercase tracking-wider text-slate-400 text-center min-w-[120px]">
                                                    {{ $subj->name }}<br><span class="text-[10px] text-slate-500 font-mono font-normal">({{ $subj->code }})</span>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/60">
                                        @foreach($students as $st)
                                            <tr class="hover:bg-slate-950/50 transition duration-150">
                                                <td class="p-4 whitespace-nowrap">
                                                    <div class="text-sm font-semibold text-white leading-tight">{{ $st->full_name }}</div>
                                                    <div class="text-xs text-slate-500 font-mono mt-0.5">{{ $st->admission_number }}</div>
                                                </td>
                                                @foreach($subjects as $subj)
                                                    @php
                                                        $isRegistered = in_array($subj->id, $registrationsGrid[$st->id] ?? []);
                                                    @endphp
                                                    <td class="p-4 text-center">
                                                        <input type="checkbox" name="registrations[{{ $st->id }}][]" value="{{ $subj->id }}" {{ $isRegistered ? 'checked' : '' }}
                                                            class="student-checkbox rounded border-slate-800 text-indigo-650 focus:ring-indigo-650 focus:ring-offset-slate-950 bg-slate-950 w-4 h-4">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                                <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30 transition duration-200">
                                    Save Registrations
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Single Subject Grid Form -->
                        <form action="{{ route('admin.optional_subjects.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
                            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($students as $st)
                                    @php
                                        $isRegistered = $st->isRegisteredForSubject($selectedSubject->id);
                                    @endphp
                                    <label class="flex items-center gap-3 p-4 bg-slate-950/30 hover:bg-slate-950/50 border border-slate-800/60 hover:border-slate-750 rounded-2xl cursor-pointer transition duration-200">
                                        <input type="checkbox" name="student_ids[]" value="{{ $st->id }}" {{ $isRegistered ? 'checked' : '' }}
                                            class="student-checkbox rounded border-slate-800 text-indigo-650 focus:ring-indigo-650 focus:ring-offset-slate-950 bg-slate-950 w-4 h-4">
                                        <div class="flex-1">
                                            <div class="text-sm font-semibold text-white leading-tight">{{ $st->full_name }}</div>
                                            <div class="text-xs text-slate-500 font-mono mt-0.5">{{ $st->admission_number }}</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div class="flex justify-end pt-4 border-t border-slate-800/80">
                                <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-500/30 transition duration-200">
                                    Save Registrations
                                </button>
                            </div>
                        </form>
                    @endif
                @endif
            </div>
        @endif
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSelect = document.getElementById('student_id');
    const subjectSelect = document.getElementById('subject_id');

    if (studentSelect && subjectSelect) {
        studentSelect.addEventListener('change', function() {
            if (this.value) {
                subjectSelect.value = '';
            }
        });
        subjectSelect.addEventListener('change', function() {
            if (this.value) {
                studentSelect.value = '';
            }
        });
    }

    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');
    const checkboxes = document.querySelectorAll('.student-checkbox');

    if (selectAllBtn && checkboxes.length) {
        selectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllBtn && checkboxes.length) {
        deselectAllBtn.addEventListener('click', function() {
            checkboxes.forEach(cb => cb.checked = false);
        });
    }
});
</script>
@endsection
