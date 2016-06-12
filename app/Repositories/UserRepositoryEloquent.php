<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    protected $fieldSearchable = [
        'name'
    ];

    public function model(){
        return User::class;
    }

    public function boot()
    {
        $this->pushCriteria(app(\Prettus\Repository\Criteria\RequestCriteria::class));
    }

}