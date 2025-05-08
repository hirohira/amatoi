<?php
declare(strict_types=1);

header("Content-Type: text/html; charset=UTF-8");

// オートローダ
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/app/Controllers/CalculatorController.php';


use App\Controllers\CalculatorController;
use App\Controllers\AjaxController;
use App\Models\CsvLoader;

$csv = new CsvLoader();

// 共通初期化（必要なら）
ini_set('display_errors', '1');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Tokyo');

// 簡易ルーター
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/amatoi/' || $uri === '/amatoi/index.php') {
    // フォーム表示・計算処理
    $controller = new CalculatorController();
    $controller->handleRequest();
    exit;
}

if ($uri === '/amatoi/ajax/getTateOptions.php') {
    if ($method === 'POST') {
        $ajax = new AjaxController();
        $ajax->handleTateToiOptions();
        exit;
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        exit;
    }
}

// その他：404
http_response_code(404);
echo "ページが見つかりません（404）";
exit;
