<?php

namespace App\Http\Resources;

use App\Http\Resources\Resource;

class LoadingResource extends Resource
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
            'loading_items' => [LoadingItemResource::class, $this->loading_items, true]
        ];
    }
}
