<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public static $wrap = null;
    protected $prefix = '';
    protected $fields = [];
    protected $merges = [];
    protected $hidden = [];

    protected function property ($merges = [])
    {
        $this->merges = $merges;
        $attributes = $this->getAttributeProperties();
        $relations = $this->getRelationProperties();

        return array_merge($attributes, $relations);
    }

    protected function includes ()
    {
        return [];
    }

    protected function getAttributeProperties ()
    {
        $results = array_merge($this->resource->getAttributes(), $this->merges);
        $fields = !empty($this->fields) ? array_flip($this->fields) : $this->resource->getAttributes();
        $fixkey = strlen($this->prefix)
            ? \Str::of($this->prefix)->replace(".","_")->append("_fields")
            : "fields";

        if ($req = request()->get($fixkey)) {
            $params = array_flip(explode(',', $req));
            $all = array_key_exists('*', $params)
                ? array_merge($this->resource->getAttributes(), array_flip($this->fields))
                : [];

            $fields = array_merge($params, $all??[]);
        }

        if (!empty($this->hidden)) {

        }

        return array_intersect_key($results, $fields);
    }

    protected function getRelationProperties ()
    {
        $relationProperties = [];

        if ($includes = request("includes"))
        {
            $includes = explode(",", $includes);


            foreach ($includes as $include) {
                // if ($include == 'item') dd($this);
                $name = \Str::of($include)->replaceFirst($this->prefix, "");
                if ($name->startsWith(".")) $name = $name->replaceFirst(".", "");

                // if($name == 'item') dd('item', request()->all());
                $name = explode(".", $name)[0];

                if (!empty($this->includes()[$name]))
                {
                    $class = $this->includes()[$name][0];
                    $model = $this->includes()[$name][1];
                    $isCollect = !empty($this->includes()[$name][2]) && $this->includes()[$name][2];
                    if ($isCollect) {
                        $relationProperties[$name] = $class::collection($model)->each->prefix($name, $this->prefix);
                    }
                    else {
                        $relationProperties[$name] = new $class($model);
                        $relationProperties[$name]->prefix($name, $this->prefix);
                    }
                }
            }

        }

        return $relationProperties;
    }

    public function prefix ($prefix, $parent)
    {
        $this->prefix = ($parent ? $parent."." : "") . $prefix;
    }
}
