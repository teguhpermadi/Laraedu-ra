<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    public function scopeTeacher(Builder $query): void
    {
        $userable_type = auth()->user()->userable->userable_type;
        $userable_id = auth()->user()->userable->userable_id;
        $grade_id = TeacherSubject::where('teacher_id', $userable_id)->orderBy('grade_id')->pluck('grade_id')->unique();
        $query->whereIn('id', $grade_id);
    }
}
