<?php

declare(strict_types=1);

namespace App;

use League\Csv\Writer;
use SplTempFileObject;

class CsvExporter
{
    public function export(array $items): string
    {
        if (empty($items)) {
            return '';
        }

        foreach ($items as $key => $item) {
            if (isset($item['password'])) {
                unset($item['password']);
            }

            $items[$key] = $item;
        }

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne(array_keys($items[0]));
        $csv->insertAll($items);

        return $csv->getContent();
    }
}
