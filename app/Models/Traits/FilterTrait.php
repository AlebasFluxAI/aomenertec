<?php

namespace App\Models\Traits;

trait FilterTrait
{
    public $filter;
    public $filterCol;

    public function cleanFilter()
    {
        $this->filterCol = null;
        $this->filter = null;
    }

    public function setFilterCol($filterCol)
    {
        $this->filterCol = $filterCol;
    }
}
