<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
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

    protected static function booted(): void
    {
        static::addGlobalScope(new AcademicYearScope);
    }

    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
