<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'gender',
        'active',
    ];

    protected $dates = ['deleted_at'];

    public function academic()
    {
        return $this->belongsToMany(AcademicYear::class, 'teacher_subject');
    }

    public function grade()
    {
        return $this->belongsToMany(Grade::class, 'teacher_subject');
    }
    
    public function subject()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    public function dataTeacher()
    {
        return $this->hasOne(DataTeacher::class);
    }

    public function teacherSubject()
    {
        return $this->hasMany(TeacherSubject::class, 'teacher_id');
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', 1);
    }
}
