<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Mark;
use App\Models\ReportCard;
use App\Models\Term;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $activeTerm = Term::where('is_active', true)->first();

        if (!$activeTerm) {
            return view('reports.index', [
                'classes' => collect(),
                'students' => collect(),
                'selectedClass' => null,
                'activeTerm' => null,
                'error' => 'No active term is set. Please ask the administrator.'
            ]);
        }

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

            // Calculate student aggregates dynamically - eager load marks and report cards
            $allMarks = Mark::with('subject')
                ->whereIn('student_id', $students->pluck('id'))
                ->where('term_id', $activeTerm->id)
                ->get()
                ->groupBy('student_id');

            $allReportCards = ReportCard::whereIn('student_id', $students->pluck('id'))
                ->where('term_id', $activeTerm->id)
                ->get()
                ->keyBy('student_id');

            $isClassS3S4 = $selectedClass->isSeniorThreeOrFour();

            foreach ($students as $student) {
                $marks = $allMarks->get($student->id, collect());
                
                if ($isClassS3S4) {
                    $registeredIds = $student->optionalSubjects()->pluck('subjects.id')->toArray();
                    $marks = $marks->filter(function($m) use ($registeredIds) {
                        if ($m->subject->isOptional()) {
                            return in_array($m->subject_id, $registeredIds);
                        }
                        return true;
                    });
                }

                $student->marks_count = $marks->count();
                $student->total_score = $marks->sum('total_score');
                $student->average_score = $student->marks_count > 0 ? ($student->total_score / $student->marks_count) : 0;
                
                $student->reportCard = $allReportCards->get($student->id);
            }

            // Rank students by average score descending
            $students = $students->sortByDesc('average_score')->values();
            foreach ($students as $rank => $student) {
                $student->computed_position = $student->marks_count > 0 ? ($rank + 1) : null;
            }
        }

        return view('reports.index', compact('classes', 'selectedClass', 'students', 'activeTerm'));
    }

    public function show($id)
    {
        $activeTerm = Term::where('is_active', true)->first();
        if (!$activeTerm) {
            abort(404, 'No active term set.');
        }

        $student = Student::with(['schoolClass'])->findOrFail($id);
        
        $marks = Mark::with(['subject', 'teacher'])
            ->where('student_id', $student->id)
            ->where('term_id', $activeTerm->id)
            ->get();

        $reportCard = ReportCard::where('student_id', $student->id)
            ->where('term_id', $activeTerm->id)
            ->first();

        // Calculate rankings in class dynamically - eager load class marks to optimize
        $allStudentsInClass = Student::where('school_class_id', $student->school_class_id)
            ->where('status', 'active')
            ->get();

        $isClassS3S4 = $student->schoolClass->isSeniorThreeOrFour();

        // Eager load all class marks to avoid N+1 query loops
        $allClassMarks = Mark::with('subject')
            ->whereIn('student_id', $allStudentsInClass->pluck('id'))
            ->where('term_id', $activeTerm->id)
            ->get()
            ->groupBy('student_id');

        $rankings = [];
        foreach ($allStudentsInClass as $s) {
            $sMarks = $allClassMarks->get($s->id, collect());
            if ($isClassS3S4) {
                $registeredIds = $s->optionalSubjects()->pluck('subjects.id')->toArray();
                $sMarks = $sMarks->filter(function($m) use ($registeredIds) {
                    if ($m->subject->isOptional()) {
                        return in_array($m->subject_id, $registeredIds);
                    }
                    return true;
                });
            }
            $sAverage = $sMarks->count() > 0 ? ($sMarks->sum('total_score') / $sMarks->count()) : 0;
            $rankings[$s->id] = $sAverage;
        }
        arsort($rankings);
        
        $position = 1;
        $studentPosition = null;
        foreach ($rankings as $sId => $avg) {
            if ($sId == $student->id) {
                $studentPosition = $position;
                break;
            }
            $position++;
        }

        // Filter the student's own marks for display
        if ($isClassS3S4) {
            $registeredIds = $student->optionalSubjects()->pluck('subjects.id')->toArray();
            $marks = $marks->filter(function($m) use ($registeredIds) {
                if ($m->subject->isOptional()) {
                    return in_array($m->subject_id, $registeredIds);
                }
                return true;
            });
        }

        $schoolSetting = SchoolSetting::first() ?? new SchoolSetting();
        $overallAverage = $marks->count() > 0 ? round($marks->avg('level_of_achievement'), 1) : 0.0;

        return view('reports.show', compact('student', 'marks', 'reportCard', 'activeTerm', 'studentPosition', 'schoolSetting', 'overallAverage'));
    }

    public function printStream($classId)
    {
        $data = $this->getStreamData($classId);
        return view('reports.print_stream', $data);
    }

    public function downloadWord($classId)
    {
        $data = $this->getStreamData($classId);
        $html = view('reports.booklet_template', $data)->render();
        
        $filename = 'Booklet_' . str_replace(' ', '_', $data['schoolClass']->name) . '_' . date('Y-m-d') . '.doc';

        return response($html, 200)
            ->header('Content-Type', 'application/vnd.ms-word')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0, must-revalidate');
    }

    public function downloadHtml($classId)
    {
        $data = $this->getStreamData($classId);
        $html = view('reports.booklet_template', $data)->render();
        
        $filename = 'Booklet_' . str_replace(' ', '_', $data['schoolClass']->name) . '_' . date('Y-m-d') . '.html';

        return response($html, 200)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0, must-revalidate');
    }

    private function getStreamData($classId)
    {
        $activeTerm = Term::where('is_active', true)->first();
        if (!$activeTerm) {
            abort(404, 'No active term set.');
        }

        $schoolClass = SchoolClass::findOrFail($classId);
        $students = Student::where('school_class_id', $schoolClass->id)->where('status', 'active')->get();
        $isClassS3S4 = $schoolClass->isSeniorThreeOrFour();

        // Eager load all class marks and report cards to optimize
        $allMarks = Mark::with(['subject', 'teacher'])
            ->whereIn('student_id', $students->pluck('id'))
            ->where('term_id', $activeTerm->id)
            ->get()
            ->groupBy('student_id');

        $allReportCards = ReportCard::whereIn('student_id', $students->pluck('id'))
            ->where('term_id', $activeTerm->id)
            ->get()
            ->keyBy('student_id');

        // Compute averages and rankings
        $rankings = [];
        foreach ($students as $student) {
            $sMarks = $allMarks->get($student->id, collect());
            if ($isClassS3S4) {
                $registeredIds = $student->optionalSubjects()->pluck('subjects.id')->toArray();
                $sMarks = $sMarks->filter(function($m) use ($registeredIds) {
                    if ($m->subject->isOptional()) {
                        return in_array($m->subject_id, $registeredIds);
                    }
                    return true;
                });
            }
            $student->filtered_marks = $sMarks;
            $student->average_score = $sMarks->count() > 0 ? ($sMarks->sum('total_score') / $sMarks->count()) : 0;
            $rankings[$student->id] = $student->average_score;
            
            $student->reportCard = $allReportCards->get($student->id);
        }
        
        arsort($rankings);

        foreach ($students as $student) {
            $position = 1;
            foreach ($rankings as $sId => $avg) {
                if ($sId == $student->id) {
                    $student->computed_position = $position;
                    break;
                }
                $position++;
            }

            $student->marks = $student->filtered_marks;
            $student->overall_average = $student->marks->count() > 0 ? round($student->marks->avg('level_of_achievement'), 1) : 0.0;
        }

        $schoolSetting = SchoolSetting::first() ?? new SchoolSetting();

        return compact('schoolClass', 'students', 'activeTerm', 'schoolSetting');
    }

    public function uploadStudentPhoto($id, Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action. Only administrators can upload student photos.');
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student = Student::findOrFail($id);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = $student->admission_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Ensure uploads/students folder exists in public_path
            $uploadPath = public_path('uploads/students');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            
            // Delete old photo if it exists
            if ($student->photo_path && file_exists(public_path($student->photo_path))) {
                @unlink(public_path($student->photo_path));
            }

            $student->photo_path = 'uploads/students/' . $fileName;
            $student->save();

            return back()->with('success', "Student photo uploaded successfully.");
        }

        return back()->with('error', "No photo selected.");
    }
}
