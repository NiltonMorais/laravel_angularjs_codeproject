<?php

namespace CodeProject\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ProjectMember extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'project_id',
        'member_id',
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function member(){
        return $this->belongsTo(User::class);
    }
}
