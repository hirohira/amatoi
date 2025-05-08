<?php
// amatoi/app/Models/Calculator.php
namespace App\Models;

class Calculator
{
    private float $K = 1.5;
    private float $m = 0.21;
    private float $C = 0.6;
    private float $g = 9.8;

    /**
     * 標準モード計算
     */
    public function calculateHyoujun(
        float $x, float $y, float $a, float $I_per_mil,
        float $A, float $R, float $sqrtR,
        float $primeA, float $h_head
    ): array {
        // 1) 降雨量 W [m^3/s]
        $W = $x * $y * ($a / 1000.0) / 3600.0;

        // 2) 流速 v [m/s]
        $I      = $I_per_mil / 1000.0;
        $v      = 100.0 * $R * sqrt($I) / ($this->m + $sqrtR);

        // 3) 軒とい排水量 Q [m^3/s]
        $Q      = (1.0 / $this->K) * $A * $v;

        // 4) 縦とい排水量 Q' [m^3/s]
        $Qp     = $this->C * $primeA * sqrt(2.0 * $this->g * $h_head);

        // 5) l/s に変換して丸め
        $Wls    = round($W  * 1000.0, 2);
        $Qls    = round($Q  * 1000.0, 2);
        $Qpls   = round($Qp * 1000.0, 2);

        // 判定
        $result = ($W < $Q && $W < $Qp)
            ? 'このサイズ以上で排水能力は十分です。'
            : 'このサイズでは排水能力は不十分です。サイズを大きくして下さい。';

        return ['W' => $Wls, 'Q' => $Qls, 'Qp' => $Qpls, 'result' => $result];
    }

    /**
     * 谷コイルモード計算
     */
    public function calculateTani(
        float $x, float $y, float $a, float $I_per_mil,
        float $h, float $v, float $primeA
    ): array {
        // 1) 降雨量 W
        $W  = $x * $y * ($a / 1000.0) / 3600.0;

        // 2) 谷コイル断面・潤辺
        $A_coil = $h * $v;
        $L      = 2.0 * $h + $v;
        $R      = $L > 0 ? $A_coil / $L : 0.0;
        $sqrtR  = sqrt($R);

        // 3) 谷コイル流速 & 排水量 Q
        $I      = $I_per_mil / 1000.0;
        $v_flow = 100.0 * $R * sqrt($I) / ($this->m + $sqrtR);
        $Q      = (1.0 / $this->K) * $A_coil * $v_flow;

        // 4) 縦とい Q'
        $Qp     = $this->C * $primeA * sqrt(2.0 * $this->g * $h);

        // 5) l/s
        $Wls    = round($W  * 1000.0, 2);
        $Qls    = round($Q  * 1000.0, 2);
        $Qpls   = round($Qp * 1000.0, 2);

        $result = ($W < $Q && $W < $Qp)
            ? 'このサイズ以上で排水能力は十分です。'
            : 'このサイズでは排水能力は不十分です。サイズを大きくして下さい。';

        return ['W' => $Wls, 'Q' => $Qls, 'Qp' => $Qpls, 'result' => $result];
    }
}
