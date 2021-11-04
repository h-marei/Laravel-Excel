<?php

namespace Maatwebsite\Excel\Imports;

use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithLimit;

class EndRowFinder
{
    /**
     * @param  object|WithLimit  $import
     * @param  int  $startRow
     * @param  int|null  $highestRow
     * @return int|null
     */
    public static function find($import, int $startRow = null, int $highestRow = null)
    {
        if (!$import instanceof WithLimit) {
            return null;
        }

        $limit = $import->limit();

        if ($limit > $highestRow) {
            return null;
        }

        // When no start row given,
        // use the first row as start row.
        // Subtract 1 row from the start row, so a limit
        // of 1 row, will have the same start and end row.
        $startRow = ($startRow ?? 1) - 1;

        if ($import instanceof WithChunkReading) {
            $chunkSize = $import->chunkSize();

            $limit = ($startRow + $chunkSize) > $highestRow
                ? $highestRow - $startRow
                : $chunkSize;
        }

        return $startRow + $limit;
    }
}
