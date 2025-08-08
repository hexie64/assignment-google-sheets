<?php

namespace App\Repository;

use App\Models\SheetRow;

class SheetDataRepository
{

    /**
     * @param $flags
     * @param $attributes
     * @return mixed
     */
    public function createOrUpdate($flags, $attributes): mixed
    {
        $sheet = SheetRow::updateOrCreate(
            $flags,
            $attributes
        );

        return $sheet;
    }

    /**
     * @param $attributes
     * @return SheetRow
     */
    public function store($attributes): SheetRow
    {
        $sheet = new SheetRow();
        $sheet->fill($attributes);
        $sheet->save();

        return $sheet;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $firstRow = SheetRow::firstOrFail();
        $fields = $firstRow->toArray();
        $data = $firstRow->data ?? [];

        unset($fields['data']);

        return [...array_keys($data), ...array_keys($fields)];
    }

    /**
     * @param $amount
     * @return array
     */
    public function getRows($amount): array
    {
        $sheets = SheetRow::all()->take($amount)->toArray();
        foreach($sheets as $index => $sheet) {
            $sheets[$index] = [... $sheet['data'], ...$sheet];
            unset($sheets[$index]['data']);
        }

        return $sheets;
    }
}
