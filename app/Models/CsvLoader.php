<?php
class CsvLoader
{
    private $data = [];

    public function __construct($csvPath)
    {
        if (!file_exists($csvPath)) {
            throw new Exception("CSVファイルが見つかりません: {$csvPath}");
        }

        if (($handle = fopen($csvPath, 'r')) !== false) {
            $header = fgetcsv($handle);
            while (($row = fgetcsv($handle)) !== false) {
                $record = array_combine($header, $row);
                $this->data[] = $record;
            }
            fclose($handle);
        } else {
            throw new Exception("CSVファイルを開けませんでした: {$csvPath}");
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function findByCode($code)
    {
        foreach ($this->data as $item) {
            if (isset($item['code']) && $item['code'] === $code) {
                return $item;
            }
        }
        return null;
    }

    public function getOptionsHtml($selectedCode = '')
    {
        $html = '';
        foreach ($this->data as $item) {
            $code = htmlspecialchars($item['code'], ENT_QUOTES, 'UTF-8');
            $name = htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8');
            $area = isset($item['area']) ? htmlspecialchars($item['area'], ENT_QUOTES, 'UTF-8') : '0';
            $selected = ($code === $selectedCode) ? ' selected' : '';
            $html .= "<option value=\"{$code}\"{$selected}>{$name} / {$area}cm²</option>\n";
        }
        return $html;
    }
}
?>
