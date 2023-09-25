<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Student extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'gender',
        'active'
    ];

    protected $dates = ['deleted_at'];

    // public function grades()
    // {
    //     return $this->belongsToMany(Grade::class, 'student_grade');
    // }

    // public function academics()
    // {
    //     return $this->belongsToMany(AcademicYear::class, 'student_grade');
    // }

    public function studentGrade()
    {
        return $this->belongsTo(StudentGrade::class, 'id', 'student_id');
    }

    public function dataStudent()
    {
        return $this->hasOne(DataStudent::class);
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('active',1);
    }

    public function studentCompetency()
    {
        return $this->hasMany(StudentCompetency::class);
    }

    public function userable()
    {
        return $this->morphOne(Userable::class, 'userable');
    }

    public static function setUserable($id)
    {
        $student = self::find($id);

        $user = User::create([
            'name' => $student->name,
            'email' => Str::slug($student->name).'@student.com',
            'password' => Hash::make('password'),
        ]);

        Userable::create([
            'user_id' => $user->id,
            'userable_id' => $student->id,
            'userable_type' => 'Student',
        ]);

        return self::find($id);
    }
}
