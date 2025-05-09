<?php
spl_autoload_register(function ($class) {
    // PSR-4風名前空間 'App\' に対応
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    // 対象でないクラスは無視
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // 名前空間部分を削除し、残りをパスに変換
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // ファイルが存在すれば読み込む
    if (file_exists($file)) {
        require $file;
    }
});
