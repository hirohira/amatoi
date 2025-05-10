<?php
namespace App\Models;

class CsvLoader
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = __DIR__ . '/../../data/';
    }

    private function loadCsv(string $filename): array
    {
        $path = $this->basePath . $filename;
        $rows = [];

        if (!file_exists($path)) {
            return $rows;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return $rows;
        }

        $header = null;
        while (($data = fgetcsv($handle)) !== false) {
            if (!$header) {
                $header = $data;
                continue;
            }
            $row = [];
            foreach ($data as $i => $value) {
                $row[$header[$i]] = $value;
            }
            $rows[] = $row;
        }

        fclose($handle);
        return $rows;
    }

    public function loadNokiToiList(): array
    {
        $rows = $this->loadCsv('nokitoi.csv');
        $list = [];
        foreach ($rows as $row) {
            $list[] = new NokiToi($row);
        }
        return $list;
    }

    public function loadTateToiList(): array
    {
        $rows = $this->loadCsv('tatetoi.csv');
        $list = [];
        foreach ($rows as $row) {
            $list[] = new TateToi($row);
        }
        return $list;
    }

    public function loadCombinationList(): array
    {
        return $this->loadCsv('kumiawase.csv');
    }

    public function findNokiToi(string $code): ?NokiToi
    {
        foreach ($this->loadNokiToiList() as $noki) {
            if ($noki->getNokiToiCode() === $code) {
                return $noki;
            }
        }
        return null;
    }

    public function findTateToi(string $code): ?TateToi
    {
        foreach ($this->loadTateToiList() as $tate) {
            if ($tate->getTateToiCode() === $code) {
                return $tate;
            }
        }
        return null;
    }

    public function filterTateToiByNokiToi(string $nokiCode): array
    {
        $combinations = $this->loadCombinationList();
        $allowedCodes = [];

        foreach ($combinations as $combo) {
            if (isset($combo['軒といコード']) && $combo['軒といコード'] === $nokiCode) {
                $allowedCodes[] = $combo['竪といコード'];
            }
        }

        $filtered = [];
        foreach ($this->loadTateToiList() as $tate) {
            if (in_array($tate->getTateToiCode(), $allowedCodes, true)) {
                $filtered[] = $tate;
            }
        }

        return $filtered;
    }
}
