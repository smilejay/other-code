<?php
header("Content-Type: text/html; charset=utf-8");

/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffBetweenTwoDays ($day1, $day2)
{
  $second1 = strtotime($day1);
  $second2 = strtotime($day2);

if ($second1 < $second2) {
$tmp = $second2;
$second2 = $second1;
$second1 = $tmp;
}
return ($second1 - $second2) / 86400;
}

$date0 = "2017-01-20";
$date1 = "2067-01-20";
$date_now = date("Y-m-d");
$money_per_day = 500;
$total_money = diffBetweenTwoDays($date0, $date1) * $money_per_day;
$remain_money = diffBetweenTwoDays($date_now, $date1) * $money_per_day;
$delta_money = diffBetweenTwoDays($date0, $date_now) * $money_per_day;

echo "
<style>
.font_size {font-size: 30px}
</style>
";
echo "<div class='font_size'>";
echo "今天是 <b>$date_now</b><br>";
echo "还需养老钱 <b>" . number_format($remain_money) . "</b> 元<br/>";
echo "如果自备50年养老钱 每天500元; 共计 <b>" . number_format($total_money) . "</b> 元<br />";
echo "</div>";
?>
