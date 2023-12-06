<?php

namespace App\Observers;

use App\Models\ProjectStudent;
use App\Models\ProjectTarget;

class ProjectTargetObserver
{
    /**
     * Handle the ProjectTarget "created" event.
     */
    public function created(ProjectTarget $projectTarget): void
    {
        $students = $projectTarget->project->grade->studentGrade;

        $data = [];

        foreach ($students as $student) {
            $data[] = [
                'student_id' => $student->id,
                'project_target_id' => $projectTarget->id,
            ]; 
        }

        ProjectStudent::insert($data);
    }

    /**
     * Handle the ProjectTarget "updated" event.
     */
    public function updated(ProjectTarget $projectTarget): void
    {
        //
    }

    /**
     * Handle the ProjectTarget "deleted" event.
     */
    public function deleted(ProjectTarget $projectTarget): void
    {
        //
    }

    /**
     * Handle the ProjectTarget "restored" event.
     */
    public function restored(ProjectTarget $projectTarget): void
    {
        //
    }

    /**
     * Handle the ProjectTarget "force deleted" event.
     */
    public function forceDeleted(ProjectTarget $projectTarget): void
    {
        //
    }
}
