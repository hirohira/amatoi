<?php
echo "<pre>";
echo "現在のディレクトリ: " . __DIR__ . "\n";
echo "ファイル一覧:\n";
print_r(scandir(__DIR__));
echo "</pre>";
