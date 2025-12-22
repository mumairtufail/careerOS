<?php

namespace App\Observers;

use App\Models\JobActivity;
use App\Models\JobApplication;
use App\Models\JobStage;

class JobApplicationObserver
{
    /**
     * Handle the JobApplication "created" event.
     */
    public function created(JobApplication $jobApplication): void
    {
        JobActivity::create([
            'user_id' => $jobApplication->user_id,
            'job_application_id' => $jobApplication->id,
            'activity_type' => 'created',
            'description' => 'Application created',
            'metadata' => [
                'company' => $jobApplication->company_name,
                'role' => $jobApplication->job_title,
            ],
        ]);
    }

    /**
     * Handle the JobApplication "updated" event.
     */
    public function updated(JobApplication $jobApplication): void
    {
        if ($jobApplication->isDirty('job_stage_id')) {
            $oldStage = JobStage::find($jobApplication->getOriginal('job_stage_id'));
            $newStage = JobStage::find($jobApplication->job_stage_id);

            JobActivity::create([
                'user_id' => $jobApplication->user_id,
                'job_application_id' => $jobApplication->id,
                'activity_type' => 'stage_changed',
                'description' => "Moved from {$oldStage->name} to {$newStage->name}",
                'metadata' => [
                    'old_stage_id' => $oldStage->id,
                    'new_stage_id' => $newStage->id,
                    'old_stage_name' => $oldStage->name,
                    'new_stage_name' => $newStage->name,
                ],
            ]);
        }
    }

    /**
     * Handle the JobApplication "deleted" event.
     */
    public function deleted(JobApplication $jobApplication): void
    {
        //
    }

    /**
     * Handle the JobApplication "restored" event.
     */
    public function restored(JobApplication $jobApplication): void
    {
        //
    }

    /**
     * Handle the JobApplication "force deleted" event.
     */
    public function forceDeleted(JobApplication $jobApplication): void
    {
        //
    }
}
