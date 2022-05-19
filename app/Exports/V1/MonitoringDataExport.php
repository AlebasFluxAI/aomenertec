<?php

namespace App\Exports\V1;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class MonitoringDataExport implements FromArray, WithStrictNullComparison
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function array(): array
    {
        return $this->data;
    }
}
