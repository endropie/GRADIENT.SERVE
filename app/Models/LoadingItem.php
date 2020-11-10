<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoadingItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function loading ()
    {
        return $this->belongsTo(\App\Models\Loading::class);
    }

    public function item_serial ()
    {
        return $this->belongsTo(\App\Models\ItemSerial::class, 'serial', 'serial')->withTrashed();
    }

    public function item ()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }
}
