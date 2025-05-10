<?php

namespace App\Controllers;

use App\Models\CsvLoader;
use App\Models\Calculator;

class CalculatorController
{
    private CsvLoader $csvLoader;
    private Calculator $calculator;

    public function __construct()
    {
        $this->csvLoader = new CsvLoader();
        $this->calculator = new Calculator();
    }

    public function handleRequest(): void
    {
        // 入力取得
        $formType = $_POST['form_type'] ?? 'hyoujun';
        $sX = $_POST['sX'] ?? '';
        $sY = $_POST['sY'] ?? '';
        $sS = $_POST['sS'] ?? '';
        $koubai = $_POST['koubai'] ?? '';
        $sH = $_POST['sH'] ?? '';
        $sV = $_POST['sV'] ?? '';
        $nokiToiCode = $_POST['nokiToiCode'] ?? '';
        $tateToiCode = $_POST['tateToiCode'] ?? '';
        $resultMessage = '';
        $sW = $sQ = $sPrimeQ = '';

        // 計算処理（省略可）
        if (isset($_POST['calc'])) {
            if ($formType === 'hyoujun') {
                $nokiToi = $this->csvLoader->findNokiToi($nokiToiCode);
                $tateToi = $this->csvLoader->findTateToi($tateToiCode);
                if ($nokiToi && $tateToi) {
                    [$sW, $sQ, $sPrimeQ, $resultMessage] = $this->calculator->calculateHyoujun(
                        (float)$sX, (float)$sY, (float)$sS, (float)$koubai,
                        $nokiToi->getA(), $nokiToi->getR(), $nokiToi->getSqrtR(),
                        $tateToi->getPrimeA(), $nokiToi->getH()
                    );
                } else {
                    $resultMessage = '軒とい または 縦といが未選択です。';
                }
            } elseif ($formType === 'tani') {
                $tateToi = $this->csvLoader->findTateToi($tateToiCode);
                if ($tateToi && $sH !== '' && $sV !== '') {
                    [$sW, $sQ, $sPrimeQ, $resultMessage] = $this->calculator->calculateTani(
                        (float)$sX, (float)$sY, (float)$sS, (float)$koubai, (float)$sH, (float)$sV, $tateToi
                    );
                } else {
                    $resultMessage = '谷部の寸法または縦といが未入力です。';
                }
            }
        }

        // 表示用リスト読み込み
        $nokiToiList = $this->csvLoader->loadNokiToiList();
        $tateToiList = $this->csvLoader->loadTateToiList();

        // form.php に渡す
        include __DIR__ . '/../Views/form.php';
    }
}
