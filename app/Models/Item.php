<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function item_serials ()
    {
        return $this->hasMany(\App\Models\ItemSerial::class);
    }

    public static function serial ($value) : ItemSerial
    {
        return app(\App\Models\ItemSerial::class)->where('serial', $value)->first();
    }
}
