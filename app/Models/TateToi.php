<?php
namespace App\Models;

class TateToi
{
    private string $tateToiCode     = '';
    private string $tateToiSize     = '';
    private float  $primeA_Original = 0.0;  // cm²
    private float  $primeA          = 0.0;  // m²

public function __construct(array $assoc = [])
{
    $this->setTateToiCode($assoc['コード'] ?? '');
    $this->setTateToiSize($assoc['サイズ'] ?? '');
    $this->setPrimeA_Original((float)($assoc['排水有効面積'] ?? 0));
}
    
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
        $this->setPrimeA($areaCm2 / 10000); // cm² → m² に変換して保持
    }

    // 計算用の m²
    public function getPrimeA(): float
    {
        return $this->primeA;
    }

    public function setPrimeA(float $areaM2): void
    {
        $this->primeA = $areaM2;
    }
}
