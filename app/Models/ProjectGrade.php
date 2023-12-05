<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'academic_year_id',
        'grade_id',
        'teacher_id',
    ];
}
