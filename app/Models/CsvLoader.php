<?php
// amatoi/app/Models/CsvLoader.php
declare(strict_types=1);

namespace App\Models;

class CsvLoader
{
    /** CSV ファイル置き場のディレクトリ */
    private string $baseDir;

    public function __construct()
    {
        // __DIR__ = amatoi/app/Models
        // CSV は amatoi/data に置いてある想定
        $this->baseDir = __DIR__ . '/../../data/';
    }

    /**
     * 標準モード用：軒とい一覧を取得
     * CSV の断面積欄は m² 単位
     *
     * @return NokiToi[]
     */
    public function loadNokiToiList(): array
    {
        $rows = $this->readCsv('nokitoi.csv');
        $out  = [];
        foreach ($rows as $r) {
            $obj = new NokiToi();
            $obj->setNokiToiCode(trim($r[0]));
            $obj->setNokiToiName(trim($r[1]));
            // CSV の 3 列目は m²
            $obj->setA_Original((float)$r[2]);  // m²
            $obj->setA((float)$r[2]);           // m²
            $obj->setR((float)$r[3]);           // m
            $obj->setSqrtR((float)$r[4]);       // √m
            // CSV の 6 列目は cm 単位の深さ
            $obj->setH_Original((float)$r[5]);  
            $obj->setH((float)$r[5] / 100.0);   // m
            $out[] = $obj;
        }
        return $out;
    }

    /**
     * 標準モード用：縦とい一覧を取得
     * CSV の排水有効面積欄は cm²
     *
     * @return TateToi[]
     */
    public function loadTateToiList(): array
    {
        $rows = $this->readCsv('tatetoi.csv');
        $out  = [];
        foreach ($rows as $r) {
            $obj = new TateToi();
            $obj->setTateToiCode(trim($r[0]));
            $obj->setTateToiSize(trim($r[1]));
            // CSV の 3 列目は cm² → m² に直すのは計算時のみ
            $obj->setPrimeA_Original((float)$r[2]);      // cm²
            $obj->setPrimeA((float)$r[2] / 10000.0);     // m²
            $out[] = $obj;
        }
        return $out;
    }

    /**
     * 谷コイルモード用：縦とい一覧を取得
     * tatetoi_tani.csv の排水有効面積も cm²
     *
     * @return TateToi[]
     */
    public function loadTateToiTaniList(): array
    {
        $rows = $this->readCsv('tatetoi_tani.csv');
        $out  = [];
        foreach ($rows as $r) {
            $obj = new TateToi();
            $obj->setTateToiCode(trim($r[0]));
            $obj->setTateToiSize(trim($r[1]));
            $obj->setPrimeA_Original((float)$r[2]);      // cm²
            $obj->setPrimeA((float)$r[2] / 10000.0);     // m²
            $out[] = $obj;
        }
        return $out;
    }

    /**
     * 標準モード用：軒–縦組み合わせ一覧を取得
     *
     * @return NokiTateCombination[]
     */
    public function loadNokiTateCombinations(): array
    {
        $rows = $this->readCsv('kumiawase.csv');
        $out  = [];
        foreach ($rows as $r) {
            $obj = new NokiTateCombination();
            $obj->setNokiToiCode(trim($r[0]));
            // $r[1] = 軒とい名称 (使わない)
            // $r[2] = 縦といサイズ (使わない)
            $obj->setTateToiCode(trim($r[3]));
            $out[] = $obj;
        }
        return $out;
    }

    /**
     * CSV を読み込んで 2次元配列で返す
     *
     * @param string $file csv ファイル名
     * @return array<int,array<string>>
     */
    private function readCsv(string $file): array
    {
        $path = $this->baseDir . $file;
        if (! file_exists($path)) {
            throw new \RuntimeException("CSV not found: $path");
        }
        $handle = fopen($path, 'r');
        if ($handle === false) {
            throw new \RuntimeException("Failed to open CSV: $path");
        }
        $out   = [];
        $first = true;
        while (($row = fgetcsv($handle)) !== false) {
            if ($first) {
                $first = false;
                continue; // ヘッダ行スキップ
            }
            $out[] = $row;
        }
        fclose($handle);
        return $out;
    }

    /**
     * コード文字列から該当軒といオブジェクトを返す
     * 見つからなければデフォルト初期化済みオブジェクト
     */
    public function findNokiToi(string $code): NokiToi
    {
        foreach ($this->loadNokiToiList() as $n) {
            if ($n->getNokiToiCode() === $code) {
                return $n;
            }
        }
        return new NokiToi();
    }

    /**
     * コード文字列から該当縦といオブジェクトを返す（標準モード用）
     */
    public function findTateToi(string $code): TateToi
    {
        foreach ($this->loadTateToiList() as $t) {
            if ($t->getTateToiCode() === $code) {
                return $t;
            }
        }
        return new TateToi();
    }

    /**
     * コード文字列から該当縦といオブジェクトを返す（谷コイルモード用）
     */
    public function findTateToiTani(string $code): TateToi
    {
        foreach ($this->loadTateToiTaniList() as $t) {
            if ($t->getTateToiCode() === $code) {
                return $t;
            }
        }
        return new TateToi();
    }
}
