<?php
session_start();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=report_17_xls.xls");
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '1';
$path_cache = 'cache/'; ////////ที่เราเก็บไฟล์
$FILE_NAME = pathinfo(__FILE__, PATHINFO_FILENAME); /////////อ่านpath.php   ชื่อไฟล์คือ payment_print.php

$sql_year = "select YEAR_BDG FROM plan_round";
$query_year = $db->query($sql_year);
while ($rec_y = $db->db_fetch_array($query_year)) {
    $arr_y[] = $rec_y['YEAR_BDG'];
}

$_POST['s_round_year_bud'] = $_SESSION['year_round'];
if (date("m") == 1) {
    $YEAR_NOW = (date("Y") + 543) - 1;
    $MONTH_NOW = 12;
} else {
    $YEAR_NOW = (date("Y") + 543);
    $MONTH_NOW = date("m") - 1;
}
$MONTH_NOW = sprintf('%02d', $MONTH_NOW);
$YEAR_MONTH_NOW = $YEAR_NOW . $MONTH_NOW;
$YEAR_MONTH_NEXT = (date("Y") + 543) . date("m");
$YEAR_NOW_SH = substr($YEAR_NOW, -2);

$sql_strgic = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic = $db->query($sql_strgic);
while ($rec_strgic  = $db->db_fetch_array($query_strgic)) {
    $arr_strgic[$rec_strgic["STRGIC_ID"]] = $rec_strgic["STRGIC_NAME"];
}
$sql_strgic2 = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic2 where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic2 = $db->query($sql_strgic2);
while ($rec_strgic2  = $db->db_fetch_array($query_strgic2)) {
    $arr_strgic2[$rec_strgic2["STRGIC_ID"]] = $rec_strgic2["STRGIC_NAME"];
}
$sql_strgic3 = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic3 where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic3 = $db->query($sql_strgic3);
while ($rec_strgic3  = $db->db_fetch_array($query_strgic3)) {
    $arr_strgic3[$rec_strgic3["STRGIC_ID"]] = $rec_strgic3["STRGIC_NAME"];
}

$filter == "";

if ($_POST['YEAR_ROUND_S'] != '' && $_POST['YEAR_ROUND_E'] != '') {
    $filter .= " AND YEAR_BDG BETWEEN '" . $_POST['YEAR_ROUND_S'] . "' AND '" . $_POST['YEAR_ROUND_E'] . "' ";
} else {
    $filter .= " AND YEAR_BDG = '" . $_POST['s_round_year_bud'] . "' ";
}
if ($_POST['s_STRGIC_type'] == 1 && $_POST['s_STRGIC_ID1'] != "") {
    $filter .= " AND STRGIC_ID = '" . $_POST['s_STRGIC_ID1'] . "' ";
}
if ($_POST['s_STRGIC_type'] == 2 && $_POST['s_STRGIC_ID2'] != "") {
    $filter .= " AND STRGIC_ID2 = '" . $_POST['s_STRGIC_ID2'] . "' ";
}
if ($_POST['s_STRGIC_type'] == 3 && $_POST['s_STRGIC_ID3'] != "") {
    $filter .= " AND STRGIC_ID3 = '" . $_POST['s_STRGIC_ID3'] . "' ";
}

$sql_main = " SELECT * FROM wh_bdg_payment WHERE 1=1 {$filter} ORDER BY ID_COLUMN_3, ID_COLUMN_1 ASC, ID_COLUMN_2 ASC  ";
$query_main = $db->query($sql_main);
$num_rows = $db->db_num_rows($query_main);
$arr_data_report     = array();
$arr_data_report2    = array();
$arr_data_report3    = array();

$arr_data_report4    = array();
$arr_data_report4_2  = array();
$arr_data_report5    = array();
$col_0                 = array();

