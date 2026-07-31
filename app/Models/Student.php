<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_number',
        'lin',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'house',
        'religion',
        'school_class_id',
        'status',
        'photo_path'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function reportCards()
    {
        return $this->hasMany(ReportCard::class);
    }

    public function optionalSubjects()
    {
        return $this->belongsToMany(Subject::class, 'optional_subject_registrations');
    }

    public function isRegisteredForSubject($subjectId)
    {
        return $this->optionalSubjects()->where('subject_id', $subjectId)->exists();
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public static function generateNextAdmissionNumber()
    {
        $year = date('Y');
        $prefix = 'ADM' . $year;
        $latest = self::where('admission_number', 'like', $prefix . '%')
            ->orderBy('admission_number', 'desc')
            ->first();
        if ($latest) {
            $num = (int) substr($latest->admission_number, strlen($prefix));
            $nextNum = $num + 1;
        } else {
            $nextNum = 1;
        }
        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
}
