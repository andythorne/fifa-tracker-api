<?php

namespace App\Import;

class CsvProcessor
{
    public function readLine($file)
    {
        if (($handle = fopen($file, 'r')) !== false) {
            $row = 0;
            $headers = [];

            while (($data = fgetcsv($handle, 5000, ',')) !== false) {
                ++$row;
                if ($row === 1) {
                    $headers = $data;
                    continue;
                }

                yield array_combine($headers, $data);
            }
            fclose($handle);
        }
    }
}
