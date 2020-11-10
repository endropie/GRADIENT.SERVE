<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemSerial extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function item ()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }
}
