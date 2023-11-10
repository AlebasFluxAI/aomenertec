<?php

namespace App\Models\Traits;

trait FeeTrait
{
    public function getTotal()
    {
        return $this->generation +
            $this->transmission +
            $this->distribution +
            $this->commercialization +
            $this->lost +
            $this->restriction;

    }
}
