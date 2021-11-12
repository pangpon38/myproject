<?php
@set_time_limit(0);
$NoChk = 1;
$path_cache = 'cache/';
$FILE_NAME = pathinfo(__FILE__, PATHINFO_FILENAME);
include($path."include/config_header_top.php");

//ตรวจสอบเดือนล่าสุดของปีงบประมาณ
if(date("m") > 9){
	$YEAR_CHECK = (date("Y")+543)+1;
}else{
	$YEAR_CHECK = (date("Y")+543);
}

if($_GET['year']){
	$_SESSION['year_round'] = $_GET['year'];
}
elseif(empty($_SESSION['year_round'])){
	$_SESSION['year_round'] = $YEAR_CHECK;
}

$DASHBOARD_YEAR = $_SESSION['year_round'];

$sql_year = "select YEAR_BDG FROM plan_round ORDER BY YEAR_BDG DESC ";
$query_year = $db->query($sql_year);
while($rec_y = $db->db_fetch_array($query_year)){
	$arr_y[$rec_y['YEAR_BDG']] = $rec_y['YEAR_BDG'];
}
$default_year_round = array(
		'10'=> (($DASHBOARD_YEAR - 1)*100) + 10 ,
		'11'=> (($DASHBOARD_YEAR - 1)*100) + 11 ,
		'12'=> (($DASHBOARD_YEAR - 1)*100) + 12 ,
		'01'=> ($DASHBOARD_YEAR*100) + 1,
		'02'=> ($DASHBOARD_YEAR*100) + 2,
		'03'=> ($DASHBOARD_YEAR*100) + 3,
		'04'=> ($DASHBOARD_YEAR*100) + 4,
		'05'=> ($DASHBOARD_YEAR*100) + 5,
		'06'=> ($DASHBOARD_YEAR*100) + 6,
		'07'=> ($DASHBOARD_YEAR*100) + 7,
		'08'=> ($DASHBOARD_YEAR*100) + 8,
		'09'=> ($DASHBOARD_YEAR*100) + 9,
		); 
		
		



