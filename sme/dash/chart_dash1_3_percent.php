<?php
@set_time_limit(0);
// $path = "../../";
$NoChk = 1;
$path = "../";
include "../dashboard/index_data.php";
// include($path."include/config_header_top.php");
$arrReplace = array(
  '{$DASHBOARD_YEAR}'       => $DASHBOARD_YEAR,
  '{$PROJECT_COUNT}'       => $count_project,
);

if(!empty($_GET['PREV_YEAR'])){
  $DASHBOARD_YEAR = $_SESSION['year_round'];
  $MIN_DASHBOARD_YEAR = $DASHBOARD_YEAR-$_GET['PREV_YEAR']+1;
}else{
  $DASHBOARD_YEAR = $column_stacked_end_year;
  $MIN_DASHBOARD_YEAR = $column_stacked_start_year;
}

//ตรวจสอบเดือนล่าสุดของปีงบประมาณ
if(date("m") > 9){
  $YEAR_CHECK = (date("Y")+543)+1;
}else{
  $YEAR_CHECK = (date("Y")+543);
}

$sql = "SELECT * FROM dash_config3 ";
$query = $db->query($sql);
$dash_config3 = $rec = $db->db_fetch_array($query);
$dash_config3['header_text'] = strtr($dash_config3['header_text'], $arrReplace);

