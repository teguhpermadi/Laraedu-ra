<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'project_target_id',
        'scores',
    ];

    protected $casts = [
        'scores' => 'array'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function projectTarget()
    {
        return $this->belongsTo(ProjectTarget::class, 'project_target_id');
    }
}
