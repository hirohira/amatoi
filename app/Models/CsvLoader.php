<?php
// amatoi/app/Models/CsvLoader.php
declare(strict_types=1);

namespace App\Models;

use RuntimeException;

class CsvLoader
{
    /** CSV ファイルのベースディレクトリ */
    private string $basePath;

    public function __construct()
    {
        $this->basePath = __DIR__ . '/../data/';
    }

    /**
     * 軒樋一覧を読み込む
     * @return NokiToi[]
     */
    public function loadNokiToiList(): array
    {
        $file = $this->basePath . 'nokitoi.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            // 0:コード, 1:品名, 2:断面積(m²), 3:径深, 4:ルートR, 5:潤辺高さ(cm)
            if (count($row) < 6) {
                continue;
            }
            $n = new NokiToi();
            $n->setNokiToiCode(trim($row[0]));
            $n->setNokiToiName(trim($row[1]));
            $origA = (float)$row[2];
            $n->setA_Original($origA);
            $n->setA($origA);
            $n->setR((float)$row[3]);
            $n->setSqrtR((float)$row[4]);
            $origH = (float)$row[5];
            $n->setH_Original($origH);
            $n->setH($origH / 100.0); // cm → m
            $list[] = $n;
        }

        return $list;
    }

    /**
     * 縦樋一覧を読み込む
     * @return TateToi[]
     */
    public function loadTateToiList(): array
    {
        $file = $this->basePath . 'tatetoi.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            // 0:コード, 1:サイズ, 2:排水有効面積(cm²)
            if (count($row) < 3) {
                continue;
            }
            $t = new TateToi();
            $t->setTateToiCode(trim($row[0]));
            $t->setTateToiSize(trim($row[1]));
            $origA = (float)$row[2];
            $t->setPrimeA_Original($origA);
            $t->setPrimeA($origA / 10000.0); // cm² → m²
            $list[] = $t;
        }

        return $list;
    }

    /**
     * 谷コイル用 縦樋一覧を読み込む
     * @return TateToi[]
     */
    public function loadTateToiTaniList(): array
    {
        $file = $this->basePath . 'tatetoi_tani.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            // 0:コード, 1:サイズ, 2:排水有効面積(cm²)
            if (count($row) < 3) {
                continue;
            }
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
     * 軒樋–縦樋組み合わせ一覧を読み込む
     * @return NokiTateCombination[]
     */
    public function loadNokiTateCombinations(): array
    {
        $file = $this->basePath . 'kumiawase.csv';
        $rows = $this->readCsv($file);

        $list = [];
        foreach ($rows as $row) {
            // 0:軒樋コード, 1:軒樋名, 2:縦樋サイズ, 3:縦樋コード
            if (count($row) < 4) {
                continue;
            }
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
     * CSV ファイルを読み込んで配列に変換する
     * ヘッダー行をスキップして返す
     *
     * @param string $path
     * @return array<int, string[]>
     * @throws RuntimeException
     */
    private function readCsv(string $path): array
    {
        if (!file_exists($path)) {
            throw new RuntimeException("CSVファイルが見つかりません: {$path}");
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new RuntimeException("CSVファイルを開けません: {$path}");
        }

        $data  = [];
        $first = true;
        while (($row = fgetcsv($handle)) !== false) {
            if ($first) {
                $first = false;
                continue; // ヘッダー行をスキップ
            }
            $data[] = $row;
        }
        fclose($handle);

        return $data;
    }
}
