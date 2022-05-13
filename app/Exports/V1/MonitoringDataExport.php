<?php

namespace App\Exports\V1;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class MonitoringDataExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('export.v1.data_monitoring_export', [
            'data' => $this->data
        ]);
    }
}
