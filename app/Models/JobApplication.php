<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'job_stage_id', 'company_name', 'job_title', 
        'job_url', 'location', 'work_mode', 'salary_min', 
        'salary_max', 'applied_at', 'notes'
    ];

    protected $casts = [
        'applied_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stage()
    {
        return $this->belongsTo(JobStage::class, 'job_stage_id');
    }

    public function activities()
    {
        return $this->hasMany(JobActivity::class)->orderBy('created_at', 'desc');
    }
}
