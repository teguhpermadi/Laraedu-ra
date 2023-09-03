<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'is_active'
    ];

    public function grade()
    {
        return $this->belongsToMany(Grade::class, 'student_grade');
    }

    public function academic()
    {
        return $this->belongsToMany(AcademicYear::class, 'student_grade');
    }
}
