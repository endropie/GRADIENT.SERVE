<?php

namespace App\Http\Resources;

use App\Http\Resources\Resource;

class ReceiveResource extends Resource
{
    protected $fields = ['id', 'reference'];

    public function toArray ($request)
    {
        return $this->property([
            'reference' => $this->reference
        ]);
    }

    protected function includes ()
    {
        return [
            'receive_items' => [ReceiveItemResource::class, $this->receive_items, true]
        ];
    }
}
