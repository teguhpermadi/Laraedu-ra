<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTarget extends Model
{
    use HasFactory;
    use \Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

    protected $fillable = [
        'project_grade_id',
        'target',
    ];

    protected $casts = [
        'target' => 'json'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // public function target()
    // {
    //     return $this->hasMany(Target::class);
    // }

    public function subElement()
    {
        return $this->belongsToJson(SubElement::class, 'target[]->sub_element');
    }
    
    public function subValue()
    {
        return $this->belongsToJson(SubValue::class, 'target[]->sub_nilai');
    }
}
