<?php
declare(strict_types=1);
// CalculatorController から渡される変数:
//   $formType, $nokiToiList, $sX, $sY, $sS, $koubai,
//   $sH, $sV, $sW, $sQ, $sPrimeQ, $resultMessage
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

  <form method="post" action="/amatoi/index.php">
    <!-- モード選択 -->
    <fieldset>
      <legend>モード選択</legend>
      <div class="inline">
        <label>
          <input type="radio" name="form_type" value="hyoujun"
            <?= $formType==='hyoujun' ? 'checked' : '' ?>>
          標準モード
        </label>
        <label>
          <input type="radio" name="form_type" value="tani"
            <?= $formType==='tani' ? 'checked' : '' ?>>
          谷コイルモード
        </label>
      </div>
    </fieldset>

    <!-- 標準モード入力 -->
    <div id="hyoujunFields">
      <fieldset>
        <legend>標準モード：軒とい &amp; 屋根情報</legend>
        <label>軒とい:
          <select name="nokiToiCode" id="nokiToiCode" required>
            <option value="">── 選択してください ──</option>
            <?php foreach($nokiToiList as $noki): ?>
              <option value="<?= htmlspecialchars($noki->getNokiToiCode(),ENT_QUOTES)?>"
                <?= $noki->getNokiToiCode()===$nokiToiCode?'selected':''?>>
                <?= htmlspecialchars($noki->getNokiToiName(),ENT_QUOTES)?>
                / <?= number_format($noki->getA_Original()*10000,1) ?>cm²
              </option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>屋根横幅 X (m):
          <input type="number" name="sX" step="0.01"
                 value="<?= htmlspecialchars($sX,ENT_QUOTES)?>">
        </label>
        <label>屋根奥行 Y (m):
          <input type="number" name="sY" step="0.01"
                 value="<?= htmlspecialchars($sY,ENT_QUOTES)?>">
        </label>
        <label>降雨強度 S (mm/h):
          <input type="number" name="sS" step="1"
                 value="<?= htmlspecialchars($sS,ENT_QUOTES)?>">
        </label>
        <label>水勾配 I (‰):
          <select name="koubai">
            <?php for($i=1;$i<=10;$i++): ?>
              <option value="<?=$i?>"
                <?=((string)$i===(string)$koubai)?'selected':''?>>
                <?=$i?>/1000
              </option>
            <?php endfor; ?>
          </select>
        </label>
      </fieldset>
    </div>

    <!-- 谷コイルモード入力 -->
    <div id="taniFields">
      <fieldset>
        <legend>谷コイルモード：谷部情報</legend>
        <label>屋根奥行 A (m):
          <input type="number" name="sX" step="0.01"
                 value="<?= htmlspecialchars($sX,ENT_QUOTES)?>">
        </label>
        <label>軒とい長さ B (m):
          <input type="number" name="sY" step="0.01"
                 value="<?= htmlspecialchars($sY,ENT_QUOTES)?>">
        </label>
        <label>降雨強度 a (mm/h):
          <input type="number" name="sS" step="1"
                 value="<?= htmlspecialchars($sS,ENT_QUOTES)?>">
        </label>
        <label>水勾配 I (‰):
          <select name="koubai">
            <?php for($i=1;$i<=10;$i++): ?>
              <option value="<?=$i?>"
                <?=((string)$i===(string)$koubai)?'selected':''?>>
                <?=$i?>/1000
              </option>
            <?php endfor; ?>
          </select>
        </label>
        <label>谷部高さ H (cm):
          <input type="number" name="sH" step="1"
                 value="<?= htmlspecialchars($sH,ENT_QUOTES)?>">
        </label>
        <label>谷部幅 V (cm):
          <input type="number" name="sV" step="1"
                 value="<?= htmlspecialchars($sV,ENT_QUOTES)?>">
        </label>
      </fieldset>
    </div>

    <!-- 縦とい選択 -->
    <fieldset>
      <legend>縦とい選択</legend>
      <select name="tateToiCode" id="tateToiCode" required>
        <option value="">── 選択してください ──</option>
      </select>
    </fieldset>

    <button type="submit" name="calc">計算する</button>
  </form>

  <!-- 結果表示 -->
  <?php if($sW!==''): ?>
    <fieldset>
      <legend>計算結果</legend>
      <p>W：降雨量 &nbsp; <?= htmlspecialchars($sW,ENT_QUOTES) ?> l/s</p>
      <p>Q：軒とい排水量 &nbsp; <?= htmlspecialchars($sQ,ENT_QUOTES) ?> l/s</p>
      <p>Q′：縦とい排水量 &nbsp; <?= htmlspecialchars($sPrimeQ,ENT_QUOTES) ?> l/s</p>
      <p><strong><?= htmlspecialchars($resultMessage,ENT_QUOTES) ?></strong></p>
    </fieldset>
  <?php endif; ?>

  <script>
  $(function(){
    function toggleAndUpdate(){
      const mode = $('input[name="form_type"]:checked').val();
      $('#hyoujunFields').toggle(mode==='hyoujun');
      $('#taniFields').toggle(mode==='tani');
      updateTate();
    }
    function updateTate(){
      const data = {
        form_type:   $('input[name="form_type"]:checked').val(),
        sX:          $('[name="sX"]').val(),
        sY:          $('[name="sY"]').val(),
        sS:          $('[name="sS"]').val(),
        koubai:      $('[name="koubai"]').val(),
        sH:          $('[name="sH"]').val(),
        sV:          $('[name="sV"]').val(),
        nokiToiCode: $('[name="nokiToiCode"]').val()
      };
      $.post('/amatoi/ajax/getTateOptions.php', data, function(html){
        $('#tateToiCode').html(html);
      });
    }

    // イベント登録
    $('input[name="form_type"]').on('change', toggleAndUpdate);
    $('#nokiToiCode').on('change', updateTate);  // 標準モードでも常に絞り込み
    $('[name="sX"],[name="sY"],[name="sS"],[name="koubai"],[name="sH"],[name="sV"]')
      .on('input change', function(){
        if($('input[name="form_type"]:checked').val()==='tani'){
          updateTate();
        }
      });

    // 初期表示
    toggleAndUpdate();
  });
  </script>
</body>
</html>
