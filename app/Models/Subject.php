<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'category'];

    const OPTIONAL_SUBJECT_CODES = ['CRE', 'ICT', 'AGR', 'RUN', 'IPS', 'ENT', 'KIS'];

    public function isOptional()
    {
        return in_array(strtoupper($this->code), self::OPTIONAL_SUBJECT_CODES);
    }

    public function allocations()
    {
        return $this->hasMany(SubjectAllocation::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
