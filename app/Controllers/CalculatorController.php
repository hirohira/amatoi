<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\CsvLoader;
use App\Models\Calculator;
use RuntimeException;

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
        // ── 1) 入力値取得 ─────────────────────────────
        $formType    = $_POST['form_type']    ?? 'hyoujun';
        $sX          = $_POST['sX']            ?? '';
        $sY          = $_POST['sY']            ?? '';
        $sS          = $_POST['sS']            ?? '160';
        $koubai      = $_POST['koubai']        ?? '5';
        $sH          = $_POST['sH']            ?? '';
        $sV          = $_POST['sV']            ?? '';
        $nokiToiCode = $_POST['nokiToiCode']   ?? '';
        $tateToiCode = $_POST['tateToiCode']   ?? '';

        // ── 2) プルダウン用データ取得 ────────────────────
        $nokiToiList = $this->loader->loadNokiToiList();

        // ── 3) 計算実行（POST & calc 押下時のみ）──────────────
        $sW      = '';
        $sQ      = '';
        $sPrimeQ = '';
        $result  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc'])) {
            $x = (float)$sX;
            $y = (float)$sY;
            $s = (float)$sS;
            $i = (float)$koubai;

            if ($formType === 'hyoujun') {
                // ── 標準モード ───────────────────────
                // 軒とい情報を検索
                $A       = 0.0;
                $R       = 0.0;
                $sqrtR   = 0.0;
                $h_head  = 0.0;
                foreach ($nokiToiList as $noki) {
                    if ($noki->getNokiToiCode() === $nokiToiCode) {
                        $A      = $noki->getA();
                        $R      = $noki->getR();
                        $sqrtR  = $noki->getSqrtR();
                        $h_head = $noki->getH();
                        break;
                    }
                }

                // たてとい情報を検索（標準用CSV）
                $primeA = 0.0;
                foreach ($this->loader->loadTateToiList() as $tate) {
                    if ($tate->getTateToiCode() === $tateToiCode) {
                        $primeA = $tate->getPrimeA();
                        break;
                    }
                }

                // 計算呼び出し
                [
                    'W'     => $sW,
                    'Q'     => $sQ,
                    'Qp'    => $sPrimeQ,
                    'result'=> $result,
                ] = $this->calculator->calculateHyoujun(
                    $x, $y, $s, $i,
                    $A, $R, $sqrtR, $h_head,
                    $primeA
                );
            } else {
                // ── 谷コイルモード ──────────────────
                $h = (float)$sH;  // cm→m は Calculator内で対応
                $v = (float)$sV;

                // たてとい情報を検索（谷コイル用CSV）
                $primeA = 0.0;
                foreach ($this->loader->loadTateToiTaniList() as $tate) {
                    if ($tate->getTateToiCode() === $tateToiCode) {
                        $primeA = $tate->getPrimeA();
                        break;
                    }
                }

                // 計算呼び出し
                [
                    'W'     => $sW,
                    'Q'     => $sQ,
                    'Qp'    => $sPrimeQ,
                    'result'=> $result,
                ] = $this->calculator->calculateTani(
                    $x, $y, $s, $i,
                    $h, $v, $primeA
                );
            }
        }

        // ── 4) ビュー表示 ───────────────────────
        include __DIR__ . '/../Views/form.php';
    }
}
