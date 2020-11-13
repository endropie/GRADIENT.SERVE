<?php
namespace App\Filters;

use App\Filters\Filter;
use Illuminate\Http\Request;

class ItemFilter extends Filter
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }
}
