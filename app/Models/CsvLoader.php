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

    public function filterTateToiByNokiToi(string $nokiToiCode): array
    {
        $combinations = $this->loadCombinationList();
        $validCodes = array_filter($combinations, fn($combo) => $combo->getNokiToiCode() === $nokiToiCode);
        $validTateCodes = array_map(fn($combo) => $combo->getTateToiCode(), $validCodes);
        $tateToiList = $this->loadTateToiList();
        return array_filter($tateToiList, fn($tate) => in_array($tate->getTateToiCode(), $validTateCodes));
    }

    private function loadCsv(string $filename, string $class): array
    {
        $path = $this->basePath . $filename;
        if (!file_exists($path)) return [];

        $handle = fopen($path, 'r');
        if (!$handle) return [];

        $header = fgetcsv($handle);
        $list = [];
        while (($row = fgetcsv($handle)) !== false) {
            if (count($header) !== count($row)) continue;
            $assoc = array_combine($header, $row);
            $list[] = new $class($assoc);
        }
        fclose($handle);
        return $list;
    }
}
