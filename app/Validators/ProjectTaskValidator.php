<?php
namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectTaskValidator extends LaravelValidator
{
    protected $rules = [
        'name' => 'required|max:255',
        'start_date' => 'date',
        'due_date' => 'date',
        'status' => 'integer',
    ];

}