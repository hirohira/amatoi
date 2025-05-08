<?php
require_once __DIR__ . '/../autoload.php';

use App\Models\CsvLoader;

$formType    = $_POST['form_type']    ?? 'hyoujun';
$nokiToiCode = $_POST['nokiToiCode'] ?? '';

$loader = new CsvLoader();

// まず共通の「選択してください」を出力
echo '<option value="">── 選択してください ──</option>';

// 谷コイルモード
if ($formType === 'tani') {
    $list = $loader->loadTateToiTaniList();
    foreach ($list as $tate) {
        $code = htmlspecialchars($tate->getTateToiCode(),   ENT_QUOTES);
        $size = htmlspecialchars($tate->getTateToiSize(),   ENT_QUOTES);
        $area = number_format($tate->getPrimeA_Original(), 1);
        echo "<option value=\"{$code}\">{$size} / {$area}cm²</option>";
    }
    exit;
}

// 標準モード：kumiawase.csv で絞り込み
$valid = [];
foreach ($loader->loadNokiTateCombinations() as $c) {
    if ($c->getNokiToiCode() === $nokiToiCode) {
        $valid[$c->getTateToiCode()] = true;
    }
}
foreach ($loader->loadTateToiList() as $tate) {
    $code = $tate->getTateToiCode();
    if (! isset($valid[$code])) continue;
    $size = htmlspecialchars($tate->getTateToiSize(),   ENT_QUOTES);
    $area = number_format($tate->getPrimeA_Original(), 1);
    echo "<option value=\"{$code}\">{$size} / {$area}cm²</option>";
}
exit;
