<?php


namespace App\Http\Services\V1\Admin\Invoicing\Report;

use App\Models\V1\Admin;
use App\Models\V1\BillableItem;
use App\Models\V1\Invoice;
use App\Models\V1\NetworkOperator;
use App\Models\V1\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class InvoiceReportData extends DefaultValueBinder implements ShouldAutoSize, WithStyles, FromCollection, WithHeadings, WithMapping, WithCustomValueBinder
{
    private $month;
    private $billableItemTypes = [BillableItem::DISTRIBUTION_ITEM,
        BillableItem::TRANSMISSION_ITEM,
        BillableItem::GENERATION_ITEM,
        BillableItem::LOST_ITEM,
        BillableItem::RESTRICTION_ITEM,
        BillableItem::COMMERCIALIZATION_ITEM,
        BillableItem::DISCOUNT_ITEM,
        BillableItem::CONTRIBUTION_ITEM,
        BillableItem::PUBLIC_TAX_ITEM,
        BillableItem::PUBLIC_TAX_TYPE_TOTAL,
        BillableItem::TOTAL_WITH_SUB,
        BillableItem::TOTAL_WITHOUT_SUB,
        BillableItem::TOTAL_CONSUMPTION_BASE,
        BillableItem::TOTAL_INVOICE];

    public function __construct($month)
    {
        $this->month = $month;
    }


    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);

            return true;
        }

        return parent::bindValue($cell, $value);
    }

    public function map($invoice): array
    {
        return array_merge([
            $invoice->id,
            $invoice->client->alias,
            $invoice->created_at,
            __("invoice." . $invoice->type),

        ], array_map(function ($billingItem) use ($invoice) {
                return $invoice->items()
                    ->whereBillableItemId(BillableItem::whereSlug($billingItem)->first()->id)
                    ->first() ? $invoice->items()
                    ->whereBillableItemId(BillableItem::whereSlug($billingItem)->first()->id)
                    ->first()->total : 0.0;
            },
                $this->billableItemTypes)
        );
    }

    public function headings(): array
    {
        return array_merge([
            'Identificador',
            'Cliente',
            'Fecha de generacion',
            "Tipo de factura"
        ], array_map(function ($billingItem) {
            return BillableItem::whereSlug($billingItem)->first()->name;
        }, $this->billableItemTypes));
    }

    function collection()
    {

        $model = User::getUserModel();
        if ($model::class == NetworkOperator::class) {
            $admin_id = $model->admin_id;
            $invoicesQuery = Invoice::whereType(Invoice::TYPE_CONSUMPTION)
                ->whereAdminId($admin_id)
                ->orderBy("created_at", "desc");
        }
        if ($model::class == Admin::class) {
            $invoicesQuery = Invoice::whereType(Invoice::TYPE_CONSUMPTION)
                ->whereAdminId($model->id)
                ->orderBy("created_at", "desc");
        }

        return $invoicesQuery->whereMonth("created_at", $this->month)->get();
    }


}
