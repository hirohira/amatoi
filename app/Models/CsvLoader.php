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
        $file = $this->basePath . 'kumiawase.csv';
        $combinations = [];

        if (!file_exists($file)) return $combinations;

        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) !== 4) continue; // 4列でない行はスキップ
                $combinations[] = new NokiTateCombination([
                    'nokiToiCode' => trim($data[0]),
                    'nokiToiName' => trim($data[1]),
                    'tateToiName' => trim($data[2]),
                    'tateToiCode' => trim($data[3])
                ]);
            }
            fclose($handle);
        }

        return $combinations;
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

        $headerMap = [
            'コード' => 'nokiToiCode',
            '品名' => 'nokiToiName',
            '断面積' => 'a_Original',
            '径深' => 'r',
            'ルートＲ' => 'sqrtR',
            '軒樋潤辺高さ' => 'h',
            'サイズ' => 'tateToiSize',
            '排水有効面積' => 'primeA_Original',
        ];

        $mappedHeader = array_map(fn($h) => $headerMap[$h] ?? $h, $header);
        $list = [];

        while (($row = fgetcsv($handle)) !== false) {
            $assoc = array_combine($mappedHeader, $row);
            $list[] = new $class($assoc);
        }

        fclose($handle);
        return $list;
    }
}
