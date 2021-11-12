<?php

$DASHBOARD_YEAR = $_SESSION['year_round'];
$MIN_DASHBOARD_YEAR = $YEAR_bdg_prev_min;

//ตรวจสอบเดือนล่าสุดของปีงบประมาณ
if (date("m") > 9) {
  $YEAR_CHECK = (date("Y") + 543) + 1;
} else {
  $YEAR_CHECK = (date("Y") + 543);
}

$arr_bdg_prev = array();
for ($i = $MIN_DASHBOARD_YEAR; $i <= $DASHBOARD_YEAR; $i++) {

  if ($i < $YEAR_CHECK) {
    $YEAR_NOW = $i;
    $MONTH_NOW = 9;
  } elseif (date("m") == 1) {
    $YEAR_NOW = (date("Y") + 543) - 1;
    $MONTH_NOW = 12;
  } else {
    $YEAR_NOW = (date("Y") + 543);
    $MONTH_NOW = date("m") - 1;
  }

  $MONTH_NOW = sprintf('%02d', $MONTH_NOW);
  $YEAR_MONTH_NOW = $YEAR_NOW . $MONTH_NOW;
  $YEAR_MONTH_NEXT = (date("Y") + 543) . date("m");
  $MAX_MONTH_OF_YEAR = $YEAR_MONTH_NOW > $i . '09' ? '09' : $MONTH_NOW;
  $MAX_YEAR_OF_YEAR = $YEAR_MONTH_NOW > $i . '09' ? $i : $YEAR_NOW;
  $MAX_YEAR_MONTH = $MAX_YEAR_OF_YEAR . $MAX_MONTH_OF_YEAR;
  if ($MAX_YEAR_MONTH > $YEAR_MONTH_NOW) {
    $MAX_YEAR_MONTH = $YEAR_MONTH_NOW;
  }
  $MIN_YEAR_MONTH = ($i - 1) . '10';

  //งบประมาณแต่ละปี
  $sql = "
  SELECT
    YEAR_BDG
    , MONEY_BDG
    , DISBURSE_VALUE AS MONEY_REPORT
    , BINDING_VALUE AS MONEY_BINDING
  FROM disburse_budget
  WHERE YEAR_BDG = '{$i}'
  ORDER BY YEAR_BDG
  ";

  $query = $db->query($sql);
  while ($rec = $db->db_fetch_array($query)) {
    $arr_bdg_prev['YEAR'][$rec['YEAR_BDG']] = $rec['YEAR_BDG'];
    $arr_bdg_prev['BDG'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'];
    $arr_bdg_prev['REPORT'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT'];
    @$arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT'] / $rec['MONEY_BDG'];
    $arr_bdg_prev['BINDING'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING'];
    @$arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING'] / $rec['MONEY_BDG'];
    $arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
    @$arr_bdg_prev['REMAIN_P'][$rec['YEAR_BDG']] = $arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']] / $rec['MONEY_BDG'];
  }

  if (count($arr_bdg_prev) > 0) {
    $wh_bdg_prev = " AND A.YEAR_BDG NOT IN (" . implode(",", array_keys($arr_bdg_prev['YEAR'])) . ") ";
  }
  $sql = "
  SELECT
    YEAR_BDG
    , SUM(MONEY_BDG) AS MONEY_BDG
    , SUM(MONEY_REPORT) AS MONEY_REPORT
    , SUM(MONEY_BINDING) AS MONEY_BINDING
  FROM (
    SELECT
      A.YEAR_BDG
      , A.MONEY_BDG
      ,ISNULL((
        SELECT SUM(AA1.BDG_VALUE)
        FROM prjp_report_money AA1
          INNER JOIN prjp_project AA2 ON AA2.PRJP_ID = AA1.PRJP_ID
        WHERE AA2.PRJP_PARENT_ID = A.PRJP_ID AND AA1.[YEAR]*100+AA1.[MONTH] <= '{$YEAR_MONTH_NOW}'
        ), 0) AS MONEY_REPORT
      , ISNULL((SELECT TOP 1 BINDING_VALUE FROM prjp_binding WHERE PRJP_ID = A.PRJP_ID AND YEAR*100+MONTH <= '{$YEAR_MONTH_NEXT}' ORDER BY YEAR*100+MONTH DESC), 0) AS MONEY_BINDING
    FROM prjp_project A
    WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$i}'
      {$wh_bdg_prev}
  ) TB
  GROUP BY YEAR_BDG
  ORDER BY YEAR_BDG
  ";
  $query = $db->query($sql);
  while ($rec = $db->db_fetch_array($query)) {
    $arr_bdg_prev['YEAR'][$rec['YEAR_BDG']] = $rec['YEAR_BDG'];
    $arr_bdg_prev['BDG'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'];
    $arr_bdg_prev['REPORT'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT'];
    @$arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT'] / $rec['MONEY_BDG'] * 100.00;
    $arr_bdg_prev['BINDING'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING'];
    @$arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING'] / $rec['MONEY_BDG'] * 100.00;
    $arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
    @$arr_bdg_prev['REMAIN_P'][$rec['YEAR_BDG']] = 100.00 - ($arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] + $arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']]);
  }
}

if ($mode == 'BDG') {
  for ($y = $YEAR_bdg_prev_max; $y >= $YEAR_bdg_prev_min + 1; $y--) {
?>
    <tr>
      <td class="text-center"> <?php echo $y; ?> </td>
      <?php if ($no_bdg != 1) { ?>
        <td class="text-right txt-dark"> <?php echo number_format($arr_bdg_prev['BDG'][$y], 2); ?> </td>
      <?php } ?>
      <td class="text-right num-green"> <?php echo number_format($arr_bdg_prev['REPORT'][$y], 2); ?> </td>
      <td class="text-right num-yellow"> <?php echo number_format($arr_bdg_prev['BINDING'][$y], 2); ?> </td>
      <td class="text-right num-red"> <?php echo number_format($arr_bdg_prev['REMAIN'][$y], 2); ?> </td>
    </tr>
  <?php
  }
} else {
  for ($y = $YEAR_bdg_prev_max; $y >= $YEAR_bdg_prev_min + 1; $y--) {
    @$REPORT_P = $arr_bdg_prev['REPORT'][$y] / $arr_bdg_prev['BDG'][$y] * 100.00;
    @$BINDING_P = $arr_bdg_prev['BINDING'][$y] / $arr_bdg_prev['BDG'][$y] * 100.00;
    @$REMAIN_P = $arr_bdg_prev['REMAIN'][$y] / $arr_bdg_prev['BDG'][$y] * 100.00;
  ?>
    <tr>
      <td class="text-center"> <?php echo $y; ?> </td>
      <?php if ($no_bdg != 1) { ?>
        <td class="text-right txt-dark"> <?php echo 100.00; ?> </td>
      <?php } ?>
      <td class="text-right num-green"> <?php echo number_format($REPORT_P, 2); ?> </td>
      <td class="text-right num-yellow"> <?php echo number_format($BINDING_P, 2); ?> </td>
      <td class="text-right num-red"> <?php echo number_format($REMAIN_P, 2); ?> </td>
    </tr>
<?php
  }
}
?>