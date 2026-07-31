<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SubjectAllocation;
use App\Models\Student;
use App\Models\Mark;
use App\Models\Term;
use App\Services\ReportProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherMarkController extends Controller
{
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $activeTerm = Term::where('is_active', true)->first();

        if (!$activeTerm) {
            return view('teacher.marks.index', [
                'allocations' => collect(),
                'students' => collect(),
                'selectedAllocation' => null,
                'activeTerm' => null,
                'error' => 'No active term is set. Please ask the administrator.'
            ]);
        }

        // Get all allocations for this teacher in the active term
        $allocations = SubjectAllocation::with(['subject', 'schoolClass'])
            ->where('teacher_id', $teacher->id)
            ->where('term_id', $activeTerm->id)
            ->get();

        $selectedAllocationId = $request->input('allocation_id');
        $selectedAllocation = null;

        if ($selectedAllocationId) {
            $selectedAllocation = $allocations->firstWhere('id', $selectedAllocationId);
        } elseif ($allocations->isNotEmpty()) {
            $selectedAllocation = $allocations->first();
        }

        $students = collect();
        if ($selectedAllocation) {
            $isClassS3S4 = $selectedAllocation->schoolClass->isSeniorThreeOrFour();
            $isSubjectOptional = $selectedAllocation->subject->isOptional();

            $studentsQuery = Student::where('school_class_id', $selectedAllocation->school_class_id)
                ->where('status', 'active');

            if ($isClassS3S4 && $isSubjectOptional) {
                $studentsQuery->whereHas('optionalSubjects', function ($q) use ($selectedAllocation) {
                    $q->where('subject_id', $selectedAllocation->subject_id);
                });
            }

            $students = $studentsQuery->get();

            // Eager load existing marks in one query (N+1 query fix)
            $marks = Mark::whereIn('student_id', $students->pluck('id'))
                ->where('subject_id', $selectedAllocation->subject_id)
                ->where('term_id', $activeTerm->id)
                ->get()
                ->keyBy('student_id');

            foreach ($students as $student) {
                $student->mark = $marks->get($student->id);
            }
        }

        return view('teacher.marks.index', compact('allocations', 'selectedAllocation', 'students', 'activeTerm'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'allocation_id' => 'required|exists:subject_allocations,id',
            'scores' => 'nullable|array',
            'scores.*.student_id' => 'required|exists:students,id',
            'scores.*.formative_score' => 'nullable|numeric|min:0|max:20',
            'scores.*.summative_score' => 'nullable|numeric|min:0|max:80',
            'scores.*.teacher_comment' => 'nullable|string|max:500',
        ]);

        $allocation = SubjectAllocation::findOrFail($request->allocation_id);
        $teacher = Auth::user();

        // Verify that this allocation belongs to the logged-in teacher
        if ($allocation->teacher_id !== $teacher->id && !$teacher->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($request->has('scores') && is_array($request->scores)) {
            foreach ($request->scores as $scoreData) {
                $processed = ReportProcessingService::processScore(
                    $scoreData['formative_score'] !== null && $scoreData['formative_score'] !== '' ? (float) $scoreData['formative_score'] : null,
                    $scoreData['summative_score'] !== null && $scoreData['summative_score'] !== '' ? (float) $scoreData['summative_score'] : null
                );

                Mark::updateOrCreate(
                    [
                        'student_id' => $scoreData['student_id'],
                        'subject_id' => $allocation->subject_id,
                        'term_id' => $allocation->term_id,
                    ],
                    [
                        'teacher_id' => $teacher->id,
                        'formative_score' => $scoreData['formative_score'] !== null && $scoreData['formative_score'] !== '' ? $scoreData['formative_score'] : null,
                        'summative_score' => $scoreData['summative_score'] !== null && $scoreData['summative_score'] !== '' ? $scoreData['summative_score'] : null,
                        'total_score' => $processed['total_score'],
                        'grade' => $processed['grade'],
                        'level_of_achievement' => $processed['achievement'],
                        'identifier' => $processed['identifier'],
                        'descriptor' => $processed['descriptor'],
                        'teacher_comment' => $scoreData['teacher_comment'] ?? null,
                    ]
                );
            }
        }

        return redirect()->route('teacher.marks.index', ['allocation_id' => $allocation->id])
            ->with('success', 'Student marks saved and computed successfully.');
    }
}
