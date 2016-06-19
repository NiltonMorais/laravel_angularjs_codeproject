<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectTask;
use League\Fractal\TransformerAbstract;

class ProjectTaskTransformer extends TransformerAbstract
{
    public function transform(ProjectTask $o)
    {
        return [
            'id' => $o->id,
            'name' => $o->name,
            'start_date' => $o->start_date,
            'due_date' => $o->due_date,
            'status' => $o->status,
        ];
    }

}