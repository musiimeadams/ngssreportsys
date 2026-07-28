<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Term;
use App\Models\SchoolClass;
use App\Models\Stream;
use App\Models\Student;
use App\Models\SubjectAllocation;
use App\Models\Mark;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 0. Create default School Settings
        \App\Models\SchoolSetting::create([
            'school_name' => "NGARAMA GIRL'S SECONDARY SCHOOL",
            'school_motto' => 'Develop a girl, Develop a nation.',
            'address' => 'P.O. Box 1020, Mbarara',
            'phone' => '0752935405',
            'email' => 'info@ngaramagirls.sc.ug',
            'next_term_begins' => '2026-05-25',
            'next_term_ends' => '2026-08-28',
            'next_term_fees' => 'Refer to Circular',
        ]);

        // 1. Create Admins & Teachers
        $admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@report.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+256700000001',
            'is_active' => true,
        ]);

        $teacher1 = User::create([
            'name' => 'John Doe (Teacher)',
            'email' => 'teacher1@report.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+256700000002',
            'is_active' => true,
        ]);

        $teacher2 = User::create([
            'name' => 'Jane Smith (Teacher)',
            'email' => 'teacher2@report.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
            'phone' => '+256700000003',
            'is_active' => true,
        ]);

        $classTeacher = User::create([
            'name' => 'Bob Johnson (Class Teacher)',
            'email' => 'classteacher@report.com',
            'password' => Hash::make('password'),
            'role' => 'class_teacher',
            'phone' => '+256700000004',
            'is_active' => true,
        ]);

        // 2. Create academic year and terms
        $year = AcademicYear::create([
            'name' => '2026',
            'is_active' => true,
        ]);

        $term1 = Term::create([
            'academic_year_id' => $year->id,
            'name' => 'Term 1',
            'is_active' => true,
        ]);

        Term::create([
            'academic_year_id' => $year->id,
            'name' => 'Term 2',
            'is_active' => false,
        ]);

        Term::create([
            'academic_year_id' => $year->id,
            'name' => 'Term 3',
            'is_active' => false,
        ]);

        // 3. Create requested subjects
        $subjectsList = [
            ['name' => 'PHYSICS', 'code' => 'PHY', 'category' => 'core'],
            ['name' => 'MATHS', 'code' => 'MTH', 'category' => 'core'],
            ['name' => 'ICT', 'code' => 'ICT', 'category' => 'core'],
            ['name' => 'RUNYANKORE', 'code' => 'RUN', 'category' => 'elective'],
            ['name' => 'GEOGRAPHY', 'code' => 'GEO', 'category' => 'core'],
            ['name' => 'IPS', 'code' => 'IPS', 'category' => 'core'], 
            ['name' => 'AGRICULTURE', 'code' => 'AGR', 'category' => 'core'],
            ['name' => 'CHEMISTRY', 'code' => 'CHM', 'category' => 'core'],
            ['name' => 'BIOLOGY', 'code' => 'BIO', 'category' => 'core'],
            ['name' => 'HISTORY', 'code' => 'HST', 'category' => 'core'],
            ['name' => 'CRE', 'code' => 'CRE', 'category' => 'core'],
            ['name' => 'ENTREPRENEURSHIP', 'code' => 'ENT', 'category' => 'core'],
            ['name' => 'KISWAHILI', 'code' => 'KIS', 'category' => 'core'],
        ];

        $createdSubjects = [];
        foreach ($subjectsList as $sub) {
            $createdSubjects[$sub['code']] = Subject::create($sub);
        }

        // 4. Create Classes
        $classS1 = SchoolClass::create(['name' => 'Senior 1', 'code' => 'S1']);
        $classS2 = SchoolClass::create(['name' => 'Senior 2', 'code' => 'S2']);

        // 5. Create Students
        $studentsData = [
            [
                'admission_number' => 'ADM2026001', 
                'lin' => 'LIN000000001', 
                'first_name' => 'Kansiime', 
                'last_name' => 'Brian', 
                'gender' => 'M', 
                'date_of_birth' => '2012-05-12',
                'house' => 'Kabalega',
                'religion' => 'Christian',
                'school_class_id' => $classS1->id
            ],
            [
                'admission_number' => 'ADM2026002', 
                'lin' => 'LIN000000002', 
                'first_name' => 'Nuwagaba', 
                'last_name' => 'Derrick', 
                'gender' => 'M', 
                'date_of_birth' => '2013-02-18',
                'house' => 'Nile',
                'religion' => 'Christian',
                'school_class_id' => $classS1->id
            ],
            [
                'admission_number' => 'ADM2026003', 
                'lin' => 'LIN000000003', 
                'first_name' => 'Tumusiime', 
                'last_name' => 'Grace', 
                'gender' => 'F', 
                'date_of_birth' => '2012-11-05',
                'house' => 'Kabalega',
                'religion' => 'Christian',
                'school_class_id' => $classS1->id
            ],
            [
                'admission_number' => 'ADM2026004', 
                'lin' => 'LIN000000004', 
                'first_name' => 'Atwine', 
                'last_name' => 'Evelyn', 
                'gender' => 'F', 
                'date_of_birth' => '2013-07-22',
                'house' => 'Mutesa',
                'religion' => 'Christian',
                'school_class_id' => $classS1->id
            ],
            [
                'admission_number' => 'ADM2026005', 
                'lin' => 'LIN000000005', 
                'first_name' => 'Mugisha', 
                'last_name' => 'Arthur', 
                'gender' => 'M', 
                'date_of_birth' => '2012-09-30',
                'house' => 'Nile',
                'religion' => 'Muslim',
                'school_class_id' => $classS1->id
            ],
            [
                'admission_number' => 'ADM2026006', 
                'lin' => 'LIN000000006', 
                'first_name' => 'Kyomugisha', 
                'last_name' => 'Diana', 
                'gender' => 'F', 
                'date_of_birth' => '2012-04-14',
                'house' => 'Mutesa',
                'religion' => 'Christian',
                'school_class_id' => $classS2->id
            ],
        ];

        $insertedStudents = [];
        foreach ($studentsData as $student) {
            $insertedStudents[] = Student::create($student);
        }

        // 6. Allocate Subjects to Teachers (Term 1)
        SubjectAllocation::create([
            'teacher_id' => $teacher1->id,
            'subject_id' => $createdSubjects['MTH']->id,
            'school_class_id' => $classS1->id,
            'term_id' => $term1->id,
        ]);
        SubjectAllocation::create([
            'teacher_id' => $teacher1->id,
            'subject_id' => $createdSubjects['PHY']->id,
            'school_class_id' => $classS1->id,
            'term_id' => $term1->id,
        ]);

        SubjectAllocation::create([
            'teacher_id' => $teacher2->id,
            'subject_id' => $createdSubjects['ICT']->id,
            'school_class_id' => $classS1->id,
            'term_id' => $term1->id,
        ]);
        SubjectAllocation::create([
            'teacher_id' => $teacher2->id,
            'subject_id' => $createdSubjects['RUN']->id,
            'school_class_id' => $classS1->id,
            'term_id' => $term1->id,
        ]);
        SubjectAllocation::create([
            'teacher_id' => $teacher2->id,
            'subject_id' => $createdSubjects['CHM']->id,
            'school_class_id' => $classS1->id,
            'term_id' => $term1->id,
        ]);

        // 7. Seed Mock Marks for Students in Senior 1 (Term 1)
        $s1Students = array_filter($insertedStudents, function($st) use ($classS1) {
            return $st->school_class_id === $classS1->id;
        });

        $mockMarksConfig = [
            'MTH' => ['teacher' => $teacher1, 'f' => 16.5, 's' => 64.0, 'c' => 'Demonstrates great logical understanding.'],
            'PHY' => ['teacher' => $teacher1, 'f' => 15.0, 's' => 52.0, 'c' => 'Good conceptual understanding of electricity.'],
            'ICT' => ['teacher' => $teacher2, 'f' => 18.0, 's' => 70.0, 'c' => 'Excellent hands-on computer skills.'],
            'RUN' => ['teacher' => $teacher2, 'f' => 14.5, 's' => 60.5, 'c' => 'Speaks and reads language very fluently.'],
            'CHM' => ['teacher' => $teacher2, 'f' => 13.0, 's' => 45.0, 'c' => 'Shows keen interest in laboratory sessions.'],
        ];

        foreach ($s1Students as $student) {
            // Seed marks
            foreach ($mockMarksConfig as $code => $cfg) {
                // Introduce slight variance
                $varianceF = rand(-20, 20) / 10.0;
                $varianceS = rand(-80, 80) / 10.0;
                
                $finalF = max(5, min(20, $cfg['f'] + $varianceF));
                $finalS = max(20, min(80, $cfg['s'] + $varianceS));
                
                $processed = \App\Services\ReportProcessingService::processScore($finalF, $finalS);
                
                Mark::create([
                    'student_id' => $student->id,
                    'subject_id' => $createdSubjects[$code]->id,
                    'term_id' => $term1->id,
                    'teacher_id' => $cfg['teacher']->id,
                    'formative_score' => $finalF,
                    'summative_score' => $finalS,
                    'total_score' => $processed['total_score'],
                    'grade' => $processed['grade'],
                    'level_of_achievement' => $processed['achievement'],
                    'identifier' => $processed['identifier'],
                    'descriptor' => $processed['descriptor'],
                    'teacher_comment' => $cfg['c'],
                ]);
            }

            // Seed Report Card
            \App\Models\ReportCard::create([
                'student_id' => $student->id,
                'term_id' => $term1->id,
                'attendance_present' => rand(75, 84),
                'total_attendance' => 84,
                'conduct_comment' => 'Well-behaved, respectful, and active in co-curricular activities.',
                'class_teacher_comment' => 'An active learner. Shows steady progress and potential to excel.',
                'status' => 'published',
            ]);
        }
    }
}
