<?php
namespace App\Models;

class TateToi
{
    // 文字列型プロパティにもデフォルトを
    private string $tateToiCode     = '';
    private string $tateToiSize     = '';
    // 数値型プロパティはすべて 0.0 で初期化
    private float  $primeA_Original = 0.0;  // CSV 元の cm²
    private float  $primeA          = 0.0;  // m²

    // コード／サイズ
    public function getTateToiCode(): string
    {
        return $this->tateToiCode;
    }
    public function setTateToiCode(string $code): void
    {
        $this->tateToiCode = $code;
    }

    public function getTateToiSize(): string
    {
        return $this->tateToiSize;
    }
    public function setTateToiSize(string $size): void
    {
        $this->tateToiSize = $size;
    }

    // 元データの cm²
    public function getPrimeA_Original(): float
    {
        return $this->primeA_Original;
    }
    public function setPrimeA_Original(float $areaCm2): void
    {
        $this->primeA_Original = $areaCm2;
    }

    // 計算用の m²
    public function getPrimeA(): float
    {
        return $this->primeA / 10000; // cm² → m²
    }
    public function setPrimeA(float $areaM2): void
    {
        $this->primeA = $areaM2;
    }
}
