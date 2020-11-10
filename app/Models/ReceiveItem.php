<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReceiveItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function receive ()
    {
        return $this->belongsTo(\App\Models\Receive::class);
    }

    public function item_serial ()
    {
        return $this->belongsTo(\App\Models\ItemSerial::class, 'serial', 'serial');
    }

    public function item ()
    {
        return $this->belongsTo(\App\Models\Item::class);
    }
}
