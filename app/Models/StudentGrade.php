<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGrade extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'student_grade';

    protected $fillable = [
        'academic_year_id',
        'grade_id',
        'student_id',
    ];

     /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new AcademicYearScope);
    }

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function studentCompetency()
    {
        return $this->hasOne(StudentCompetency::class,'student_id','student_id');
    }
}
