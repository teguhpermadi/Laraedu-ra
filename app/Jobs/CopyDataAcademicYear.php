<?php

namespace App\Jobs;

use App\Models\AcademicYear;
use App\Models\ProjectCoordinator;
use App\Models\Scopes\AcademicYearScope;
use App\Models\StudentExtracurricular;
use App\Models\StudentGrade;
use App\Models\TeacherExtracurricular;
use App\Models\TeacherGrade;
use App\Models\TeacherSubject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CopyDataAcademicYear implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $yearId;

    /**
     * Create a new job instance.
     */
    public function __construct($yearId)
    {
        $this->yearId = $yearId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // cek jika copy year id = active year id
        $activeYear = AcademicYear::active()->first()->id;
        $copyYear = $this->yearId;

        $countStudentGrade = StudentGrade::where('academic_year_id', $activeYear)->withoutGlobalScope(AcademicYearScope::class)->count();
        $countTeacherSubject = TeacherSubject::where('academic_year_id', $activeYear)->withoutGlobalScope(AcademicYearScope::class)->count();
        $countTeacherGrade = TeacherGrade::where('academic_year_id', $activeYear)->withoutGlobalScope(AcademicYearScope::class)->count();
        $countTeacherExtra = TeacherExtracurricular::where('academic_year_id', $activeYear)->withoutGlobalScope(AcademicYearScope::class)->count();
        $countStudentExtra = StudentExtracurricular::where('academic_year_id', $activeYear)->withoutGlobalScope(AcademicYearScope::class)->count();
        $countProjectCoor = ProjectCoordinator::where('academic_year_id', $activeYear)->withoutGlobalScope(AcademicYearScope::class)->count();

        if($copyYear != $activeYear) {
            // copy student grade
            if($countStudentGrade == 0) {
                StudentGrade::withoutGlobalScope(AcademicYearScope::class)->get()->each(function ($studentGrade) use ($activeYear){
                    // Duplicate the record
                    $newStudentGrade = $studentGrade->replicate();
                
                    // Change the value of 'academic_year_id' to your desired value
                    $newStudentGrade->academic_year_id = $activeYear;
                
                    // Save the new record
                    $newStudentGrade->save();
                });
            }

            // copy teacher subject
            if($countTeacherSubject == 0) {
                TeacherSubject::withoutGlobalScope(AcademicYearScope::class)->get()->each(function ($teacherSubject) use ($activeYear){
                    // Duplikasi catatan
                    $newTeacherSubject = $teacherSubject->replicate();
                
                    // Ubah nilai 'academic_year_id' sesuai keinginan Anda
                    $newTeacherSubject->academic_year_id = $activeYear;
                
                    // Simpan catatan baru
                    $newTeacherSubject->save();
                });
            }

            // copy teacher grade
            if($countTeacherGrade == 0) {
                TeacherGrade::withoutGlobalScope(AcademicYearScope::class)->get()->each(function ($teacherGrade) use ($activeYear){
                    // Duplikasi data
                    $newTeacherGrade = $teacherGrade->replicate();
                
                    // Ubah nilai kolom 'academic_year_id' menjadi nilai yang diinginkan
                    $newTeacherGrade->academic_year_id = $activeYear;
                
                    // Simpan data baru
                    $newTeacherGrade->save();
                });
            }

            // copy teacher extracurricular
            if($countTeacherExtra == 0) {
                TeacherExtracurricular::withoutGlobalScope(AcademicYearScope::class)->get()->each(function ($teacherExtracurricular) use ($activeYear) {
                    // Duplicate the record
                    $newTeacherExtracurricular = $teacherExtracurricular->replicate();
                
                    // Change the value of 'academic_year_id' to the value of $activeYear
                    $newTeacherExtracurricular->academic_year_id = $activeYear;
                
                    // Save the new record
                    $newTeacherExtracurricular->save();
                });
            }

            // copy student extracurricular
            if($countStudentExtra == 0){
                StudentExtracurricular::withoutGlobalScope(AcademicYearScope::class)->get()->each(function ($studentExtracurricular) use ($activeYear) {
                    // Duplicate the record
                    $newStudentExtracurricular = $studentExtracurricular->replicate();
                
                    // Change the value of 'academic_year_id' to the value of $activeYear
                    $newStudentExtracurricular->academic_year_id = $activeYear;
                
                    // Save the new record
                    $newStudentExtracurricular->save();
                });
            }

            // copy project coordinator
            if($countProjectCoor == 0){
                ProjectCoordinator::withoutGlobalScope(AcademicYearScope::class)->get()->each(function ($projectCoordinator) use ($activeYear) {
                    // Duplikasi rekaman
                    $newProjectCoordinator = $projectCoordinator->replicate();
                
                    // Ubah nilai 'academic_year_id' menjadi nilai yang Anda inginkan
                    $newProjectCoordinator->academic_year_id = $activeYear;
                
                    // Simpan rekaman baru
                    $newProjectCoordinator->save();
                });
            }
        }
    }
}