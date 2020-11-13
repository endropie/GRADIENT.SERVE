<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory, Filterable;

    protected $guarded = [];

    public function item_serials ()
    {
        return $this->hasMany(\App\Models\ItemSerial::class);
    }

    public static function serial ($value)
    {
        return app(\App\Models\ItemSerial::class)->where('serial', $value)->first() ?? null;
    }
}
