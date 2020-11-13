<?php

namespace App\Http\Resources;

use App\Http\Resources\Resource;

class LoadingItemResource extends Resource
{
    protected $fields = ['id', 'serial', 'notes'];

    public function toArray ($request)
    {
        return $this->property();
    }

    protected function includes ()
    {
        return [
            'item' => [ItemResource::class, $this->item]
        ];
    }
}