$arr_bdg_prev = array();
for($i=$MIN_DASHBOARD_YEAR; $i<=$DASHBOARD_YEAR; $i++){

  if($i < $YEAR_CHECK){
    $YEAR_NOW = $i;
    $MONTH_NOW = 9;
  }
  elseif(date("m") == 1){
    $YEAR_NOW = (date("Y")+543)-1;
    $MONTH_NOW = 12;
  }else{
    $YEAR_NOW = (date("Y")+543);
    $MONTH_NOW = date("m")-1;
  }

  $MONTH_NOW = sprintf('%02d', $MONTH_NOW);
  $YEAR_MONTH_NOW = $YEAR_NOW.$MONTH_NOW;
  $YEAR_MONTH_NEXT = (date("Y")+543).date("m");
  $MAX_MONTH_OF_YEAR = $YEAR_MONTH_NOW > $i.'09' ? '09' : $MONTH_NOW;
  $MAX_YEAR_OF_YEAR = $YEAR_MONTH_NOW > $i.'09' ? $i : $YEAR_NOW;
  $MAX_YEAR_MONTH = $MAX_YEAR_OF_YEAR.$MAX_MONTH_OF_YEAR;
  if($MAX_YEAR_MONTH > $YEAR_MONTH_NOW){
    $MAX_YEAR_MONTH = $YEAR_MONTH_NOW;
  }
  $MIN_YEAR_MONTH = ($i-1).'10';
  
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
  while($rec = $db->db_fetch_array($query)){
    $arr_bdg_prev['YEAR'][$rec['YEAR_BDG']] = $rec['YEAR_BDG'];
    $arr_bdg_prev['BDG'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'];
    $arr_bdg_prev['REPORT'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT'];
    @$arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT']/$rec['MONEY_BDG'];
    $arr_bdg_prev['BINDING'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING'];
    @$arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING']/$rec['MONEY_BDG'];
    $arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
    @$arr_bdg_prev['REMAIN_P'][$rec['YEAR_BDG']] = $arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']]/$rec['MONEY_BDG'];
  }

  if(count($arr_bdg_prev) > 0){
    $wh_bdg_prev = " AND A.YEAR_BDG NOT IN (".implode(",", array_keys($arr_bdg_prev['YEAR'])).") ";
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
  while($rec = $db->db_fetch_array($query)){
    $arr_bdg_prev['YEAR'][$rec['YEAR_BDG']] = $rec['YEAR_BDG'];
    $arr_bdg_prev['BDG'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'];
    $arr_bdg_prev['REPORT'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT'];
    @$arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] = $rec['MONEY_REPORT']/$rec['MONEY_BDG']*100.00;
    $arr_bdg_prev['BINDING'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING'];
    @$arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']] = $rec['MONEY_BINDING']/$rec['MONEY_BDG']*100.00;
    $arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']] = $rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
    @$arr_bdg_prev['REMAIN_P'][$rec['YEAR_BDG']] = 100.00 - ($arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] + $arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']]);
  }
}

$xAxis = array();
$series1 = array();
$series2 = array();
$series3 = array();
if($_GET['mode'] == 'BDG'){
  for($i=$MIN_DASHBOARD_YEAR; $i<=$DASHBOARD_YEAR; $i++){
    $xAxis[] = $i;
    $series1[] = number_format($arr_bdg_prev['REMAIN'][$i]/1000000.00, 2, '.', '');
    $series2[] = number_format($arr_bdg_prev['BINDING'][$i]/1000000.00, 2, '.', '');
    $series3[] = number_format($arr_bdg_prev['REPORT'][$i]/1000000.00, 2, '.', '');
  }
}else{
  for($i=$MIN_DASHBOARD_YEAR; $i<=$DASHBOARD_YEAR; $i++){
    $xAxis[] = $i;
    @$REPORT_P = $arr_bdg_prev['REPORT'][$i]/$arr_bdg_prev['BDG'][$i]*100.00;
    @$BINDING_P = $arr_bdg_prev['BINDING'][$i]/$arr_bdg_prev['BDG'][$i]*100.00;
    $REMAIN_P = 100 - ($REPORT_P + $BINDING_P);
    @$series1[] = number_format($REMAIN_P, 2, '.', '');
    @$series2[] = number_format($BINDING_P, 2, '.', '');
    @$series3[] = number_format($REPORT_P, 2, '.', '');
  }
}
// print_r($series3);
?>
    <link rel="stylesheet" href="chart/stacked-column/index.css" />
  
    <div id="chartdiv"></div>
    <script src="chart/core.js"></script>
    <script src="chart/charts.js"></script>
    <script src="chart/themes/animated.js"></script>
    <script>
      am4core.useTheme(am4themes_animated);

      var chart = am4core.create("chartdiv", am4charts.XYChart);

      chart.data = [ ]

      <?php foreach ($series1 as $key => $value) { ?>

      chart.data.push({
          "category": "<?php echo $xAxis[$key]; ?>",
          "value1": "<?php echo $series3[$key]; ?>",
          "value2": "<?php echo $series2[$key]; ?>",
          "value3": "<?php echo $series1[$key]; ?>"
      })
      <?php } ?>

      chart.colors.step = 2;
      chart.padding(30, 30, 10, 30);

      chart.legend = new am4charts.Legend();
      chart.legend.itemContainers.template.cursorOverStyle = am4core.MouseCursorStyle.pointer;

      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "category";
      categoryAxis.renderer.minGridDistance = 60;
      categoryAxis.renderer.grid.template.location = 0;
      categoryAxis.interactionsEnabled = false;

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
      valueAxis.min = 0;
      valueAxis.max = 100;
      valueAxis.strictMinMax = true;
      valueAxis.calculateTotals = true;

      valueAxis.renderer.minGridDistance = 20;
      valueAxis.renderer.minWidth = 35;

      var series1 = chart.series.push(new am4charts.ColumnSeries());
      series1.columns.template.tooltipText = "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
      series1.columns.template.column.strokeOpacity = 1;
      series1.name = "ยอดเบิกจ่าย";
      series1.dataFields.categoryX = "category";
      series1.dataFields.valueY = "value1";
      series1.dataFields.valueYShow = "totalPercent";
      series1.dataItems.template.locations.categoryX = 0.5;
      series1.stacked = true;
      series1.tooltip.pointerOrientation = "vertical";
      series1.tooltip.dy = - 20;
      series1.cursorHoverEnabled = false;
      series1.fill = am4core.color("#2e8b2c");

      var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
      bullet1.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
      bullet1.locationY = 0.5;
      bullet1.label.fill = am4core.color("#ffffff");
      bullet1.interactionsEnabled = false;

      var series2 = chart.series.push(series1.clone());
      series2.name = "ยอดผูกพัน";
      series2.dataFields.valueY = "value2";
      series2.fill = chart.colors.next();
      /*series2.stroke = series2.fill;*/
      series2.fill = am4core.color("#ed9416");
      series2.cursorHoverEnabled = false;

      var series3 = chart.series.push(series1.clone());
      series3.name = "ยอดคงเหลือ";
      series3.dataFields.valueY = "value3";
      series3.fill = chart.colors.next();
      /*series3.stroke = series3.fill;*/
      series3.fill = am4core.color("#b81723");
      series3.cursorHoverEnabled = false;

      chart.scrollbarX = new am4core.Scrollbar();

      chart.cursor = new am4charts.XYCursor();
      chart.cursor.behavior = "panX";
    
    </script>
  