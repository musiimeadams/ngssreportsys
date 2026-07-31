<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function isSeniorThreeOrFour()
    {
        return in_array(strtoupper($this->code), ['S3', 'S4']);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function allocations()
    {
        return $this->hasMany(SubjectAllocation::class);
    }
}
