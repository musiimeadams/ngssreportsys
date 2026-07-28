<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\User;
use App\Models\Term;
use App\Models\SubjectAllocation;
use App\Models\Mark;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect teachers immediately to their mark entry sheet
        if ($user->role === 'teacher' || $user->role === 'class_teacher') {
            return redirect()->route('teacher.marks.index');
        }

        $activeTerm = Term::where('is_active', true)->first();

        // System-wide statistics for display
        $stats = [
            'students_count' => Student::count(),
            'teachers_count' => User::whereIn('role', ['teacher', 'class_teacher', 'headteacher'])->count(),
            'classes_count' => SchoolClass::count(),
            'subjects_count' => Subject::count(),
        ];

        // Specific statistics for logged-in teacher
        $teacherAllocations = [];
        if ($user->isTeacher() && $activeTerm) {
            $teacherAllocations = SubjectAllocation::with(['subject', 'schoolClass'])
                ->where('teacher_id', $user->id)
                ->where('term_id', $activeTerm->id)
                ->get();
        }

        return view('dashboard', compact('stats', 'teacherAllocations', 'activeTerm'));
    }
}
