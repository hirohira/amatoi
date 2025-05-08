<?php

namespace App\Models;

class NokiTateCombination
{
    private string $nokiToiCode;     // 軒といコード（例：N30）
    private string $nokiToiName;     // 表示用（例：サーフェスケア）

    private string $tateToiSize;     // 縦といのサイズ表記（例：VP75）
    private string $tateToiCode;     // 縦といコード（例：T150）

    // --- Getters ---

    public function getNokiToiCode(): string
    {
        return $this->nokiToiCode;
    }

    public function getNokiToiName(): string
    {
        return $this->nokiToiName;
    }

    public function getTateToiSize(): string
    {
        return $this->tateToiSize;
    }

    public function getTateToiCode(): string
    {
        return $this->tateToiCode;
    }

    // --- Setters ---

    public function setNokiToiCode(string $code): void
    {
        $this->nokiToiCode = $code;
    }

    public function setNokiToiName(string $name): void
    {
        $this->nokiToiName = $name;
    }

    public function setTateToiSize(string $size): void
    {
        $this->tateToiSize = $size;
    }

    public function setTateToiCode(string $code): void
    {
        $this->tateToiCode = $code;
    }
}
