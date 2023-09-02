<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'student_id',
    ];
}
