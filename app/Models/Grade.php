<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'grade',
    ];

    protected $dates = ['deleted_at'];

    public function teacherSubject()
    {
        return $this->hasMany(TeacherSubject::class)->orderBy('academic_year_id', 'asc');
    }

    public function studentGrade()
    {
        return $this->hasMany(StudentGrade::class)->orderBy('academic_year_id', 'asc');
    }
}
