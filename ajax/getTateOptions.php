<?php
declare(strict_types=1);
require_once __DIR__ . '/../autoload.php';

use App\Models\CsvLoader;

$formType    = $_POST['form_type']    ?? 'hyoujun';
$nokiToiCode = $_POST['nokiToiCode'] ?? '';
$selected    = $_POST['tateToiCode'] ?? '';

$loader = new CsvLoader();

// 先頭プレースホルダ
echo '<option value="">── 選択してください ──</option>';

if ($formType === 'tani') {
    // 谷コイル用全件
    foreach ($loader->loadTateToiTaniList() as $tate) {
        $code = htmlspecialchars($tate->getTateToiCode(), ENT_QUOTES);
        $size = htmlspecialchars($tate->getTateToiSize(), ENT_QUOTES);
        $area = number_format($tate->getPrimeA_Original(), 1);
        $sel  = $code === $selected ? ' selected' : '';
        echo "<option value=\"{$code}\"{$sel}>{$size} / {$area}cm²</option>";
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
    if (! isset($valid[$code])) {
        continue;
    }
    $size = htmlspecialchars($tate->getTateToiSize(),   ENT_QUOTES);
    $area = number_format($tate->getPrimeA_Original(), 1);
    $sel  = $code === $selected ? ' selected' : '';
    echo "<option value=\"{$code}\"{$sel}>{$size} / {$area}cm²</option>";
}
exit;
