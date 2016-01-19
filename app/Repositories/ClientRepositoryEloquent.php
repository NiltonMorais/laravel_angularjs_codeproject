<?php

namespace CodeProject\Repositories;

use CodeProject\Entities\Client;
use Prettus\Repository\Eloquent\BaseRepository;

class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    public function model(){
        return Client::class;
    }


    public function validator()
    {
        return \CodeProject\Validators\ClientValidator::class;
    }

}