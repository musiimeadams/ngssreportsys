<?php

namespace App\Http\Controllers\ClassTeacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\ReportCard;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassTeacherController extends Controller
{
    public function index(Request $request)
    {
        $activeTerm = Term::where('is_active', true)->first();

        if (!$activeTerm) {
            return view('classteacher.comments.index', [
                'classes' => collect(),
                'students' => collect(),
                'selectedClass' => null,
                'activeTerm' => null,
                'error' => 'No active term is set. Please ask the administrator.'
            ]);
        }

        // Get all classes
        $classes = SchoolClass::all();

        $selectedClassId = $request->input('school_class_id');
        $selectedClass = null;

        if ($selectedClassId) {
            $selectedClass = $classes->firstWhere('id', $selectedClassId);
        } elseif ($classes->isNotEmpty()) {
            $selectedClass = $classes->first();
        }

        $students = collect();
        if ($selectedClass) {
            $students = Student::where('school_class_id', $selectedClass->id)
                ->where('status', 'active')
                ->get();

            // Load existing report cards
            foreach ($students as $student) {
                $student->reportCard = ReportCard::where('student_id', $student->id)
                    ->where('term_id', $activeTerm->id)
                    ->first();
            }
        }

        return view('classteacher.comments.index', compact('classes', 'selectedClass', 'students', 'activeTerm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'term_id' => 'required|exists:terms,id',
            'reports' => 'required|array',
            'reports.*.student_id' => 'required|exists:students,id',
            'reports.*.attendance_present' => 'nullable|integer|min:0',
            'reports.*.total_attendance' => 'nullable|integer|min:0',
            'reports.*.conduct_comment' => 'nullable|string|max:1000',
            'reports.*.class_teacher_comment' => 'nullable|string|max:1000',
        ]);

        foreach ($request->reports as $reportData) {
            ReportCard::updateOrCreate(
                [
                    'student_id' => $reportData['student_id'],
                    'term_id' => $request->term_id,
                ],
                [
                    'attendance_present' => $reportData['attendance_present'] ?? 0,
                    'total_attendance' => $reportData['total_attendance'] ?? 0,
                    'conduct_comment' => $reportData['conduct_comment'] ?? null,
                    'class_teacher_comment' => $reportData['class_teacher_comment'] ?? null,
                    'status' => 'draft',
                ]
            );
        }

        return redirect()->route('classteacher.comments.index', ['school_class_id' => $request->school_class_id])
            ->with('success', 'Class teacher remarks and attendance saved successfully.');
    }
}
