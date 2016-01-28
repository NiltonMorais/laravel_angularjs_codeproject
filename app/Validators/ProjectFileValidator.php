<?php
namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectFileValidator extends LaravelValidator
{
    protected $rules = [
        'file' => 'required',
        'name' => 'required|max:255',
        'description' => 'required|max:255',
        'project_id' => 'required|integer',
    ];

}