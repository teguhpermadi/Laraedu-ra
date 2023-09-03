<?php

namespace App\Models;

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
        'is_active'
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
}
