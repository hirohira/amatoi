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
        $formType = $_POST['form_type'] ?? 'hyoujun';

        $sX = $_POST['sX'] ?? '';
        $sY = $_POST['sY'] ?? '';
        $sS = $_POST['sS'] ?? '';
        $koubai = $_POST['koubai'] ?? '';
        $sH = $_POST['sH'] ?? '';
        $sV = $_POST['sV'] ?? '';
        $nokiToiCode = $_POST['nokiToiCode'] ?? '';
        $tateToiCode = $_POST['tateToiCode'] ?? '';

        $sW = $sQ = $sPrimeQ = '';
        $resultMessage = '';

        if (isset($_POST['calc'])) {
            if ($formType === 'hyoujun') {
                $nokiToi = $this->csvLoader->findNokiToi($nokiToiCode);
                $tateToi = $this->csvLoader->findTateToi($tateToiCode);

                if ($nokiToi && $tateToi) {
                    $A = $nokiToi->getA(); // cm² → m²
                    $result = $this->calculator->calculateHyoujun((float)$sX, (float)$sY, (float)$sS, (float)$koubai, $A, $tateToi);
                    [$sW, $sQ, $sPrimeQ, $resultMessage] = $result;
                } else {
                    $resultMessage = '軒といまたは縦といが選択されていません。';
                }

            } elseif ($formType === 'tani') {
                $tateToi = $this->csvLoader->findTateToi($tateToiCode);

                if ($tateToi && $sH !== '' && $sV !== '') {
                    $result = $this->calculator->calculateTani((float)$sX, (float)$sY, (float)$sS, (float)$koubai, (float)$sH, (float)$sV, $tateToi);
                    [$sW, $sQ, $sPrimeQ, $resultMessage] = $result;
                } else {
                    $resultMessage = '必要な値が未入力、または縦といが選択されていません。';
                }
            }
        }

        $nokiToiList = $this->csvLoader->loadNokiToiList();
        $tateToiList = $this->csvLoader->loadTateToiList();

        include __DIR__ . '/../Views/form.php';
    }
}
