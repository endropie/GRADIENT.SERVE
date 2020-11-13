<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receive extends Model
{
    use SoftDeletes, Filterable;

    protected $guarded = [];

    protected $appends = ['reference'];

    public function receive_items ()
    {
        return $this->hasMany(\App\Models\ReceiveItem::class);
    }

    public function getReferenceAttribute ()
    {
        return (string) $this->reference_number . "(". $this->reference_batch .")";
    }
}
