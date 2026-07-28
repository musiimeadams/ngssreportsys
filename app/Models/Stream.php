<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    protected $fillable = ['school_class_id', 'name'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function getFullDisplayNameAttribute()
    {
        return $this->schoolClass ? "{$this->schoolClass->name} - {$this->name}" : $this->name;
    }
}
