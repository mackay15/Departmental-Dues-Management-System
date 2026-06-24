<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToArray, WithHeadingRow
{
    protected array $rows = [];

    /**
     * Store the sheet rows in an array.
     *
     * @param array $array
     */
    public function array(array $array): void
    {
        $this->rows = $array;
    }

    /**
     * Get the parsed rows.
     *
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }
}
