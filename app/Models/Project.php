<?php

namespace App\Models;

use App\Models\Scopes\AcademicYearScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'project_theme_id',
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

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function projectTheme()
    {
        return $this->belongsTo(ProjectTheme::class);
    }

    public function projectTarget()
    {
        return $this->hasOne(ProjectTarget::class);
    }
}
