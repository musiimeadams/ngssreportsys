@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto space-y-8">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.students.index', ['school_class_id' => $student->school_class_id]) }}" class="p-2.5 bg-slate-900 border border-slate-800 rounded-xl hover:border-slate-700 transition text-slate-400 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-white">Edit Learner Profile</h2>
            <p class="text-sm text-slate-400 mt-1">Modify details for {{ $student->full_name }}</p>
        </div>
    </div>

    <div class="bg-slate-900/60 border border-slate-800/80 rounded-3xl p-8 shadow-xl shadow-slate-950/20">
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Photo Upload Area -->
            <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-slate-800">
                <div class="relative group">
                    @if($student->photo_path)
                        <img id="avatar-preview" src="{{ asset($student->photo_path) }}" alt="{{ $student->full_name }}" class="w-24 h-24 object-cover rounded-2xl border border-slate-750 shadow-md">
                    @else
                        <div id="avatar-placeholder" class="w-24 h-24 rounded-2xl border border-slate-800 bg-slate-950 flex items-center justify-center text-slate-500 text-2xl font-bold font-mono">
                            {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="space-y-2 text-center sm:text-left w-full">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Student Photo (Only Admin)</label>
                    <input type="file" name="photo" accept="image/*" onchange="previewImage(this)"
                        class="w-full text-xs text-slate-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-300 hover:file:bg-slate-700 transition cursor-pointer">
                    <p class="text-[10px] text-slate-500 font-medium">JPEG, PNG, JPG, GIF max 2MB. Leave blank to keep current photo.</p>
                </div>
            </div>

            <!-- Basic Details -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="admission_number" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Admission Number</label>
                    <input id="admission_number" type="text" name="admission_number" required value="{{ old('admission_number', $student->admission_number) }}"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                </div>
                <div>
                    <label for="lin" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">LIN / NIN</label>
                    <input id="lin" type="text" name="lin" value="{{ old('lin', $student->lin) }}" placeholder="N/A"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white placeholder-slate-650 focus:outline-none transition-all duration-300">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="first_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">First Name</label>
                    <input id="first_name" type="text" name="first_name" required value="{{ old('first_name', $student->first_name) }}"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                </div>
                <div>
                    <label for="last_name" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Last Name</label>
                    <input id="last_name" type="text" name="last_name" required value="{{ old('last_name', $student->last_name) }}"
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none transition-all duration-300">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                    <label for="gender" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Gender</label>
                    <select id="gender" name="gender" required
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none">
                        <option value="F" {{ $student->gender == 'F' ? 'selected' : '' }}>Female</option>
                        <option value="M" {{ $student->gender == 'M' ? 'selected' : '' }}>Male</option>
                    </select>
                </div>
                <div>
                    <label for="school_class_id" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Assigned Class</label>
                    <select id="school_class_id" name="school_class_id" required
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none">
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $student->school_class_id == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status" class="block text-xs font-semibold uppercase tracking-wider text-slate-400 mb-1.5">Registry Status</label>
                    <select id="status" name="status" required
                        class="w-full bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 rounded-xl px-4 py-2.5 text-white focus:outline-none">
                        <option value="active" {{ $student->status == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ $student->status == 'inactive' ? 'selected' : '' }}>Inactive / Suspended</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                <a href="{{ route('admin.students.index', ['school_class_id' => $student->school_class_id]) }}" 
                    class="px-5 py-2.5 border border-slate-700 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl transition duration-200">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/20 transition duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('avatar-preview');
            if (preview) {
                preview.src = e.target.result;
            } else {
                var placeholder = document.getElementById('avatar-placeholder');
                if (placeholder) {
                    var img = document.createElement('img');
                    img.id = 'avatar-preview';
                    img.src = e.target.result;
                    img.className = 'w-24 h-24 object-cover rounded-2xl border border-slate-750 shadow-md';
                    placeholder.parentNode.replaceChild(img, placeholder);
                }
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
