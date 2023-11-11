<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
