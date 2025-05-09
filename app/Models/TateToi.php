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
        if (!empty($data)) {
            $this->setTateToiCode($data['tateToiCode'] ?? '');
            $this->setTateToiSize($data['tateToiSize'] ?? '');
            $this->setPrimeA_Original((float)($data['primeA_Original'] ?? 0));
            $this->setPrimeA((float)($data['primeA'] ?? 0));
        }
    }

    public function getTateToiCode(): string { return $this->tateToiCode; }
    public function setTateToiCode(string $code): void { $this->tateToiCode = $code; }

    public function getTateToiSize(): string { return $this->tateToiSize; }
    public function setTateToiSize(string $size): void { $this->tateToiSize = $size; }

    public function getPrimeA_Original(): float { return $this->primeA_Original; }
    public function setPrimeA_Original(float $areaCm2): void { $this->primeA_Original = $areaCm2; }

    public function getPrimeA(): float { return $this->primeA / 10000; }
    public function setPrimeA(float $areaM2): void { $this->primeA = $areaM2; }
}
