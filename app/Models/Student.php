<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'gender',
        'active'
    ];

    protected $dates = ['deleted_at'];

    public function grade()
    {
        return $this->belongsToMany(Grade::class, 'student_grade');
    }

    public function academic()
    {
        return $this->belongsToMany(AcademicYear::class, 'student_grade');
    }

    public function dataStudent()
    {
        return $this->hasOne(DataStudent::class);
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('active',1);
    }

    public function score()
    {
        return $this->hasMany(StudentCompetency::class);
    }
}
