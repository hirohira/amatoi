<?php
namespace App\Models;

class NokiToi
{
    private string $nokiToiCode = '';
    private string $nokiToiName = '';
    private float $a = 0.0;       // m^2
    private float $r = 0.0;
    private float $sqrtR = 0.0;
    private float $h = 0.0;       // cm

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->setNokiToiCode($data['コード'] ?? '');
            $this->setNokiToiName($data['品名'] ?? '');
            $this->setA((float)($data['断面積'] ?? 0.0));
            $this->setR((float)($data['径深'] ?? 0.0));
            $this->setSqrtR((float)($data['ルートＲ'] ?? 0.0));
            $this->setH((float)($data['軒樋潤辺高さ'] ?? 0.0));
        }
    }

    public function getNokiToiCode(): string { return $this->nokiToiCode; }
    public function setNokiToiCode(string $code): void { $this->nokiToiCode = $code; }

    public function getNokiToiName(): string { return $this->nokiToiName; }
    public function setNokiToiName(string $name): void { $this->nokiToiName = $name; }

    public function getA(): float { return $this->a; }
    public function setA(float $a): void { $this->a = $a; }

    public function getR(): float { return $this->r; }
    public function setR(float $r): void { $this->r = $r; }

    public function getSqrtR(): float { return $this->sqrtR; }
    public function setSqrtR(float $sqrtR): void { $this->sqrtR = $sqrtR; }

    public function getH(): float { return $this->h; }
    public function setH(float $h): void { $this->h = $h; }
}
