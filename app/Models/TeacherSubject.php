<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'teacher_id',
        'subject_id',
        'passing_grade',
    ];
}
