<?php

namespace App\Controllers;

use App\Models\CsvLoader;
use App\Models\Calculator;

class CalculatorController
{
    public function handleRequest()
    {
        // POSTデータの取得
        $formType = $_POST['form_type'] ?? 'hyoujun';
        $sX = $_POST['sX'] ?? '';
        $sY = $_POST['sY'] ?? '';
        $sS = $_POST['sS'] ?? '';
        $koubai = $_POST['koubai'] ?? '';
        $sH = $_POST['sH'] ?? '';
        $sV = $_POST['sV'] ?? '';
        $nokiToiCode = $_POST['nokiToiCode'] ?? '';
        $tateToiCode = $_POST['tateToiCode'] ?? '';

        // 必要なデータの読み込み
        $csvLoader = new CsvLoader();
        $nokiToiList = $csvLoader->loadNokiToiList();
        $tateToiList = $csvLoader->loadTateToiList();
        $resultMessage = '';
        $sW = $sQ = $sPrimeQ = '';

        // 計算処理
        if (isset($_POST['calc'])) {
            $calculator = new Calculator();

            if ($formType === 'hyoujun') {
                // 標準モードの計算処理
                [$sW, $sQ, $sPrimeQ, $resultMessage] = $calculator->calculateHyoujun(
                    (float)$sX, (float)$sY, (float)$sS, (float)$koubai, $nokiToiCode, $tateToiCode
                );
            } elseif ($formType === 'tani') {
                // 谷コイルモードの計算処理
                [$sW, $sQ, $sPrimeQ, $resultMessage] = $calculator->calculateTani(
                    (float)$sX, (float)$sY, (float)$sS, (float)$koubai, (float)$sH, (float)$sV, $tateToiCode
                );
            }
        }

        // ビュー表示
        include __DIR__ . '/../Views/form.php';
    }
}
