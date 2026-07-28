<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'term_id',
        'attendance_present',
        'total_attendance',
        'conduct_comment',
        'class_teacher_comment',
        'headteacher_comment',
        'position',
        'decision',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
