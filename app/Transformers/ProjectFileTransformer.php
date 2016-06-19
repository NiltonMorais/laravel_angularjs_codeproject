<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\ProjectFile;
use League\Fractal\TransformerAbstract;

class ProjectFileTransformer extends TransformerAbstract
{
    public function transform(ProjectFile $o)
    {
        return [
            'id' => $o->id,
            'project_id' => $o->project->id,
            'name' => $o->name,
            'extension' => $o->extension,
            'description' => $o->description,
        ];
    }

}