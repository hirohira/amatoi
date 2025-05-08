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

    /**
     * 特定の軒といに適合する縦といの一覧を取得
     */
    public function filterTateToiByNokiToi(string $nokiToiCode): array
    {
        $combinations = $this->loadCombinationList();
        $validCodes = array_filter($combinations, function ($combo) use ($nokiToiCode) {
            return $combo->getNokiToiCode() === $nokiToiCode;
        });

        $validTateCodes = array_map(fn($combo) => $combo->getTateToiCode(), $validCodes);
        $tateToiList = $this->loadTateToiList();

        return array_filter($tateToiList, fn($tate) => in_array($tate->getTateToiCode(), $validTateCodes));
    }

    /**
     * 一致する軒といオブジェクトを取得
     */
    public function findNokiToi(string $code): ?NokiToi
    {
        foreach ($this->loadNokiToiList() as $item) {
            if ($item->getNokiToiCode() === $code) {
                return $item;
            }
        }
        return null;
    }

    /**
     * 一致する縦といオブジェクトを取得
     */
    public function findTateToi(string $code): ?TateToi
    {
        foreach ($this->loadTateToiList() as $item) {
            if ($item->getTateToiCode() === $code) {
                return $item;
            }
        }
        return null;
    }

    /**
     * 汎用 CSV ローダー
     */
    private function loadCsv(string $filename, string $class): array
    {
        $path = $this->basePath . $filename;
        if (!file_exists($path)) {
            return [];
        }

        $handle = fopen($path, 'r');
        if (!$handle) {
            return [];
        }

        $header = fgetcsv($handle);
        $list = [];

        while (($row = fgetcsv($handle)) !== false) {
            $assoc = array_combine($header, $row);
            $list[] = new $class($assoc);
        }

        fclose($handle);
        return $list;
    }
}
