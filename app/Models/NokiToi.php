<?php
namespace App\Models;

class NokiToi
{
    private string $nokiToiCode  = '';
    private string $nokiToiName  = '';
    private float  $a            = 0.0;
    private float  $r            = 0.0;
    private float  $sqrtR        = 0.0;
    private float  $h            = 0.0;

    public function __construct(array $data)
    {
        $this->nokiToiCode = $data['コード'] ?? '';
        $this->nokiToiName = $data['品名'] ?? '';
        $this->a           = isset($data['断面積']) ? (float)$data['断面積'] : 0.0;
        $this->r           = isset($data['径深']) ? (float)$data['径深'] : 0.0;
        $this->sqrtR       = isset($data['ルートＲ']) ? (float)$data['ルートＲ'] : 0.0;
        $this->h           = isset($data['軒樋潤辺高さ']) ? (float)$data['軒樋潤辺高さ'] / 100.0 : 0.0; // cm → m
    }

    public function getNokiToiCode(): string
    {
        return $this->nokiToiCode;
    }

    public function getNokiToiName(): string
    {
        return $this->nokiToiName;
    }

    public function getA(): float
    {
        return $this->a; // すでに m² 単位
    }

    public function getR(): float
    {
        return $this->r;
    }

    public function getSqrtR(): float
    {
        return $this->sqrtR;
    }

    public function getH(): float
    {
        return $this->h;
    }
}
