<?php

namespace App\Models;

class CsvLoader
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = __DIR__ . '/../../data/';
    }

    public function loadNokiToiList(): array
    {
        return $this->loadCsv('nokitoi.csv', NokiToi::class);
    }

    public function loadTateToiList(): array
    {
        return $this->loadCsv('tatetoi.csv', TateToi::class);
    }

    public function loadCombinationList(): array
    {
        return $this->loadCsv('kumiawase.csv', NokiTateCombination::class);
    }

    public function loadTaniTateToiList(): array
    {
        return $this->loadCsv('tatetoi_tani.csv', TateToi::class);
    }

    public function filterTateToiByNokiToi(string $nokiToiCode): array
    {
        $combinations = $this->loadCombinationList();
        $validCodes = array_filter($combinations, fn($combo) => $combo->getNokiToiCode() === $nokiToiCode);
        $validTateCodes = array_map(fn($combo) => $combo->getTateToiCode(), $validCodes);
        $tateToiList = $this->loadTateToiList();
        return array_filter($tateToiList, fn($tate) => in_array($tate->getTateToiCode(), $validTateCodes));
    }

    public function findNokiToi(string $code): ?NokiToi
    {
        foreach ($this->loadNokiToiList() as $item) {
            if ($item->getNokiToiCode() === $code) {
                return $item;
            }
        }
        return null;
    }

    public function findTateToi(string $code): ?TateToi
    {
        foreach ($this->loadTateToiList() as $item) {
            if ($item->getTateToiCode() === $code) {
                return $item;
            }
        }
        return null;
    }

    private function loadCsv(string $filename, string $class): array
    {
        $path = $this->basePath . $filename;
        if (!file_exists($path)) return [];

        $handle = fopen($path, 'r');
        if (!$handle) return [];

        $header = fgetcsv($handle);

        $headerMap = [];
        if ($filename === 'tatetoi.csv' || $filename === 'tatetoi_tani.csv') {
            $headerMap = [
                'コード' => 'tateToiCode',
                'サイズ' => 'tateToiSize',
                '排水有効面積' => 'primeA_Original',
            ];
        } elseif ($filename === 'nokitoi.csv') {
            $headerMap = [
                'コード' => 'nokiToiCode',
                '品名' => 'nokiToiName',
                '断面積' => 'a_Original',
                '径深' => 'r',
                'ルートＲ' => 'sqrtR',
                '高さ' => 'h_Original',
            ];
        }

        $list = [];

        while (($row = fgetcsv($handle)) !== false) {
            $assoc = array_combine($header, $row);

            $converted = [];
            foreach ($assoc as $key => $value) {
                $mappedKey = $headerMap[$key] ?? $key;
                $converted[$mappedKey] = $value;
            }

            $list[] = new $class($converted);
        }

        fclose($handle);
        return $list;
    }
}
