<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubElement extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimention_code',
        'element_code',
        'code',
        'description',
    ];
}
