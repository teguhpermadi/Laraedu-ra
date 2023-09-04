<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Competency extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'teacher_subject_id',
        'description',
    ];

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }
}
