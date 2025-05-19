<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Order::with(['vehicle', 'requester', 'driver'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Vehicle',
            'Requester',
            'Driver',
            'Start Date',
            'End Date',
            'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->id,
            $order->vehicle->name ?? '-',
            $order->requester->name ?? '-',
            $order->driver->name ?? '-',
            $order->start_date,
            $order->end_date,
            ucfirst($order->status),
        ];
    }
}
