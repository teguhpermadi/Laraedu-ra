<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'is_active',
    ];

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
}
