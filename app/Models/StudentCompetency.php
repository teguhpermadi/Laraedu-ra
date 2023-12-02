<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Valuestore\Valuestore;

class StudentCompetency extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'teacher_subject_id',
        'student_id',
        'competency_id',
        'score',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function competency()
    {
        return $this->belongsTo(Competency::class);
    }

    public function scopeResult(Builder $query)
    {
        $query->join('competencies', 'competencies.id', '=', 'student_competencies.competency_id')
            ->select('student_competencies.*', 
                    DB::raw('CASE WHEN score >= passing_grade THEN "LULUS" ELSE "TIDAK LULUS" END as result'),
                    DB::raw('competencies.description'),
                    DB::raw('CASE 
                        WHEN score >= 90 THEN "Amat Baik"
                        WHEN score >= 80 THEN "Baik"
                        WHEN score >= 70 THEN "Cukup"
                        WHEN score >= 60 THEN "Sedang"
                        ELSE "Kurang" 
                    END as predicate_desc'),
                    DB::raw('CASE 
                        WHEN score >= 90 THEN "A"
                        WHEN score >= 80 THEN "B"
                        WHEN score >= 70 THEN "C"
                        WHEN score >= 60 THEN "D"
                        ELSE "Kurang" 
                    END as predicat'),
                    // DB::raw('CONCAT(CASE 
                    //                     WHEN score >= 90 THEN "Amat baik"
                    //                     WHEN score >= 80 THEN "Baik"
                    //                     WHEN score >= 70 THEN "Cukup"
                    //                     ELSE "Sedang" END, 
                    //         " dalam aspek ", 
                    //         competencies.description) as result_predicate_description'),
                    DB::raw('CONCAT(CASE WHEN score > passing_grade THEN "sudah menguasai" 
                                        WHEN score = passing_grade THEN "cukup menguasai"
                                        ELSE "belum menguasai" END, 
                            " dalam aspek ", 
                            competencies.description) as result_description'),
                );

        return $query;
    }
}
