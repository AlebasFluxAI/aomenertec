<?php

namespace App\Exports\V1;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultipleSheetsMonitoringData implements WithMultipleSheets
{
    use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $arrays;

    public function __construct(array $array)
    {
        $this->arrays = $array;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets_titles = ["Reporte de Variables", "Matriz_activa", "Matriz_inductiva", "Matriz_capacitiva", "Inductiva_penalizable", "Capacitiva_penalizable"];

        foreach ($this->arrays as $index => $sheet) {
            $sheets[] = new MonitoringDataExport($sheet, $sheets_titles[$index]);
        }

        return $sheets;
    }
}