if($DASHBOARD_YEAR < $YEAR_CHECK){
	$YEAR_NOW = $DASHBOARD_YEAR;
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
$MAX_MONTH_OF_YEAR = $YEAR_MONTH_NOW > $DASHBOARD_YEAR.'09' ? '09' : $MONTH_NOW;
$MAX_YEAR_OF_YEAR = $YEAR_MONTH_NOW > $DASHBOARD_YEAR.'09' ? $DASHBOARD_YEAR : $YEAR_NOW;
$MAX_YEAR_MONTH = $MAX_YEAR_OF_YEAR.$MAX_MONTH_OF_YEAR;
if($MAX_YEAR_MONTH > $YEAR_MONTH_NOW){
	$MAX_YEAR_MONTH = $YEAR_MONTH_NOW;
}
$MIN_YEAR_MONTH = ($DASHBOARD_YEAR-1).'10';

$arr_year_month = array();
$arr_year = array();
for($y_m=$MIN_YEAR_MONTH;$y_m<=$MAX_YEAR_MONTH;$y_m++){
	$y = substr($y_m, 0, 4);
	$m = substr($y_m, 4, 2);
	
	$arr_year_month[$y_m]['Y'] = $y;
	$arr_year_month[$y_m]['M'] = $m;
	$arr_year_month[$y_m]['Y_SH'] = substr($y, 2, 2);
	$arr_year_month[$y_m]['M_T'] = $arr_month_short[$m];
	
	$arr_year[$y]++;
	
	if($m==12){
		$y_m = ($y+1).sprintf('%02d', '00');
	}
}

$arr_pie_color = array();
$arr_pie_color[] = '#35afbe';
$arr_pie_color[] = '#145470';
$arr_pie_color[] = '#e65a0f';
$arr_pie_color[] = '#317bd4';
$arr_pie_color[] = '#0077A2';
$arr_data_detail_text = array('เบิกจ่าย', 'ผูกพัน', '%เบิกจ่ายและผูกพัน', 'คงเหลือ');
$arr_result_type = array("ล่าช้า" , "ใกล้เคียง", "ตามแผน");
$arr_result_type_full = array("ช้ากว่าแผน" , "ใกล้เคียงกับแผน", "ตามแผน");

$sum_bdg = $db->get_data_field("SELECT SUM(MONEY_BDG) AS MONEY_BDG FROM prjp_project  A
	WHERE PRJP_LEVEL = '1' AND YEAR_BDG = '".$DASHBOARD_YEAR."' AND PRJP_STATUS_SHOW = '1' ", 'MONEY_BDG');
$sum_bdg_sme = $db->get_data_field("SELECT SUM(MONEY_BDG_SME) AS MONEY_BDG_SME FROM prjp_project A
	WHERE PRJP_LEVEL = '1' AND YEAR_BDG = '".$DASHBOARD_YEAR."' AND PRJP_STATUS_SHOW = '1' ", 'MONEY_BDG_SME');
$sum_bdg_out = $db->get_data_field("SELECT SUM(MONEY_BDG_OUT) AS MONEY_BDG_OUT FROM prjp_project A
	WHERE PRJP_LEVEL = '1' AND YEAR_BDG = '".$DASHBOARD_YEAR."' AND PRJP_STATUS_SHOW = '1' ", 'MONEY_BDG_OUT');

$arr_money = array(
	1 =>array(
		'color'=>'#0177c1',
		'name'=>'รวมงบประมาณ',
		'money'=>$sum_bdg,
		'class'=>'col-lg-6 col-sm-12 font1',
	),
	2 =>array(
		'color'=>'#00a652',
		'name'=>'เงินงบประมาณ',
		'money'=>$sum_bdg_sme,
		'class'=>'col-lg-3 col-sm-6 font2',
	),
	3 =>array(
		'color'=>'#f36523',
		'name'=>'เงินนอกงบประมาณ',
		'money'=>$sum_bdg_out,
		'class'=>'col-lg-3 col-sm-6 font2',
	),
);

$sql = "SELECT * FROM setup_org_bu ";
$query = $db->query($sql);
$arr_all_org = array();
while($rec = $db->db_fetch_array($query)){
	$arr_all_org[$rec['ORG_ID']] = $rec;
}

$arr_dash_unit = array();
//PRODUCT
$sql = "SELECT A.dash_unit_id, A.dash_name, A.dash_icon, A.dash_color, D.UNIT_NAME_TH AS dash_unit, SUM(C.PLAN_VALUE) AS dash_value
		, A.dash_size,A.dash_icon_status, A.dash_value_add ,A.dash_order_no
	FROM dash_unit A
		LEFT JOIN dash_unit_product B ON B.dash_unit_id = A.dash_unit_id
		LEFT JOIN prjp_report_product C ON C.PRJP_PRODUCT_ID = B.PRJP_PRODUCT_ID
		LEFT JOIN setup_unit D ON D.UNIT_ID = A.UNIT_ID
	WHERE A.YEAR_BDG = '{$DASHBOARD_YEAR}'
	GROUP BY A.dash_unit_id, A.dash_name, A.dash_icon, A.dash_color, D.UNIT_NAME_TH, A.dash_size,A.dash_icon_status, A.dash_value_add,A.dash_order_no
	ORDER BY A.dash_order_no,A.dash_unit_id 
	";
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$arr_dash_unit[$rec['dash_unit_id']]['dash_unit_id'] = $rec['dash_unit_id'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_name'] = $rec['dash_name'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_icon'] = $rec['dash_icon'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_color'] = $rec['dash_color'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_unit'] = $rec['dash_unit'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_value'] = $rec['dash_value']+$rec['dash_value_add'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_size'] = $rec['dash_size'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_icon_status'] = $rec['dash_icon_status'];
	
	$dash_value = $arr_dash_unit[$rec['dash_unit_id']]['dash_value'];
	$dash_decimal = ceil($dash_value) == floor($dash_value) ? 0 : 2;
	$arr_dash_unit[$rec['dash_unit_id']]['dash_decimal'] = $dash_decimal;
}

//RESULT
$sql = "SELECT A.dash_unit_id, A.dash_name, A.dash_icon, A.dash_color, D.UNIT_NAME_TH AS dash_unit, SUM(C.PLAN_VALUE) AS dash_value
		, A.dash_size,A.dash_icon_status
	FROM dash_unit A
		LEFT JOIN dash_unit_result B ON B.dash_unit_id = A.dash_unit_id
		LEFT JOIN prjp_report_result C ON C.PRJP_RESULT_ID = B.PRJP_RESULT_ID
		LEFT JOIN setup_unit D ON D.UNIT_ID = A.UNIT_ID
	WHERE A.YEAR_BDG = '{$DASHBOARD_YEAR}'
	GROUP BY A.dash_unit_id, A.dash_name, A.dash_icon, A.dash_color, D.UNIT_NAME_TH, A.dash_size,A.dash_icon_status
	ORDER BY A.dash_unit_id
	";
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$arr_dash_unit[$rec['dash_unit_id']]['dash_unit_id'] = $rec['dash_unit_id'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_name'] = $rec['dash_name'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_icon'] = $rec['dash_icon'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_color'] = $rec['dash_color'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_unit'] = $rec['dash_unit'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_value'] += $rec['dash_value'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_size'] = $rec['dash_size'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_icon_status'] = $rec['dash_icon_status'];
	
	$dash_value = $arr_dash_unit[$rec['dash_unit_id']]['dash_value'];
	$dash_decimal = ceil($dash_value) == floor($dash_value) ? 0 : 2;
	$arr_dash_unit[$rec['dash_unit_id']]['dash_decimal'] = $dash_decimal;
}


//ECONOMIC
$sql = "SELECT A.dash_unit_id, A.dash_name, A.dash_icon, A.dash_color, D.UNIT_NAME_TH AS dash_unit, SUM(C.economic_value) AS dash_value
		, A.dash_size,A.dash_icon_status
	FROM dash_unit A
		LEFT JOIN dash_unit_economic B ON B.dash_unit_id = A.dash_unit_id
		LEFT JOIN prjp_eco C ON C.PRJP_ID = B.PRJP_ID
		LEFT JOIN setup_unit D ON D.UNIT_ID = A.UNIT_ID
	WHERE A.YEAR_BDG = '{$DASHBOARD_YEAR}'
	GROUP BY A.dash_unit_id, A.dash_name, A.dash_icon, A.dash_color, D.UNIT_NAME_TH, A.dash_size,A.dash_icon_status
	ORDER BY A.dash_unit_id
	";
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$arr_dash_unit[$rec['dash_unit_id']]['dash_unit_id'] = $rec['dash_unit_id'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_name'] = $rec['dash_name'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_icon'] = $rec['dash_icon'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_color'] = $rec['dash_color'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_unit'] = $rec['dash_unit'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_value'] += $rec['dash_value'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_size'] = $rec['dash_size'];
	$arr_dash_unit[$rec['dash_unit_id']]['dash_icon_status'] = $rec['dash_icon_status'];
	
	$dash_value = $arr_dash_unit[$rec['dash_unit_id']]['dash_value'];
	$dash_decimal = ceil($dash_value) == floor($dash_value) ? 0 : 2;
	$arr_dash_unit[$rec['dash_unit_id']]['dash_decimal'] = $dash_decimal;
}

$sql = "
	SELECT COUNT(A.PRJP_ID) as count_project
	FROM prjp_project A
	WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}'
		AND A.PRJP_ID IS NOT NULL
		{$wh_search}
";
$query = $db->query($sql);
$rec = $db->db_fetch_array($query);
$count_project = $rec['count_project'] > 0 ? $rec['count_project'] : 0;

$arrReplace = array(
	'{$DASHBOARD_YEAR}'       => $DASHBOARD_YEAR,
	'{$PROJECT_COUNT}'       => $count_project,
);


$sql = "SELECT * FROM dash_config1 ";
$query = $db->query($sql);
$dash_config1 = $rec = $db->db_fetch_array($query);
$dash_config1['header_text'] = strtr($dash_config1['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config2 ";
$query = $db->query($sql);
$dash_config2 = $rec = $db->db_fetch_array($query);
$dash_config2['header_text'] = strtr($dash_config2['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config_table1 ";
$query = $db->query($sql);
$dash_config_table1 = $rec = $db->db_fetch_array($query);
$dash_config_table1['header_text'] = strtr($dash_config_table1['header_text'], $arrReplace);
$dash_config_table1['header2_text'] = strtr($dash_config_table1['header2_text'], $arrReplace);
$dash_config_table1['header3_text'] = strtr($dash_config_table1['header3_text'], $arrReplace);

$sql = "SELECT * FROM dash_config3 ";
$query = $db->query($sql);
$dash_config3 = $rec = $db->db_fetch_array($query);
$dash_config3['header_text'] = strtr($dash_config3['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config5 ";
$query = $db->query($sql);
$dash_config5 = $rec = $db->db_fetch_array($query);
$dash_config5['header_text'] = strtr($dash_config5['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config6 ";
$query = $db->query($sql);
$dash_config6 = $rec = $db->db_fetch_array($query);
$dash_config6['header_text'] = strtr($dash_config6['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config7 ";
$query = $db->query($sql);
$dash_config7 = $rec = $db->db_fetch_array($query);
$dash_config7['header_text'] = strtr($dash_config7['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config9 ";
$query = $db->query($sql);
$dash_config9 = $rec = $db->db_fetch_array($query);
$dash_config9['header_text'] = strtr($dash_config9['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config10 ";
$query = $db->query($sql);
$dash_config10 = $rec = $db->db_fetch_array($query);
$dash_config10['header_text'] = strtr($dash_config10['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config12 ";
$query = $db->query($sql);
$dash_config12 = $rec = $db->db_fetch_array($query);
$dash_config12['header_text'] = strtr($dash_config12['header_text'], $arrReplace);

$sql = "SELECT * FROM dash_config11 ";
$query = $db->query($sql);
$dash_config11 = $rec = $db->db_fetch_array($query);
$dash_config11['header_text'] = strtr($dash_config11['header_text'], $arrReplace);


$sql = "SELECT * FROM plan_strgic3 WHERE STRGIC_LEVEL = 1 AND YEAR_BDG = '{$DASHBOARD_YEAR}'";
$query = $db->query($sql);
$arr_plan_strgic3 = array();
while($rec = $db->db_fetch_array($query)){
	$arr_plan_strgic3[$rec['STRGIC_ID']] = $rec;
}

$sql = "SELECT * FROM config_rate_status WHERE YEAR_BDG = '{$DASHBOARD_YEAR}' ORDER BY rate_status_percent DESC ";
$query = $db->query($sql);
$arr_config_rate_status = array();
while($rec = $db->db_fetch_array($query)){
	$arr_config_rate_status[$rec['rate_status_percent']]['rate_status_name'] = text($rec['rate_status_name']);
	$arr_config_rate_status[$rec['rate_status_percent']]['rate_status_color'] = $rec['rate_status_color'];
}
if(count($arr_config_rate_status) == 0){
	$arr_config_rate_status[80]['rate_status_name'] = $arr_result_type_full[2];
	$arr_config_rate_status[80]['rate_status_color'] = 'green';
	
	$arr_config_rate_status[60]['rate_status_name'] = $arr_result_type_full[1];
	$arr_config_rate_status[60]['rate_status_color'] = 'orange';
	
	$arr_config_rate_status[0]['rate_status_name'] = $arr_result_type_full[0];
	$arr_config_rate_status[0]['rate_status_color'] = 'red';
}

$sql = "
SELECT *
	, (CASE WHEN MONEY_REPORT >= MONEY_BDG THEN 100 WHEN MONEY_BDG > 0 THEN MONEY_REPORT/MONEY_BDG*100 ELSE 0 END) AS MONEY_REPORT_PERC
	, MONEY_REPORT+MONEY_BINDING AS SUM_MONEY_BINDING
	, (CASE WHEN (MONEY_REPORT+MONEY_BINDING) >= MONEY_BDG THEN 100 WHEN MONEY_BDG > 0 THEN (MONEY_REPORT+MONEY_BINDING)/MONEY_BDG*100 ELSE 0 END) AS SUM_MONEY_BINDING_PERC
FROM (
	SELECT A.PRJP_ID, A.PRJP_CODE, A.PRJP_NAME, A.MONEY_BDG, A.STRGIC_ID, A.STRGIC_ID2, A.STRGIC_ID3, PS3.STRGIC_CODE AS STRGIC_CODE3, PS3.STRGIC_NAME AS STRGIC_NAME3, D2.ORG_SHORTNAME, A.PRJP_RUN_STATUS
		,ISNULL((
			SELECT SUM(AA1.BDG_VALUE)
			FROM prjp_report_money AA1
				INNER JOIN prjp_project AA2 ON AA2.PRJP_ID = AA1.PRJP_ID
			WHERE AA2.PRJP_PARENT_ID = A.PRJP_ID AND AA1.[YEAR]*100+AA1.[MONTH] <= '{$YEAR_MONTH_NOW}'
			), 0) AS MONEY_REPORT
		, ISNULL((SELECT TOP 1 BINDING_VALUE FROM prjp_binding WHERE PRJP_ID = A.PRJP_ID AND YEAR*100+MONTH <= '{$YEAR_MONTH_NEXT}' ORDER BY YEAR*100+MONTH DESC), 0) AS MONEY_BINDING
	FROM prjp_project A
	LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
	LEFT JOIN setup_org_bu D ON D.ORG_ID = (CASE WHEN D2.ORG_LEVEL = 2 THEN D2.ORG_PARENT_ID ELSE D2.ORG_ID END)
	LEFT JOIN setup_org_type DT ON DT.ORG_TYPE_ID = D.ORG_TYPE_ID
	LEFT JOIN setup_org_bu ON setup_org_bu.ORG_ID = A.ORG_ID
	LEFT JOIN plan_strgic3 PS3 ON PS3.STRGIC_ID = A.STRGIC_ID3
	WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}'
		{$wh_search}
) TB
WHERE PRJP_ID IS NOT NULL {$wh}
ORDER BY RIGHT(PRJP_CODE, 3) ASC, PRJP_CODE ASC,PRJP_ID ASC
";
//  echo $sql;
$query = $db->query($sql);
$arr_pie = array();
$i=0;
$slide01_total_bdg = 0;
$arr_table_slide_01 = array();
while($rec = $db->db_fetch_array($query)){
	if(!empty($rec['STRGIC_CODE3'])){
		$arr_pie[$rec['STRGIC_CODE3']]['percent'] += $rec['MONEY_BDG'];
		$arr_pie[$rec['STRGIC_CODE3']]['topic'] = text($rec['STRGIC_NAME3']);
		$arr_pie[$rec['STRGIC_CODE3']]['name'] = text($rec['STRGIC_CODE3']);
		$arr_pie[$rec['STRGIC_CODE3']]['data_money'] += $rec['MONEY_BDG'];
		$arr_pie[$rec['STRGIC_CODE3']]['data_all']++;
		$arr_pie[$rec['STRGIC_CODE3']]['data_complete'] += $rec['PRJP_RUN_STATUS'] == 2 ? 1 : 0;
		$arr_pie[$rec['STRGIC_CODE3']]['data_detail'][0] += $rec['MONEY_REPORT'];
		$arr_pie[$rec['STRGIC_CODE3']]['data_detail'][1] += $rec['MONEY_BINDING'];
		$arr_pie[$rec['STRGIC_CODE3']]['data_detail'][2] = 0.00;
		$arr_pie[$rec['STRGIC_CODE3']]['data_detail'][3] += $rec['MONEY_BDG']-($rec['MONEY_REPORT']+$rec['MONEY_BINDING']);

		$arr_table_slide_01[1]['NAME'] = text($dash_config_table1['header3_text']);
		$arr_table_slide_01[1]['COLOR'] = text($dash_config_table1['header3_color']);
		$arr_table_slide_01[1]['MONEY_BDG'] += $rec['MONEY_BDG'];
		$arr_table_slide_01[1]['MONEY_REPORT'] += $rec['MONEY_REPORT'];
		$arr_table_slide_01[1]['MONEY_BINDING'] += $rec['MONEY_BINDING'];
		$arr_table_slide_01[1]['MONEY_REPORT_BINDING'] += ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
		$arr_table_slide_01[1]['MONEY_REMAIN'] += ($rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']));

		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['NAME'] = text($rec['STRGIC_NAME3']);
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['MONEY_BDG'] += $rec['MONEY_BDG'];
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['MONEY_REPORT'] += $rec['MONEY_REPORT'];
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['MONEY_BINDING'] += $rec['MONEY_BINDING'];
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['MONEY_REPORT_BINDING'] += ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['MONEY_REMAIN'] += ($rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']));

		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['DTL'][$rec['PRJP_CODE']]['NAME'] = text($rec['PRJP_NAME']);
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['DTL'][$rec['PRJP_CODE']]['MONEY_BDG'] = $rec['MONEY_BDG'];
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['DTL'][$rec['PRJP_CODE']]['MONEY_REPORT'] = $rec['MONEY_REPORT'];
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['DTL'][$rec['PRJP_CODE']]['MONEY_BINDING'] = $rec['MONEY_BINDING'];
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['DTL'][$rec['PRJP_CODE']]['MONEY_REPORT_BINDING'] = ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
		$arr_table_slide_01[1]["DTL"][$rec['STRGIC_CODE3']]['DTL'][$rec['PRJP_CODE']]['MONEY_REMAIN'] = ($rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']));

		$slide01_total_bdg += $rec['MONEY_BDG'];
	}
}

if(count($arr_pie) > 0){
	foreach($arr_pie as $STRGIC_CODE3 => $val){
		@$arr_pie[$STRGIC_CODE3]['percent'] = number_format($arr_pie[$STRGIC_CODE3]['percent']/$sum_bdg*100, 6);
	}
}
$i=0;
$j=0;
if(count($arr_plan_strgic3) > 0){
	foreach($arr_plan_strgic3 as $STRGIC_CODE3 => $rec){
		$arr_pie[$STRGIC_CODE3]['percent'] = number_format($arr_pie[$STRGIC_CODE3]['percent'], 6);
		$arr_pie[$STRGIC_CODE3]['topic'] = text($rec['STRGIC_NAME']);
		$arr_pie[$STRGIC_CODE3]['name'] = text($rec['STRGIC_CODE']);
		$arr_pie[$STRGIC_CODE3]['data_money'] = $arr_pie[$STRGIC_CODE3]['data_money'];
		$arr_pie[$STRGIC_CODE3]['data_all'] = $arr_pie[$STRGIC_CODE3]['data_all'];
		$arr_pie[$STRGIC_CODE3]['data_complete'] = $arr_pie[$STRGIC_CODE3]['data_complete'];
		if($arr_pie[$STRGIC_CODE3]['fgcolor']==''){
			$arr_pie[$STRGIC_CODE3]['fgcolor'] = $arr_pie_color[$j];
		}
		$j++;
		if($j >= count($arr_pie_color)){
			$j=0;
		}
		$arr_pie[$STRGIC_CODE3]['data_detail'][0] = $arr_pie[$STRGIC_CODE3]['data_detail'][0];
		$arr_pie[$STRGIC_CODE3]['data_detail'][1] = $arr_pie[$STRGIC_CODE3]['data_detail'][1];
		@$arr_pie[$STRGIC_CODE3]['data_detail'][2] = ($arr_pie[$STRGIC_CODE3]['data_detail'][0]+$arr_pie[$STRGIC_CODE3]['data_detail'][1])/$arr_pie[$STRGIC_CODE3]['data_money']*100;
		$arr_pie[$STRGIC_CODE3]['data_detail'][3] = $arr_pie[$STRGIC_CODE3]['data_detail'][3];
	}
}
//print_arr($arr_pie);
@ksort($arr_pie);

$sql = "
SELECT
	A.PRJP_ID
	, A.PRJP_CODE
	, A.YEAR_BDG
	, CAST(A.PRJP_NAME AS VARCHAR(4000)) AS PRJP_NAME
	, SUM(C.BDG_VALUE)*100/A.MONEY_BDG AS BDG_VALUE
	, (SELECT TOP 1 BINDING_VALUE FROM prjp_binding WHERE YEAR*100+MONTH <= '{$YEAR_MONTH_NEXT}' AND PRJP_ID = A.PRJP_ID ORDER BY MONTH DESC) AS BINDING_VALUE
FROM prjp_project A
	LEFT JOIN prjp_project A2 ON A2.PRJP_PARENT_ID = A.PRJP_ID
	LEFT JOIN prjp_report_money C ON C.PRJP_ID = A2.PRJP_ID AND C.YEAR*100+C.MONTH <= '".$YEAR_MONTH_NOW."'
WHERE A.PRJP_LEVEL = '1' AND A.YEAR_BDG = '".$DASHBOARD_YEAR."' AND A.PRJP_STATUS_SHOW = '1'
GROUP BY
	A.PRJP_ID
	, A.PRJP_CODE
	, A.MONEY_BDG
	, CAST(A.PRJP_NAME AS VARCHAR(4000))
	, A.YEAR_BDG
ORDER BY
	A.PRJP_CODE ASC
";
$query = $db->query($sql);
$arr_prjp = array();
$arr_prjp_name = array();
while($rec = $db->db_fetch_array($query)){
	$arr_prjp[] = number_format($rec['BDG_VALUE'], 2, '.', '');
	$arr_prjp_name[] = text($rec['PRJP_CODE']." ".$rec['PRJP_NAME']);
}

$sql = "
	SELECT *
		, (CASE WHEN MONEY_2 >= MONEY_1 THEN 100 WHEN MONEY_1 > 0 THEN MONEY_2/MONEY_1*100 ELSE 0 END) AS R_T
		, (CASE WHEN MONEY_2 >= MONEY_1 THEN 2 WHEN MONEY_1 > 0 AND MONEY_2/MONEY_1*100>=80 THEN 2 WHEN MONEY_1 > 0 AND MONEY_2/MONEY_1*100>=60 THEN 1 ELSE 0 END) AS COLUMN_T
		, (CASE WHEN MONEY_4 >= MONEY_3 THEN 100 WHEN MONEY_3 > 0 THEN MONEY_4/MONEY_3*100 ELSE 0 END) AS R_M
		, (CASE WHEN MONEY_4 >= MONEY_3 THEN 2 WHEN MONEY_3 > 0 AND MONEY_4/MONEY_3*100>=80 THEN 2 WHEN MONEY_3 > 0 AND MONEY_4/MONEY_3*100>=60 THEN 1 ELSE 0 END) AS COLUMN_M
	FROM
	(
	SELECT A.YEAR_BDG AS YEAR_BDG, A.PRJP_ID, A.PRJP_CODE, A.PRJP_NAME, A.STRGIC_ID, A.STRGIC_ID2, A.STRGIC_ID3
		, D.ORG_TYPE_ID AS ORG_TYPE_ID1
		, D.ORG_ID AS ORG_ID1, D.ORG_LEVEL AS ORG_LEVEL1, D.ORG_NAME AS ORG_NAME1
		, D2.ORG_TYPE_ID AS ORG_TYPE_ID2
		, D2.ORG_ID AS ORG_ID2, D2.ORG_LEVEL AS ORG_LEVEL2, D2.ORG_NAME AS ORG_NAME2, D2.ORG_SHORTNAME AS ORG_SHORTNAME2
		,ISNULL((SELECT SUM(PLAN_T) FROM wh_project_plan_task WHERE PRJP_ID = A.PRJP_ID AND YEAR_MONTH <= '{$YEAR_MONTH_NOW}'), 0) AS MONEY_1
		,ISNULL((SELECT REPORT_T FROM wh_project_report_task WHERE PRJP_ID = A.PRJP_ID AND YEAR_MONTH = '{$YEAR_MONTH_NOW}'), 0) AS MONEY_2
		,ISNULL((SELECT SUM(PLAN_M) FROM wh_project_plan_money WHERE PRJP_ID = A.PRJP_ID AND YEAR_MONTH <= '{$YEAR_MONTH_NOW}'), 0) AS MONEY_3
		,ISNULL((SELECT SUM(REPORT_M) FROM wh_project_report_money WHERE PRJP_ID = A.PRJP_ID AND YEAR_MONTH <= '{$YEAR_MONTH_NOW}'), 0) AS MONEY_4
		, RIGHT('00'+CAST(MONTH(EDATE_PRJP) AS VARCHAR(2)), 2) AS END_TIME
	FROM prjp_project A
	LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
	LEFT JOIN setup_org_bu D ON D.ORG_ID = (CASE WHEN D2.ORG_LEVEL = 2 THEN D2.ORG_PARENT_ID ELSE D2.ORG_ID END)
	LEFT JOIN setup_org_bu ON setup_org_bu.ORG_ID = A.ORG_ID
	WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}'
		{$wh_search}
	) TB
	WHERE PRJP_ID IS NOT NULL
	ORDER BY ORG_SHORTNAME2, RIGHT(PRJP_CODE, 3) ASC, PRJP_CODE ASC,PRJP_ID ASC
";
$query = $db->query($sql);
$arr_container_result1 = array();
$arr_container_result2 = array();
$arr_container_result3 = array();

$arr_container_result_percent1 = array();
$arr_container_result_percent2 = array();
$arr_container_result_percent3 = array();

$arr_org1 = array();
$arr_org2 = array();

$count_project = 0;
while($rec = $db->db_fetch_array($query)){
	
	foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
		if($rec['R_T'] >= $rate_status_percent){
			$rec['COLUMN_T'] = $rate_status_percent;
			break;
		}
	}
	// echo $rec['R_T']."<>".$rec['COLUMN_T']."<br />";
	foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
		if($rec['R_M'] >= $rate_status_percent){
			$rec['COLUMN_M'] = $rate_status_percent;
			break;
		}
	}
	
	if($rec['ORG_ID1'] == 313){
		$rec['ORG_ID1'] = 199;//fix กลุ่มงาน ฝอก.(บง) ให้ไปอยู่ใน กลุ่มอำนวยการ
		$rec['ORG_NAME1'] = $arr_all_org[199]['ORG_NAME'];//fix กลุ่มงาน ฝอก.(บง) ให้ไปอยู่ใน กลุ่มอำนวยการ
	}
	elseif($rec['ORG_ID1'] == 237){
		$rec['ORG_ID1'] = 147;//fix กลุ่มงาน ฝอก.(บง) ให้ไปอยู่ใน กลุ่มอำนวยการ
		$rec['ORG_NAME1'] = $arr_all_org[147]['ORG_NAME'];//fix กลุ่มงาน ฝอก.(บง) ให้ไปอยู่ใน กลุ่มอำนวยการ
	}

	$count_project++;

	$arr_container_result1[1]['t'][$rec['COLUMN_T']]++;
	$arr_container_result1[1]['m'][$rec['COLUMN_M']]++;

	$arr_container_result_percent1[1]['MONEY_1'] += $rec['MONEY_1'];
	$arr_container_result_percent1[1]['MONEY_2'] += $rec['MONEY_2'];
	$arr_container_result_percent1[1]['MONEY_3'] += $rec['MONEY_3'];
	$arr_container_result_percent1[1]['MONEY_4'] += $rec['MONEY_4'];
	$arr_container_result_percent1[1]['COUNT']++;

	$arr_container_result2[$rec['ORG_ID2']]['t'][$rec['COLUMN_T']]++;
	$arr_container_result2[$rec['ORG_ID2']]['m'][$rec['COLUMN_M']]++;
	$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['NAME'] = $rec['PRJP_NAME'];
	$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['t'][$rec['COLUMN_T']] = 1;
	$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['m'][$rec['COLUMN_M']] = 1;

	$arr_container_result_percent2[$rec['ORG_ID2']]['MONEY_1'] += $rec['MONEY_1'];
	$arr_container_result_percent2[$rec['ORG_ID2']]['MONEY_2'] += $rec['MONEY_2'];
	$arr_container_result_percent2[$rec['ORG_ID2']]['MONEY_3'] += $rec['MONEY_3'];
	$arr_container_result_percent2[$rec['ORG_ID2']]['MONEY_4'] += $rec['MONEY_4'];
	$arr_container_result_percent2[$rec['ORG_ID2']]['COUNT']++;

	$arr_container_result3[$rec['ORG_ID1']]['t'][$rec['COLUMN_T']]++;
	$arr_container_result3[$rec['ORG_ID1']]['m'][$rec['COLUMN_M']]++;
	$arr_container_result3[$rec['ORG_ID1']]['DTL'][$rec['ORG_ID2']]['t'][$rec['COLUMN_T']]++;
	$arr_container_result3[$rec['ORG_ID1']]['DTL'][$rec['ORG_ID2']]['m'][$rec['COLUMN_M']]++;

	$arr_container_result_percent3[$rec['ORG_ID1']]['t'] += $rec['COLUMN_T'];
	$arr_container_result_percent3[$rec['ORG_ID1']]['m'] += $rec['COLUMN_M'];

	$arr_container_result_percent3[$rec['ORG_ID1']]['MONEY_1'] += $rec['MONEY_1'];
	$arr_container_result_percent3[$rec['ORG_ID1']]['MONEY_2'] += $rec['MONEY_2'];
	$arr_container_result_percent3[$rec['ORG_ID1']]['MONEY_3'] += $rec['MONEY_3'];
	$arr_container_result_percent3[$rec['ORG_ID1']]['MONEY_4'] += $rec['MONEY_4'];
	$arr_container_result_percent3[$rec['ORG_ID1']]['COUNT']++;

	$arr_org2[$rec['ORG_ID2']] = text($rec['ORG_NAME2']);
	$arr_org1[$rec['ORG_ID1']] = text($rec['ORG_NAME1']);
	
	foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
		$arr_container_result1[1]['t'][$rate_status_percent] = (int)$arr_container_result1[1]['t'][$rate_status_percent];
		$arr_container_result1[1]['m'][$rate_status_percent] = (int)$arr_container_result1[1]['m'][$rate_status_percent];

		$arr_container_result2[$rec['ORG_ID2']]['t'][$rate_status_percent] = (int)$arr_container_result2[$rec['ORG_ID2']]['t'][$rate_status_percent];
		$arr_container_result2[$rec['ORG_ID2']]['m'][$rate_status_percent] = (int)$arr_container_result2[$rec['ORG_ID2']]['m'][$rate_status_percent];
		$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['t'][$rate_status_percent] = (int)$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['t'][$rate_status_percent];
		$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['m'][$rate_status_percent] = (int)$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['m'][$rate_status_percent];
		$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['MONEY_1']= $rec['MONEY_1'];
		$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['MONEY_2']= $rec['MONEY_2'];
		$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['MONEY_3']= $rec['MONEY_3'];
		$arr_container_result2[$rec['ORG_ID2']]['DTL'][$rec['PRJP_ID']]['MONEY_4']= $rec['MONEY_4'];

		$arr_container_result3[$rec['ORG_ID1']]['t'][$rate_status_percent] = (int)$arr_container_result3[$rec['ORG_ID1']]['t'][$rate_status_percent];
		$arr_container_result3[$rec['ORG_ID1']]['m'][$rate_status_percent] = (int)$arr_container_result3[$rec['ORG_ID1']]['m'][$rate_status_percent];
		$arr_container_result3[$rec['ORG_ID1']]['DTL'][$rec['ORG_ID2']]['t'][$rate_status_percent] = (int)$arr_container_result3[$rec['ORG_ID1']]['DTL'][$rec['ORG_ID2']]['t'][$rate_status_percent];
		$arr_container_result3[$rec['ORG_ID1']]['DTL'][$rec['ORG_ID2']]['m'][$rate_status_percent] = (int)$arr_container_result3[$rec['ORG_ID1']]['DTL'][$rec['ORG_ID2']]['m'][$rate_status_percent];
	}
}

if(count($arr_container_result1[1]['t']) > 0){
	@ksort($arr_container_result1[1]['t']);
	@ksort($arr_container_result1[1]['m']);
}

if(count($arr_container_result1) > 0){
	foreach($arr_container_result1 as $key => $value){
		$MONEY_1 = number_format($arr_container_result_percent1[$key]['MONEY_1']/$arr_container_result_percent1[$key]['COUNT'], 2, '.', '');
		$MONEY_2 = number_format($arr_container_result_percent1[$key]['MONEY_2']/$arr_container_result_percent1[$key]['COUNT'], 2, '.', '');
		$MONEY_3 = number_format($arr_container_result_percent1[$key]['MONEY_3']/$arr_container_result_percent1[$key]['COUNT'], 2, '.', '');
		$MONEY_4 = number_format($arr_container_result_percent1[$key]['MONEY_4']/$arr_container_result_percent1[$key]['COUNT'], 2, '.', '');

		$MONEY_1 = $MONEY_1 > 100 ? 100 : $MONEY_1;
		$MONEY_2 = $MONEY_2 > 100 ? 100 : $MONEY_2;
		$MONEY_3 = $MONEY_3 > 100 ? 100 : $MONEY_3;
		$MONEY_4 = $MONEY_4 > 100 ? 100 : $MONEY_4;

		$result1_R_T = $MONEY_2 >= $MONEY_1 ? 100 : ($MONEY_1 > 0 ? $MONEY_2/$MONEY_1*100 : 0);
		$result1_R_M = $MONEY_4 >= $MONEY_3 ? 100 : ($MONEY_3 > 0 ? $MONEY_4/$MONEY_3*100 : 0);
		
		foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
			if($result1_R_T >= $rate_status_percent){
				$result1_COLUMN_T = $rate_status_percent;
				break;
			}
		}
		
		foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
			if($result1_R_M >= $rate_status_percent){
				$result1_COLUMN_M = $rate_status_percent;
				break;
			}
		}
	}
}

if(count($arr_container_result2) > 0){
	foreach($arr_container_result2 as $ORG_ID2 => $value2){
		@ksort($arr_container_result2[$ORG_ID2]['t']);
		@ksort($arr_container_result2[$ORG_ID2]['m']);
	}
}

$arr_container_result2_percent = array();
if(count($arr_container_result2) > 0){
	foreach($arr_container_result2 as $key => $arrDTL){
		$arrDTL_2 = $arr_container_result_percent2[$key];
		
		$MONEY_1 = number_format($arrDTL_2['MONEY_1']/$arrDTL_2['COUNT'], 2, '.', '');
		$MONEY_2 = number_format($arrDTL_2['MONEY_2']/$arrDTL_2['COUNT'], 2, '.', '');
		$MONEY_3 = number_format($arrDTL_2['MONEY_3']/$arrDTL_2['COUNT'], 2, '.', '');
		$MONEY_4 = number_format($arrDTL_2['MONEY_4']/$arrDTL_2['COUNT'], 2, '.', '');

		$MONEY_1 = $MONEY_1 > 100 ? 100 : $MONEY_1;
		$MONEY_2 = $MONEY_2 > 100 ? 100 : $MONEY_2;
		$MONEY_3 = $MONEY_3 > 100 ? 100 : $MONEY_3;
		$MONEY_4 = $MONEY_4 > 100 ? 100 : $MONEY_4;

		$R_T = $MONEY_2 >= $MONEY_1 ? 100 : ($MONEY_1 > 0 ? $MONEY_2/$MONEY_1*100 : 0);
		$R_M = $MONEY_4 >= $MONEY_3 ? 100 : ($MONEY_3 > 0 ? $MONEY_4/$MONEY_3*100 : 0);
		
		foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
			if($R_T >= $rate_status_percent){
				$COLUMN_T = $rate_status_percent;
				break;
			}
		}
		
		foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
			if($R_M >= $rate_status_percent){
				$COLUMN_M = $rate_status_percent;
				break;
			}
		}
		
		$arr_container_result2_percent['t'][$key] = $R_T;
		$arr_container_result2_percent['m'][$key] = $R_M;
	}
}
@asort($arr_container_result2_percent['t']);
@asort($arr_container_result2_percent['m']);

if(count($arr_container_result3) > 0){
	foreach($arr_container_result3 as $ORG_ID1 => $value2){
		@ksort($arr_container_result3[$ORG_ID1]['t']);
		@ksort($arr_container_result3[$ORG_ID1]['m']);
	}
}

$arr_container_result3_percent = array();
if(count($arr_container_result3) > 0){
	foreach($arr_container_result3 as $key => $arrDTL){
		$arrDTL_2 = $arr_container_result_percent3[$key];
		$MONEY_1 = number_format($arrDTL_2['MONEY_1']/$arrDTL_2['COUNT'], 2, '.', '');
		$MONEY_2 = number_format($arrDTL_2['MONEY_2']/$arrDTL_2['COUNT'], 2, '.', '');
		$MONEY_3 = number_format($arrDTL_2['MONEY_3']/$arrDTL_2['COUNT'], 2, '.', '');
		$MONEY_4 = number_format($arrDTL_2['MONEY_4']/$arrDTL_2['COUNT'], 2, '.', '');

		$MONEY_1 = $MONEY_1 > 100 ? 100 : $MONEY_1;
		$MONEY_2 = $MONEY_2 > 100 ? 100 : $MONEY_2;
		$MONEY_3 = $MONEY_3 > 100 ? 100 : $MONEY_3;
		$MONEY_4 = $MONEY_4 > 100 ? 100 : $MONEY_4;

		$R_T = $MONEY_2 >= $MONEY_1 ? 100 : ($MONEY_1 > 0 ? $MONEY_2/$MONEY_1*100 : 0);
		$R_M = $MONEY_4 >= $MONEY_3 ? 100 : ($MONEY_3 > 0 ? $MONEY_4/$MONEY_3*100 : 0);
		
		foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
			if($R_T >= $rate_status_percent){
				$COLUMN_T = $rate_status_percent;
				break;
			}
		}
		
		foreach($arr_config_rate_status as $rate_status_percent => $config_rate_status){
			if($R_M >= $rate_status_percent){
				$COLUMN_M = $rate_status_percent;
				break;
			}
		}
		
		$arr_container_result3_percent['t'][$key] = $R_T;
		$arr_container_result3_percent['m'][$key] = $R_M;
	}
}
@asort($arr_container_result3_percent['t']);
@asort($arr_container_result3_percent['m']);


$arr_prjp_bdg = array();
$sql = "
SELECT A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)) AS PRJP_NAME, ISNULL(A.MONEY_BDG, 0) AS MONEY_BDG 
		,ISNULL((
			SELECT SUM(AA1.BDG_VALUE)
			FROM prjp_plan_money AA1
				INNER JOIN prjp_project AA2 ON AA2.PRJP_ID = AA1.PRJP_ID
			WHERE AA2.PRJP_PARENT_ID = A.PRJP_ID AND AA1.[YEAR]*100+AA1.[MONTH] <= '".$YEAR_MONTH_NOW."'
			), 0) AS MONEY_PLAN
	, D2.ORG_SHORTNAME AS ORG_SHORTNAME2
FROM prjp_project A
	LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
	LEFT JOIN setup_org_bu D ON D.ORG_ID = (CASE WHEN D2.ORG_LEVEL = 2 THEN D2.ORG_PARENT_ID ELSE D2.ORG_ID END)
	LEFT JOIN setup_org_bu ON setup_org_bu.ORG_ID = A.ORG_ID
WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}' 
	{$wh_search}
GROUP BY A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)), ISNULL(A.MONEY_BDG, 0), D2.ORG_SHORTNAME
ORDER BY RIGHT(A.PRJP_CODE, 3) ASC, A.PRJP_CODE ASC, A.PRJP_ID ASC
";
$query = $db->query($sql);
$arr1 = array();
$arr_prjp_crit_result = array();
$arr_prjp_crit_result2 = array();
$arr_prjp_order = array();
while($rec = $db->db_fetch_array($query)){

	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_CODE'] = $rec['PRJP_CODE'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_NAME'] = text($rec['PRJP_NAME']);
	$arr_prjp_bdg[$rec['PRJP_ID']]['ORG_SHORTNAME'] = text($rec['ORG_SHORTNAME2']);
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_PLAN'] = $rec['MONEY_PLAN'];
	$arr1[$rec['PRJP_ID']] += $rec['MONEY_PLAN'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_PLAN'] = $arr1[$rec['PRJP_ID']];

	$MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_PLAN'];
	$MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'];
	$MONEY_PLAN_P = get_percent($MONEY_BDG, $MONEY_PLAN);
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_PLAN_P'] = $MONEY_PLAN_P;

	$arr_prjp_crit_result2[$rec['PRJP_ID']]['R_M'] = 0.00;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['MONEY_5'] = 0.00;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['MONEY_BDG'] = $MONEY_BDG;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['name'] = $rec['PRJP_CODE'].' '.text($rec['PRJP_NAME']);

	$arr_prjp_order[$rec['PRJP_ID']] = 0.00;
}
// print_arr($arr_prjp_bdg); exit;
$sql = "
SELECT PRJP_ID, PRJP_CODE, PRJP_NAME, MONEY_BDG
	, ISNULL((SELECT TOP 1 BINDING_VALUE FROM prjp_binding WHERE PRJP_ID = TB.PRJP_ID AND YEAR*100+MONTH <= '{$YEAR_MONTH_NEXT}' ORDER BY YEAR*100+MONTH DESC), 0) AS MONEY_BINDING
	, SUM(MONEY_REPORT2) AS MONEY_REPORT,ORG_SHORTNAME2
FROM (
	SELECT A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)) AS PRJP_NAME, ISNULL(A.MONEY_BDG, 0) AS MONEY_BDG
		,ISNULL((
			SELECT SUM(AA1.BDG_VALUE)
			FROM prjp_report_money AA1
				INNER JOIN prjp_project AA2 ON AA2.PRJP_ID = AA1.PRJP_ID
			WHERE AA2.PRJP_PARENT_ID = A.PRJP_ID AND AA1.[YEAR]*100+AA1.[MONTH] <= '{$YEAR_MONTH_NOW}'
			), 0) AS MONEY_REPORT2
		, A.YEAR_BDG
		, D2.ORG_SHORTNAME AS ORG_SHORTNAME2
	FROM prjp_project A
		LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
		LEFT JOIN setup_org_bu D ON D.ORG_ID = (CASE WHEN D2.ORG_LEVEL = 2 THEN D2.ORG_PARENT_ID ELSE D2.ORG_ID END)
		LEFT JOIN setup_org_bu ON setup_org_bu.ORG_ID = A.ORG_ID
	WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}' 
	GROUP BY A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)), ISNULL(A.MONEY_BDG, 0), A.YEAR_BDG, D2.ORG_SHORTNAME
) TB
GROUP BY PRJP_ID, PRJP_CODE,PRJP_NAME, MONEY_BDG, YEAR_BDG ,ORG_SHORTNAME2
ORDER BY RIGHT(PRJP_CODE, 3) ASC, PRJP_CODE ASC, PRJP_ID ASC
";
// echo $sql;
$query = $db->query($sql);
$arr1 = array();
$arr2 = array();
$arr3 = array();

while($rec = $db->db_fetch_array($query)){

	$arr_prjp_bdg[$rec['PRJP_ID']]['YEAR'] = $y;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONTH'] = $m;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONTH_NAME'] = ($arr_month_short[$m]." ".($y-2500));

	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_CODE'] = $rec['PRJP_CODE'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_NAME'] = text($rec['PRJP_NAME']);
	$arr_prjp_bdg[$rec['PRJP_ID']]['ORG_SHORTNAME'] = text($rec['ORG_SHORTNAME2']);
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT'] = $rec['MONEY_REPORT'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BINDING'] = $rec['MONEY_BINDING'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_BINDING'] = ($rec['MONEY_REPORT']+$rec['MONEY_BINDING']);

	$arr1[$rec['PRJP_ID']] += $rec['MONEY_REPORT'];
	$arr2[$rec['PRJP_ID']] = $rec['MONEY_BINDING'];
	$arr3[$rec['PRJP_ID']] = $arr1[$rec['PRJP_ID']]+$rec['MONEY_BINDING'];

	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT'] = $arr1[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_BINDING'] = $arr2[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_BINDING'] = $arr3[$rec['PRJP_ID']];

	$MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'];
	$MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_PLAN'];
	$MONEY_PLAN_P = get_percent($MONEY_BDG, $MONEY_PLAN);
	$MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT'];
	$MONEY_REPORT_P = get_percent($MONEY_BDG, $MONEY_REPORT);
	$MONEY_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BINDING'];
	$MONEY_BINDING_P = get_percent($MONEY_BDG, $MONEY_BINDING);
	$MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_BINDING'];
	$MONEY_REPORT_BINDING_P = get_percent($MONEY_BDG, $MONEY_REPORT_BINDING);

	$SUM_MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_BDG'];
	$SUM_MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_PLAN'];
	$SUM_MONEY_PLAN_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_PLAN);
	$SUM_MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT'];
	$SUM_MONEY_REPORT_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT);
	$SUM_MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_BINDING'];
	$SUM_MONEY_REPORT_BINDING_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT_BINDING);

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_PLAN_P'] = $MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_P'] = $MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BINDING_P'] = $MONEY_BINDING_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_BINDING_P'] = $MONEY_REPORT_BINDING_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_PLAN_P'] = $SUM_MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_P'] = $SUM_MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_BINDING_P'] = $SUM_MONEY_REPORT_BINDING_P;

	$arr_prjp_crit_result2[$rec['PRJP_ID']]['R_M'] = $MONEY_REPORT_BINDING_P;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['MONEY_5'] = $MONEY_REPORT_BINDING;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['MONEY_BDG'] = $MONEY_BDG;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['name'] = $rec['PRJP_CODE'].' '.text($rec['PRJP_NAME']);

	$arr_prjp_order[$rec['PRJP_ID']] = $MONEY_REPORT_BINDING_P;
}
// print_arr($arr_prjp_bdg); exit;
$sql = "
SELECT TOP 1 * FROM sme_expenses_fund A LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
LEFT JOIN setup_org_bu ON setup_org_bu.ORG_ID = A.ORG_ID
WHERE YEAR_BDG = '{$DASHBOARD_YEAR}' AND [YEAR]*100+[MONTH] <= '{$YEAR_MONTH_NOW}'
	{$wh_search}
