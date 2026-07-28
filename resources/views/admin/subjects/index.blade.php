@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div>
        <h2 class="text-3xl font-bold tracking-tight text-white">Subjects Configuration</h2>
        <p class="text-sm text-slate-400 mt-1">Configure subjects offered in the curriculum (Core or Elective)</p>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Add Subject Form -->
        <div class="bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <h3 class="text-lg font-semibold text-white mb-4">Add Subject</h3>
            <form action="{{ route('admin.subjects.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="sub_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Subject Name</label>
                    <input id="sub_name" type="text" name="name" required placeholder="e.g. History & Political Education"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                </div>
                <div>
                    <label for="sub_code" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Subject Code</label>
                    <input id="sub_code" type="text" name="code" required placeholder="e.g. HIS"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                </div>
                <div>
                    <label for="sub_category" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Category</label>
                    <select id="sub_category" name="category" required
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                        <option value="core">Core Subject</option>
                        <option value="elective">Elective Subject</option>
                    </select>
                </div>
                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow transition duration-200">
                    Create Subject
                </button>
            </form>
        </div>

        <!-- Subject List Table -->
        <div class="lg:col-span-2 bg-slate-900/60 border border-slate-800/80 rounded-2xl p-6 shadow-lg shadow-slate-950/20">
            <h3 class="text-lg font-semibold text-white mb-4">Subject Registry</h3>
            @if($subjects->isEmpty())
                <p class="text-slate-500 text-sm py-4">No subjects registered yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-350 border-collapse">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 font-semibold">
                                <th class="py-3.5 px-4">Code</th>
                                <th class="py-3.5 px-4">Subject Name</th>
                                <th class="py-3.5 px-4 text-right">Category</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @foreach($subjects as $sub)
                                <tr class="hover:bg-slate-950/20 transition duration-150">
                                    <td class="py-3 px-4 font-mono text-indigo-400 font-semibold">{{ $sub->code }}</td>
                                    <td class="py-3 px-4 text-white font-medium">{{ $sub->name }}</td>
                                    <td class="py-3 px-4 text-right">
                                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded capitalize {{ $sub->category == 'core' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/25' : 'bg-amber-500/10 text-amber-400 border border-amber-500/25' }}">
                                            {{ $sub->category }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
