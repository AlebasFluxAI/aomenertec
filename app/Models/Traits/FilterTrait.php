<?php

namespace App\Models\Traits;

use App\Models\V1\Image;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Request;

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
