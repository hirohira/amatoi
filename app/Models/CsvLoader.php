<?php
declare(strict_types=1);

namespace App\Models;

use RuntimeException;

class CsvLoader
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = __DIR__ . '/../data/';
    }

    /**
     * 軒樋一覧
     * @return NokiToi[]
     */
    public function loadNokiToiList(): array
    {
        $file = $this->basePath . 'nokitoi.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            if (count($row) < 6) continue;
            $n = new NokiToi();
            $n->setNokiToiCode(trim($row[0]));
            $n->setNokiToiName(trim($row[1]));
            $origA = (float)$row[2]; // m²
            $n->setA_Original($origA);
            $n->setA($origA);
            $n->setR((float)$row[3]);
            $n->setSqrtR((float)$row[4]);
            $origH = (float)$row[5]; // cm
            $n->setH_Original($origH);
            $n->setH($origH / 100.0); // m
            $list[] = $n;
        }
        return $list;
    }

    /**
     * 縦樋一覧（標準モード用）
     * @return TateToi[]
     */
    public function loadTateToiList(): array
    {
        $file = $this->basePath . 'tatetoi.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            if (count($row) < 3) continue;
            $t = new TateToi();
            $t->setTateToiCode(trim($row[0]));
            $t->setTateToiSize(trim($row[1]));
            $origA = (float)$row[2]; // cm²
            $t->setPrimeA_Original($origA);
            $t->setPrimeA($origA / 10000.0); // m²
            $list[] = $t;
        }
        return $list;
    }

    /**
     * 縦樋一覧（谷コイルモード用）
     * @return TateToi[]
     */
    public function loadTateToiTaniList(): array
    {
        $file = $this->basePath . 'tatetoi_tani.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            if (count($row) < 3) continue;
            $t = new TateToi();
            $t->setTateToiCode(trim($row[0]));
            $t->setTateToiSize(trim($row[1]));
            $origA = (float)$row[2];
            $t->setPrimeA_Original($origA);
            $t->setPrimeA($origA / 10000.0);
            $list[] = $t;
        }
        return $list;
    }

    /**
     * 軒樋–縦樋組み合わせ
     * @return NokiTateCombination[]
     */
    public function loadNokiTateCombinations(): array
    {
        $file = $this->basePath . 'kumiawase.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            if (count($row) < 4) continue;
            $c = new NokiTateCombination();
            $c->setNokiToiCode(trim($row[0]));
            $c->setNokiToiName(trim($row[1]));
            $c->setTateToiSize(trim($row[2]));
            $c->setTateToiCode(trim($row[3]));
            $list[] = $c;
        }
        return $list;
    }

    /**
     * CSV → 2次元配列 (ヘッダー行スキップ)
     * @param string $path
     * @return array<int,string[]>
     * @throws RuntimeException
     */
    private function readCsv(string $path): array
    {
        if (!file_exists($path)) {
            throw new RuntimeException("CSVファイルが見つかりません: {$path}");
        }
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new RuntimeException("CSVを開けません: {$path}");
        }
        $data  = [];
        $first = true;
        while (($row = fgetcsv($handle)) !== false) {
            if ($first) { $first = false; continue; }
            $data[] = $row;
        }
        fclose($handle);
        return $data;
    }
}
