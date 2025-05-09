<?php
require_once __DIR__ . '/../autoload.php';

use App\Models\CsvLoader;

$formType    = $_POST['form_type']    ?? 'hyoujun';
$nokiToiCode = $_POST['nokiToiCode'] ?? '';
$selected    = $_POST['tateToiCode'] ?? '';

$loader = new CsvLoader();

// デバッグ出力：受け取ったPOST値
echo "<!-- formType: {$formType}, nokiToiCode: {$nokiToiCode}, selected: {$selected} -->\n";

echo '<option value="">── 選択してください ──</option>';

// 谷モードは全件出力
if ($formType === 'tani') {
    foreach ($loader->loadTaniTateToiList() as $tate) {
        $code = htmlspecialchars($tate->getTateToiCode(), ENT_QUOTES);
        $size = htmlspecialchars($tate->getTateToiSize(), ENT_QUOTES);
        $area = number_format($tate->getPrimeA_Original(), 1);
        $sel  = $code === $selected ? ' selected' : '';
        echo "<option value=\"{$code}\"{$sel}>{$size} / {$area}cm²</option>";
    }
    exit;
}

// 組合せに対応する縦といコードを抽出
$validCodes = [];
foreach ($loader->loadNokiTateCombinations() as $c) {
    if ($c->getNokiToiCode() === $nokiToiCode) {
        $validCodes[] = $c->getTateToiCode();
    }
}

// デバッグ出力：マッチした縦といコード一覧
echo "<!-- validTateCodes: " . implode(', ', $validCodes) . " -->\n";

// 縦といリストを取得し、マッチするものだけ表示
$tateToiList = $loader->loadTateToiList();
echo "<!-- total TateToi count: " . count($tateToiList) . " -->\n";

foreach ($tateToiList as $tate) {
    $code = $tate->getTateToiCode();
    if (!in_array($code, $validCodes, true)) continue;

    $size = htmlspecialchars($tate->getTateToiSize(), ENT_QUOTES);
    $area = number_format($tate->getPrimeA_Original(), 1);
    $sel  = $code === $selected ? ' selected' : '';
    echo "<option value=\"{$code}\"{$sel}>{$size} / {$area}cm²</option>";
}
