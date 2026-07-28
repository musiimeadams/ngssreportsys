@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Subject Allocations</h2>
        <p class="text-sm text-slate-400 mt-1">Assign subject classes to teachers for active continuous assessment entry</p>
    </div>

    @if(!$activeTerm)
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl p-4 text-sm">
            Please make a term active in the database before allocating subjects.
        </div>
    @else
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Add Allocation Form -->
            <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-lg font-semibold text-white mb-4">Allocate Subject Class</h3>
                <form action="{{ route('admin.allocations.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="term_id" value="{{ $activeTerm->id }}">

                    <div>
                        <label for="teacher_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Select Teacher</label>
                        <select id="teacher_id" name="teacher_id" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->name }} ({{ str_replace('_', ' ', $t->role) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subject_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Select Subject</label>
                        <select id="subject_id" name="subject_id" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->code }} - {{ $sub->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="school_class_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Select Class</label>
                        <select id="school_class_id" name="school_class_id" required
                            class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                        Assign Allocation
                    </button>
                </form>
            </div>

            <!-- Allocations Registry Table -->
            <div class="lg:col-span-2 bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
                <h3 class="text-lg font-semibold text-white mb-4">Allocation Registry (Active Term: {{ $activeTerm->name }})</h3>
                @if($allocations->isEmpty())
                    <p class="text-slate-500 text-sm py-4">No subjects allocated yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-350 border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                                    <th class="py-3.5 px-4">Teacher Name</th>
                                    <th class="py-3.5 px-4">Subject</th>
                                    <th class="py-3.5 px-4">Class</th>
                                    <th class="py-3.5 px-4 text-right">Academic Term</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60">
                                @foreach($allocations as $alloc)
                                    <tr class="hover:bg-slate-950/20 transition duration-150">
                                        <td class="py-3 px-4 text-white font-semibold">{{ $alloc->teacher->name }}</td>
                                        <td class="py-3 px-4">
                                            <span class="font-mono text-xs px-2 py-0.5 bg-indigo-500/10 text-indigo-400 border border-indigo-500/25 rounded">
                                                {{ $alloc->subject->code }}
                                            </span>
                                            <span class="ml-1 text-slate-300 font-medium">{{ $alloc->subject->name }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-slate-300 font-medium">{{ $alloc->schoolClass->name }}</td>
                                        <td class="py-3 px-4 text-right text-slate-500 font-medium">
                                            {{ $alloc->term->name }} ({{ $alloc->term->academicYear->name }})
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