ORDER BY [YEAR]*100+[MONTH] DESC ";
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$y = $rec['YEAR'];
	$m = sprintf('%02d', $rec['MONTH']);
	$y_m = $y.$m;

	$rec['PRJP_ID'] = 0;
	$rec['YEAR_MONTH'] = $y_m;
	$rec['MONEY_BDG'] = $rec['MONEY_BDG'];
	$rec['MONEY_REPORT'] = $rec['DISBURSE_VALUE'];
	$rec['MONEY_BINDING'] = $rec['BINDING_VALUE'];
	$rec['PRJP_CODE'] = 0;
	$rec['PRJP_NAME'] = text($dash_config_table1['header2_text']);
	$rec['COLOR'] = text($dash_config_table1['header2_color']);

	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_CODE'] = $rec['PRJP_CODE'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_NAME'] = $rec['PRJP_NAME'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['ORG_SHORTNAME'] = text($rec['ORG_SHORTNAME']);
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'] = $rec['MONEY_BDG'];

	$arr_prjp_bdg[$rec['PRJP_ID']]['YEAR'] = $y;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONTH'] = $m;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONTH_NAME'] = ($arr_month_short[$m]." ".($y-2500));

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT'] = $rec['MONEY_REPORT'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BINDING'] = $rec['MONEY_BINDING'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_BINDING'] = ($rec['MONEY_REPORT']+$rec['MONEY_BINDING']);

	$arr1[$rec['PRJP_ID']] = $rec['MONEY_REPORT'];
	$arr2[$rec['PRJP_ID']] = $rec['MONEY_BINDING'];
	$arr3[$rec['PRJP_ID']] = $arr1[$rec['PRJP_ID']]+$rec['MONEY_BINDING'];

	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT'] = $arr1[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_BINDING'] = $arr2[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_BINDING'] = $arr3[$rec['PRJP_ID']];

	$MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'];
	$MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_PLAN'];
	$MONEY_PLAN_P = get_percent($MONEY_BDG, $MONEY_PLAN);
	$MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT'];
	$MONEY_REPORT_P = get_percent($MONEY_BDG, $MONEY_REPORT);
	$MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_BINDING'];
	$MONEY_REPORT_BINDING_P = get_percent($MONEY_BDG, $MONEY_REPORT_BINDING);

	$SUM_MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_BDG'];
	$SUM_MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_PLAN'];
	$SUM_MONEY_PLAN_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_PLAN);
	$SUM_MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT'];
	$SUM_MONEY_REPORT_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT);
	$SUM_MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_BINDING'];
	$SUM_MONEY_REPORT_BINDING_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT_BINDING);

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_PLAN_P'] = $MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_P'] = $MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_REPORT_BINDING_P'] = $MONEY_REPORT_BINDING_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_PLAN_P'] = $SUM_MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_P'] = $SUM_MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['SUM_MONEY_REPORT_BINDING_P'] = $SUM_MONEY_REPORT_BINDING_P;

	$arr_prjp_crit_result2[$rec['PRJP_ID']]['R_M'] = $MONEY_REPORT_BINDING_P;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['MONEY_5'] = $MONEY_REPORT_BINDING;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['MONEY_BDG'] = $MONEY_BDG;
	$arr_prjp_crit_result2[$rec['PRJP_ID']]['name'] = $rec['PRJP_CODE'].' '.($rec['PRJP_NAME']);

	$arr_prjp_order[$rec['PRJP_ID']] = $MONEY_REPORT_BINDING_P;

	$arr_table_slide_01[0]['NAME'] = ($rec['PRJP_NAME']);
	$arr_table_slide_01[0]['COLOR'] = ($rec['COLOR']);
	$arr_table_slide_01[0]['MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_table_slide_01[0]['MONEY_REPORT'] = $rec['MONEY_REPORT'];
	$arr_table_slide_01[0]['MONEY_BINDING'] = $rec['MONEY_BINDING'];
	$arr_table_slide_01[0]['MONEY_REPORT_BINDING'] = ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']);
	$arr_table_slide_01[0]['MONEY_REMAIN'] = ($rec['MONEY_BDG'] - ($rec['MONEY_REPORT'] + $rec['MONEY_BINDING']));

	$slide01_total_bdg += $rec['MONEY_BDG'];
}
// print_arr($arr_prjp_bdg); exit;
@ksort($arr_table_slide_01);
$arr_table_slide_01_3 = array();
foreach($arr_table_slide_01 as $key => $arrDTL){
	@$arr_table_slide_01[$key]['MONEY_BDG_P'] = $arrDTL['MONEY_BDG']/$slide01_total_bdg*100.00;
	@$arr_table_slide_01[$key]['MONEY_REPORT_P'] = $arrDTL['MONEY_REPORT']/$slide01_total_bdg*100.00;
	@$arr_table_slide_01[$key]['MONEY_BINDING_P'] = $arrDTL['MONEY_BINDING']/$slide01_total_bdg*100.00;
	@$arr_table_slide_01[$key]['MONEY_REPORT_BINDING_P'] = $arrDTL['MONEY_REPORT_BINDING']/$slide01_total_bdg*100.00;
	@$arr_table_slide_01[$key]['MONEY_REMAIN_P'] = $arrDTL['MONEY_REMAIN']/$slide01_total_bdg*100.00;

	if(count($arrDTL['DTL']) > 0){
		foreach($arrDTL['DTL'] as $key2 => $arrDTL2){
			@$arr_table_slide_01[$key]['DTL'][$key2]['MONEY_BDG_P'] = $arrDTL2['MONEY_BDG']/$arrDTL['MONEY_BDG']*100.00;
			@$arr_table_slide_01[$key]['DTL'][$key2]['MONEY_REPORT_P'] = $arrDTL2['MONEY_REPORT']/$arrDTL['MONEY_BDG']*100.00;
			@$arr_table_slide_01[$key]['DTL'][$key2]['MONEY_BINDING_P'] = $arrDTL2['MONEY_BINDING']/$arrDTL['MONEY_BDG']*100.00;
			@$arr_table_slide_01[$key]['DTL'][$key2]['MONEY_REPORT_BINDING_P'] = $arrDTL2['MONEY_REPORT_BINDING']/$arrDTL['MONEY_BDG']*100.00;
			@$arr_table_slide_01[$key]['DTL'][$key2]['MONEY_REMAIN_P'] = $arrDTL2['MONEY_REMAIN']/$arrDTL['MONEY_BDG']*100.00;

			if(count($arrDTL2['DTL']) > 0){
				foreach($arrDTL2['DTL'] as $key3 => $arrDTL3){
					@$arr_table_slide_01[$key]['DTL'][$key2]['DTL'][$key3]['MONEY_BDG_P'] = $arrDTL3['MONEY_BDG']/$arrDTL2['MONEY_BDG']*100.00;
					@$arr_table_slide_01[$key]['DTL'][$key2]['DTL'][$key3]['MONEY_REPORT_P'] = $arrDTL3['MONEY_REPORT']/$arrDTL2['MONEY_BDG']*100.00;
					@$arr_table_slide_01[$key]['DTL'][$key2]['DTL'][$key3]['MONEY_BINDING_P'] = $arrDTL3['MONEY_BINDING']/$arrDTL2['MONEY_BDG']*100.00;
					@$arr_table_slide_01[$key]['DTL'][$key2]['DTL'][$key3]['MONEY_REPORT_BINDING_P'] = $arrDTL3['MONEY_REPORT_BINDING']/$arrDTL2['MONEY_BDG']*100.00;
					@$arr_table_slide_01[$key]['DTL'][$key2]['DTL'][$key3]['MONEY_REMAIN_P'] = $arrDTL3['MONEY_REMAIN']/$arrDTL2['MONEY_BDG']*100.00;
				}
			}
		}
	}

	$arr_table_slide_01_3['MONEY_BDG'] += $arrDTL['MONEY_BDG'];
	$arr_table_slide_01_3['MONEY_REPORT'] += $arrDTL['MONEY_REPORT'];
	$arr_table_slide_01_3['MONEY_BINDING'] += $arrDTL['MONEY_BINDING'];
	$arr_table_slide_01_3['MONEY_REPORT_BINDING'] += $arrDTL['MONEY_REPORT_BINDING'];
	$arr_table_slide_01_3['MONEY_REMAIN'] += $arrDTL['MONEY_REMAIN'];
}
@$arr_table_slide_01_3['MONEY_BDG_P'] = $arr_table_slide_01_3['MONEY_BDG']/$arr_table_slide_01_3['MONEY_BDG']*100.00;
@$arr_table_slide_01_3['MONEY_REPORT_P'] = $arr_table_slide_01_3['MONEY_REPORT']/$arr_table_slide_01_3['MONEY_BDG']*100.00;
@$arr_table_slide_01_3['MONEY_BINDING_P'] = $arr_table_slide_01_3['MONEY_BINDING']/$arr_table_slide_01_3['MONEY_BDG']*100.00;
@$arr_table_slide_01_3['MONEY_REPORT_BINDING_P'] = $arr_table_slide_01_3['MONEY_REPORT_BINDING']/$arr_table_slide_01_3['MONEY_BDG']*100.00;
@$arr_table_slide_01_3['MONEY_REMAIN_P'] = $arr_table_slide_01_3['MONEY_REMAIN']/$arr_table_slide_01_3['MONEY_BDG']*100.00;


