@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Top Welcome Banner -->
    <div class="bg-gradient-to-r from-indigo-900/60 to-purple-900/60 backdrop-blur-xl border border-indigo-500/20 rounded-3xl p-8 relative overflow-hidden shadow-xl shadow-indigo-950/20">
        <div class="relative z-10 max-w-2xl">
            <h2 class="text-3xl font-bold tracking-tight text-white mb-2">Hello, {{ auth()->user()->name }}!</h2>
            <p class="text-indigo-200/80 text-sm md:text-base leading-relaxed">
                Welcome to the Secondary School Report System. You are signed in as a <span class="text-white font-semibold capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</span>. 
                Use the side menu to navigate through student enrollment, subject allocations, and grading tasks.
            </p>
        </div>
        <!-- Decorative graphics -->
        <div class="absolute right-8 bottom-0 top-0 hidden lg:flex items-center text-indigo-400/10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-48 h-48">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.9c2.79 0 5.437-.472 7.893-1.33A60.43 60.43 0 0 0 19.4 13.25m-17.14 0a48.11 48.11 0 0 1 3.476-8.59 4.89 4.89 0 0 1 4.023-2.74c1.043-.086 2.11-.127 3.18-.127 1.07 0 2.137.041 3.18.127a4.89 4.89 0 0 1 4.023 2.74c1.31 2.42 2.48 4.965 3.477 8.59m-17.14 0a48.108 48.108 0 0 0 3.478 8.59m10.186-20.08c-.468-.008-.94-.013-1.414-.013-.474 0-.946.005-1.414.013m0 0a1.5 1.5 0 1 0-2.828.403m8.47 20.08c-.468.008-.94.013-1.414.013-.475 0-.947-.005-1.414-.013m0 0a1.5 1.5 0 1 0-2.828.403M9.083 20.505a1.5 1.5 0 1 1-2.828-.403m10.187-20.08a1.5 1.5 0 1 0 2.828-.403M12 3v18" />
            </svg>
        </div>
    </div>

    <!-- Quick Stats Grid (Accessible to all but highlighted for admins) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Stat Card 1 -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block mb-1">Total Students</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold text-white">{{ $stats['students_count'] }}</span>
                <span class="text-xs text-indigo-400 font-medium">Enrolled</span>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block mb-1">Teaching Staff</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold text-white">{{ $stats['teachers_count'] }}</span>
                <span class="text-xs text-indigo-400 font-medium">Registered</span>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block mb-1">Active Classes</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold text-white">{{ $stats['classes_count'] }}</span>
                <span class="text-xs text-indigo-400 font-medium">Streams</span>
            </div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 block mb-1">Total Subjects</span>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-bold text-white">{{ $stats['subjects_count'] }}</span>
                <span class="text-xs text-indigo-400 font-medium">Subjects</span>
            </div>
        </div>
    </div>

    <!-- Teacher Assignments & Actions Section -->
    @if(auth()->user()->isTeacher())
    <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-6 md:p-8 shadow-lg shadow-slate-950/20">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-white">Your Subject Allocations</h3>
                <p class="text-sm text-slate-400">Classes and subjects assigned to you for grading in the current term</p>
            </div>
        </div>

        @if($teacherAllocations->isEmpty())
            <div class="text-center py-12 border border-dashed border-slate-800 rounded-2xl bg-slate-950/30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mx-auto text-slate-600 mb-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <p class="text-slate-400 font-medium">No subject allocations found for you this term.</p>
                <p class="text-xs text-slate-500 mt-1">Please ask the Administrator to allocate subjects and classes to your account.</p>
            </div>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($teacherAllocations as $allocation)
                    <div class="bg-slate-950/60 border border-slate-800/60 rounded-2xl p-5 hover:border-indigo-500/40 transition duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-semibold px-2.5 py-1 bg-indigo-500/10 border border-indigo-500/25 text-indigo-400 rounded-md">
                                    {{ $allocation->subject->code }}
                                </span>
                                <span class="text-xs font-medium text-slate-500 capitalize">
                                    {{ $allocation->subject->category }}
                                </span>
                            </div>
                            <h4 class="text-lg font-bold text-white group-hover:text-indigo-400 transition">{{ $allocation->subject->name }}</h4>
                            <p class="text-sm text-slate-400 mt-1">Class: <span class="text-slate-350 font-semibold">{{ $allocation->schoolClass->name }}</span></p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-900 flex justify-end">
                            <a href="{{ route('teacher.marks.index', ['allocation_id' => $allocation->id]) }}" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow transition duration-200">
                                Enter Marks
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif
</div>
@endsection
