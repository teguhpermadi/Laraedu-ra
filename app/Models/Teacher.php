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

    public function academics()
    {
        return $this->belongsToMany(AcademicYear::class, 'teacher_subject');
    }

    public function grades()
    {
        return $this->belongsToMany(Grade::class, 'teacher_subject');
    }
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject');
    }

    public function dataTeacher()
    {
        return $this->hasOne(DataTeacher::class);
    }

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class, 'id','teacher_id');
    }

    public function scopeActive(Builder $query)
    {
        $query->where('active', 1);
    }

    public function userable()
    {
        return $this->morphOne(Userable::class, 'userable');
    }
}
