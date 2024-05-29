<?php

namespace App\Services;

class CsvService
{
    /**
     * @param $filePath
     * @return array
     */
    public static function readCsv($filePath): array
    {
        $csvData = [];

        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, ',')) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        }

        // Shift the header from the first row
        $headers = array_shift($csvData);

        // Map the data to the new headers and return
        return array_map(function($row) use ($headers) {
            return array_combine($headers, $row);
        }, $csvData);
    }
}
