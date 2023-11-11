<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class StudentExtracurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'student_id',
        'extracurricular_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function extracurricular()
    {
        return $this->belongsTo(Extracurricular::class);
    }

    public function scopeMyExtracurricular(Builder $query)
    {
        $teacher_id = auth()->user()->userable->userable_id;
        $extra = Extracurricular::where('teacher_id', $teacher_id)->first();

        $query->where('extracurricular_id', $extra->id);
    }
}
