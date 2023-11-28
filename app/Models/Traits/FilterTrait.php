<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{
    public $filter;
    public $filterCol;
    public $filterCustom;

    public function cleanFilter()
    {
        $this->filterCol = null;
        $this->filter = null;
    }

    public function setFilterCol($filterCol)
    {
        $this->filterCol = $filterCol;
    }

    public function setFilterCustom($filterCustom)
    {
        $this->filterCustom = $filterCustom;
    }

    private function applyFilter(Builder $query, $component)
    {
        if (!$component->filter) {
            return;
        }
        $explode_filter = explode(".", $component->filterCol);
        if (count($explode_filter) > 1) {
            $attribute_name = $explode_filter[0];
            $deep_filter = $explode_filter[1];
            $query->whereHas($attribute_name, function ($query) use ($component, $deep_filter) {
                $query->where($deep_filter, 'ilike', '%' . $component->filter . '%');
            });
            return;
        }
        if (!in_array($component->filterCol, $query->getModel()->getFillable())) {
            $this->{"customFilter_" . $component->filterCol}($query, $component);
            return;
        }
        $query->where($component->filterCol, 'ilike', '%' . $component->filter . '%');
    }
}
