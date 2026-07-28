@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Student Directory</h2>
        <p class="text-sm text-slate-400 mt-1">Enroll and manage school students</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Forms Column -->
        <div class="space-y-6">
            <!-- Add Student Form -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-lg font-semibold text-white mb-4">Enroll New Student</h3>
                <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="admission_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Admission Number</label>
                        <input id="admission_number" type="text" name="admission_number" value="{{ $nextAdmissionNumber }}" placeholder="e.g. ADM2026001"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                        <span class="text-[10px] text-slate-500 mt-1 block">Leave default or customize. Auto-generated sequentially.</span>
                    </div>
                    <div>
                        <label for="lin" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">LIN / NIN (Optional)</label>
                        <input id="lin" type="text" name="lin" placeholder="Learner Identification Number"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">First Name</label>
                            <input id="first_name" type="text" name="first_name" required placeholder="Brian"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                        </div>
                        <div>
                            <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Last Name</label>
                            <input id="last_name" type="text" name="last_name" required placeholder="Kansiime"
                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="gender" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Gender</label>
                            <select id="gender" name="gender" required
                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none">
                                <option value="F">Female</option>
                                <option value="M">Male</option>
                            </select>
                        </div>
                        <div>
                            <label for="school_class_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Assigned Class</label>
                            <select id="school_class_id" name="school_class_id" required
                                class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                        Enroll Student
                    </button>
                </form>
            </div>

            <!-- Bulk Import Card -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-lg font-semibold text-white mb-4">Bulk Import Learners</h3>
                <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label for="import_class_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Import into Class</label>
                        <select id="import_class_id" name="school_class_id" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- CSV Upload Option -->
                    <div>
                        <label for="csv_file" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Upload CSV File</label>
                        <input id="csv_file" type="file" name="file" accept=".csv,.txt"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2 text-slate-350 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 transition duration-150">
                        <span class="text-[10px] text-slate-500 mt-1 block">Expected CSV Format: first_name, last_name, gender (M/F), [admission_number], [lin]</span>
                    </div>

                    <div class="text-center text-xs text-slate-500 font-semibold">— OR —</div>

                    <!-- Copy Paste Option -->
                    <div>
                        <label for="paste_data" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Paste List of Names</label>
                        <textarea id="paste_data" name="paste_data" rows="5" placeholder="Format: Firstname Lastname [M/F]&#10;e.g.&#10;Agaba Molly F&#10;Kansiime Brian M"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300 text-xs"></textarea>
                        <span class="text-[10px] text-slate-500 mt-1 block">Paste names one per line. Copy directly from PDF/Excel lists. Admission numbers will auto-generate.</span>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-750 border border-slate-700 text-slate-200 font-semibold rounded-xl shadow transition duration-200">
                        Import Learners
                    </button>
                </form>
            </div>
        </div>

        <!-- Student List Registry -->
        <div class="lg:col-span-2 bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <h3 class="text-lg font-semibold text-white">Student Registry</h3>
                
                <!-- Class Selection Filter -->
                <form id="classFilterForm" method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-3">
                    <label for="school_class_id_filter" class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Class:</label>
                    <select id="school_class_id_filter" name="school_class_id" onchange="document.getElementById('classFilterForm').submit()"
                        class="bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-1.5 text-white text-xs font-semibold focus:outline-none transition duration-300">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass && $selectedClass->id == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if($students->isEmpty())
                <p class="text-slate-500 text-sm py-4">No students enrolled in this class yet.</p>
            @else
                <form id="bulkDeleteStudentsForm" action="{{ route('admin.students.bulk_destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all selected student records? This action cannot be undone.')">
                    @csrf
                    <div class="mb-4 flex justify-between items-center gap-4 bg-slate-950 p-3.5 border border-slate-800 rounded-2xl">
                        <span class="text-xs text-slate-400 font-medium select-none">
                            <span id="selectedCount" class="font-bold text-white font-mono">0</span> learner(s) selected
                        </span>
                        <button type="submit" id="bulkDeleteBtn" disabled
                            class="px-3.5 py-2 bg-red-600/10 hover:bg-red-600 disabled:opacity-40 disabled:cursor-not-allowed text-red-400 hover:text-white border border-red-500/20 rounded-xl text-xs font-semibold shadow transition duration-200">
                            Delete Selected
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-350 border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                                    <th class="py-3.5 px-4 w-12 text-center">
                                        <input type="checkbox" id="selectAllStudents" class="rounded border-slate-800 text-indigo-600 focus:ring-indigo-500/20 bg-slate-950">
                                    </th>
                                    <th class="py-3.5 px-4 w-24">Photo</th>
                                    <th class="py-3.5 px-4">Adm No.</th>
                                    <th class="py-3.5 px-4">LIN</th>
                                    <th class="py-3.5 px-4">Full Name</th>
                                    <th class="py-3.5 px-4">Gender</th>
                                    <th class="py-3.5 px-4">Class</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($students as $st)
                                    <tr class="hover:bg-slate-950/20 transition duration-150">
                                        <td class="py-3 px-4 text-center">
                                            <input type="checkbox" name="ids[]" value="{{ $st->id }}" class="student-select rounded border-slate-800 text-indigo-600 focus:ring-indigo-500/20 bg-slate-950">
                                        </td>
                                        <td class="py-3 px-4">
                                            @if($st->photo_path)
                                                <img src="{{ asset($st->photo_path) }}" alt="{{ $st->full_name }}" class="w-9 h-9 object-cover rounded-full border border-slate-800 shadow-sm">
                                            @else
                                                <div class="w-9 h-9 rounded-full border border-slate-850 bg-slate-950 flex items-center justify-center text-slate-500 text-xs font-bold font-mono">
                                                    {{ strtoupper(substr($st->first_name, 0, 1) . substr($st->last_name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 font-mono font-medium text-white">{{ $st->admission_number }}</td>
                                        <td class="py-3 px-4 font-mono text-xs text-slate-500">{{ $st->lin ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 text-white font-semibold">{{ $st->full_name }}</td>
                                        <td class="py-3 px-4">{{ $st->gender == 'M' ? 'Male' : 'Female' }}</td>
                                        <td class="py-3 px-4 text-indigo-400 font-medium">{{ $st->schoolClass->name }}</td>
                                        <td class="py-3 px-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded capitalize bg-emerald-500/10 text-emerald-400 border border-emerald-500/25">
                                                    {{ $st->status }}
                                                </span>
                                                <a href="{{ route('admin.students.edit', $st->id) }}" class="px-2.5 py-1.5 bg-slate-800 hover:bg-slate-750 text-slate-200 border border-slate-700 rounded-lg text-xs font-semibold transition duration-150">
                                                    Edit
                                                </a>
                                                <button type="button" onclick="if(confirm('Are you sure you want to delete this student record? This action cannot be undone.')) { document.getElementById('single-delete-{{ $st->id }}').submit(); }"
                                                    class="px-2.5 py-1.5 bg-red-600/10 hover:bg-red-650 text-red-400 hover:text-white border border-red-500/20 rounded-lg text-xs font-semibold transition duration-150">
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Hidden Single Delete Forms -->
                @foreach($students as $st)
                    <form id="single-delete-{{ $st->id }}" action="{{ route('admin.students.destroy', $st->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAllStudents');
    const studentSelects = document.querySelectorAll('.student-select');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');

    function updateBulkDeleteState() {
        const checkedCount = document.querySelectorAll('.student-select:checked').length;
        selectedCount.textContent = checkedCount;
        if (checkedCount > 0) {
            bulkDeleteBtn.removeAttribute('disabled');
        } else {
            bulkDeleteBtn.setAttribute('disabled', 'true');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            studentSelects.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkDeleteState();
        });
    }

    studentSelects.forEach(cb => {
        cb.addEventListener('change', function() {
            updateBulkDeleteState();
            // Update Select All state
            const allChecked = document.querySelectorAll('.student-select:checked').length === studentSelects.length;
            if (selectAll) {
                selectAll.checked = allChecked;
            }
        });
    });
});
</script>
</div>
@endsection