$MONEY_BDG_SME        = 0;
$MONEY_BDG_OUT        = 0;
$allow_money         = 0;
$pay_money        = 0;
$po_money        = 0;
$pay_po_money        = 0;
while ($rec_main = $db->db_fetch_array($query_main)) {
    $col_0[$rec_main['ID_COLUMN_1'] . '_' . $rec_main['ID_COLUMN_2']] = $rec_main['ID_COLUMN_2'];
    $arr_data_report[$rec_main['ID_COLUMN_1']] = $rec_main['COLUMN_1'];

    $arr_data_report2[$rec_main['ID_COLUMN_1']][$rec_main['ID_COLUMN_2']] = $rec_main['COLUMN_2'];
    $arr_data_report3[$rec_main['ID_COLUMN_3']] = $rec_main['COLUMN_3'];

    $arr_data_report4[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']][$rec_main['ID_COLUMN_5']] = $rec_main['COLUMN_4'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['PRJP_CODE'] = $rec_main['PRJP_CODE'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['PRJP_YEAR'] = $rec_main['YEAR_BDG'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['MONEY_BDG_SME'] = $rec_main['MONEY_BDG_SME'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['MONEY_BDG_OUT'] = $rec_main['MONEY_BDG_OUT'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['MONEY_1'] = $rec_main['MONEY_1'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['MONEY_2_1'] = $rec_main['MONEY_2_1'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['MONEY_2_2'] = $rec_main['MONEY_2_2'];
    $arr_data_report4_2[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']]['MONEY_2'] = $rec_main['MONEY_2'];

    $arr_data_report5[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']][$rec_main['ID_COLUMN_1']][$rec_main['ID_COLUMN_2']][$rec_main['ID_COLUMN_5']]['MONEY_3'] = $rec_main['MONEY_3'];
    $arr_data_report5[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']][$rec_main['ID_COLUMN_1']][$rec_main['ID_COLUMN_2']][$rec_main['ID_COLUMN_5']]['MONEY_4'] = $rec_main['MONEY_4'];
    $arr_data_report5[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']][$rec_main['ID_COLUMN_1']][$rec_main['ID_COLUMN_2']][$rec_main['ID_COLUMN_5']]['UNIT_NAME'] = $rec_main['UNIT_NAME'];
    if ($rec_main['PLAN_TYPE'] == 'R') {
        $arr_data_report5[$rec_main['ID_COLUMN_3']][$rec_main['ID_COLUMN_4']][$rec_main['ID_COLUMN_1']][$rec_main['ID_COLUMN_2']][$rec_main['ID_COLUMN_5']]['COLOR'] = '#ffffff';
    }
}

ksort($arr_data_report);
ksort($arr_data_report2);
ksort($arr_data_report3);

$col_1 = array();
if (count($arr_data_report2) > 0) {
    foreach ($arr_data_report2 as $key => $val) {
        foreach ($arr_data_report2[$key] as $key2 => $val2) {
            $col_1[$key] += 1;
        }
    }
}
//print_arr($arr_data_report3);
foreach ($arr_data_report3 as $k_1 => $v_1) {
    $x = 1;
    //$arr_data_report3_1  = array();
    foreach ($arr_data_report4[$k_1] as $k_2 => $v_2) {
        $x = 1;

        foreach ($arr_data_report4[$k_1][$k_2] as $k_3 => $v_3) {
            if ($x == 1) {
                $MONEY_BDG_SME += $arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_SME'];
                $MONEY_BDG_OUT += $arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_OUT'];
                $allow_money += $arr_data_report4_2[$k_1][$k_2]['MONEY_1'];
                $pay_money += $arr_data_report4_2[$k_1][$k_2]['MONEY_2_1'];
                $po_money += $arr_data_report4_2[$k_1][$k_2]['MONEY_2_2'];
                $pay_po_money += $arr_data_report4_2[$k_1][$k_2]['MONEY_2'];

                $arr_data_report3_1[$k_1]['MONEY_BDG_SME'] += $arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_SME'];
                $arr_data_report3_1[$k_1]['MONEY_BDG_OUT'] += $arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_OUT'];
                $arr_data_report3_1[$k_1]['MONEY_1'] += $arr_data_report4_2[$k_1][$k_2]['MONEY_1'];
                $arr_data_report3_1[$k_1]['MONEY_2_1'] += $arr_data_report4_2[$k_1][$k_2]['MONEY_2_1'];
                $arr_data_report3_1[$k_1]['MONEY_2_2'] += $arr_data_report4_2[$k_1][$k_2]['MONEY_2_2'];
                $arr_data_report3_1[$k_1]['MONEY_2'] += $arr_data_report4_2[$k_1][$k_2]['MONEY_2'];
            }

            $x++;
        }
    }
}
//echo count($arr_data_report4_2[''])+count($arr_data_report4_2[1])+count($arr_data_report4_2[2])+count($arr_data_report4_2[3])+count($arr_data_report4_2[4])+count($arr_data_report4_2[5]);
function number_format_chk($number = '', $digit = 0)
{
    if ($number == '') {
        return '';
    } else {
        return number_format($number, $digit);
    }
}
/* 
 echo '<pre>';
print_r($col_1);
echo '</pre>'  */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    $html = '';
    if ($_POST['s_round_year_bud'] != "") {
        if ($_POST['YEAR_ROUND_S'] != '' && $_POST['YEAR_ROUND_E'] != '' && $_POST['YEAR_ROUND_S'] != $_POST['YEAR_ROUND_E']) {
            $text_year = "ระหว่างปี " . $_POST['YEAR_ROUND_S'] . " ถึง " . $_POST['YEAR_ROUND_E'] . " ";
        } else if ($_POST['YEAR_ROUND_S'] != '' && $_POST['YEAR_ROUND_E'] != '' && $_POST['YEAR_ROUND_S'] == $_POST['YEAR_ROUND_E']) {
            $text_year = $_POST['YEAR_ROUND_S'];
        } else {
            $text_year = $_SESSION['year_round'];
        }
        $html = '';
        $html .= '<div class="row">
								<div align="center" >
									<strong>รายงานเชิงคุณภาพต่างๆ ปี ' . $text_year . '</strong>
                                   
								</div>
							</div>
							<div class="row">
								<div align="center" >
									<strong>&nbsp;</strong>
								</div>
							</div>';
        //class="table table-bordered table-striped table-hover table-condensed" width="100%"
        $html .= '<table  border="1" width="100%" class=" table-hover table-condensed table-freeze" style="border-collapse: collapse;background-color:#FFFFFF;">';
        $html .= '<thead>';
        $html .= '<tr >';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"><strong>รหัสโครงการ  </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:4cm;"><strong> แนวทาง/<br>ตัวชี้วัด/<br>โครงการ/<br>กิจกรรม </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"><strong> กระทรวง </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"><strong> หน่วยงาน </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> ชื่อย่อหน่วยงาน </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:4cm;"  ><strong> รวมทั้งสิ้น </strong></div></td>';
        $html .= '<td class="" colspan="10" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> ผลที่คาดว่าจะได้รับ </strong></div></td>';
        $html .= '</tr>';

        $html .= '<tr >';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 1 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 2 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 3 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 4 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 5 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 6 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 7 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 8 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 9 </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> 10 </strong></div></td>';
        $html .= '</tr>';

        $html .= '<tr >';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> N/A </strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> อื่น ๆ</strong></div></td>';
        $html .= '</tr>';




        // if(count($arr_data_report > 0)){
        // 	foreach($arr_data_report as $k_1 => $v_1){ //##  Start ยุทธศาสตร์
        // 		$html .= '<td colspan='.(($col_1[$k_1])*3).' valign="top" style="background-color:#ffffff;" nowrap><div align="center" valign="top"><strong> '.text($v_1).' </strong></div></td>';
        // 	} // ## End ยุทธศาสตร์

        // }







        // $html .= '<tr >';
        // 	$html .= '<td style="vertical-align:middle;background-color:#E0E0E0;"><div align="center" style="width:2.1cm;"><strong>  </strong></div></td>';
        // 	if(count($arr_data_report2 > 0)){
        // 		foreach($arr_data_report2 as $k_2 => $v_2){ //##  Start
        // 			foreach($arr_data_report2[$k_2] as $k_2_1 => $v_2_1){
        // 				$html .= '<td style="vertical-align:middle;background-color:#E0E0E0;"><div align="center" style="width:2.1cm;"><strong> เป้าหมาย </strong></div></td>';
        // 				$html .= '<td style="vertical-align:middle;background-color:#E0E0E0;"><div align="center" style="width:2.1cm;"><strong> ผลทีได้ </strong></div></td>';
        // 				$html .= '<td style="vertical-align:middle;background-color:#E0E0E0;"><div align="center" style="width:2.1cm;"><strong> หน่วยนับ </strong></div></td>';
        // 			}
        // 		} // ## End
        // 	}

        // $html .= '</tr>'; 

        $html .= '</thead>';
        $html .= '<tbody>';




        if (count($arr_data_report3 > 0)) { //##  Start แนวทาง
            $i_proj0   = 1;
            $S_MONEY_1 = 0;
            $i_proj = 1;
            foreach ($arr_data_report3 as $k_1 => $v_1) {

                $html .= '<tr >';
                $html .= '<td class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="right" style="width:100%;"  ><strong>%</strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '</tr>';

                $html .= '<tr >';
                $html .= '<td class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="right" style="width:100%;"  ><strong>รวม</strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '</tr>';

                $html .= '<tr >';
                $html .= '<td class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong>กระทรวง</strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';


                $html .= '</tr>';

                $html .= '<tr >';
                $html .= '<td class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong>หน่วยงาน</strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '</tr>';



                $html .= '<tr >';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="right" style="width:100%;"  ><strong>65020011</strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="left" style="width:100%;"  ><strong>โครงการ xxx <br>กิจกรรม xxx<br>ตัวชี้วัดxxx</strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';
                $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" align="center" ><div style="width:2cm;"  ><strong> </strong></div></td>';

                $html .= '</tr>';



                // 	$html .= '<td class="freez-1"  style="background-color:#F5F5F5;" colspan="3"><div align="left"><strong> '.text($v_1).' </strong></div></td>';
                // 	$html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:#F5F5F5;\' nowrap><div align="right">'.(!isset($arr_data_report3_1[$k_1]['MONEY_BDG_SME']) ? '-' : number_format_chk($arr_data_report3_1[$k_1]['MONEY_BDG_SME'],2)).'</div></td>'; 
                // 	$html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:#F5F5F5;\' nowrap><div align="right">'.(!isset($arr_data_report3_1[$k_1]['MONEY_BDG_OUT']) ? '-' : number_format_chk($arr_data_report3_1[$k_1]['MONEY_BDG_OUT'],2)).'</div></td>'; 
                // 	$html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:#F5F5F5;\' nowrap><div align="right">'.(!isset($arr_data_report3_1[$k_1]['MONEY_1']) ? '-' : number_format_chk($arr_data_report3_1[$k_1]['MONEY_1'],2)).'</div></td>'; 
                // 	$html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:#F5F5F5;\' nowrap><div align="right">'.(!isset($arr_data_report3_1[$k_1]['MONEY_2_1']) ? '-' : number_format_chk($arr_data_report3_1[$k_1]['MONEY_2_1'],2)).'</div></td>';
                // 	$html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:#F5F5F5;\' nowrap><div align="right">'.(!isset($arr_data_report3_1[$k_1]['MONEY_2_2']) ? '-' : number_format_chk($arr_data_report3_1[$k_1]['MONEY_2_2'],2)).'</div></td>';
                // 	$html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:#F5F5F5;\' nowrap><div align="right">'.(!isset($arr_data_report3_1[$k_1]['MONEY_2']) ? '-' : number_format_chk($arr_data_report3_1[$k_1]['MONEY_2'],2)).'</div></td>';
                // 	$html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:#F5F5F5;\' nowrap><div align="right">'.number_format_chk($arr_data_report3_1[$k_1]['MONEY_1'] - $arr_data_report3_1[$k_1]['MONEY_2'],2).'</div></td>';
                // 	$html .= '<td style="background-color:#F5F5F5;"><div align="left"></div></td>';

                if (count($arr_data_report2 > 0)) {
                    foreach ($arr_data_report2 as $k_2 => $v_2) { //##  Start
                        foreach ($arr_data_report2[$k_2] as $k_2_1 => $v_2_1) {

                            // $html .= '<td style="background-color:#F5F5F5;"><div align="right"><strong>  </strong></div></td>';
                            // $html .= '<td style="background-color:#F5F5F5;"><div align="right"><strong>  </strong></div></td>';
                            // $html .= '<td style="background-color:#F5F5F5;"><div align="right"><strong>  </strong></div></td>';
                        }
                    } // ## End
                }

                $html .= '</tr >';
                $ddd = 1;
                foreach ($arr_data_report4[$k_1] as $k_2 => $v_2) {
                    $ddd = 1;
                    foreach ($arr_data_report4[$k_1][$k_2] as $k_3 => $v_3) {

                        // $html .= '<tr valign="top">';
                        // 	if($ddd == 1){
                        // 		$S_MONEY_BDG_SME += $arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_SME'];
                        // 		$S_MONEY_BDG_OUT += $arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_OUT'];
                        // 		$S_MONEY_1 += $arr_data_report4_2[$k_1][$k_2]['MONEY_1'];
                        // 		$S_MONEY_2_1 += $arr_data_report4_2[$k_1][$k_2]['MONEY_2_1'];
                        // 		$S_MONEY_2_2 += $arr_data_report4_2[$k_1][$k_2]['MONEY_2_2'];
                        // 		$S_MONEY_2 += $arr_data_report4_2[$k_1][$k_2]['MONEY_2'];
                        // 		$html .= '<td class="freez-1" rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;background-color:#FFFFFF;" ><div align="center" style="" >  '.$arr_data_report4_2[$k_1][$k_2]['PRJP_YEAR'].' </div></td>';
                        // 		$html .= '<td class="freez-1" rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;background-color:#FFFFFF;" ><div align="center" style="" >  '.$arr_data_report4_2[$k_1][$k_2]['PRJP_CODE'].' </div></td>';
                        // 		$html .= '<td class="freez-2" rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;background-color:#FFFFFF;" ><div align="left" style="" >  '.text($v_3).' </div></td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" align="right" style=\'mso-number-format:"\#\,\#\#0\.00"\'><div align="right" nowrap> '.number_format_chk($arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_SME'],2).' </div></td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" align="right" style=\'mso-number-format:"\#\,\#\#0\.00"\'><div align="right" nowrap> '.number_format_chk($arr_data_report4_2[$k_1][$k_2]['MONEY_BDG_OUT'],2).' </div></td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" align="right" style=\'mso-number-format:"\#\,\#\#0\.00"\'><div align="right" nowrap> '.number_format_chk($arr_data_report4_2[$k_1][$k_2]['MONEY_1'],2).' </div></td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" align="right" style=\'mso-number-format:"\#\,\#\#0\.00"\'><div align="right" nowrap></div>'.number_format_chk($arr_data_report4_2[$k_1][$k_2]['MONEY_2_1'],2).'</td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" align="right" style=\'mso-number-format:"\#\,\#\#0\.00"\'><div align="right" nowrap></div>'.number_format_chk($arr_data_report4_2[$k_1][$k_2]['MONEY_2_2'],2).'</td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" align="right" style=\'mso-number-format:"\#\,\#\#0\.00"\'><div align="right" nowrap></div>'.number_format_chk($arr_data_report4_2[$k_1][$k_2]['MONEY_2'],2).'</td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" align="right" style=\'mso-number-format:"\#\,\#\#0\.00"\'><div align="right" nowrap></div>'.number_format_chk(($arr_data_report4_2[$k_1][$k_2]['MONEY_1'])-($arr_data_report4_2[$k_1][$k_2]['MONEY_2']),2).'</td>';
                        // 		$html .= '<td rowspan="'.count($arr_data_report4[$k_1][$k_2]).'" style="border-bottom:none;" ><div align="left"></div></td>';
                        // 	}else{
                        // 		// $html .= '<td class="freez-1"  style="border-top:none;border-bottom:none;"><div align="left" style="text-indent:40px;" >  </div></td>';
                        // 		// $html .= '<td class="freez-2"  style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap></div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap></div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap></div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap>  </div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap>  </div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap>  </div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap></div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" align="right"><div align="right" nowrap></div></td>';
                        // 		// $html .= '<td style="border-top:none;border-bottom:none;" ><div align="left"></div></td>';
                        // 	}


                        if (count($arr_data_report2) > 0) {
                            foreach ($arr_data_report2 as $k_4 => $v_4) { //##  Start
                                foreach ($arr_data_report2[$k_4] as $k_4_1 => $v_4_1) {
                                    // $html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:'.$arr_data_report5[$k_1][$k_2][$k_4][$k_4_1][$k_3]['COLOR'].';\'><div align="right">'.(number_format_chk($arr_data_report5[$k_1][$k_2][$k_4][$k_4_1][$k_3]['MONEY_3'],2)).'</div></td>';
                                    // $html .= '<td style=\'mso-number-format:"\#\,\#\#0\.00";background-color:'.$arr_data_report5[$k_1][$k_2][$k_4][$k_4_1][$k_3]['COLOR'].';\'><div align="right">'.(number_format_chk($arr_data_report5[$k_1][$k_2][$k_4][$k_4_1][$k_3]['MONEY_4'],2)).'</div></td>';
                                    // $html .= '<td style=";background-color:'.$arr_data_report5[$k_1][$k_2][$k_4][$k_4_1][$k_3]['COLOR'].';"><div align="center">'.text($arr_data_report5[$k_1][$k_2][$k_4][$k_4_1][$k_3]['UNIT_NAME']).'</div></td>';
                                }
                            } // ## End
                        }

                        $html .= '</tr >';
                        $ddd++;
                    }

                    $i_proj++;
                }

                $i_proj0++;
            }
        } //##  End แนวทาง



        //if($num_rows>0){
        //	$i=1;
        //while($rec_main = $db->db_fetch_array($query_main)){

        /* $html .= '<tr bgcolor="#FFFFFF">';
										$html .= '<td align="center">'.$i.'</td>';
										$html .= '<td align="left">'.text($rec_main['COLUMN_1']).'</td>';
										$html .= '<td align="center" style=\'mso-number-format:"\#\,\#\#0\.00"\'>'.number_format_chk($rec_main['COLUMN_2'],2).'</td>';
									$html .= '</tr>'; */

        //$i++;
        //}
        //}else{ 

        //$html .= '<tr>';
        //		$html .= '<td colspan="3" align="center">ไม่พบข้อมูล</td>';
        //$html .= '</tr>';

        //} 

        $html .= '</tbody>';
        $html .= '</table>';
    }
    ?>
    <!--div class="row"-->
    <!--div class="table-responsive"-->
    <?php
    // $obj = fopen($path_cache.$FILE_NAME.".txt", 'w');
    // fwrite($obj, $html);
    // fclose($obj);		
    echo $html;

    ?>
</body>

</html>