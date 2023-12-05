<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_grade_id',
        'target_id',
    ];

    public function projectGrade()
    {
        return $this->belongsTo(ProjectGrade::class, 'project_grade_id');
    }

    public function target()
    {
        return $this->hasMany(Target::class);
    }
}
