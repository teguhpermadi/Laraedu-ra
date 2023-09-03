<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'birthday',
        'father_name',
        'mother_name',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
