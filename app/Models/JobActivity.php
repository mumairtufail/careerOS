<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobActivity extends Model
{
    protected $fillable = ['user_id', 'job_application_id', 'activity_type', 'description', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }
}
