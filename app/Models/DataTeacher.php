<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'birthday',
        'father_name',
        'mother_name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
