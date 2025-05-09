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
        if (!empty($data)) {
            $this->setNokiToiCode($data['nokiToiCode'] ?? '');
            $this->setNokiToiName($data['nokiToiName'] ?? '');
            $this->setA_Original((float)($data['a_Original'] ?? 0));
            $this->setA((float)($data['a'] ?? 0));
            $this->setR((float)($data['r'] ?? 0));
            $this->setSqrtR((float)($data['sqrtR'] ?? 0));
            $this->setH_Original((float)($data['h_Original'] ?? 0));
            $this->setH((float)($data['h'] ?? 0));
        }
    }

    public function getNokiToiCode(): string { return $this->nokiToiCode; }
    public function setNokiToiCode(string $code): void { $this->nokiToiCode = $code; }

    public function getNokiToiName(): string { return $this->nokiToiName; }
    public function setNokiToiName(string $name): void { $this->nokiToiName = $name; }

    public function getA_Original(): float { return $this->a_Original; }
    public function setA_Original(float $orig): void { $this->a_Original = $orig; }

    public function getA(): float { return $this->a / 10000; }
    public function setA(float $area): void { $this->a = $area; }

    public function getR(): float { return $this->r; }
    public function setR(float $r): void { $this->r = $r; }

    public function getSqrtR(): float { return $this->sqrtR; }
    public function setSqrtR(float $sqrtR): void { $this->sqrtR = $sqrtR; }

    public function getH_Original(): float { return $this->h_Original; }
    public function setH_Original(float $hOrig): void { $this->h_Original = $hOrig; }

    public function getH(): float { return $this->h; }
    public function setH(float $h): void { $this->h = $h; }
}
