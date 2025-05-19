<?php

namespace App\Imports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VehiclesImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Vehicle([
            'name' => $row['name'],
            'type' => $row['type'], 
            'license_plate' => $row['license_plate'], 
        ]);
    }
}
