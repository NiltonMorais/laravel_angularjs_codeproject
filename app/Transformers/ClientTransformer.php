<?php

namespace CodeProject\Transformers;

use CodeProject\Entities\Client;
use League\Fractal\TransformerAbstract;

class ClientTransformer extends TransformerAbstract
{
    public function transform(Client $o)
    {
        return [
            'id' => (int)$o->id,
            'name' => $o->name,
            'responsible' => $o->responsible,
            'email' => $o->email,
            'phone' => $o->phone,
            'address' => $o->address,
            'obs' => $o->obs,
        ];
    }

}