$arr_prjp_crit_result = $arr_prjp_order;
//print_arr($arr_prjp_crit_result2); exit;
@arsort($arr_prjp_crit_result);

$i = 1;
$sum_MONEY_3 = 0.00;
$sum_MONEY_4 = 0.00;
$sum_MONEY_5 = 0.00;
$sum_MONEY_BDG = 0.00;
$arr_R_M_key = array();
$arr_R_M_data = array();
$arr_R_M_name = array();
$arr_prjp_crit = array();
foreach($arr_prjp_crit_result as $kPRJP_ID => $vPRJP_ID){
	$value = $arr_prjp_crit_result2[$kPRJP_ID];
	$value['R_M'] = $value['R_M'] > 100 ? 100 : $value['R_M'];
	$sum_MONEY_5 += $value['MONEY_5'];
	$sum_MONEY_BDG += $value['MONEY_BDG'];
	$arr_R_M_key[] = $i++;
	$arr_R_M_data[] = number_format($value['R_M'], 2);
	$arr_R_M_name[] = $value['name'];
	$arr_prjp_crit[] = 88;
}
//print_arr($arr_R_M_data); exit;

if($sum_MONEY_5 >= $sum_MONEY_BDG){
	$sum_R_M = 100;
}else{
	@$sum_R_M = $sum_MONEY_BDG > 0 ? ($sum_MONEY_5/$sum_MONEY_BDG)*100 : 0.00;
}
$sum_R_M = number_format(($sum_R_M > 100 ? 100 : $sum_R_M), 2);
//print_arr($arr_R_M_data);
$arr_crit_R_M = array(
	88 => 'ร้อยละการเบิกจ่ายเทียบกับงบประมาณที่ได้ 88 ขึ้นไป คะแนนที่ได้ 1',
	90 => 'ร้อยละการเบิกจ่ายเทียบกับงบประมาณที่ได้ 90 ขึ้นไป คะแนนที่ได้ 2',
	92 => 'ร้อยละการเบิกจ่ายเทียบกับงบประมาณที่ได้ 92 ขึ้นไป คะแนนที่ได้ 3',
	94 => 'ร้อยละการเบิกจ่ายเทียบกับงบประมาณที่ได้ 94 ขึ้นไป คะแนนที่ได้ 4',
	96 => 'ร้อยละการเบิกจ่ายเทียบกับงบประมาณที่ได้ 96 ขึ้นไป คะแนนที่ได้ 5',
);

