<?php

namespace App\Imports;

use App\Models\Guest;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;

class GuestsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            Guest::create([
                'name' => $row[0],
                'phone_number' => $row[1],
                'guest_type' => $row[2],
                'slug' => Str::slug($row[0]),
                'will_attend' => 0,
                'number_of_guests' => 0,
            ]);
        }
    }
}