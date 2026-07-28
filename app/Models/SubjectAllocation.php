<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectAllocation extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'subject_id', 'school_class_id', 'term_id'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
