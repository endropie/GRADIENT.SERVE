<?php

namespace App\Http\Resources;

use App\Http\Resources\Resource;

class ItemResource extends Resource
{
    protected $fields = ['id', 'name'];

    public function toArray ($request)
    {
        return $this->property();
    }

    protected function relations ()
    {
        return [
            'item_serials' => [],
        ];
    }
}
