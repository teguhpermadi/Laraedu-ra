<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'year',
        'semester',
        'active',
    ];

    protected $dates = ['deleted_at'];

    public function scopeActive(Builder $builder)
    {
        return $builder->where('active',1);
    }
}