$score_R_M = 1;
$i = 1;
//หาคะแนนที่ได้
foreach($arr_crit_R_M as $crit){
	if($sum_R_M >= $crit){
		$score_R_M = $i;
	}
	$i++;
}

//$arr_prjp_bdg = array();
$arr_prjp_bdg2 = array();
for($y=$DASHBOARD_YEAR-1; $y<=$MAX_YEAR_OF_YEAR; $y++){
	foreach($arr_month_short as $m => $vm){
		$y_m = $y.$m;
		if($y_m >= $MIN_YEAR_MONTH && $y_m <= $MAX_YEAR_MONTH){
			$arr_prjp_bdg2[$y_m]['YEAR'] = $y;
			$arr_prjp_bdg2[$y_m]['MONTH'] = $m;
			$arr_prjp_bdg2[$y_m]['MONTH_NAME'] = $arr_month_short[$m]." ".($y-2500);
		}
	}
}

$sql = "
SELECT A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)) AS PRJP_NAME, ISNULL(A.MONEY_BDG, 0) AS MONEY_BDG, SUM(AA1.BDG_VALUE) AS MONEY_PLAN, AA1.[YEAR], AA1.[MONTH], AA1.[YEAR]*100+AA1.[MONTH] AS YEAR_MONTH
	, D2.ORG_SHORTNAME AS ORG_SHORTNAME2
FROM prjp_project A
	LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
	LEFT JOIN prjp_project AA2 ON AA2.PRJP_PARENT_ID = A.PRJP_ID
	LEFT JOIN prjp_plan_money AA1 ON AA1.PRJP_ID = AA2.PRJP_ID
		AND ISNULL(AA1.[YEAR]*100+AA1.[MONTH], 0) <> 0
WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}'
GROUP BY A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)), A.MONEY_BDG, AA1.[YEAR], AA1.[MONTH], D2.ORG_SHORTNAME
ORDER BY RIGHT(A.PRJP_CODE, 3) ASC, A.PRJP_CODE ASC, A.PRJP_ID ASC, AA1.[YEAR] ASC, AA1.[MONTH]
";
// echo $sql; exit;
// echo $YEAR_MONTH_NOW; exit;
$query = $db->query($sql);
$arr1 = array();
$arr_prjp_order = array();
while($rec = $db->db_fetch_array($query)){
	$y_m = $rec['YEAR_MONTH'];
	if(empty($y_m) || $y_m > $YEAR_MONTH_NOW){continue;}

	// $arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_CODE'] = $rec['PRJP_CODE'];
	// $arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_NAME'] = text($rec['PRJP_NAME']);
	// $arr_prjp_bdg[$rec['PRJP_ID']]['ORG_SHORTNAME'] = text($rec['ORG_SHORTNAME2']);
	// $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_PLAN'] = $rec['MONEY_PLAN'];
	$arr1[$rec['PRJP_ID']] += $rec['MONEY_PLAN'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_PLAN'] = $arr1[$rec['PRJP_ID']];

	$MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_PLAN'];
	$MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'];
	$MONEY_PLAN_P = get_percent($MONEY_BDG, $MONEY_PLAN);
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_PLAN_P'] = $MONEY_PLAN_P;
	// print_arr($arr_prjp_bdg); exit;
	$arr_prjp_order[$rec['YEAR_MONTH']][$rec['PRJP_ID']] = 0.00;
}

$sql = "
SELECT A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)) AS PRJP_NAME, ISNULL(A.MONEY_BDG, 0) AS MONEY_BDG, SUM(AA1.BDG_VALUE) AS MONEY_REPORT, AA1.[YEAR], AA1.[MONTH], AA1.[YEAR]*100+AA1.[MONTH] AS YEAR_MONTH
	, ISNULL((SELECT TOP 1 BINDING_VALUE FROM prjp_binding WHERE PRJP_ID = A.PRJP_ID AND YEAR*100+MONTH <= '{$YEAR_MONTH_NEXT}' ORDER BY YEAR*100+MONTH DESC), 0) AS MONEY_BINDING
FROM prjp_project A
	LEFT JOIN prjp_project AA2 ON AA2.PRJP_PARENT_ID = A.PRJP_ID
	LEFT JOIN prjp_report_money AA1 ON AA1.PRJP_ID = AA2.PRJP_ID AND ISNULL(AA1.[YEAR]*100+AA1.[MONTH], 0) <> 0 AND AA1.[YEAR]*100+AA1.[MONTH] <= '{$YEAR_MONTH_NOW}'
WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}'
GROUP BY A.PRJP_ID, A.PRJP_CODE, CAST(A.PRJP_NAME AS VARCHAR(8000)), A.MONEY_BDG, AA1.[YEAR], AA1.[MONTH], A.YEAR_BDG
ORDER BY RIGHT(A.PRJP_CODE, 3) ASC, A.PRJP_CODE ASC, A.PRJP_ID ASC, AA1.[YEAR] ASC, AA1.[MONTH]
";
// echo $sql; exit;
$query = $db->query($sql);
$arr1 = array();
$arr2 = array();
$arr3 = array();
while($rec = $db->db_fetch_array($query)){
	$y_m = $rec['YEAR_MONTH'];
	
	$arr1[$rec['PRJP_ID']] += $rec['MONEY_REPORT'];
	$arr2[$rec['PRJP_ID']] = $rec['MONEY_BINDING'];
	$arr3[$rec['PRJP_ID']] = $arr1[$rec['PRJP_ID']]+$rec['MONEY_BINDING'];
	
	if(empty($y_m) || $y_m > $YEAR_MONTH_NOW){continue;}
	$y = substr($y_m, 0, 4);
	$m = substr($y_m, 4, 2);

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['YEAR'] = $y;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONTH'] = $m;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONTH_NAME'] = ($arr_month_short[$m]." ".($y-2500));

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT'] = $rec['MONEY_REPORT'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_BINDING'] = $rec['MONEY_BINDING'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_BINDING'] = ($rec['MONEY_REPORT']+$rec['MONEY_BINDING']);

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT'] = $arr1[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_BINDING'] = $arr2[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_BINDING'] = $arr3[$rec['PRJP_ID']];

	$MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_BDG'];
	$MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_PLAN'];
	$MONEY_PLAN_P = get_percent($MONEY_BDG, $MONEY_PLAN);
	$MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT'];
	$MONEY_REPORT_P = get_percent($MONEY_BDG, $MONEY_REPORT);
	$MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_BINDING'];
	$MONEY_REPORT_BINDING_P = get_percent($MONEY_BDG, $MONEY_REPORT_BINDING);

	$SUM_MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_BDG'];
	$SUM_MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_PLAN'];
	$SUM_MONEY_PLAN_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_PLAN);
	$SUM_MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT'];
	$SUM_MONEY_REPORT_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT);
	$SUM_MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_BINDING'];
	$SUM_MONEY_REPORT_BINDING_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT_BINDING);

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_PLAN_P'] = $MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_P'] = $MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_BINDING_P'] = $MONEY_REPORT_BINDING_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_PLAN_P'] = $SUM_MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_P'] = $SUM_MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_BINDING_P'] = $SUM_MONEY_REPORT_BINDING_P;

	$arr_prjp_order[$rec['YEAR_MONTH']][$rec['PRJP_ID']] = $SUM_MONEY_REPORT_BINDING_P;
}
$arr_prjp_rep = $arr1;
$arr_prjp_bid = $arr2;

