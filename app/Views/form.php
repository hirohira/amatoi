<?php
// 初期化
$form_type = $_POST['form_type'] ?? 'hyoujun';
$nokiToiCode = $_POST['nokiToiCode'] ?? '';
$sX = $_POST['sX'] ?? '';
$sY = $_POST['sY'] ?? '';
$sS = $_POST['sS'] ?? '';
$koubai = $_POST['koubai'] ?? '';
$sH = $_POST['sH'] ?? '';
$sV = $_POST['sV'] ?? '';
$tateToiCode = $_POST['tateToiCode'] ?? '';
$resultMessage = $resultMessage ?? '';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>雨樋排水計算システム</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    fieldset { margin:1em 0; padding:1em; border:1px solid #ccc; }
    legend   { font-weight:bold; }
    .inline label { display:inline-block; margin-right:1em; }
    label, select, input { display:block; margin:0.5em 0; }
    #hyoujunFields, #taniFields { margin-bottom:1em; }
  </style>
</head>
<body>
  <h1>雨樋排水計算システム</h1>

  <form method="post" action="">
    <!-- モード選択 -->
    <fieldset>
      <legend>モード選択</legend>
      <div class="inline">
        <label>
          <input type="radio" name="form_type" value="hyoujun" <?= $form_type === 'hyoujun' ? 'checked' : '' ?>>
          標準モード
        </label>
        <label>
          <input type="radio" name="form_type" value="tani" <?= $form_type === 'tani' ? 'checked' : '' ?>>
          谷コイルモード
        </label>
      </div>
    </fieldset>

    <!-- 標準モード -->
    <div id="hyoujunFields" style="display: <?= $form_type === 'hyoujun' ? 'block' : 'none' ?>;">
      <fieldset>
        <legend>標準モード：軒とい &amp; 屋根情報</legend>
        <label>軒とい:
          <select name="nokiToiCode" id="nokiToiCode" required>
            <option value="">── 選択してください ──</option>
            <!-- 適切な選択肢をここに追加 -->
            <option value="N30" <?= $nokiToiCode === 'N30' ? 'selected' : '' ?>>メタリック調軒といサーフェスケアＦＳ−II型 / 108.7cm²</option>
            <!-- 他の選択肢も同様に追加 -->
          </select>
        </label>
        <label>屋根横幅 X (m):
          <input type="number" name="sX" step="0.01" value="<?= htmlspecialchars($sX) ?>">
        </label>
        <label>屋根奥行 Y (m):
          <input type="number" name="sY" step="0.01" value="<?= htmlspecialchars($sY) ?>">
        </label>
        <label>降雨強度 S (mm/h):
          <input type="number" name="sS" step="1" value="<?= htmlspecialchars($sS) ?>">
        </label>
        <label>水勾配 I (‰):
          <select name="koubai">
            <?php for ($i = 1; $i <= 10; $i++): ?>
              <option value="<?= $i ?>" <?= $koubai == $i ? 'selected' : '' ?>><?= $i ?>/1000</option>
            <?php endfor; ?>
          </select>
        </label>
      </fieldset>
    </div>

    <!-- 谷コイルモード -->
    <div id="taniFields" style="display: <?= $form_type === 'tani' ? 'block' : 'none' ?>;">
      <fieldset>
        <legend>谷コイルモード：谷部情報</legend>
        <label>屋根奥行 A (m):
          <input type="number" name="sX" step="0.01" value="<?= htmlspecialchars($sX) ?>">
        </label>
        <label>軒とい長さ B (m):
          <input type="number" name="sY" step="0.01" value="<?= htmlspecialchars($sY) ?>">
        </label>
        <label>降雨強度 a (mm/h):
          <input type="number" name="sS" step="1" value="<?= htmlspecialchars($sS) ?>">
        </label>
        <label>水勾配 I (‰):
          <select name="koubai">
            <?php for ($i = 1; $i <= 10; $i++): ?>
              <option value="<?= $i ?>" <?= $koubai == $i ? 'selected' : '' ?>><?= $i ?>/1000</option>
            <?php endfor; ?>
          </select>
        </label>
        <label>谷部高さ H (cm):
          <input type="number" name="sH" step="1" value="<?= htmlspecialchars($sH) ?>">
        </label>
        <label>谷部幅 V (cm):
          <input type="number" name="sV" step="1" value="<?= htmlspecialchars($sV) ?>">
        </label>
      </fieldset>
    </div>

    <!-- 縦とい選択 -->
    <fieldset>
      <legend>縦とい選択</legend>
      <select name="tateToiCode" id="tateToiCode" required>
        <option value="">── 選択してください ──</option>
        <!-- 適切な選択肢をここに追加 -->
        <option value="T60" <?= $tateToiCode === 'T60' ? 'selected' : '' ?>>メタリック調たてとい60 / 26.6cm²</option>
        <!-- 他の選択肢も同様に追加 -->
      </select>
    </fieldset>

    <button type="submit" name="calc">計算する</button>
  </form>

  <?php if (!empty($resultMessage)): ?>
    <div class="result-message">
      <?= htmlspecialchars($resultMessage) ?>
    </div>
  <?php endif; ?>

  <script>
  $(function(){
    function toggleFields(){
      const mode = $('input[name="form_type"]:checked').val();
      $('#hyoujunFields').toggle(mode === 'hyoujun');
      $('#taniFields').toggle(mode === 'tani');
    }
    $('input[name="form_type"]').on('change', toggleFields);
    toggleFields();
  });
  </script>
</body>
</html>
