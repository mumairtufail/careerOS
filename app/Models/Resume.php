<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resume extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'file_path',
        'parsed_content',
        'summary',
        'skills',
        'experience',
        'years_of_experience',
        'education',
        'projects',
        'certifications',
        'parse_status',
        'parse_error',
    ];

    protected $casts = [
        'skills' => 'array',
        'experience' => 'array',
        'education' => 'array',
        'projects' => 'array',
        'certifications' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for parse status
    public function isParsed(): bool
    {
        return $this->parse_status === 'success';
    }

    public function isPending(): bool
    {
        return $this->parse_status === 'pending';
    }

    public function hasFailed(): bool
    {
        return $this->parse_status === 'failed';
    }

    public function getStatusBadgeClass(): string
    {
        return match($this->parse_status) {
            'success' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }

    public function getStatusLabel(): string
    {
        return match($this->parse_status) {
            'success' => 'Successfully Parsed',
            'failed' => 'Parse Failed',
            'pending' => 'Pending Parse',
            default => 'Unknown',
        };
    }
}
