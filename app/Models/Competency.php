<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competency extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'teacher_subject_id',
        'passing_grade',
        'description',
    ];

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class,'teacher_subject_id');
    }

    public function studentCompetency()
    {
        return $this->hasMany(StudentCompetency::class);
    }
    
    public function scopeActive(Builder $query): void
    {
        $query->whereHas('teacherSubject');
    }
}
