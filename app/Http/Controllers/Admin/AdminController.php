<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\Student;
use App\Models\User;
use App\Models\Term;
use App\Models\SubjectAllocation;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // === CLASSES & STREAMS ===
    // === CLASSES ===
    public function classes()
    {
        $classes = SchoolClass::all();
        return view('admin.classes.index', compact('classes'));
    }

    public function storeClass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:school_classes,name',
            'code' => 'nullable|string',
        ]);

        SchoolClass::create($request->only('name', 'code'));

        return back()->with('success', 'Class created successfully.');
    }

    // === SUBJECTS ===
    public function subjects()
    {
        $subjects = Subject::all();
        return view('admin.subjects.index', compact('subjects'));
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:subjects,code',
            'category' => 'required|string|in:core,elective',
        ]);

        Subject::create($request->only('name', 'code', 'category'));

        return back()->with('success', 'Subject created successfully.');
    }

    // === STUDENTS ===
    public function students(Request $request)
    {
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
            $students = Student::where('school_class_id', $selectedClass->id)->with('schoolClass')->get();
        }

        $nextAdmissionNumber = Student::generateNextAdmissionNumber();
        return view('admin.students.index', compact('students', 'classes', 'selectedClass', 'nextAdmissionNumber'));
    }

    public function storeStudent(Request $request)
    {
        $request->validate([
            'admission_number' => 'nullable|string|unique:students,admission_number',
            'lin' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string|in:M,F',
            'school_class_id' => 'required|exists:school_classes,id',
        ]);

        $data = $request->all();
        if (empty($data['admission_number'])) {
            $data['admission_number'] = Student::generateNextAdmissionNumber();
        }

        Student::create($data);

        return redirect()->route('admin.students.index', ['school_class_id' => $request->school_class_id])
            ->with('success', 'Student created successfully.');
    }

    public function editStudent($id)
    {
        $student = Student::findOrFail($id);
        $classes = SchoolClass::all();
        return view('admin.students.edit', compact('student', 'classes'));
    }

    public function updateStudent(Request $request, $id)
    {
        $request->validate([
            'admission_number' => 'required|string|unique:students,admission_number,' . $id,
            'lin' => 'nullable|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string|in:M,F',
            'school_class_id' => 'required|exists:school_classes,id',
            'status' => 'required|string|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $student = Student::findOrFail($id);
        
        $data = $request->only('admission_number', 'lin', 'first_name', 'last_name', 'gender', 'school_class_id', 'status');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = $request->admission_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            $uploadPath = public_path('uploads/students');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            
            if ($student->photo_path && file_exists(public_path($student->photo_path))) {
                @unlink(public_path($student->photo_path));
            }
            
            $data['photo_path'] = 'uploads/students/' . $fileName;
        }

        $student->update($data);

        return redirect()->route('admin.students.index', ['school_class_id' => $student->school_class_id])
            ->with('success', 'Student details updated successfully.');
    }

    // === ALLOCATIONS ===
    public function allocations()
    {
        $allocations = SubjectAllocation::with(['teacher', 'subject', 'schoolClass', 'term'])->get();
        $teachers = User::whereIn('role', ['teacher', 'class_teacher'])->get();
        $subjects = Subject::all();
        $classes = SchoolClass::all();
        $activeTerm = Term::where('is_active', true)->first();

        return view('admin.allocations.index', compact('allocations', 'teachers', 'subjects', 'classes', 'activeTerm'));
    }

    public function storeAllocation(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'school_class_id' => 'required|exists:school_classes,id',
            'term_id' => 'required|exists:terms,id',
        ]);

        try {
            SubjectAllocation::create($request->only('teacher_id', 'subject_id', 'school_class_id', 'term_id'));
            return back()->with('success', 'Subject allocated to teacher successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'This allocation already exists.');
        }
    }

    // === USER MANAGEMENT (TEACHER APPROVALS & ROLES) ===
    public function users()
    {
        $users = User::where('id', '!=', auth()->id())->with(['subjectAllocations.subject', 'subjectAllocations.schoolClass'])->get();
        $subjects = Subject::all();
        $classes = SchoolClass::all();
        $activeTerm = Term::where('is_active', true)->first();
        return view('admin.users.index', compact('users', 'subjects', 'classes', 'activeTerm'));
    }

    public function toggleUserActive($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User account {$user->name} has been {$status} successfully.");
    }

    public function updateUserRole($id, Request $request)
    {
        $request->validate([
            'role' => 'required|string|in:admin,teacher,class_teacher,headteacher',
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return back()->with('success', "Role for {$user->name} updated to {$request->role} successfully.");
    }

    // === SCHOOL SETTINGS ===
    public function settings()
    {
        $settings = SchoolSetting::first() ?? new SchoolSetting();
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_motto' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'next_term_begins' => 'nullable|date',
            'next_term_ends' => 'nullable|date',
            'next_term_fees' => 'required|string|max:255',
        ]);

        $settings = SchoolSetting::first();
        if (!$settings) {
            $settings = new SchoolSetting();
        }

        $settings->fill($request->all());
        $settings->save();

        return back()->with('success', 'School settings updated successfully.');
    }

    public function destroyStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return back()->with('success', 'Student record deleted successfully.');
    }

    public function bulkDestroyStudents(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:students,id',
        ]);

        Student::whereIn('id', $request->ids)->delete();

        return back()->with('success', 'Selected student records deleted successfully.');
    }

    public function importStudents(Request $request)
    {
        $request->validate([
            'school_class_id' => 'required|exists:school_classes,id',
            'file' => 'nullable|file',
            'paste_data' => 'nullable|string',
        ]);

        $classId = $request->school_class_id;
        $importCount = 0;

        // Option 1: File Upload (CSV)
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            if (($handle = fopen($path, 'r')) !== false) {
                // Read header row
                $header = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 3) continue;
                    
                    $firstName = trim($row[0]);
                    $lastName = trim($row[1]);
                    $gender = strtoupper(trim($row[2])) === 'M' ? 'M' : 'F';
                    
                    $adm = isset($row[3]) && !empty(trim($row[3])) 
                        ? trim($row[3]) 
                        : Student::generateNextAdmissionNumber();
                        
                    $lin = isset($row[4]) ? trim($row[4]) : null;

                    Student::create([
                        'school_class_id' => $classId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'gender' => $gender,
                        'admission_number' => $adm,
                        'lin' => $lin,
                        'status' => 'active',
                    ]);
                    $importCount++;
                }
                fclose($handle);
            }
        }
        // Option 2: Copied & Pasted Raw Names List (one per line)
        elseif ($request->has('paste_data') && !empty(trim($request->paste_data))) {
            $lines = explode("\n", $request->paste_data);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                // Try splitting by comma first, then fallback to whitespace
                if (str_contains($line, ',')) {
                    $parts = explode(',', $line);
                } else {
                    $parts = preg_split('/\s+/', $line, 3);
                }
                
                if (count($parts) >= 2) {
                    $firstName = trim($parts[0]);
                    $lastName = trim($parts[1]);
                    $gender = 'F'; // Default to F for Ngarama Girls
                    
                    if (isset($parts[2])) {
                        $g = strtoupper(trim($parts[2]));
                        if ($g === 'M' || $g === 'F') {
                            $gender = $g;
                        }
                    }
                    
                    Student::create([
                        'school_class_id' => $classId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'gender' => $gender,
                        'admission_number' => Student::generateNextAdmissionNumber(),
                        'status' => 'active',
                    ]);
                    $importCount++;
                }
            }
        }

        if ($importCount === 0) {
            return back()->with('error', 'No valid student records found. Ensure CSV format is: first_name, last_name, gender, [admission_number], [lin]');
        }

        return back()->with('success', "Successfully imported {$importCount} students.");
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|in:teacher,admin,class_teacher,headteacher',
        ]);

        // Auto-generate email based on last name
        $nameParts = explode(' ', trim($request->name));
        $lastName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', end($nameParts)));
        $email = $lastName . '@ngssreports.com';
        
        // Ensure email uniqueness
        $counter = 2;
        while (User::where('email', $email)->exists()) {
            $email = $lastName . $counter . '@ngssreports.com';
            $counter++;
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => bcrypt('password'),
            'role' => $request->role,
            'is_active' => true,
        ]);

        $activeTerm = Term::where('is_active', true)->first();
        if ($activeTerm && $request->has('allocations')) {
            foreach ($request->allocations as $allocString) {
                $parts = explode('-', $allocString);
                if (count($parts) == 2) {
                    $classId = $parts[0];
                    $subjectId = $parts[1];
                    SubjectAllocation::create([
                        'teacher_id' => $user->id,
                        'subject_id' => $subjectId,
                        'school_class_id' => $classId,
                        'term_id' => $activeTerm->id,
                    ]);
                }
            }
        }

        return back()->with('success', "User account created with email '{$email}' and default password 'password' successfully.");
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file' => 'nullable|file',
            'paste_data' => 'nullable|string',
        ]);

        $importCount = 0;
        $defaultPassword = bcrypt('password'); // Default password for imported teachers

        // Option 1: File Upload (CSV)
        if ($request->hasFile('file')) {
            $path = $request->file('file')->getRealPath();
            if (($handle = fopen($path, 'r')) !== false) {
                // Read header row
                $header = fgetcsv($handle);
                
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) < 1) continue;
                    
                    $name = trim($row[0]);
                    
                    // Auto-generate email based on last name
                    $nameParts = explode(' ', trim($name));
                    $lastName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', end($nameParts)));
                    $email = $lastName . '@ngssreports.com';
                    
                    // Ensure email uniqueness
                    $counter = 2;
                    while (User::where('email', $email)->exists()) {
                        $email = $lastName . $counter . '@ngssreports.com';
                        $counter++;
                    }
                    
                    $phone = isset($row[2]) ? trim($row[2]) : null;
                    $role = isset($row[3]) && in_array(trim($row[3]), ['teacher', 'class_teacher', 'headteacher', 'admin']) 
                        ? trim($row[3]) 
                        : 'teacher';

                    User::create([
                        'name' => $name,
                        'email' => $email,
                        'password' => $defaultPassword,
                        'role' => $role,
                        'phone' => $phone,
                        'is_active' => true,
                    ]);
                    $importCount++;
                }
                fclose($handle);
            }
        }
        // Option 2: Copied & Pasted Raw Names List (one per line)
        elseif ($request->has('paste_data') && !empty(trim($request->paste_data))) {
            $lines = explode("\n", $request->paste_data);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                
                $name = $line;
                
                // Auto-generate email based on last name
                $nameParts = explode(' ', trim($name));
                $lastName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', end($nameParts)));
                $email = $lastName . '@ngssreports.com';
                
                // Ensure email uniqueness
                $counter = 2;
                while (User::where('email', $email)->exists()) {
                    $email = $lastName . $counter . '@ngssreports.com';
                    $counter++;
                }

                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $defaultPassword,
                    'role' => 'teacher',
                    'is_active' => true,
                ]);
                $importCount++;
            }
        }

        if ($importCount === 0) {
            return back()->with('error', 'No valid teacher records found.');
        }

        return back()->with('success', "Successfully imported {$importCount} teachers. Emails were assigned automatically using their last names. The default password is 'password'.");
    }

    public function destroyUser($id)
    {
        if (auth()->id() == $id) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        $user = User::findOrFail($id);
        $user->delete();
        return back()->with('success', 'User account deleted successfully.');
    }

    public function bulkDestroyUsers(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id',
        ]);

        $ids = array_filter($request->ids, function ($id) {
            return $id != auth()->id();
        });

        if (empty($ids)) {
            return back()->with('error', 'No valid user accounts selected for deletion.');
        }

        User::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected user accounts deleted successfully.');
    }

    public function updateUserAllocations($id, Request $request)
    {
        $user = User::findOrFail($id);
        $activeTerm = Term::where('is_active', true)->first();
        if (!$activeTerm) {
            return back()->with('error', 'Please make a term active first.');
        }

        // Delete existing allocations for this user for the active term
        SubjectAllocation::where('teacher_id', $user->id)
            ->where('term_id', $activeTerm->id)
            ->delete();

        // Add new allocations
        if ($request->has('allocations')) {
            foreach ($request->allocations as $allocString) {
                $parts = explode('-', $allocString);
                if (count($parts) == 2) {
                    $classId = $parts[0];
                    $subjectId = $parts[1];
                    SubjectAllocation::create([
                        'teacher_id' => $user->id,
                        'subject_id' => $subjectId,
                        'school_class_id' => $classId,
                        'term_id' => $activeTerm->id,
                    ]);
                }
            }
        }

        return back()->with('success', "Allocations for {$user->name} updated successfully.");
    }

    public function storeAllocationsRequest(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
        ]);
        return $this->updateUserAllocations($request->teacher_id, $request);
    }

    // === ACADEMIC YEARS & TERMS ===
    public function years()
    {
        $years = AcademicYear::with('terms')->orderBy('name', 'desc')->get();
        $activeTerm = Term::where('is_active', true)->with('academicYear')->first();
        return view('admin.years.index', compact('years', 'activeTerm'));
    }

    public function storeYear(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:academic_years,name',
        ]);
        AcademicYear::create(['name' => $request->name, 'is_active' => false]);
        return back()->with('success', 'Academic year created successfully.');
    }

    public function destroyYear($id)
    {
        $year = AcademicYear::findOrFail($id);
        $year->terms()->delete();
        $year->delete();
        return back()->with('success', 'Academic year and its terms deleted.');
    }

    public function storeTerm(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string',
        ]);
        // Ensure no duplicate term name within same year
        $exists = Term::where('academic_year_id', $request->academic_year_id)
            ->where('name', $request->name)->exists();
        if ($exists) {
            return back()->with('error', 'This term already exists for the selected year.');
        }
        Term::create([
            'academic_year_id' => $request->academic_year_id,
            'name' => $request->name,
            'is_active' => false,
        ]);
        return back()->with('success', 'Term added successfully.');
    }

    public function activateTerm($id)
    {
        // Deactivate all terms and years first
        Term::query()->update(['is_active' => false]);
        AcademicYear::query()->update(['is_active' => false]);

        // Activate selected term and its academic year
        $term = Term::findOrFail($id);
        $term->is_active = true;
        $term->save();
        $term->academicYear->is_active = true;
        $term->academicYear->save();

        return back()->with('success', "Term \"{$term->name} ({$term->academicYear->name})\" is now the active term.");
    }

    public function destroyTerm($id)
    {
        $term = Term::findOrFail($id);
        if ($term->is_active) {
            return back()->with('error', 'Cannot delete the currently active term.');
        }
        $term->delete();
        return back()->with('success', 'Term deleted successfully.');
    }

    public function resetUserPassword($id, Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8',
        ]);

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->save();

        return back()->with('success', "Password for {$user->name} has been reset successfully to '{$request->password}'.");
    }

    public function optionalSubjects(Request $request)
    {
        $classes = SchoolClass::whereIn('code', ['S3', 'S4'])->get();
        $subjects = Subject::whereIn('code', Subject::OPTIONAL_SUBJECT_CODES)->get();

        $selectedClassId = $request->input('class_id');
        $selectedSubjectId = $request->input('subject_id');

        $students = collect();
        $selectedClass = null;
        $selectedSubject = null;
        $registrationsGrid = [];

        if ($selectedClassId && $selectedSubjectId) {
            $selectedClass = SchoolClass::findOrFail($selectedClassId);

            $students = Student::where('school_class_id', $selectedClassId)
                ->where('status', 'active')
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();

            if ($selectedSubjectId === 'all') {
                $selectedSubject = (object)[
                    'id' => 'all',
                    'name' => 'All Optional Subjects',
                    'code' => 'ALL'
                ];

                $registrationsGrid = \DB::table('optional_subject_registrations')
                    ->whereIn('student_id', $students->pluck('id'))
                    ->get()
                    ->groupBy('student_id')
                    ->map(function ($items) {
                        return $items->pluck('subject_id')->toArray();
                    })
                    ->toArray();
            } else {
                $selectedSubject = Subject::findOrFail($selectedSubjectId);
            }
        }

        return view('admin.optional_subjects', compact(
            'classes',
            'subjects',
            'selectedClassId',
            'selectedSubjectId',
            'selectedClass',
            'selectedSubject',
            'students',
            'registrationsGrid'
        ));
    }

    public function storeOptionalSubjects(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|string',
        ]);

        $classId = $request->class_id;
        $subjectId = $request->subject_id;

        $allClassStudentIds = Student::where('school_class_id', $classId)
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();

        if ($subjectId === 'all') {
            $registrations = $request->input('registrations', []);

            \DB::table('optional_subject_registrations')
                ->whereIn('student_id', $allClassStudentIds)
                ->delete();

            $insertData = [];
            foreach ($registrations as $studentId => $subjectIds) {
                if (!in_array($studentId, $allClassStudentIds)) continue;
                foreach ($subjectIds as $subId) {
                    $insertData[] = [
                        'student_id' => $studentId,
                        'subject_id' => $subId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($insertData)) {
                foreach (array_chunk($insertData, 500) as $chunk) {
                    \DB::table('optional_subject_registrations')->insert($chunk);
                }
            }
        } else {
            $request->validate([
                'subject_id' => 'exists:subjects,id',
                'student_ids' => 'nullable|array',
                'student_ids.*' => 'exists:students,id',
            ]);

            $registeredStudentIds = $request->input('student_ids', []);

            \DB::table('optional_subject_registrations')
                ->where('subject_id', $subjectId)
                ->whereIn('student_id', $allClassStudentIds)
                ->whereNotIn('student_id', $registeredStudentIds)
                ->delete();

            foreach ($registeredStudentIds as $studentId) {
                \DB::table('optional_subject_registrations')->insertOrIgnore([
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return back()->with('success', 'Optional subject registrations updated successfully.');
    }
}

