<?php

namespace App\Controllers;

use App\Models\CsvLoader;

class AjaxController
{
    public function handleTateToiOptions(): void
    {
        // ヘッダーをJSONに設定
        header('Content-Type: application/json; charset=UTF-8');

        // POST受信＆軒樋コードを取得
        $nokiToiCode = $_POST['nokiToiCode'] ?? '';

        if (empty($nokiToiCode)) {
            echo json_encode(['error' => 'no_nokiToiCode']);
            return;
        }

        // データロード
        $loader = new CsvLoader();
        $tateList = $loader->loadTateToiList();         // tatetoi.csv
        $combiList = $loader->loadNokiTateCombinations(); // kumiawase.csv

        // 対応する縦樋だけ抽出
        $result = [];
        foreach ($combiList as $combination) {
            if ($combination->getNokiToiCode() === $nokiToiCode) {
                $tateCode = $combination->getTateToiCode();
                $tateInfo = $this->findTateToiByCode($tateList, $tateCode);
                if ($tateInfo) {
                    $result[] = [
                        'code' => $tateInfo->getTateToiCode(),
                        'label' => $tateInfo->getTateToiSize() . ' / ' . $tateInfo->getPrimeA_Original()
                    ];
                }
            }
        }

        echo json_encode($result);
    }

    private function findTateToiByCode(array $list, string $code): ?object
    {
        foreach ($list as $item) {
            if ($item->getTateToiCode() === $code) {
                return $item;
            }
        }
        return null;
    }
}
