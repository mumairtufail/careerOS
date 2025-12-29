<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeJobMatch extends Model
{
    protected $fillable = [
        'resume_id',
        'job_application_id',
        'match_score',
        'strengths',
        'gaps',
        'ai_feedback',
        'ai_provider',
    ];

    protected $casts = [
        'strengths' => 'array',
        'gaps' => 'array',
        'match_score' => 'integer',
    ];

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function jobApplication(): BelongsTo
    {
        return $this->belongsTo(JobApplication::class);
    }

    /**
     * Get match score category
     */
    public function getScoreCategory(): string
    {
        return match(true) {
            $this->match_score >= 80 => 'excellent',
            $this->match_score >= 60 => 'good',
            $this->match_score >= 40 => 'fair',
            default => 'poor',
        };
    }

    /**
     * Get color class for score
     */
    public function getScoreColorClass(): string
    {
        return match($this->getScoreCategory()) {
            'excellent' => 'text-green-600 dark:text-green-400',
            'good' => 'text-blue-600 dark:text-blue-400',
            'fair' => 'text-yellow-600 dark:text-yellow-400',
            'poor' => 'text-red-600 dark:text-red-400',
        };
    }

    /**
     * Get background color class for score
     */
    public function getScoreBgClass(): string
    {
        return match($this->getScoreCategory()) {
            'excellent' => 'bg-green-100 dark:bg-green-900/30',
            'good' => 'bg-blue-100 dark:bg-blue-900/30',
            'fair' => 'bg-yellow-100 dark:bg-yellow-900/30',
            'poor' => 'bg-red-100 dark:bg-red-900/30',
        };
    }
}
