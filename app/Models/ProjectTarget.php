<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_grade_id',
        'target',
    ];

    protected $casts = [
        'target' => 'array'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function target()
    {
        return $this->hasMany(Target::class);
    }
}
