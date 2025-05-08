<?php
// amatoi/autoload.php
spl_autoload_register(function ($class) {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/app/';

    // 名前空間が App\ で始まらない場合はスキップ
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // 相対クラス名を取得してファイルパスに変換
    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
