<?php
// amatoi/ajax/getTateOptions.php
declare(strict_types=1);

require_once __DIR__ . '/../autoload.php';

use App\Models\CsvLoader;

// POST パラメータ取得
$formType    = $_POST['form_type']    ?? 'hyoujun';
$sX          = (float)($_POST['sX']   ?? 0);
$sY          = (float)($_POST['sY']   ?? 0);
$sS          = (float)($_POST['sS']   ?? 0);
$koubai      = (float)($_POST['koubai']?? 0);
$sH_cm       = (float)($_POST['sH']   ?? 0);
$sV_cm       = (float)($_POST['sV']   ?? 0);
$nokiToiCode = $_POST['nokiToiCode'] ?? '';

$loader = new CsvLoader();

// プレースホルダ
echo '<option value="">── 選択してください ──</option>';

if ($formType === 'tani') {
    // 谷コイルモード：毎回全件表示（tani.php 相当）
    $list = $loader->loadTateToiTaniList();
    foreach ($list as $tate) {
        $code = htmlspecialchars($tate->getTateToiCode(),   ENT_QUOTES);
        $size = htmlspecialchars($tate->getTateToiSize(),   ENT_QUOTES);
        $area = number_format($tate->getPrimeA_Original(), 1);
        echo "<option value=\"{$code}\">{$size} / {$area}cm²</option>";
    }
    exit;
}

// 標準モード：kumiawase.csv による絞り込み
$valid = [];
foreach ($loader->loadNokiTateCombinations() as $c) {
    if ($c->getNokiToiCode() === $nokiToiCode) {
        $valid[$c->getTateToiCode()] = true;
    }
}

foreach ($loader->loadTateToiList() as $tate) {
    $code = $tate->getTateToiCode();
    if (! isset($valid[$code])) {
        continue;
    }
    $size = htmlspecialchars($tate->getTateToiSize(),   ENT_QUOTES);
    $area = number_format($tate->getPrimeA_Original(), 1);
    echo "<option value=\"{$code}\">{$size} / {$area}cm²</option>";
}
exit;
