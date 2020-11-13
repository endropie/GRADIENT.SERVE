<?php
Namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Filters\Filter;

trait Filterable
{
    protected function scopeFilter($query, Filter $filters)
    {
        return $filters->apply($query);
    }

    protected function scopePagetable($query)
    {
        $perPage = request('pagetable.rowsPerPage', 25);
        $columns = ['*'];
        $pageName = 'page';
        $page = request('pagetable.page', 1);

        if($perPage == "all") $perPage = $query->count();

        return $query->paginate($perPage, $columns, $pageName, $page);
    }

    protected function scopeLimitable($query)
    {
        $limit = request('limitable.limit', 25);
        $offset = request('limitable.offset', 0);

        return $query->offset($offset)->limit($limit)->get();
    }
}
