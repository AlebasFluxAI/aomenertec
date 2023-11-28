<?php

namespace App\Models\Traits;

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
}
