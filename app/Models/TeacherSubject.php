<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'teacher_subject';

    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'teacher_id',
        'subject_id',
        'passing_grade',
    ];

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function competencies()
    {
        return $this->hasMany(Competency::class, 'teacher_subject_id');
    }
}
