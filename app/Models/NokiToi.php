<?php
namespace App\Models;

class NokiToi
{
    private string $nokiToiCode = '';
    private string $nokiToiName = '';
    private float $a_Original = 0.0;
    private float $a = 0.0;
    private float $r = 0.0;
    private float $sqrtR = 0.0;
    private float $h_Original = 0.0;
    private float $h = 0.0;

    public function __construct(array $data = [])
    {
        if ($data) {
            $this->nokiToiCode = $data['コード'] ?? '';
            $this->nokiToiName = $data['名称'] ?? '';
            $this->a_Original = (float)($data['有効断面積'] ?? 0);
            $this->a = (float)($data['有効断面積'] ?? 0);
            $this->r = (float)($data['水理半径'] ?? 0);
            $this->sqrtR = sqrt($this->r);
            $this->h_Original = (float)($data['水頭高さ'] ?? 0);
            $this->h = $this->h_Original / 100;
        }
    }

    public function getNokiToiCode(): string { return $this->nokiToiCode; }
    public function getNokiToiName(): string { return $this->nokiToiName; }
    public function getA_Original(): float { return $this->a_Original; }
    public function getA(): float { return $this->a / 10000; }
    public function getR(): float { return $this->r; }
    public function getSqrtR(): float { return $this->sqrtR; }
    public function getH_Original(): float { return $this->h_Original; }
    public function getH(): float { return $this->h; }
}