$sql = "
SELECT * FROM sme_expenses_fund WHERE YEAR_BDG = '{$DASHBOARD_YEAR}' AND [YEAR]*100+[MONTH] <= '{$YEAR_MONTH_NOW}' ";
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$arr_rec[] = $rec;
	$y = $rec['YEAR'];
	$m = sprintf('%02d', $rec['MONTH']);
	$y_m = $y.$m;

	$rec['PRJP_ID'] = 0;
	$rec['YEAR_MONTH'] = $y_m;
	$rec['MONEY_BDG'] = $rec['MONEY_BDG'];
	$rec['MONEY_REPORT'] = $rec['DISBURSE_VALUE'];
	$rec['MONEY_BINDING'] = $rec['BINDING_VALUE'];
	$rec['PRJP_CODE'] = 0;
	$rec['PRJP_NAME'] = text($dash_config_table1['header2_text']);
	$rec['COLOR'] = text($dash_config_table1['header2_color']);
	
	$money_year['BDG'][$y_m] = $rec['MONEY_BDG'];
	$money_year['REPORT'][$y_m] = $rec['DISBURSE_VALUE'];
	$money_year['BINDING'][$y_m] = $rec['BINDING_VALUE'];
	
	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_CODE'] = $rec['PRJP_CODE'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['PRJP_NAME'] = $rec['PRJP_NAME'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_BDG'] = $rec['MONEY_BDG'];

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['YEAR'] = $y;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONTH'] = $m;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONTH_NAME'] = ($arr_month_short[$m]." ".($y-2500));

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT'] = $rec['MONEY_REPORT'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_BINDING'] = $rec['MONEY_BINDING'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_BINDING'] = ($rec['MONEY_REPORT']+$rec['MONEY_BINDING']);

	$arr1[$rec['PRJP_ID']] = $rec['MONEY_REPORT'];
	$arr2[$rec['PRJP_ID']] = $rec['MONEY_BINDING'];
	$arr3[$rec['PRJP_ID']] = $arr1[$rec['PRJP_ID']]+$rec['MONEY_BINDING'];

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_BDG'] = $rec['MONEY_BDG'];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT'] = $arr1[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_BINDING'] = $arr2[$rec['PRJP_ID']];
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_BINDING'] = $arr3[$rec['PRJP_ID']];

	$MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_BDG'];
	$MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_PLAN'];
	$MONEY_PLAN_P = get_percent($MONEY_BDG, $MONEY_PLAN);
	$MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT'];
	$MONEY_REPORT_P = get_percent($MONEY_BDG, $MONEY_REPORT);
	$MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_BINDING'];
	$MONEY_REPORT_BINDING_P = get_percent($MONEY_BDG, $MONEY_REPORT_BINDING);

	$SUM_MONEY_BDG = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_BDG'];
	$SUM_MONEY_PLAN = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_PLAN'];
	$SUM_MONEY_PLAN_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_PLAN);
	$SUM_MONEY_REPORT = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT'];
	$SUM_MONEY_REPORT_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT);
	$SUM_MONEY_REPORT_BINDING = $arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_BINDING'];
	$SUM_MONEY_REPORT_BINDING_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT_BINDING);

	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_PLAN_P'] = $MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_P'] = $MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['MONEY_REPORT_BINDING_P'] = $MONEY_REPORT_BINDING_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_PLAN_P'] = $SUM_MONEY_PLAN_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_P'] = $SUM_MONEY_REPORT_P;
	$arr_prjp_bdg[$rec['PRJP_ID']]['MONEY_MONTH'][$rec['YEAR_MONTH']]['SUM_MONEY_REPORT_BINDING_P'] = $SUM_MONEY_REPORT_BINDING_P;

	$arr_prjp_order[$rec['YEAR_MONTH']][$rec['PRJP_ID']] = $SUM_MONEY_REPORT_BINDING_P;
}
// print_arr($arr1); exit;
$arr_prjp_rep += $arr1;
$arr_prjp_bid += $arr2;

$arr_prjp_bdg_month = array();
if(count($arr_prjp_bdg) > 0){
	foreach($arr_prjp_order as $y_m => $arr1){
		@arsort($arr_prjp_order[$y_m]);
	}

	foreach($arr_prjp_bdg as $key1 => $arr1){
		if(count($arr1['MONEY_MONTH'])>0){
			foreach($arr1['MONEY_MONTH'] as $y_m => $arr2){
				$y = substr($y_m, 0, 4);
				$m = substr($y_m, 4, 2);
				$arr_prjp_bdg2[$y_m]['YEAR'] = $y;
				$arr_prjp_bdg2[$y_m]['MONTH'] = $m;
				$arr_prjp_bdg2[$y_m]['MONTH_NAME'] = ($arr_month_short[$m]." ".($y-2500));
				$arr_prjp_bdg_month[$y_m] = ($arr_month_short[$m])." ".($y-2500);

				$arr_prjp_bdg2[$y_m]['MONEY_BDG'] += $arr_prjp_bdg[$key1]['MONEY_BDG'];
				$arr_prjp_bdg2[$y_m]['MONEY_PLAN'] += $arr2['MONEY_PLAN'];
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_PLAN'] += $arr2['SUM_MONEY_PLAN'];
				$arr_prjp_bdg2[$y_m]['MONEY_REPORT'] += $arr2['MONEY_REPORT'];
				$arr_prjp_bdg2[$y_m]['MONEY_BINDING'] += $arr2['MONEY_BINDING'];
				$arr_prjp_bdg2[$y_m]['MONEY_REPORT_BINDING'] += $arr2['MONEY_REPORT_BINDING'];
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_BDG'] += $arr_prjp_bdg[$key1]['MONEY_BDG'];
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_REPORT'] += $arr2['SUM_MONEY_REPORT'];
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_BINDING'] += $arr2['SUM_MONEY_BINDING'];
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_REPORT_BINDING'] += $arr2['SUM_MONEY_REPORT_BINDING'];

				$MONEY_BDG = $arr_prjp_bdg2[$y_m]['MONEY_BDG'];
				$MONEY_PLAN = $arr_prjp_bdg2[$y_m]['MONEY_PLAN'];
				$MONEY_PLAN_P = get_percent($MONEY_BDG, $MONEY_PLAN);
				$MONEY_REPORT = $arr_prjp_bdg2[$y_m]['MONEY_REPORT'];
				$MONEY_REPORT_P = get_percent($MONEY_BDG, $MONEY_REPORT);
				$MONEY_REPORT_BINDING = $arr_prjp_bdg2[$y_m]['MONEY_REPORT_BINDING'];
				$MONEY_REPORT_BINDING_P = get_percent($MONEY_BDG, $MONEY_REPORT_BINDING);

				$SUM_MONEY_BDG = $arr_prjp_bdg2[$y_m]['SUM_MONEY_BDG'];
				$SUM_MONEY_PLAN = $arr_prjp_bdg2[$y_m]['SUM_MONEY_PLAN'];
				$SUM_MONEY_PLAN_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_PLAN);
				$SUM_MONEY_REPORT = $arr_prjp_bdg2[$y_m]['SUM_MONEY_REPORT'];
				$SUM_MONEY_REPORT_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT);
				$SUM_MONEY_REPORT_BINDING = $arr_prjp_bdg2[$y_m]['SUM_MONEY_REPORT_BINDING'];
				$SUM_MONEY_REPORT_BINDING_P = get_percent($SUM_MONEY_BDG, $SUM_MONEY_REPORT_BINDING);

				$arr_prjp_bdg2[$y_m]['MONEY_PLAN_P'] = $MONEY_PLAN_P;
				$arr_prjp_bdg2[$y_m]['MONEY_REPORT_P'] = $MONEY_REPORT_P;
				$arr_prjp_bdg2[$y_m]['MONEY_REPORT_BINDING_P'] = $MONEY_REPORT_BINDING_P;
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_PLAN_P'] = $SUM_MONEY_PLAN_P;
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_REPORT_P'] = $SUM_MONEY_REPORT_P;
				$arr_prjp_bdg2[$y_m]['SUM_MONEY_REPORT_BINDING_P'] = $SUM_MONEY_REPORT_BINDING_P;
			}
		}
	}
}

$arr_bdg_list = array();
foreach($default_year_round AS $month => $year){
	$y_m = $year;
	if($y_m <= $YEAR_MONTH_NOW){
		$sql="SELECT
			A.PRJP_ID
			, A.PRJP_CODE
			, A.YEAR_BDG
			, A.MONEY_BDG 
	, isnull(C.YEAR*100+C.MONTH,{$y_m}) AS YEAR_MONTH
			, SUM(isnull(C.BDG_VALUE,0)) AS MONEY_REPORT
			, isnull((SELECT TOP 1 BINDING_VALUE FROM prjp_binding WHERE YEAR*100+MONTH <= {$YEAR_MONTH_NEXT} AND PRJP_ID = A.PRJP_ID ORDER BY YEAR*100 + MONTH DESC),0) AS MONEY_BINDING
		FROM prjp_project A
			LEFT JOIN prjp_project A2 ON A2.PRJP_PARENT_ID = A.PRJP_ID
			LEFT JOIN prjp_report_money C ON C.PRJP_ID = A2.PRJP_ID AND C.[YEAR]*100+C.[MONTH] = '".$y_m."'
			
		WHERE A.PRJP_LEVEL = '1' AND A.YEAR_BDG = '{$DASHBOARD_YEAR}' AND A.PRJP_STATUS_SHOW = '1' 
		GROUP BY
			A.PRJP_ID
			, A.PRJP_CODE
			, A.YEAR_BDG
			, CAST(A.PRJP_NAME AS VARCHAR(4000)) 
			, A.MONEY_BDG 
			, C.YEAR*100+C.MONTH 
			";
			// echo $sql."<br>"; exit;
		$query = $db->query($sql);
		
		while($rec = $db->db_fetch_array($query)){
			$arr_sum_MONEY_BDG[$rec['PRJP_ID']] = $rec['MONEY_BDG'];
			$arr_bdg_list[$rec['PRJP_ID']][$y_m]['MONEY_BDG'] = $rec['MONEY_BDG'];
			$arr_bdg_list[$rec['PRJP_ID']][$y_m]['MONEY_REPORT'] = $rec['MONEY_REPORT'];
			$arr_bdg_list[$rec['PRJP_ID']][$y_m]['MONEY_BINDING'] = $rec['MONEY_BINDING'];
			
			$b += $rec['MONEY_REPORT'];
			$c += $rec['MONEY_BINDING'];
		}
	}
}

// echo @array_sum($arr_sum_MONEY_BDG);
// print_arr($b);
// print_arr($c);
// print_arr($arr_bdg_list); exit;

$sum_MONEYbdg = @array_sum($arr_sum_MONEY_BDG) + $money_year['BDG'][$YEAR_MONTH_NOW];
$sum_bdg = array();
foreach($arr_bdg_list AS $PRJP_ID => $data){
	foreach($data AS $y_m => $dtl){
		$sum_bdg['SUM_MONEY_REPORT'][$y_m] += $dtl['MONEY_REPORT'];
		$sum_bdg['SUM_MONEY_BINDING'][$y_m] += $dtl['MONEY_BINDING'];
	}
}
// print_arr($sum_bdg); exit;

$arr_prjp_bdg_data = array();
$arr_prjp_bdg_data_rep = array();

$REPORT = $money_year['REPORT'][$YEAR_MONTH_NOW];
$BINDING = $money_year['BINDING'][$YEAR_MONTH_NOW] + $sum_bdg['SUM_MONEY_BINDING'][$YEAR_MONTH_NOW];
foreach($default_year_round AS $month => $year){
	$y_m = $year;
	if($y_m <= $YEAR_MONTH_NOW){
		// echo "(".$REPORT."/".$sum_MONEYbdg.")*100"."<br>";
		$REPORT += $sum_bdg['SUM_MONEY_REPORT'][$y_m];
		//$BINDING += $sum_bdg['SUM_MONEY_BINDING'][$y_m];
		$sum_bdg['REPORT'][$y_m] = $REPORT;
		$sum_bdg['SUM_MONEY_REPORT_P'][$y_m] = get_percent($sum_MONEYbdg, $REPORT);
		$sum_bdg['BINDING'][$y_m] = $BINDING;
		$sum_bdg['SUM_MONEY_BINDING_P'][$y_m] = get_percent($sum_MONEYbdg, $BINDING);
		$sum_bdg['SUM_MONEY_REPORT_BINDING'][$y_m] = $REPORT + $BINDING;
		$sum_bdg['SUM_MONEY_REPORT_BINDING_P'][$y_m] = get_percent($sum_MONEYbdg, $sum_bdg['SUM_MONEY_REPORT_BINDING'][$y_m]);
		
		$arr_prjp_bdg_data_rep[$y_m] = $sum_bdg['SUM_MONEY_REPORT_P'][$y_m];
		$arr_prjp_bdg_data[$y_m] = $sum_bdg['SUM_MONEY_REPORT_BINDING_P'][$y_m];
	}
}
// print_arr($sum_bdg); exit;

