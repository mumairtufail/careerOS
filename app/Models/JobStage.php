<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobStage extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'slug', 'sort_order', 'is_system_default'];

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
