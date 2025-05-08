<?php
namespace App\Controllers;

use App\Models\CsvLoader;
use App\Models\Calculator;

class CalculatorController
{
    private CsvLoader $loader;
    private Calculator $calculator;

    public function __construct()
    {
        $this->loader     = new CsvLoader();
        $this->calculator = new Calculator();
    }

    public function handleRequest(): void
    {
        // ── 1. 個別変数の初期化 ──
        $formType      = $_POST['form_type']    ?? 'hyoujun';
        $sX            = $_POST['sX']            ?? '';
        $sY            = $_POST['sY']            ?? '';
        $sS            = $_POST['sS']            ?? '160';
        $koubai        = $_POST['koubai']        ?? '5';
        $sH            = $_POST['sH']            ?? '';
        $sV            = $_POST['sV']            ?? '';
        $nokiToiCode   = $_POST['nokiToiCode']   ?? '';
        $tateToiCode   = $_POST['tateToiCode']   ?? '';
        $sW            = '';
        $sQ            = '';
        $sPrimeQ       = '';
        $resultMessage = '';

        // ── 2. プルダウン用データを読み込む ──
        $nokiToiList = $this->loader->loadNokiToiList();

        // ── 3. 計算実行 ──
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc'])) {
            $x = (float)$sX;
            $y = (float)$sY;
            $s = (float)$sS;
            $i = (float)$koubai;

            if ($formType === 'hyoujun') {
                // 標準モード
                [$sW, $sQ, $sPrimeQ, $resultMessage] =
                    $this->calculator->calculateHyoujun(
                        $x, $y, $s, $i,
                        $nokiToiCode, $tateToiCode
                    );
            } else {
                // 谷コイルモード
                $h = (float)$sH;
                $v = (float)$sV;
                [$sW, $sQ, $sPrimeQ, $resultMessage] =
                    $this->calculator->calculateTani(
                        $x, $y, $s, $i, $h, $v, $tateToiCode
                    );
            }
        }

        // ── 4. ビューを読み込む ──
        include __DIR__ . '/../Views/form.php';
    }
}