$arr_impact_economy1 = array(
	array('name'=>'ผู้ได้รับผลประโยชน์'				, 'number'=>107, 'unit'=>'คน/ราย'),
	array('name'=>'การจ้างงาน'					, 'number'=>137, 'unit'=>'คน/ราย'),
	array('name'=>'การรักษาสภาพการจ้างงาน'			, 'number'=>128, 'unit'=>'คน/ราย'),
	array('name'=>'การจ้างงานในธุรกิจจัดตั้งใหม่'		, 'number'=>99, 'unit'=>'คน/ราย'),
	array('name'=>'กิจการ SME ที่ได้รับผลประโยชน์'	, 'number'=>175, 'unit'=>'กิจการ'),
	array('name'=>'เพิ่มผลิตภาพ'					, 'number'=>98, 'unit'=>'กิจการ'),
	array('name'=>'เพิ่มมูลค่าผลิตภัณฑ์'				, 'number'=>107, 'unit'=>'กิจการ'),
	array('name'=>'สร้างผู้ประกอบการใหม่'				, 'number'=>107, 'unit'=>'กิจการ'),
	array('name'=>'จดทะเบียนธุรกิจ'				, 'number'=>107, 'unit'=>'กิจการ'),
	array('name'=>'ขยายกิจการ'				, 'number'=>107, 'unit'=>'กิจการ'),
);
$arr_impact_economy2 = array(
	array('name'=>'เข้าเป็นสมาชิกสมาคม/หอการค้า'				, 'number'=>107, 'unit'=>'กิจการ'),
	array('name'=>'จำนวนมาตรฐานที่ได้รับ'				, 'number'=>107, 'unit'=>'กิจการ'),
	array('name'=>'รายได้ของธุรกิจ'				, 'number'=>107, 'unit'=>'บาท'),
	array('name'=>'ยอดขายสินค้า'				, 'number'=>107, 'unit'=>'บาท'),
	array('name'=>'การส่งอออก'				, 'number'=>107, 'unit'=>'บาท'),
	array('name'=>'การลงทุน'				, 'number'=>107, 'unit'=>'บาท'),
	array('name'=>'ลดต้นทุน'				, 'number'=>107, 'unit'=>'บาท'),
	array('name'=>'ลดของเสียในกระบวนการผลิต'				, 'number'=>107, 'unit'=>'บาท'),
	array('name'=>'มูลค่าการลงทุนจัดตั้งธุรกิจ'				, 'number'=>107, 'unit'=>'บาท'),
	array('name'=>'การรวมกลุ่มธุรกิจ'				, 'number'=>107, 'unit'=>'คลัสเตอร์'),
);


$arr_tab_plan_result = array(	1 =>array(
	'id'=>1,
	'name'=>'หมวดใหญ่',
	'arr'=>array(
			"1"=>"1",
			"2"=>"2",
			"3"=>"3",
			"4"=>"4",
			"5"=>"5",
		),
	),
	2 =>array(
	'id'=>2,
	'name'=>'หมวดหย่อย',
	'arr'=>array(
			"1"=>"1",
			"2"=>"2",
			"3"=>"3",
			"4"=>"4",
			"5"=>"5",
		),
	),
	3 =>array(
	'id'=>3,
	'name'=>'หมู่ใหญ่',
	'arr'=>array(
			"1"=>"1",
			"2"=>"2",
			"3"=>"3",
			"4"=>"4",
			"5"=>"5",
		),
	),
	4 =>array(
	'id'=>4,
	'name'=>'หมู่ย่อย',
	'arr'=>array(
			"1"=>"1",
			"2"=>"2",
			"3"=>"3",
			"4"=>"4",
			"5"=>"5",
		),
	),
	5 =>array(
	'id'=>5,
	'name'=>'กิจกรรม',
	'arr'=>array(
			"1"=>"1",
			"2"=>"2",
			"3"=>"3",
			"4"=>"4",
			"5"=>"5",
		),
	),
);
// slide 2 
//งบประมาณแต่ละปี
$sql = "
	SELECT
		YEAR_BDG
		, MONEY_BDG
		, DISBURSE_VALUE AS MONEY_REPORT
		, BINDING_VALUE AS MONEY_BINDING
	FROM disburse_budget
	ORDER BY YEAR_BDG
";

$query = $db->query($sql);
$arr_bdg_prev = array();
while($rec = $db->db_fetch_array($query)){
	$arr_bdg_prev['YEAR'][$rec['YEAR_BDG']] = $rec['YEAR_BDG'];
	$BDG = $rec['MONEY_BDG'] ;
	$REPORT = $rec['MONEY_REPORT'] ;
	$BINDING = $rec['MONEY_BINDING'] ;
	$REMAIN = $BDG - ($REPORT + $BINDING);
	
	$arr_bdg_prev['BDG'][$rec['YEAR_BDG']] = $BDG;
	$arr_bdg_prev['REPORT'][$rec['YEAR_BDG']] = $REPORT;
	@$arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] = $REPORT/$BDG*100.00;
	$arr_bdg_prev['BINDING'][$rec['YEAR_BDG']] = $BINDING;
	@$arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']] = $BINDING/$BDG*100.00;
	$arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']] = $REMAIN;
	@$arr_bdg_prev['REMAIN_P'][$rec['YEAR_BDG']] = $REMAIN/$BDG*100.00;
}

if(count($arr_bdg_prev) > 0){
	$wh_bdg_prev = " AND A.YEAR_BDG NOT IN (".implode(",", array_keys($arr_bdg_prev['YEAR'])).") ";
}
$sql = "
SELECT
	YEAR_BDG
	, SUM (MONEY_BDG) + isnull(MONEY_BDG2,0) AS MONEY_BDG
	, SUM (MONEY_REPORT) + isnull(DISBURSE_VALUE,0) AS MONEY_REPORT
	, SUM (MONEY_BINDING) + isnull(BINDING_VALUE,0) AS MONEY_BINDING
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
		,(SELECT TOP 1 MONEY_BDG FROM sme_expenses_fund WHERE YEAR_BDG = A.YEAR_BDG AND [YEAR]*100+[MONTH] <= '{$YEAR_MONTH_NOW}' ORDER BY [YEAR]*100+[MONTH] DESC) AS MONEY_BDG2
		,(SELECT TOP 1 DISBURSE_VALUE FROM sme_expenses_fund WHERE YEAR_BDG = A.YEAR_BDG AND [YEAR]*100+[MONTH] <= '{$YEAR_MONTH_NOW}' ORDER BY [YEAR]*100+[MONTH] DESC) AS DISBURSE_VALUE
		,(SELECT TOP 1 BINDING_VALUE FROM sme_expenses_fund WHERE YEAR_BDG = A.YEAR_BDG AND [YEAR]*100+[MONTH] <= '{$YEAR_MONTH_NOW}' ORDER BY [YEAR]*100+[MONTH] DESC) AS BINDING_VALUE
	FROM prjp_project A
	WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1
		{$wh_bdg_prev}
) TB
GROUP BY YEAR_BDG,MONEY_BDG2,DISBURSE_VALUE,BINDING_VALUE
ORDER BY YEAR_BDG
";
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$arr_bdg_prev['YEAR'][$rec['YEAR_BDG']] = $rec['YEAR_BDG'];
	$BDG = $rec['MONEY_BDG'] ;
	$REPORT = $rec['MONEY_REPORT'] ;
	$BINDING = $rec['MONEY_BINDING'] ;
	$REMAIN = $BDG - ($REPORT + $BINDING);
	
	$arr_bdg_prev['BDG'][$rec['YEAR_BDG']] = $BDG;
	$arr_bdg_prev['REPORT'][$rec['YEAR_BDG']] = $REPORT;
	@$arr_bdg_prev['REPORT_P'][$rec['YEAR_BDG']] = $REPORT/$BDG*100.00;
	$arr_bdg_prev['BINDING'][$rec['YEAR_BDG']] = $BINDING;
	@$arr_bdg_prev['BINDING_P'][$rec['YEAR_BDG']] = $BINDING/$BDG*100.00;
	$arr_bdg_prev['REMAIN'][$rec['YEAR_BDG']] = $REMAIN;
	@$arr_bdg_prev['REMAIN_P'][$rec['YEAR_BDG']] = $REMAIN/$BDG*100.00;
}

$arr_slide3_bg[0] = 'dot-red';
$arr_slide3_bg[1] = 'dot-yellow';
$arr_slide3_bg[2] = 'dot-green';

$arr_slide3_txt[0] = 'txt-red';
$arr_slide3_txt[1] = 'txt-orange';
$arr_slide3_txt[2] = 'txt-green';

$arr_slide5_prjp = array();
$arr_slide5_prjp_categories = array();
$arr_slide5_prjp_series1 = array();
$arr_slide5_prjp_series2 = array();
$arr_slide5_prjp_series3 = array();
$slide05_total_bdg = 0;
$arr_slide5_prjp_total = array();

if(count($arr_prjp_bdg) > 0){
	foreach($arr_prjp_bdg as $PRJP_ID => $arrDTL){
		$arr_slide5_prjp['NAME'][$PRJP_ID] = $arrDTL['PRJP_NAME'];
		$arr_slide5_prjp['CODE'][$PRJP_ID] = $arrDTL['PRJP_CODE'];
		$arr_slide5_prjp['ORG'][$PRJP_ID] = $arrDTL['ORG_SHORTNAME'];

		$arr_slide5_prjp['BDG'][$PRJP_ID] = $arrDTL['MONEY_BDG'];
		$arr_slide5_prjp['REP'][$PRJP_ID] = $arr_prjp_rep[$PRJP_ID];
		$arr_slide5_prjp['BID'][$PRJP_ID] = $arr_prjp_bid[$PRJP_ID];
		$arr_slide5_prjp['REP_BID'][$PRJP_ID] = $arr_prjp_rep[$PRJP_ID] + $arr_prjp_bid[$PRJP_ID];
		$arr_slide5_prjp['REM'][$PRJP_ID] = $arrDTL['MONEY_BDG'] - $arr_slide5_prjp['REP_BID'][$PRJP_ID];

		$arr_slide5_prjp['REP_P'][$PRJP_ID] = get_percent($arrDTL['MONEY_BDG'], $arr_prjp_rep[$PRJP_ID]);
		$arr_slide5_prjp['BID_P'][$PRJP_ID] = get_percent($arrDTL['MONEY_BDG'], $arr_prjp_bid[$PRJP_ID]);
		$arr_slide5_prjp['REP_BID_P'][$PRJP_ID] = get_percent($arrDTL['MONEY_BDG'], $arr_slide5_prjp['REP_BID'][$PRJP_ID]);
		$arr_slide5_prjp['REM_P'][$PRJP_ID] = 100.00 - $arr_slide5_prjp['REP_BID_P'][$PRJP_ID];
	}

	@arsort($arr_slide5_prjp['REP_P']);
	foreach($arr_slide5_prjp['REP_P'] as $PRJP_ID => $arrDTL){
		$arr_slide5_prjp_categories[$PRJP_ID] = $arr_slide5_prjp['NAME'][$PRJP_ID];
		$arr_slide5_prjp_series1[$PRJP_ID] = $arr_slide5_prjp['REM_P'][$PRJP_ID];
		$arr_slide5_prjp_series2[$PRJP_ID] = $arr_slide5_prjp['BID_P'][$PRJP_ID];
		$arr_slide5_prjp_series3[$PRJP_ID] = $arr_slide5_prjp['REP_P'][$PRJP_ID];

		$arr_slide5_prjp['BDG_P'][$PRJP_ID] = get_percent($slide05_total_bdg, $arr_slide5_prjp['BDG'][$PRJP_ID]);

		$arr_slide5_prjp_total['BDG'] += $arr_slide5_prjp['BDG'][$PRJP_ID];
		$arr_slide5_prjp_total['REP'] += $arr_slide5_prjp['REP'][$PRJP_ID];
		$arr_slide5_prjp_total['BID'] += $arr_slide5_prjp['BID'][$PRJP_ID];
		$arr_slide5_prjp_total['REP_BID'] += $arr_slide5_prjp['REP_BID'][$PRJP_ID];
		$arr_slide5_prjp_total['REM'] += $arr_slide5_prjp['REM'][$PRJP_ID];
	}
}
// print_arr($arr_slide5_prjp); exit;
$arr_slide5_prjp_sort = $arr_slide5_prjp['REP_P'];

//slide6
if($DASHBOARD_YEAR < $YEAR_CHECK){
	$wh_prjp_project_log = " AND YEAR(CREATE_DATE)*100+MONTH(CREATE_DATE) >= '".($DASHBOARD_YEAR-543)."07' ";
}else{
	$wh_prjp_project_log = " AND YEAR(CREATE_DATE)*100+MONTH(CREATE_DATE) = YEAR(GETDATE())*100+MONTH(GETDATE()) ";
	$max_prjp_project_log_date1 = date('Ym').'07';
	$max_prjp_project_log_date2 = date('Ym').'07';
}
$sql = "
SELECT A.PRJP_ID, A.PRJP_CODE, A.PRJP_NAME, A.MONEY_BDG, prjp_ae.AE_FNAME, prjp_ae.AE_LNAME, D2.ORG_SHORTNAME
	, (SELECT MAX(YEAR(CREATE_DATE)*10000+MONTH(CREATE_DATE)*100+DAY(CREATE_DATE)) FROM prjp_project_log A2 INNER JOIN aut_user B ON B.AUT_USER_ID = A2.AUT_USER_ID WHERE REPORT_STATUS = 1 AND REPORT_STATUS_TYPE = 1 AND PRJP_ID = A.PRJP_ID {$wh_prjp_project_log}) AS report_date1
	, (SELECT MAX(YEAR(CREATE_DATE)*10000+MONTH(CREATE_DATE)*100+DAY(CREATE_DATE)) FROM prjp_project_log A2 INNER JOIN aut_user B ON B.AUT_USER_ID = A2.AUT_USER_ID WHERE REPORT_STATUS = 1 AND REPORT_STATUS_TYPE = 2 AND PRJP_ID = A.PRJP_ID {$wh_prjp_project_log}) AS report_date2
	, (SELECT MAX(YEAR(CREATE_DATE)*10000+MONTH(CREATE_DATE)*100+DAY(CREATE_DATE)) FROM prjp_project_log A2 INNER JOIN aut_user B ON B.AUT_USER_ID = A2.AUT_USER_ID WHERE REPORT_STATUS_TYPE = 3 AND PRJP_ID = A.PRJP_ID {$wh_prjp_project_log}) AS report_date3
