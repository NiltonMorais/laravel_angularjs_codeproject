<?php
namespace CodeProject\Validators;

use Prettus\Validator\LaravelValidator;

class ProjectValidator extends LaravelValidator
{
    protected $rules = [
        'owner_id' => 'required|integer',
        'client_id' => 'required|integer',
        'name' => 'required|max:255',
        'description' => 'max:255',
        'status' => 'required|integer|between:1,5',
        'progress' => 'required|integer|between:0,100',
        'due_date' => 'required|date',
    ];

}