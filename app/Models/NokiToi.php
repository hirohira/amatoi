<?php
namespace App\Models;

class NokiToi
{
    private string $nokiToiCode        = '';
    private string $nokiToiName        = '';
    private float  $a_Original         = 0.0;   // cm²
    private float  $a                  = 0.0;   // m²
    private float  $r                  = 0.0;   // 径深（m）
    private float  $sqrtR              = 0.0;   // √R
    private float  $h_Original         = 0.0;   // cm
    private float  $h                  = 0.0;   // m

public function __construct(array $assoc = [])
{
    $this->setNokiToiCode($assoc['コード'] ?? '');
    $this->setNokiToiName($assoc['品名'] ?? '');
    $this->setA_Original((float)($assoc['断面積'] ?? 0));
    $this->setR((float)($assoc['R'] ?? 0));
    $this->setSqrtR((float)($assoc['√R'] ?? 0));
    $this->setH_Original((float)($assoc['H'] ?? 0));
}
    
    public function getNokiToiCode(): string
    {
        return $this->nokiToiCode;
    }

    public function setNokiToiCode(string $code): void
    {
        $this->nokiToiCode = $code;
    }

    public function getNokiToiName(): string
    {
        return $this->nokiToiName;
    }

    public function setNokiToiName(string $name): void
    {
        $this->nokiToiName = $name;
    }

    public function getA_Original(): float
    {
        return $this->a_Original;
    }

    public function setA_Original(float $orig): void
    {
        $this->a_Original = $orig;
        $this->setA($orig / 10000); // cm² → m² に変換
    }

    public function getA(): float
    {
        return $this->a; // m² で返す
    }

    public function setA(float $area): void
    {
        $this->a = $area;
    }

    public function getR(): float
    {
        return $this->r;
    }

    public function setR(float $r): void
    {
        $this->r = $r;
    }

    public function getSqrtR(): float
    {
        return $this->sqrtR;
    }

    public function setSqrtR(float $sqrtR): void
    {
        $this->sqrtR = $sqrtR;
    }

    public function getH_Original(): float
    {
        return $this->h_Original;
    }

    public function setH_Original(float $hOrig): void
    {
        $this->h_Original = $hOrig;
    }

    public function getH(): float
    {
        return $this->h;
    }

    public function setH(float $h): void
    {
        $this->h = $h;
    }
}
