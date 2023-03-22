<?php

namespace App\Models\Traits;

use App\Models\V1\Admin;
use App\Models\V1\Image;
use App\Models\V1\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

trait FeeTrait
{
    public function getTotal()
    {
        return $this->total_fee = $this->generation +
            $this->transmission +
            $this->distribution +
            $this->commercialization +
            $this->lost +
            $this->restriction +
            $this->unit_cost;
    }
}
