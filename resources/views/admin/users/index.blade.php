@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Teacher Registration Approvals & Role Control</h2>
        <p class="text-sm text-slate-400 mt-1">Approve teacher signups, manage active account access, and assign grading roles</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Forms Column -->
        <div class="space-y-6 h-fit">
            <!-- Add Teacher Account Form -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-lg font-semibold text-white mb-4">Register New Teacher</h3>
                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Full Name</label>
                        <input id="name" type="text" name="name" required placeholder="e.g. John Adams"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">System Role</label>
                        <select id="role" name="role" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            <option value="teacher">Subject Teacher</option>
                            <option value="class_teacher">Class Teacher</option>
                            <option value="headteacher">Head Teacher</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>

                    @if($activeTerm)
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Assign Subjects & Classes ({{ $activeTerm->name }})</label>
                            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto bg-slate-950 p-3 border border-slate-800 rounded-xl pr-2">
                                @foreach($classes as $c)
                                    @foreach($subjects as $sub)
                                        <label class="flex items-center gap-2 text-xs text-slate-400 cursor-pointer hover:text-white transition duration-150 select-none">
                                            <input type="checkbox" name="allocations[]" value="{{ $c->id }}-{{ $sub->id }}"
                                                class="rounded border-slate-800 text-indigo-600 focus:ring-indigo-500/20 bg-slate-950">
                                            <span>{{ $c->name }} - {{ $sub->name }} ({{ $sub->code }})</span>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-[10px] text-red-400 italic">No active academic term configured. Make a term active first to assign subjects.</div>
                    @endif

                    <div class="bg-indigo-500/5 border border-indigo-500/10 rounded-xl p-3.5 space-y-1.5">
                        <div class="text-xs text-indigo-400 font-bold">💡 Automatic Setup Information:</div>
                        <ul class="text-[10px] text-slate-400 list-disc pl-4 space-y-0.5">
                            <li>Email is auto-generated using last name: e.g. <span class="font-mono text-indigo-300">adams@ngssreports.com</span></li>
                            <li>Default password is set to <span class="font-mono text-indigo-300">"password"</span></li>
                            <li>Teachers can change their password after logging in</li>
                        </ul>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                        Create Teacher Account
                    </button>
                </form>
            </div>

            <!-- Bulk Import Teachers Card -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-lg font-semibold text-white mb-4">Bulk Import Teachers</h3>
                <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <!-- CSV Upload Option -->
                    <div>
                        <label for="csv_file" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Upload CSV File</label>
                        <input id="csv_file" type="file" name="file" accept=".csv,.txt"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2 text-slate-350 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 transition duration-150">
                        <span class="text-[10px] text-slate-500 mt-1 block font-mono">CSV format: name, email, [phone], [role]</span>
                    </div>

                    <div class="text-center text-xs text-slate-500 font-semibold">— OR —</div>

                    <!-- Copy Paste Option -->
                    <div>
                        <label for="paste_data" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Paste List of Teachers</label>
                        <textarea id="paste_data" name="paste_data" rows="5" placeholder="Format: Full Name, email@domain.com&#10;e.g.&#10;John Doe, john@ngaramagirls.sc.ug&#10;Jane Smith, jane@ngaramagirls.sc.ug"
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300 text-xs"></textarea>
                        <span class="text-[10px] text-slate-500 mt-1 block">Paste list (one per line). Default password is 'password'. You can assign their subjects using the inline toggle options after import.</span>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-slate-800 hover:bg-slate-750 border border-slate-700 text-slate-200 font-semibold rounded-xl shadow transition duration-200">
                        Import Teachers
                    </button>
                </form>
            </div>

            <!-- Allocate Subjects to Existing Teacher Card -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-lg font-semibold text-white mb-4">Assign Subjects to Teacher</h3>
                <form action="{{ route('admin.users.store_allocations') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="alloc_teacher_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Select Teacher</label>
                        <select id="alloc_teacher_id" name="teacher_id" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            <option value="">Choose Teacher...</option>
                            @foreach($users as $t)
                                @if($t->isTeacher())
                                    <option value="{{ $t->id }}">{{ $t->name }} ({{ $t->email }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    @if($activeTerm)
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Assign Subjects & Classes ({{ $activeTerm->name }})</label>
                            <div class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto bg-slate-950 p-3 border border-slate-800 rounded-xl pr-2">
                                @foreach($classes as $c)
                                    @foreach($subjects as $sub)
                                        <label class="flex items-center gap-2 text-xs text-slate-400 cursor-pointer hover:text-white transition duration-150 select-none">
                                            <input type="checkbox" name="allocations[]" value="{{ $c->id }}-{{ $sub->id }}"
                                                class="rounded border-slate-800 text-indigo-600 focus:ring-indigo-500/20 bg-slate-950">
                                            <span>{{ $c->name }} - {{ $sub->name }} ({{ $sub->code }})</span>
                                        </label>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-[10px] text-red-400 italic">No active academic term configured. Make a term active first to assign subjects.</div>
                    @endif

                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                        Update Allocations
                    </button>
                </form>
            </div>
        </div>

        <!-- Users Registry Table -->
        <div class="lg:col-span-2 bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <h3 class="text-lg font-semibold text-white mb-4">Teacher Accounts Registry</h3>
            @if($users->isEmpty())
                <p class="text-slate-500 text-sm py-4">No other teacher accounts registered on the system yet.</p>
            @else
                <form id="bulkDeleteUsersForm" action="{{ route('admin.users.bulk_destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all selected teacher accounts? This action cannot be undone.')">
                    @csrf
                    <div class="mb-4 flex justify-between items-center gap-4 bg-slate-950 p-3.5 border border-slate-800 rounded-2xl">
                        <span class="text-xs text-slate-400 font-medium select-none">
                            <span id="selectedUsersCount" class="font-bold text-white font-mono">0</span> teacher(s) selected
                        </span>
                        <button type="submit" id="bulkDeleteUsersBtn" disabled
                            class="px-3.5 py-2 bg-red-600/10 hover:bg-red-650 disabled:opacity-40 disabled:cursor-not-allowed text-red-400 hover:text-white border border-red-500/20 rounded-xl text-xs font-semibold shadow transition duration-200">
                            Delete Selected
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-350 border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                                    <th class="py-3.5 px-4 w-12 text-center">
                                        <input type="checkbox" id="selectAllUsers" class="rounded border-slate-800 text-indigo-600 focus:ring-indigo-500/20 bg-slate-950">
                                    </th>
                                    <th class="py-3.5 px-4">Name & Email</th>
                                    <th class="py-3.5 px-4">Current Role</th>
                                    <th class="py-3.5 px-4">Assigned Classes/Subjects</th>
                                    <th class="py-3.5 px-4 text-center">Status</th>
                                    <th class="py-3.5 px-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($users as $user)
                                    <tr class="hover:bg-slate-950/20 transition duration-150">
                                        <td class="py-4 px-4 text-center">
                                            <input type="checkbox" name="ids[]" value="{{ $user->id }}" class="user-select rounded border-slate-800 text-indigo-600 focus:ring-indigo-500/20 bg-slate-950">
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-white font-semibold">{{ $user->name }}</div>
                                            <div class="font-mono text-xs text-slate-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <form action="{{ route('admin.users.update_role', $user->id) }}" method="POST" class="space-y-1">
                                                @csrf
                                                <select name="role" required onchange="this.form.submit()"
                                                    class="bg-slate-950 border border-slate-850 rounded-lg px-2 py-1 text-slate-300 text-xs focus:outline-none focus:border-indigo-500 transition duration-350 w-32">
                                                    <option value="teacher" {{ $user->role == 'teacher' ? 'selected' : '' }}>Subject Teacher</option>
                                                    <option value="class_teacher" {{ $user->role == 'class_teacher' ? 'selected' : '' }}>Class Teacher</option>
                                                    <option value="headteacher" {{ $user->role == 'headteacher' ? 'selected' : '' }}>Head Teacher</option>
                                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex flex-wrap gap-1 mb-2 max-w-[200px]">
                                                @forelse($user->subjectAllocations as $alloc)
                                                    <span class="text-[9px] font-mono font-bold px-1.5 py-0.5 rounded bg-indigo-500/10 text-indigo-400 border border-indigo-500/25">
                                                        {{ $alloc->schoolClass->name }} ({{ $alloc->subject->code }})
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-slate-600 italic">No allocations assigned</span>
                                                @endforelse
                                            </div>

                                            @if($activeTerm)
                                                <details class="text-left mt-2">
                                                    <summary class="text-xs text-indigo-400 hover:text-indigo-300 cursor-pointer font-semibold select-none outline-none">
                                                        Configure Assigned Subjects
                                                    </summary>
                                                    <form action="{{ route('admin.users.allocations', $user->id) }}" method="POST" class="mt-2 p-3 bg-slate-950/80 border border-slate-850 rounded-xl space-y-3 max-w-[220px]">
                                                        @csrf
                                                        <div class="grid grid-cols-1 gap-2 max-h-40 overflow-y-auto pr-2">
                                                            @foreach($classes as $c)
                                                                @foreach($subjects as $sub)
                                                                    @php
                                                                        $isAllocated = $user->subjectAllocations->contains(function($alloc) use ($c, $sub) {
                                                                            return $alloc->school_class_id == $c->id && $alloc->subject_id == $sub->id;
                                                                        });
                                                                    @endphp
                                                                    <label class="flex items-center gap-2 text-[10px] text-slate-400 hover:text-white cursor-pointer select-none">
                                                                        <input type="checkbox" name="allocations[]" value="{{ $c->id }}-{{ $sub->id }}" {{ $isAllocated ? 'checked' : '' }}
                                                                            class="rounded border-slate-850 text-indigo-600 focus:ring-indigo-500/20 bg-slate-950">
                                                                        <span>{{ $c->name }} - {{ $sub->code }}</span>
                                                                    </label>
                                                                @endforeach
                                                            @endforeach
                                                        </div>
                                                        <button type="submit" class="w-full py-1 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-bold rounded-lg transition duration-200">
                                                            Save Changes
                                                        </button>
                                                    </form>
                                                </details>
                                            @endif

                                            <details class="text-left mt-2">
                                                <summary class="text-xs text-indigo-400 hover:text-indigo-300 cursor-pointer font-semibold select-none outline-none">
                                                    Reset Password
                                                </summary>
                                                <form action="{{ route('admin.users.reset_password', $user->id) }}" method="POST" class="mt-2 p-3 bg-slate-950/80 border border-slate-850 rounded-xl space-y-2 max-w-[220px]">
                                                    @csrf
                                                    <div>
                                                        <label class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-1">Set Password</label>
                                                        <input type="text" name="password" value="password" required
                                                            class="w-full bg-slate-955 border border-slate-800 focus:border-indigo-500 rounded-lg px-2.5 py-1 text-white text-xs placeholder-slate-650">
                                                    </div>
                                                    <button type="submit" class="w-full py-1 bg-indigo-600 hover:bg-indigo-500 text-white text-[10px] font-bold rounded-lg transition duration-200 cursor-pointer">
                                                        Reset Password
                                                    </button>
                                                </form>
                                            </details>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if($user->is_active)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold px-2 py-0.5 rounded-full bg-red-500/10 text-red-400 border border-red-500/20">
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @if($user->is_active)
                                                        <button type="submit" 
                                                            class="px-2 py-1 bg-red-600/10 hover:bg-red-650 border border-red-500/20 hover:border-red-500 text-red-400 hover:text-white text-xs font-bold rounded-lg transition duration-200 cursor-pointer">
                                                            Deactivate
                                                        </button>
                                                    @else
                                                        <button type="submit" 
                                                            class="px-2 py-1 bg-emerald-600/10 hover:bg-emerald-600 border border-emerald-500/20 hover:border-emerald-500 text-emerald-400 hover:text-white text-xs font-bold rounded-lg transition duration-200 cursor-pointer">
                                                            Approve
                                                        </button>
                                                    @endif
                                                </form>

                                                @if(auth()->id() !== $user->id)
                                                    <button type="button" onclick="if(confirm('Are you sure you want to delete this user account? This action cannot be undone.')) { document.getElementById('single-delete-user-{{ $user->id }}').submit(); }"
                                                        class="px-2 py-1 bg-red-600/10 hover:bg-red-650 border border-red-500/20 hover:border-red-500 text-red-400 hover:text-white text-xs font-bold rounded-lg transition duration-200 cursor-pointer">
                                                        Delete
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Hidden Single Delete Forms -->
                @foreach($users as $user)
                    @if(auth()->id() !== $user->id)
                        <form id="single-delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAllUsers');
    const userSelects = document.querySelectorAll('.user-select');
    const bulkDeleteBtn = document.getElementById('bulkDeleteUsersBtn');
    const selectedCount = document.getElementById('selectedUsersCount');

    function updateBulkDeleteState() {
        const checkedCount = document.querySelectorAll('.user-select:checked').length;
        selectedCount.textContent = checkedCount;
        if (checkedCount > 0) {
            bulkDeleteBtn.removeAttribute('disabled');
        } else {
            bulkDeleteBtn.setAttribute('disabled', 'true');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            userSelects.forEach(cb => {
                cb.checked = selectAll.checked;
            });
            updateBulkDeleteState();
        });
    }

    userSelects.forEach(cb => {
        cb.addEventListener('change', function() {
            updateBulkDeleteState();
            // Update Select All state
            const allChecked = document.querySelectorAll('.user-select:checked').length === userSelects.length;
            if (selectAll) {
                selectAll.checked = allChecked;
            }
        });
    });
});
</script>
        </div>
    </div>
</div>
@endsection
