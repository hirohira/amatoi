<?php
namespace App\Models;

class TateToi
{
    private string $tateToiCode = '';
    private string $tateToiSize = '';
    private float $primeA_Original = 0.0;
    private float $primeA = 0.0;

    public function __construct(array $data = [])
    {
        if ($data) {
            $this->tateToiCode = $data['コード'] ?? '';
            $this->tateToiSize = $data['呼び名'] ?? '';
            $this->primeA_Original = (float)($data['有効断面積'] ?? 0);
            $this->primeA = $this->primeA_Original;
        }
    }

    public function getTateToiCode(): string { return $this->tateToiCode; }
    public function getTateToiSize(): string { return $this->tateToiSize; }
    public function getPrimeA_Original(): float { return $this->primeA_Original; }
    public function getPrimeA(): float { return $this->primeA / 10000; }
}
