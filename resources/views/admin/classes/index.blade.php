@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">School Classes</h2>
        <p class="text-sm text-slate-400 mt-1">Manage school academic classes</p>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Add Class Form -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <h3 class="text-lg font-semibold text-white mb-4">Add Class</h3>
            <form action="{{ route('admin.classes.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="class_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Class Name</label>
                    <input id="class_name" type="text" name="name" required placeholder="e.g. Senior 1"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                </div>
                <div>
                    <label for="class_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Code</label>
                    <input id="class_code" type="text" name="code" placeholder="e.g. S1"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                    Create Class
                </button>
            </form>
        </div>

        <!-- Class List Table / Card -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <h3 class="text-lg font-semibold text-white mb-4">Class List</h3>
            @if($classes->isEmpty())
                <p class="text-slate-500 text-sm py-4">No classes created yet.</p>
            @else
                <div class="space-y-4">
                    @foreach($classes as $class)
                        <div class="p-4 bg-slate-950/50 border border-slate-800 rounded-xl">
                            <div class="flex items-center justify-between">
                                <h4 class="font-bold text-white text-base">{{ $class->name }} ({{ $class->code ?? 'N/A' }})</h4>
                                <span class="text-xs px-2.5 py-0.5 bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 rounded-md font-medium">
                                    Active Class
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