FROM prjp_project A
LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
LEFT JOIN setup_org_bu ON setup_org_bu.ORG_ID = A.ORG_ID
LEFT JOIN prjp_ae ON prjp_ae.PRJP_ID = A.PRJP_ID
WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}' {$wh_search}
ORDER BY RIGHT(A.PRJP_CODE, 3) ASC, A.PRJP_CODE ASC, A.PRJP_ID ASC
";
// echo $sql; exit;
$i = 1;
$i_pass = ' dot-green ';
$i_pass2 = ' dot-yellow ';
$i_unpass = ' dot-red ';
$arr_prjp_report_status = array();
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$chk_report_text = array();
	$report_date1 = !empty($rec['report_date1']) ? $rec['report_date1'] : '';
	$report_date2 = !empty($rec['report_date2']) ? $rec['report_date2'] : '';
	
	if($DASHBOARD_YEAR < $YEAR_CHECK){
		$report_date1_ym = substr($report_date1, 0, 6);
		$report_date2_ym = substr($report_date2, 0, 6);
		
		$max_prjp_project_log_date1 = $report_date1_ym.'07';
		$max_prjp_project_log_date2 = $report_date2_ym.'07';
	}
	
	$report_date_i1 = '';
	$report_date_i2 = '';
	$report_text_i1 = '';
	$report_text_i2 = '';

	if(empty($report_date1) || empty($report_date2)){
		$chk_report_date = 0;
		$chk_report_icon = $i_unpass;
	}
	
	elseif($report_date1 <= ($max_prjp_project_log_date1) && $report_date2 <= ($max_prjp_project_log_date2)){
		$chk_report_date = 2;
		$chk_report_icon = $i_pass;
	}
	else{
		$chk_report_date = 1;
		$chk_report_icon = $i_pass2;
	}

	if(empty($report_date1)){
		$report_date_i1 = $i_unpass;
		$report_text_i1 = ' bg-status-very-late ';
	}
	elseif($report_date1 <= ($max_prjp_project_log_date1)){
		$report_date_i1 = $i_pass;
		$report_text_i1 = ' bg-status-planing ';
	}
	else{
		$report_date_i1 = $i_pass2;
		$report_text_i1 = ' bg-status-moderate-late ';
	}
	
	if(empty($report_date2)){
		$report_date_i2 = $i_unpass;
		$report_text_i2 = ' bg-status-very-late ';
	}
	elseif($report_date2 <= ($max_prjp_project_log_date2)){
		$report_date_i2 = $i_pass;
		$report_text_i2 = ' bg-status-planing ';
	}
	else{
		$report_date_i2 = $i_pass2;
		$report_text_i2 = ' bg-status-moderate-late ';
	}

	$chk_report_text[] = $report_date_i1." 200/1-2";
	$chk_report_text[] = $report_date_i2." 200/3";
	
	$chk_report_text_show = implode("<br />", $chk_report_text);
	
	$arr_prjp_report_status[$rec['PRJP_ID']]['CODE'] = $rec['PRJP_CODE'];
	$arr_prjp_report_status[$rec['PRJP_ID']]['NAME'] = $rec['PRJP_NAME'];
	$arr_prjp_report_status[$rec['PRJP_ID']]['ORG'] = $rec['ORG_SHORTNAME'];
	$arr_prjp_report_status[$rec['PRJP_ID']]['AE'] = $rec['AE_FNAME'].' '.$rec['AE_LNAME'];
	$arr_prjp_report_status[$rec['PRJP_ID']]['ICON'] = $chk_report_icon;
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][1]['TEXT'] = '200/1-2';
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][1]['DATE'] = $report_date1;
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][1]['COLOR'] = $report_text_i1;
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][1]['ICON'] = $report_date_i1;
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][2]['TEXT'] = '200/3';
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][2]['DATE'] = $report_date2;
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][2]['COLOR'] = $report_text_i2;
	$arr_prjp_report_status[$rec['PRJP_ID']]['STATUS'][2]['ICON'] = $report_date_i2;
}

$arr_query = array();
if(count($arr_prjp_report_status) > 0){
	foreach($arr_prjp_report_status as $PRJP_ID => $arrDTL){
		$report_date1 = $arrDTL['STATUS'][1]['DATE'];
		$report_date2 = $arrDTL['STATUS'][2]['DATE'];

		$sql = "SELECT TOP 1 A.AUT_USER_ID, B.AUT_F_NAME, B.AUT_L_NAME, A.CREATE_DATE
			FROM prjp_project_log A
			INNER JOIN aut_user B ON B.AUT_USER_ID = A.AUT_USER_ID
			WHERE A.REPORT_STATUS = 1 AND A.REPORT_STATUS_TYPE = 1 AND A.PRJP_ID = '{$PRJP_ID}' AND YEAR(A.CREATE_DATE)*10000+MONTH(A.CREATE_DATE)*100+DAY(A.CREATE_DATE) = '{$report_date1}'
			ORDER BY CREATE_DATE DESC
		";
		// echo $sql; exit;
		$arr_query[1][$arrDTL['CODE']] = $sql;
		$query = $db->query($sql);
		$rec = $db->db_fetch_array($query);
		if(empty($rec['AUT_USER_ID'])){
			//$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['TEXT'] .= "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['DATE2'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['TIME'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['AUT_F_NAME'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['AUT_L_NAME'] = "(ยังไม่บันทึกข้อมูล)";
		}else{
			/*
			if($_SESSION["sys_group_id"]=='5' || $_SESSION['sys_program_administrator'] == 1){
				$date = conv_date($rec['CREATE_DATE'], '', '');
				$time = conv_date($rec['CREATE_DATE'], '', 3);
			}else{
				$date = conv_date($rec['CREATE_DATE']);
			}
			*/
			$date = conv_date($rec['CREATE_DATE'], '', '');
			$time = conv_date($rec['CREATE_DATE'], '', 3);
			//$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['TEXT'] .= $date." ".text($rec['AUT_F_NAME']." ".$rec['AUT_L_NAME']);
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['DATE2'] = $date;
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['TIME'] = $time;
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['AUT_F_NAME'] = text($rec['AUT_F_NAME']);
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['AUT_L_NAME'] = text(empty($rec['AUT_L_NAME'])?"-":$rec['AUT_L_NAME']);
		}

		$sql = "SELECT TOP 1 A.AUT_USER_ID, B.AUT_F_NAME, B.AUT_L_NAME, A.CREATE_DATE
			FROM prjp_project_log A
			INNER JOIN aut_user B ON B.AUT_USER_ID = A.AUT_USER_ID
			WHERE A.REPORT_STATUS = 1 AND A.REPORT_STATUS_TYPE = 2 AND A.PRJP_ID = '{$PRJP_ID}' AND YEAR(A.CREATE_DATE)*10000+MONTH(A.CREATE_DATE)*100+DAY(A.CREATE_DATE) = '{$report_date2}'
			ORDER BY CREATE_DATE DESC
		";
		// echo $sql; exit;
		$arr_query[2][$arrDTL['CODE']] = $sql;
		$query = $db->query($sql);
		$rec = $db->db_fetch_array($query);
		if(empty($rec['AUT_USER_ID'])){
			//$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['TEXT'] .= "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['DATE2'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['TIME'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['AUT_F_NAME'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['AUT_L_NAME'] = "(ยังไม่บันทึกข้อมูล)";
		}else{
			/*if($_SESSION["sys_group_id"]=='5' || $_SESSION['sys_program_administrator'] == 1){
				$date = conv_date($rec['CREATE_DATE'], '', '');
				$time = conv_date($rec['CREATE_DATE'], '', 3);
			}else{
				$date = conv_date($rec['CREATE_DATE']);
			}*/
			$date = conv_date($rec['CREATE_DATE'], '', '');
			$time = conv_date($rec['CREATE_DATE'], '', 3);
			//$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['TEXT'] .= $date." ".text($rec['AUT_F_NAME']." ".$rec['AUT_L_NAME']);
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['DATE2'] = $date;
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['TIME'] = $time;
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['AUT_F_NAME'] = text($rec['AUT_F_NAME']);
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][2]['AUT_L_NAME'] = text(empty($rec['AUT_L_NAME'])?"-":$rec['AUT_L_NAME']);
		}
		
		$sql = "SELECT TOP 1 A.AUT_USER_ID, B.AUT_F_NAME, B.AUT_L_NAME, A.CREATE_DATE
			FROM prjp_project_log A
			INNER JOIN aut_user B ON B.AUT_USER_ID = A.AUT_USER_ID
			WHERE A.REPORT_STATUS_TYPE = 3 AND A.PRJP_ID = '{$PRJP_ID}' AND YEAR(A.CREATE_DATE)*10000+MONTH(A.CREATE_DATE)*100+DAY(A.CREATE_DATE) = '{$report_date2}'
			ORDER BY CREATE_DATE DESC
		";
		// echo $sql; exit;
		$arr_query[3][$arrDTL['CODE']] = $sql;
		$query = $db->query($sql);
		$rec = $db->db_fetch_array($query);
		if(empty($rec['AUT_USER_ID'])){
			//$arr_prjp_report_status[$PRJP_ID]['STATUS'][1]['TEXT'] .= "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['DATE2'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['TIME'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['AUT_F_NAME'] = "(ยังไม่บันทึกข้อมูล)";
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['AUT_L_NAME'] = "(ยังไม่บันทึกข้อมูล)";
		}else{
			/*if($_SESSION["sys_group_id"]=='5' || $_SESSION['sys_program_administrator'] == 1){
				$date = conv_date($rec['CREATE_DATE'], '', '');
				$time = conv_date($rec['CREATE_DATE'], '', 3);
			}else{
				$date = conv_date($rec['CREATE_DATE']);
			}*/
			$date = conv_date($rec['CREATE_DATE'], '', '');
			$time = conv_date($rec['CREATE_DATE'], '', 3);
			//$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['TEXT'] .= $date." ".text($rec['AUT_F_NAME']." ".$rec['AUT_L_NAME']);
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['DATE2'] = $date;
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['TIME'] = $time;
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['AUT_F_NAME'] = text($rec['AUT_F_NAME']);
			$arr_prjp_report_status[$PRJP_ID]['STATUS'][3]['AUT_L_NAME'] = text(empty($rec['AUT_L_NAME'])?"-":$rec['AUT_L_NAME']);
		}
	}
}
// print_arr($arr_prjp_report_status); exit;

//slide7
$sql = "
SELECT A.PRJP_ID, A.PRJP_CODE, A.PRJP_NAME, D2.ORG_SHORTNAME
	, A.PRJP_RUN_STATUS , B.status_active
	, B.prjp_step_id, B.step_sdate, B.step_edate, B.step_file
FROM prjp_project A
	LEFT JOIN setup_org_bu D2 ON D2.ORG_ID = A.ORG_ID
	LEFT JOIN prjp_set_step B ON B.PRJP_ID = A.PRJP_ID
WHERE A.PRJP_LEVEL = 1 AND A.PRJP_STATUS_SHOW = 1 AND A.YEAR_BDG = '{$DASHBOARD_YEAR}' {$wh_search}
ORDER BY RIGHT(A.PRJP_CODE, 3) ASC, A.PRJP_CODE ASC, A.PRJP_ID ASC
";
$arr_prjp_report_step = array();
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$arr_prjp_report_step[$rec['PRJP_ID']]['CODE'] = $rec['PRJP_CODE'];
	$arr_prjp_report_step[$rec['PRJP_ID']]['NAME'] = $rec['PRJP_NAME'];
	$arr_prjp_report_step[$rec['PRJP_ID']]['ORG'] = $rec['ORG_SHORTNAME'];
	$arr_prjp_report_step[$rec['PRJP_ID']]['STATUS'] = $rec['PRJP_RUN_STATUS'];
	$arr_prjp_report_step[$rec['PRJP_ID']]['STEP'][$rec['prjp_step_id']]['DATE'] = conv_date($rec['step_sdate']);
	
	if(($rec['prjp_step_id'] == 2 && $rec['status_active'] == 1)){
		$status = 2;
	}
	elseif($rec['status_active'] == 1){
		$status = 1;
	}else{
		$status = 0 ;
	}
	$arr_prjp_report_step[$rec['PRJP_ID']]['STEP'][$rec['prjp_step_id']]['STATUS'] = $status;
	//$arr_prjp_report_step[$rec['PRJP_ID']]['STEP'][$rec['prjp_step_id']]['FILE_C'] = $num_step_file;
}

if(count($arr_prjp_report_step) > 0){
	$list_prjp_id = implode(",", array_keys($arr_prjp_report_step));
	$sql_step_file = "select PRJP_ID, prjp_step_id, count(step_file_id) AS count_file FROM prjp_step_file WHERE PRJP_ID IN ({$list_prjp_id}) GROUP BY PRJP_ID, prjp_step_id ";
	$query_step_file = $db->query($sql_step_file);
	while($rec = $db->db_fetch_array($query_step_file)){
		$status = $arr_prjp_report_step[$rec['PRJP_ID']]['STEP'][$rec['prjp_step_id']]['STATUS'];
		
		if($status != 2){
			if($rec['count_file'] > 0 && $status > 0){
				$status = 2;
			}
			elseif($rec['count_file'] > 0){
				$status = 1;
			}
		}
		
		$arr_prjp_report_step[$rec['PRJP_ID']]['STEP'][$rec['prjp_step_id']]['STATUS'] = $status;
	}
}

	// echo "<pre>";
	//    echo text($dash_config3['grap3_text']);
	// echo "</pre>";

				

$bar_stacked_input_org0 = 1;
