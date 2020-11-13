<?php

namespace App\Models;

use App\Traits\Filterable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loading extends Model
{
    use SoftDeletes, Filterable;

    protected $guarded = [];

    protected $appends = ['reference'];

    public function loading_items ()
    {
        return $this->hasMany(\App\Models\LoadingItem::class);
    }

    public function getReferenceAttribute ()
    {
        return (string) $this->reference_via . "/" . date("m.d", strtotime($this->reference_date)). "(". $this->reference_batch .")";
    }
}
