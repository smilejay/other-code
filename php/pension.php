<?php
header("Content-Type: text/html; charset=utf-8");

/**
 * 求两个日期之间相差的天数
 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
 * @param string $day1
 * @param string $day2
 * @return number
 */
function diffBetweenTwoDays($day1, $day2)
{
    $second1 = strtotime($day1);
    $second2 = strtotime($day2);

    if ($second1 < $second2) {
        $tmp = $second2;
        $second2 = $second1;
        $second1 = $tmp;
    }
    $seconds_per_day = 60 * 60 * 24;
    return ($second1 - $second2) / $seconds_per_day;
}

/**
 * 计算养老钱进度
 * @param string $start_date    起点日期(开始攒钱)
 * @param int    $years         攒养老钱的年限(终点 = 起点 + N 年)
 * @param string $now           今天
 * @param int    $money_per_day 每天预备的养老钱(元)
 * @return array
 */
function calcPensionPlan($start_date, $years, $now, $money_per_day)
{
    $end_date = date("Y-m-d", strtotime("+{$years} years", strtotime($start_date)));

    $total_days = diffBetweenTwoDays($start_date, $end_date);
    $passed_days = diffBetweenTwoDays($start_date, $now);
    $remain_days = diffBetweenTwoDays($now, $end_date);

    $total_money = $total_days * $money_per_day;
    $saved_money = $passed_days * $money_per_day;
    $remain_money = $remain_days * $money_per_day;

    return array(
        'money_per_day' => $money_per_day,
        'total_money'   => $total_money,
        'saved_money'   => $saved_money,
        'remain_money'  => $remain_money,
        'ratio'         => $total_days > 0 ? $saved_money / $total_money : 0,
    );
}

/**
 * 计算养娃进度
 * @param array  $birthdays    每个孩子的生日数组
 * @param string $now          今天
 * @param int    $grow_up_age  视为可独立生活的年龄
 * @return array
 */
function calcParentingProgress($birthdays, $now, $grow_up_age)
{
    $days_needed = count($birthdays) * $grow_up_age * 365.25;

    $days = 0;
    foreach ($birthdays as $birthday) {
        $days += diffBetweenTwoDays($now, $birthday);
    }

    return array(
        'children'    => count($birthdays),
        'grow_up_age' => $grow_up_age,
        'days'        => $days,
        'days_needed' => $days_needed,
        'ratio'       => $days_needed > 0 ? (float)$days / $days_needed : 0,
    );
}

$date_now = date("Y-m-d");
$weekday_names = array("日", "一", "二", "三", "四", "五", "六");
$weekday = "星期" . $weekday_names[(int)date("w")];

$pension = calcPensionPlan("2017-01-01", 50, $date_now, 500);
$parenting = calcParentingProgress(
    array("2017-01-01", "2021-01-01"),
    $date_now,
    25
);

/**
 * 渲染一个带数值与进度条的卡片
 */
function renderProgressCard($icon, $title, $percent, $lines)
{
    $percent_text = number_format($percent * 100, 2) . "%";
    $bar_width = max(0, min(100, $percent * 100));

    $html = "<div class='card'>";
    $html .= "<div class='card-head'><span class='icon'>$icon</span><span class='card-title'>$title</span><span class='percent'>$percent_text</span></div>";
    $html .= "<div class='bar'><div class='bar-fill' style='width: {$bar_width}%'></div></div>";
    $html .= "<ul class='detail'>";
    foreach ($lines as $line) {
        $html .= "<li>$line</li>";
    }
    $html .= "</ul>";
    $html .= "</div>";
    return $html;
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>养老 & 养娃进度</title>
<style>
  * { box-sizing: border-box; }
  body {
    margin: 0;
    min-height: 100vh;
    font-family: -apple-system, "PingFang SC", "Segoe UI", Roboto, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #1f2430;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
  }
  .wrap { width: 100%; max-width: 560px; }
  .today {
    text-align: center;
    color: #fff;
    font-size: 15px;
    margin-bottom: 20px;
    opacity: .92;
  }
  .today b { font-size: 18px; }
  .card {
    background: #fff;
    border-radius: 16px;
    padding: 22px 24px;
    margin-bottom: 18px;
    box-shadow: 0 10px 30px rgba(31, 36, 48, .18);
  }
  .card-head {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 14px;
  }
  .icon { font-size: 24px; }
  .card-title { font-size: 17px; font-weight: 600; color: #111827; }
  .percent {
    margin-left: auto;
    font-size: 20px;
    font-weight: 700;
    color: #6d28d9;
  }
  .bar {
    height: 12px;
    background: #ece9f7;
    border-radius: 999px;
    overflow: hidden;
  }
  .bar-fill {
    height: 100%;
    border-radius: 999px;
    background: linear-gradient(90deg, #8b5cf6, #ec4899);
    transition: width .6s ease;
  }
  ul.detail {
    list-style: none;
    margin: 16px 0 0;
    padding: 0;
    color: #4b5563;
    font-size: 14px;
    line-height: 1.9;
  }
  ul.detail b { color: #111827; }
</style>
</head>
<body>
<div class="wrap">
  <div class="today">今天是 <b><?php echo $date_now; ?></b> <b><?php echo $weekday; ?></b></div>

  <?php
  echo renderProgressCard(
      "&#128176;",
      "养老钱进度",
      $pension['ratio'],
      array(
          "还需存的养老钱 <b>" . number_format($pension['remain_money']) . "</b> 元",
          "随时间流逝 已自动扣减养老钱 <b>" . number_format($pension['saved_money']) . "</b> 元",
          "从30岁开始 自备50年养老钱 每天" . $pension['money_per_day'] . "元；共计 <b>" . number_format($pension['total_money']) . "</b> 元",
      )
  );

  echo renderProgressCard(
      "&#128118;",
      "养娃进度",
      $parenting['ratio'],
      array(
          "共 <b>" . $parenting['children'] . "</b> 个娃，各养到 <b>" . $parenting['grow_up_age'] . "</b> 岁可独立生活",
          "已养 <b>" . number_format($parenting['days']) . "</b> 人天 / 共需 <b>" . number_format($parenting['days_needed']) . "</b> 人天",
      )
  );
  ?>
</div>
</body>
</html>
