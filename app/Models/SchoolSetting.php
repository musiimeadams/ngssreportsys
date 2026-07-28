<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name',
        'school_motto',
        'address',
        'phone',
        'email',
        'next_term_begins',
        'next_term_ends',
        'next_term_fees',
        'logo_path',
    ];

    protected $casts = [
        'next_term_begins' => 'date',
        'next_term_ends' => 'date',
    ];
}
