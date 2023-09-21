<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code'
    ];

    protected $dates = ['deleted_at'];

    public function competencies()
    {
        return $this->hasMany(Competency::class, 'teacher_subject_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Scope a query to only include popular users.
     */
    public function scopeTeacher(Builder $query): void
    {
        $userable_type = auth()->user()->userable->userable_type;
        $userable_id = auth()->user()->userable->userable_id;
        $subjects_id = TeacherSubject::where('teacher_id', $userable_id)->orderBy('subject_id')->pluck('subject_id')->unique();
        $query->whereIn('id', $subjects_id);
    }

}
