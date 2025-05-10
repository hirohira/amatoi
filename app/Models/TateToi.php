<?php
namespace App\Models;

class TateToi
{
    private string $tateToiCode     = '';
    private string $tateToiSize     = '';
    private float  $primeA_Original = 0.0;
    private float  $primeA          = 0.0;

    public function __construct(array $data)
    {
        $this->tateToiCode     = $data['コード'] ?? '';
        $this->tateToiSize     = $data['サイズ'] ?? '';
        $this->primeA_Original = isset($data['排水有効面積']) ? (float)$data['排水有効面積'] : 0.0;
        $this->primeA          = $this->primeA_Original;
    }

    public function getTateToiCode(): string
    {
        return $this->tateToiCode;
    }

    public function getTateToiSize(): string
    {
        return $this->tateToiSize;
    }

    public function getPrimeA_Original(): float
    {
        return $this->primeA_Original;
    }

    public function getPrimeA(): float
    {
        return $this->primeA / 10000.0; // cm² → m²
    }
}
