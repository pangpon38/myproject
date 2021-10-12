<?php
session_start();
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '7';
if (!in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
    $ymchk = (date("Y") + 543) . date("m") + 1;
    $ymchk_js = (date("Y") + 543) . sprintf("%'02d", date("m") + 1);
} else {
    $ymchk = (date("Y") + 543) . date("m");
    $ymchk_js = (date("Y") + 543) . sprintf("%'02d", date("m"));
}

$DASHBOARD_YEAR = $_SESSION['year_round'];

//ตรวจสอบเดือนล่าสุดของปีงบประมาณ
if (date("m") > 9) {
    $YEAR_CHECK = (date("Y") + 543) + 1;
} else {
    $YEAR_CHECK = (date("Y") + 543);
}

if ($DASHBOARD_YEAR < $YEAR_CHECK) {
    $YEAR2 = $DASHBOARD_YEAR;
    $MONTH2 = 9;
} elseif (date("m") == 1) {
    $YEAR2 = (date("Y") + 543) - 1;
    $MONTH2 = 12;
} else {
    $YEAR2 = (date("Y") + 543);
    $MONTH2 = date("m") - 1;
}
$YEAR_inBDG = $YEAR2 . sprintf('%02d', $MONTH2);

if (date("m") == 1) {
    $YEAR_NOW = (date("Y") + 543) - 1;
    $MONTH_NOW = 12;
} else {
    $YEAR_NOW = (date("Y") + 543);
    $MONTH_NOW = date("m") - 1;
}
if ($_POST['PRJP_ID'] != '') {
    $PRJP_ID = $_POST['PRJP_ID'];
} else {
    $PRJP_ID = $PRJP_ID;
}
$month = array("10" => "ต.ค.", "11" => "พ.ย.", "12" => "ธ.ค.", "1" => "ม.ค.", "2" => "ก.พ.", "3" => "มี.ค.", "4" => "เม.ย.", "5" => "พ.ค.", "6" => "มิ.ย.", "7" => "ก.ค.", "8" => "ส.ค.", "9" => "ก.ย.");
$month_full = array("1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");
$month_full_bdg = array("10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม", "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน");
$sql_head = "SELECT PRJP_CODE,PRJP_NAME,EDATE_PRJP,SDATE_PRJP,PROLONG_STATUS,MONEY_BDG,PRJP_CON_ID,PRJP_SET_STIME,PRJP_SET_ETIME,PRJP_SET_TIME_CHK, COST_TYPE
	FROM prjp_project 
	left join prjp_set_time on prjp_set_time.PRJP_ID = prjp_project.PRJP_ID
		AND '" . date('Y-m-d') . "' BETWEEN prjp_set_time.PRJP_SET_STIME AND prjp_set_time.PRJP_SET_ETIME
	WHERE prjp_project.PRJP_ID = '" . $PRJP_ID . "' 
	order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO ";

$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);
if ($rec_head['PROLONG_STATUS'] == 1) {
    $shead = "(ขอขยายเวลา)";
} else {
    $shead = "";
}
////////// เช็คสถานะการบันทึกย้อนหลัง ///////////////////
if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
    $ds_set = substr($rec_head['PRJP_SET_STIME'], 8, 2) * 1;
    $ms_set = substr($rec_head['PRJP_SET_STIME'], 5, 2) * 1;
    $ys_set = substr($rec_head['PRJP_SET_STIME'], 0, 4) + 543;
    $chk_set_start = $ys_set . sprintf("%'02d", $ms_set) . sprintf("%'02d", $ds_set);

    $de_set = sprintf("%'02d", substr($rec_head['PRJP_SET_ETIME'], 8, 2) * 1);
    $me_set = substr($rec_head['PRJP_SET_ETIME'], 5, 2) * 1;
    $ye_set = substr($rec_head['PRJP_SET_ETIME'], 0, 4) + 543;
    $chk_set = $ye_set . sprintf("%'02d", $me_set);
    $chk_set_end = $ye_set . sprintf("%'02d", $me_set) . sprintf("%'02d", $de_set);
}
///////////////////////////////////////
$ms = substr($rec_head['SDATE_PRJP'], 5, 2) * 1;
$ys = substr($rec_head['SDATE_PRJP'], 0, 4) + 543;
$me = substr($rec_head['EDATE_PRJP'], 5, 2) * 1;
$ye = substr($rec_head['EDATE_PRJP'], 0, 4) + 543;

$yse = ((($ye - $ys) * 12)) - (12 - $me);
$row_col = (((12 - $ms) + 1) + ((($ye - $ys) - 1) * 12) + (12 - (12 - $me)));
$row_wh = ceil($row_col / 12);
$row_round = (($row_wh * 12) - $row_col);
$fbs = $ys . sprintf("%'02d", $ms);
$fbe = $ye . sprintf("%'02d", $me);

if ($row_round == 0) {
    $row_round = 12;
} elseif ($row_round < 12) {
    $row_round = (12 + $row_round);
} else {
    $row_round = $row_round;
}
$row_m = $me + $row_round;

$row_mn = ($row_m - 12);
$ye_mn = ($ye + $row_wh);
if ($row_mn < 10) {
    $fbe_r = $ye_mn . "0" . $row_mn;
} else {
    $fbe_r = $ye_mn . $row_mn;
}
$k = $fbs;
while ($k <= $fbe_r) {
    $rr[] = $k;
    $smr = substr($k, 4, 2);
    $syr = substr($k, 0, 4);
    if ($smr == '12') {
        $k = ($syr + 1) . "01";
    } else {
        $k++;
    }
}

$x = $fbs;
while ($x <= $fbe) {
    $m[] = $x;
    $sm = substr($x, 4, 2);
    $sy = substr($x, 0, 4);
    if ($sm == '12') {
        $x = ($sy + 1) . "01";
    } else {
        $x++;
    }
}
$c_arr = count($m);
$sql = "SELECT 	a.PRJP_PARENT_ID,a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.SDATE_PRJP,a.EDATE_PRJP,a.MONEY_BDG,a.ORDER_NO, a.COST_TYPE,
				(select sum(BDG_VALUE) from prjp_report_money where prjp_report_money.PRJP_ID = a.PRJP_ID)as s_val
		  		FROM prjp_project a 
				WHERE 1=1 AND a.PRJP_LEVEL = '2' AND a.PRJP_PARENT_ID = '" . $PRJP_ID . "' 
				order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
				";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);

$sql_binding = "select * from prjp_binding where PRJP_ID = '" . $PRJP_ID . "' and YEAR = '" . (date("Y") + 543) . "' and MONTH = '" . (date("m") * 1) . "' ";
$query_binding = $db->query($sql_binding);
$rec_binding = $db->db_fetch_array($query_binding);

$sql_r_value_now = " SELECT sum(prjp_report_money.BDG_VALUE)as sumnow
					FROM prjp_report_money
					JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_money.PRJP_ID
					WHERE prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "'
					AND (YEAR + RIGHT ('0' + CAST(MONTH AS VARCHAR), 2) <= '" . date("Y") . sprintf("%'02d", date("m")) . "')";
$query_r_value_now = $db->query($sql_r_value_now);
$rec_r_value_now = $db->db_fetch_array($query_r_value_now);

if ($rec_head['PRJP_CON_ID'] != '') {
    ///////////////////////////////////////////// ปีเก่า ////////////////////////////////////////////
    $sql_dataold = "SELECT EDATE_PRJP,SDATE_PRJP,MONEY_BDG FROM prjp_project WHERE PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . " '";
    $query_dataold = $db->query($sql_dataold);
    $rec_dataold = $db->db_fetch_array($query_dataold);
    $mso = substr($rec_dataold['SDATE_PRJP'], 5, 2) * 1;
    $yso = substr($rec_dataold['SDATE_PRJP'], 0, 4) + 543;
    $meo = substr($rec_dataold['EDATE_PRJP'], 5, 2) * 1;
    $yeo = substr($rec_dataold['EDATE_PRJP'], 0, 4) + 543;

    $yseo = ((($yeo - $yso) * 12)) - (12 - $meo);
    $row_colo = (((12 - $mso) + 1) + ((($yeo - $yso) - 1) * 12) + (12 - (12 - $meo)));
    $row_who = ceil($row_colo / 12);
    $row_roundo = (($row_who * 12) - $row_colo);
    $fbso = $yso . sprintf("%'02d", $mso);
    $fbeo = $yeo . sprintf("%'02d", $meo);

    if ($row_roundo == 0) {
        $row_roundo = 12;
    } elseif ($row_roundo < 12) {
        $row_roundo = (12 + $row_roundo);
    } else {
        $row_roundo = $row_roundo;
    }
    $row_mo = $meo + $row_roundo;

    $row_mno = ($row_mo - 12);
    $ye_mno = ($yeo + $row_who);
    if ($row_mno < 10) {
        $fbe_ro = $ye_mno . "0" . $row_mno;
    } else {
        $fbe_ro = $ye_mno . $row_mno;
    }
    $ko = $fbso;
    while ($ko <= $fbe_ro) {
        $rro[] = $ko;
        $smro = substr($ko, 4, 2);
        $syro = substr($ko, 0, 4);
        if ($smro == '12') {
            $ko = ($syro + 1) . "01";
        } else {
            $ko++;
        }
    }

    $xo = $fbso;
    while ($xo <= $fbeo) {
        $mo[] = $xo;
        $smo = substr($xo, 4, 2);
        $syo = substr($xo, 0, 4);
        if ($smo == '12') {
            $xo = ($syo + 1) . "01";
        } else {
            $xo++;
        }
    }
    $c_arro = count($mo);
    $sqlo = "SELECT 	a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.SDATE_PRJP,a.EDATE_PRJP,a.MONEY_BDG,a.ORDER_NO,
				(select sum(BDG_VALUE) from prjp_report_money where prjp_report_money.PRJP_ID = a.PRJP_ID)as s_val
		  		FROM prjp_project a 
				WHERE 1=1 AND a.PRJP_LEVEL = '2' AND a.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "' 
				order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
				";
    $queryo = $db->query($sqlo);
    $num_rowso = $db->db_num_rows($queryo);
    //////////////////////////////////////////////////////////////////////////////////////////////

}
?>
<!DOCTYPE html>
<html>

<head>
    <?php include($path . "include/inc_main_top.php"); ?>
    <script src="js/disp_project_send_act_money.js?<?php echo rand(); ?>"></script>
    <script type="text/javascript">
        function chk_old(id) {
            if (id == 0) {
                $("#hide_old").val(1);
            } else if (id == 1) {
                $("#hide_old").val(0);
            } else if (id == 2) {
                $("#hide_old").val(0);
            }
            id = $("#hide_old").val();
            show_old(id);
        }

        function show_old(id) {
            if (id == 1) {
                $(".data_old").show();
                $(".data_old2").show();
                $("#tab_old").show();
                //$("#TB_BR").html("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
            } else {
                $(".data_old").hide();
                $(".data_old2").hide();
                $("#tab_old").hide();
                //$("#TB_BR").html("");
            }
            stableo(id);
        }

        function stable(id) {
            $(".htb").hide();
            $(".htbv").hide();
            $("#tb_" + id).show();
            $("#tbv_" + id).show();
        }

        function stableo(id) {
            $(".htbo").hide();
            $(".htbvo").hide();
            $("#tbo_" + id).show();
            $("#tbvo_" + id).show();
        }
    </script>
</head>

<body style="display:inline-block">
    <div class="container-full">
        <div><?php include($path . "include/header.php"); ?></div>
        <div class="col-xs-12 col-sm-12">
            <ol class="breadcrumb">
                <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
                <li><a href="disp_send_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>">รายละเอียด</a></li>
                <li class="active">ผลการใช้จ่ายเงินของกิจกรรม</li>
            </ol>
        </div>

        <div class="col-xs-12 col-sm-12 page-prjp-money">
            <div class="groupdata">
                <form id="frm-search" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="year_round" name="year_round" value="<?php echo $_SESSION['year_round']; ?>">
                    <input type="hidden" id="code_user" name="code_user" value="<?php echo $_SESSION['sys_dept_id']; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <input type="hidden" id="YMAX" name="YMAX" value="<?php echo $ye; ?>">
                    <input type="hidden" id="YMIN" name="YMIN" value="<?php echo $ys; ?>">
                    <input type="hidden" id="MONEY_BDG" name="MONEY_BDG" value="<?php echo $rec_head['MONEY_BDG']; ?>">
                    <input type="hidden" id="MONEY_BDG_OLD" name="MONEY_BDG_OLD" value="<?php echo $rec_dataold['MONEY_BDG']; ?>">
                    <input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />

                    <!-- สถานะผลเทียบแผน -->
                    <?php
                    $sql_status = "SELECT * FROM config_rate_status WHERE YEAR_BDG = '" . $_SESSION['year_round'] . "' 
                        ORDER BY rate_status_percent DESC";
                    $query_status =  $db->query($sql_status);
                    $i = 1;
                    while ($rec_status = $db->db_fetch_array($query_status)) {
                    ?>
                        <input type="hidden" id="status_per_<?php echo $i; ?>" value="<?php echo $rec_status['rate_status_percent'] ?>">
                        <input type="hidden" id="status_name_<?php echo $i; ?>" value="<?php echo text($rec_status['rate_status_name']) ?>">
                        <input type="hidden" id="status_color_<?php echo $i; ?>" value="<?php echo $rec_status['rate_status_color'] ?>">

                    <?php $i++;
                    } ?>

                    <!-- Modal -->

                    <div id="myModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <select id="S_TYPE" name="S_TYPE" class="selectbox form-control" placeholder="ประเภทรายงาน" style="width:150px;">

                                                <option value="1">WORD</option>
                                                <option value="2">EXCEL</option>
                                                <?php /*?><option value="2">PDF</option><?php */ ?>

                                            </select>
                                        </div>
                                    </div>
                                    <?php /* ?>
						<div class="row">
							<div class="col-md-12">
								<select id="S_MONTH" name="S_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;" >
									<?php 
										foreach($month_full_bdg as $key_mfull => $val_mfull){
									?>
										<option value="<?php echo $key_mfull; ?>"><?php echo $val_mfull; ?></option>
									<?php 
										}
									?>
								</select>
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<select id="S_MONTH" name="S_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;" >
									<?php
										foreach($m as $key => $value){
									?>
										<option value="<?php echo $value; ?>"><?php echo $month_full[(substr($value,4,2)*1)]."  ".substr($value,0,4); ?></option>
									<?php						
										}
									?>
								</select>
							</div>
						</div>
						<?php */ ?>
                                    <div style="display:none;"><label>จาก</label></div>
                                    <div class="row">
                                        <div class="col-md-12" style="display:none;">
                                            <select id="S_MONTH" name="S_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;">
                                                <?php
                                                foreach ($m as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value; ?>"><?php echo $month_full[(substr($value, 4, 2) * 1)] . "  " . substr($value, 0, 4); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div><label>ถึง</label></div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select id="E_MONTH" name="E_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;">
                                                <?php
                                                foreach ($m as $key => $value) {
                                                ?>
                                                    <option value="<?php echo $value; ?>"><?php echo $month_full[(substr($value, 4, 2) * 1)] . "  " . substr($value, 0, 4); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4" style="text-align:left;">
                                            <button type="button" class="btn btn-default" data-dismiss="modal" onClick="submitPrint();">พิมพ์</button>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer"></div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12"><?php include("tab_menu2_r.php"); ?></div><br>
                        <?php
                        if ($_SESSION["sys_group_id"] == '5' || $_SESSION["sys_group_id"] == '9') {
                        ?>
                            <div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php"); ?></div><br><br>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12"> </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 font-blue" align="center">
                            <strong><?php echo $rec_head['PRJP_CODE'] . " " . text($rec_head['PRJP_NAME']) ?></strong>
                        </div>
                    </div>
                    <?php
                    if ($_SESSION['sys_status_print'] == '1') {
                        $print_form = "<a class=\"btn btn-info\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form3('" . $PRJP_ID . "');\">" . $img_print . "  พิมพ์ สสว.200/3</a> ";
                    }
                    ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading row" style="">
                                    <div class="pull-left" style="">ผลการใช้จ่ายเงินของกิจกรรม</div>
                                    <div class="pull-right" style="">สสว.200/3</div>
                                </div>
                                <div class="panel-body epm-gradient">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12"><?php echo $print_form; ?></div>
                                    </div>

                                    <?php if ($rec_head['PRJP_CON_ID'] != '') { ?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12" align="center">
                                                <input type="hidden" id="hide_old" name="hide_old" value="0">
                                                <a href="javascript:void(0)" onClick="chk_old(hide_old.value);"><?php echo $img_save; ?> ผลการใช้จ่ายเงิน</a>
                                            </div>

                                            <div class="row" id="tab_old" style="display:none;">
                                                <table boder='1' align='left'>
                                                    <tr>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <==== <?php
                                                                    for ($row_tbo = 1; $row_tbo <= $row_who; $row_tbo++) {
                                                                    ?> <a href="javascript:void(0);" onClick="stableo('<?php echo $row_tbo; ?>')">&nbsp;<font size='5'><?php echo $row_tbo; ?></font>&nbsp;</a>
                                                            <?php
                                                                    }
                                                            ?>
                                                            ====>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            <?php
                                            $mstarto = 0;
                                            $mendo = 11;
                                            $rowtbo = 0;
                                            for ($rowo = 1; $rowo <= $row_who; $rowo++) {
                                            ?>
                                                <div id="tbo_<?php echo $rowo; ?>" class=" col-xs-12 col-sm-12 htbo data_old" style="display:none;">
                                                    <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                                        <thead>
                                                            <tr class="bgHead">
                                                                <th width="40px" rowspan="2">
                                                                    <div align="center"><strong>ลำดับ</strong></div>
                                                                </th>
                                                                <th width="230px" rowspan="2">
                                                                    <div align="center"><strong>ชื่อกิจกรรม</strong></div>
                                                                </th>
                                                                <th width="85px" rowspan="2">
                                                                    <div align="center"><strong>เงินที่วางแผน</strong></div>
                                                                </th>
                                                                <th width="95px" rowspan="2">
                                                                    <div align="center"><strong>ยอดสะสม</strong></div>
                                                                </th>
                                                                <th rowspan="2">
                                                                    <div align="center"><strong></strong></div>
                                                                </th>
                                                                <th width="6%" colspan="12">
                                                                    <div align="center"><strong>ผลการใช้จ่ายเงินของกิจกรรม ปี <?php echo $_SESSION['year_round']; ?></strong></div>
                                                                </th>
                                                            </tr>
                                                            <tr class="bgHead">
                                                                <?php
                                                                foreach ($rro as $key => $val) {
                                                                    if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                        $smho = substr($val, 4, 2);
                                                                        $syho = substr($val, 2, 2);
                                                                ?>
                                                                        <th width="6%">
                                                                            <div align="center"><strong><?php echo $month[$smho * 1] . $syho; ?></strong></div>
                                                                        </th>
                                                                <?php }
                                                                } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $sql_value_rpo = "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR,
									((prjp_plan_money.BDG_VALUE/prjp_project.MONEY_BDG)*100)as per_val,prjp_project.MONEY_BDG
									FROM prjp_plan_money 
									JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
									WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "'
									";
                                                            $query_value_rpo = $db->query($sql_value_rpo);
                                                            $sum_pro = array();
                                                            while ($rec_value_rpo = $db->db_fetch_array($query_value_rpo)) {
                                                                $arr_pro[$rec_value_rpo['PRJP_ID']][$rec_value_rpo['YEAR']][$rec_value_rpo['MONTH']] = $rec_value_rpo['BDG_VALUE'];



                                                                $sql_value_rp_childo = "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR,
														((prjp_plan_money.BDG_VALUE/prjp_project.MONEY_BDG)*100)as per_val,prjp_project.MONEY_BDG
														FROM prjp_plan_money 
														JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
														WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_value_rpo['PRJP_ID'] . "'
														";
                                                                $query_value_rp_childo = $db->query($sql_value_rp_childo);
                                                                while ($rec_value_rp_childo = $db->db_fetch_array($query_value_rp_childo)) {
                                                                    $arr_pro[$rec_value_rp_childo['PRJP_ID']][$rec_value_rp_childo['YEAR']][$rec_value_rp_childo['MONTH']] = $rec_value_rp_childo['BDG_VALUE'];
                                                                }




                                                                //$arr_mp[$rec_value_rp['PRJP_ID'][$rec_value_rp['YEAR'][$rec_value_rp['MONTH']]+=$rec_value_rp['BDG_VALUE'];
                                                                $sum_pro[$rec_value_rpo['YEAR']][$rec_value_rpo['MONTH']] += $rec_value_rpo['BDG_VALUE'];
                                                            }
                                                            $sql_r_value_rpo = "select prjp_report_money.BDG_VALUE,prjp_report_money.MONTH,prjp_project.PRJP_ID,prjp_report_money.YEAR 
															FROM prjp_report_money 
															JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_money.PRJP_ID
															WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "'";
                                                            $query_r_value_rpo = $db->query($sql_r_value_rpo);
                                                            while ($rec_r_value_rpo = $db->db_fetch_array($query_r_value_rpo)) {
                                                                $arr_rro[$rec_r_value_rpo['PRJP_ID']][$rec_r_value_rpo['YEAR']][$rec_r_value_rpo['MONTH']] = $rec_r_value_rpo['BDG_VALUE'];

                                                                $sql_r_value_rp_childo = "select prjp_report_money.BDG_VALUE,prjp_report_money.MONTH,prjp_project.PRJP_ID,prjp_report_money.YEAR 
															FROM prjp_report_money 
															JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_money.PRJP_ID
															WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_r_value_rpo['PRJP_ID'] . "' ";
                                                                $query_r_value_rp_childo = $db->query($sql_r_value_rp_childo);
                                                                while ($rec_r_value_rp_childo = $db->db_fetch_array($query_r_value_rp_childo)) {
                                                                    $arr_rro[$rec_r_value_rp_childo['PRJP_ID']][$rec_r_value_rp_childo['YEAR']][$rec_r_value_rp_childo['MONTH']] = $rec_r_value_rp_childo['BDG_VALUE'];
                                                                }


                                                                $sum_rro[$rec_r_value_rpo['YEAR']][$rec_r_value_rpo['MONTH']] += $rec_r_value_rpo['BDG_VALUE'];
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td colspan="4" rowspan="3" align="right">รวมแผนการใช้จ่ายเงิน (%)</td>
                                                                <td align="center" nowrap>แผนสะสม</td>
                                                                <?php
                                                                if ($rowo == 1) {
                                                                    $val_sum_pr_mo = 0;
                                                                }
                                                                foreach ($rro as $key => $val) {
                                                                    if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                        $m_do = substr($val, 4, 2) * 1;
                                                                        $y_do = substr($val, 0, 4);
                                                                        $val_sum_pr_mo += $sum_pro[$y_do][$m_do];

                                                                ?>
                                                                        <td align="right">
                                                                            <?php
                                                                            if ($mo[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                                                if ((@($val_sum_pr_mo / $rec_head['MONEY_BDG'])) * 100 > 100) {
                                                                                    echo "100.00";
                                                                                } else {
                                                                                    echo @number_format(($val_sum_pr_mo / $rec_head['MONEY_BDG']) * 100, 2);
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>

                                                            <tr>
                                                                <td align="center">ผลสะสม</td>
                                                                <?php
                                                                if ($rowo == 1) {
                                                                    $val_sum_rr_mo = 0;
                                                                }
                                                                foreach ($rro as $key => $val) {
                                                                    if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                        $m_do = substr($val, 4, 2) * 1;
                                                                        $y_do = substr($val, 0, 4);
                                                                        $val_sum_rr_mo += $sum_rro[$y_do][$m_do];
                                                                ?>
                                                                        <td align="right" id="per_sum_old_<?php echo $key + 1; ?>">
                                                                            <?php //if(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100 > 100){echo "100.00";}else{ echo @number_format(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100,2);}
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>

                                                            <tr>
                                                                <td colspan="4" rowspan="2" align="right">รวมแผนการใช้จ่ายเงิน</td>
                                                                <td align="center" nowrap>แผนสะสม</td>
                                                                <?php
                                                                if ($rowo == 1) {
                                                                    $val_sum_pr_mvo = 0;
                                                                }
                                                                foreach ($rro as $key => $val) {
                                                                    if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                        $m_do = substr($val, 4, 2) * 1;
                                                                        $y_do = substr($val, 0, 4);
                                                                        $val_sum_pr_mvo += $sum_pro[$y_do][$m_do];

                                                                ?>
                                                                        <td align="right">
                                                                            <?php
                                                                            if ($mo[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                                                echo @number_format($val_sum_pr_mvo, 2);
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>
                                                            <tr>
                                                                <td align="center">ผลสะสม</td>
                                                                <?php
                                                                if ($rowo == 1) {
                                                                    $val_sum_rr_mo = 0;
                                                                }
                                                                foreach ($rro as $key => $val) {
                                                                    if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                        $m_do = substr($val, 4, 2) * 1;
                                                                        $y_do = substr($val, 0, 4);
                                                                        $val_sum_rr_mo += $sum_rro[$y_do][$m_do];
                                                                ?>
                                                                        <td align="right" id="val_sum_old_<?php echo $key + 1; ?>">
                                                                            <?php //if(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100 > 100){echo "100.00";}else{ echo @number_format(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100,2);}
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>
                                                        </tbody>

                                                        <?php
                                                        if ($num_rowso > 0) {
                                                            $ii = 1;
                                                            $queryo = $db->query($sqlo);
                                                            while ($reco = $db->db_fetch_array($queryo)) {
                                                                $msao = 10;
                                                                $meao = substr($reco['EDATE_PRJP'], 5, 2);
                                                                $yeao = substr($reco['EDATE_PRJP'], 0, 4) + 543;
                                                                $ysao = substr($reco['SDATE_PRJP'], 0, 4) + 543;

                                                                $year_mso = $ysao . $msao;
                                                                $year_meo = $yeao . $meao;
                                                                $row_colao = (((12 - $msao) + 1) + ((($yeao - $ysao) - 1) * 12) + (12 - (12 - $meao)));

                                                                $sqlChildCount = "SELECT
														count(prjp_id) totalChild
													FROM
														prjp_project
													WHERE
														PRJP_PARENT_ID = '" . $reco['PRJP_ID'] . "'";
                                                                $queryChildCount = $db->query($sqlChildCount);
                                                                $recTotalChild = $db->db_fetch_array($queryChildCount);
                                                                $totalChild = $recTotalChild["totalChild"];

                                                        ?>
                                                                <tr bgcolor="#FFFFFF">
                                                                    <td align="center" rowspan="4" width="50px"><?php echo $reco['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $reco['PRJP_ID']; ?>"></td>
                                                                    <td rowspan="4" align="left" width="215px"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($reco['PRJP_NAME']); ?></textarea></td>
                                                                    <td rowspan="4" align="right" width="90px"><?php echo number_format($reco['MONEY_BDG'], 2); ?></td>
                                                                    <td rowspan="4" align="center" width="85px"><?php echo number_format($reco['s_val'], 2); ?></td>
                                                                    <td align="center" width="40px" style="background:#bebebe" nowrap>แผนสะสม</td>
                                                                    <?php
                                                                    $ts[$reco['PRJP_ID']] = 0;
                                                                    foreach ($rro as $key => $val) {
                                                                        if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                            $m_do = substr($val, 4, 2) * 1;
                                                                            $y_do = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                    ?>
                                                                            <td align="right" style="background:#bebebe;width:102px">
                                                                                <?php if ($mo[$key] == '') {
                                                                                    echo "-";
                                                                                } else {
                                                                                    $sum_all[$reco['PRJP_ID']] = 0;
                                                                                    $i = $fbso;
                                                                                    while ($i < $val) {
                                                                                        $smo = substr($i, 4, 2);
                                                                                        $syo = substr($i, 0, 4);
                                                                                        if ($smo * 1 == '12') {
                                                                                            $i = ($syo + 1) . "01";
                                                                                        } else {
                                                                                            $i++;
                                                                                        }
                                                                                        $sum_all[$rec['PRJP_ID']] += $arr_pro[$reco['PRJP_ID']][$syo][$smo * 1];
                                                                                    }
                                                                                    echo number_format($sum_all[$reco['PRJP_ID']] + $arr_pro[$reco['PRJP_ID']][$y_do][$m_do], 2);
                                                                                } ?>
                                                                            </td>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr bgcolor="#FFFFFF">
                                                                    <td align="center" style="background:#afeeee">แผน</td>
                                                                    <?php
                                                                    foreach ($rro as $key => $val) {
                                                                        if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                            $m_do = substr($val, 4, 2) * 1;
                                                                            $y_do = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                    ?>
                                                                            <td align="right" style="background:#afeeee">
                                                                                <?php if ($mo[$key] == '') {
                                                                                    echo "-";
                                                                                } else {
                                                                                ?>
                                                                                <?php echo @number_format($arr_pro[$reco['PRJP_ID']][$y_do][$m_do], 2);
                                                                                } ?></td>
                                                                            <?php     //}else{ 
                                                                            ?>
                                                                            <?php /*?><td align="center">-</td>	<?php */ ?>
                                                                    <?php
                                                                        } //if 
                                                                    } //foreach
                                                                    ?>
                                                                </tr>
                                                                <tr bgcolor="#FFFFFF">
                                                                    <td align="center" style="background:#bebebe">ผลสะสม</td>
                                                                    <?php

                                                                    $i = 1;
                                                                    foreach ($rro as $key => $val) {
                                                                        if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                            $m_do = substr($val, 4, 2) * 1;
                                                                            $y_do = substr($val, 0, 4);
                                                                    ?>
                                                                            <td align="right" id="psum_old_<?php echo $reco['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe"></td>
                                                                    <?php
                                                                        }
                                                                        $i++;
                                                                    }
                                                                    ?>
                                                                </tr>
                                                                <tr bgcolor="#FFFFFF">
                                                                    <td align="center" style="background:#afeeee">ผล</td>
                                                                    <?php
                                                                    $k = 1;
                                                                    foreach ($rro as $key => $val) {
                                                                        if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                            $m_do = substr($val, 4, 2) * 1;
                                                                            $y_do = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                            $mcko = $y_do . sprintf("%'02d", $m_do);
                                                                            //if($mck>=$ymchk){
                                                                            //$distxt = "disabled";
                                                                            //$bgdis = "background:#9F9;";
                                                                            //}else{
                                                                            //$distxt = "";	
                                                                            //$bgdis = "";
                                                                            //}
                                                                            //if($mck==$ymchk){
                                                                            //if(date("d") >=1 && date("d")<11){
                                                                            //$distxt = "";	
                                                                            //$bgdis = "";	
                                                                            //}else{
                                                                            //$distxt = "disabled";
                                                                            //$bgdis = "background:#9F9;";
                                                                            //}
                                                                            //}else{
                                                                            //$distxt = "disabled";
                                                                            //$bgdis = "background:#9F9;";
                                                                            //}
                                                                    ?>
                                                                            <td align="right" style="background:#afeeee">
                                                                                <?php if ($mo[$key] == '') {
                                                                                    echo "-";
                                                                                } else {
                                                                                ?>
                                                                                    <input type="hidden" id="VCHK_YEAR_OLD_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR_OLD[]" value="<?php echo $mcko; ?>">
                                                                                    <input name="YEAR_OLD[<?php echo $reco['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="YEAR_OLD_<?php echo $key; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_do); ?>">



                                                                                    <?php
                                                                                    $input_type = "text";
                                                                                    $readonly = "";
                                                                                    if ($totalChild > 0) {
                                                                                        $readonly = "readonly";
                                                                                        //$input_type = "hidden";
                                                                                    } else {
                                                                                    }
                                                                                    ?>

                                                                                    <input <?php echo $readonly; ?> prjp-parent-id="<?php echo $reco["PRJP_PARENT_ID"]; ?>" prjp-id="<?php echo $reco['PRJP_ID']; ?>" month="<?php echo $m_do; ?>" year="<?php echo $y_do; ?>" name="BDG_VALUE_OLD[<?php echo $reco['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="BDG_VALUE_OLD_<?php echo $reco['PRJP_ID']; ?>_<?php echo $k; ?>" type="hidden" size="5" class="form-control number_format PVO_<?php echo $ii; ?>_<?php echo $k; ?>" value="<?php echo number_format($arr_rro[$reco['PRJP_ID']][$y_do][$m_do], 2); ?>" onBlur="Chk_sval_old('<?php echo $c_arr; ?>',<?php echo $reco['PRJP_ID']; ?>);
						   Sum_result_old('<?php echo $c_arro; ?>','<?php echo $num_rowso; ?>','<?php echo $ymchk_js; ?>');
						   Sum_result_old_v('<?php echo $c_arro; ?>','<?php echo $num_rowso; ?>','<?php echo $ymchk_js; ?>');
						   NumberFormat(this,2);" style="text-align:right;<?php //echo $bgdis; 
                                                                            ?>" <?php //echo $distxt; 
                                                                                ?>>
                                                                                    <?php echo number_format($arr_rro[$reco['PRJP_ID']][$y_do][$m_do], 2); ?>
                                                                                <?php } ?>
                                                                            </td>
                                                                    <?php } //if
                                                                        $k++;
                                                                    } //foreach 
                                                                    ?>
                                                                </tr>
                                                                <script>
                                                                    Chk_sval_old('<?php echo $c_arro; ?>', <?php echo $reco['PRJP_ID']; ?>);
                                                                    Sum_result_old('<?php echo $c_arro; ?>', '<?php echo $num_rowso; ?>', '<?php echo $ymchk_js; ?>');
                                                                    Sum_result_old_v('<?php echo $c_arro; ?>', '<?php echo $num_rowso; ?>', '<?php echo $ymchk_js; ?>');
                                                                </script>
                                                                <?php

                                                                $sqlChild = "SELECT 	a.PRJP_PARENT_ID,a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.SDATE_PRJP,a.EDATE_PRJP,a.MONEY_BDG,a.ORDER_NO,
								(select sum(BDG_VALUE) from prjp_report_money where prjp_report_money.PRJP_ID = a.PRJP_ID)as s_val
								FROM prjp_project a 
								WHERE 1=1 AND a.PRJP_LEVEL = '3' AND a.PRJP_PARENT_ID = '" . $reco['PRJP_ID'] . "' 
								order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
								";

                                                                $ii = 1;
                                                                $queryChild = $db->query($sqlChild);
                                                                while ($recChild = $db->db_fetch_array($queryChild)) {
                                                                    $msa = 10;
                                                                    $mea = substr($rec['EDATE_PRJP'], 5, 2);
                                                                    $yea = substr($rec['EDATE_PRJP'], 0, 4) + 543;
                                                                    $ysa = substr($rec['SDATE_PRJP'], 0, 4) + 543;

                                                                    $year_ms = $ysa . $msa;
                                                                    $year_me = $yea . $mea;
                                                                    $row_cola = (((12 - $msa) + 1) + ((($yea - $ysa) - 1) * 12) + (12 - (12 - $mea)));


                                                                ?>
                                                                    <tr bgcolor="#FFFFFF">
                                                                        <td align="center" rowspan="4" width="50px"><?php echo $recChild['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $recChild['PRJP_ID']; ?>"></td>
                                                                        <td rowspan="4" align="left" width="215px"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($recChild['PRJP_NAME']); ?></textarea></td>
                                                                        <td rowspan="4" align="right" width="90px"><?php echo number_format($recChild['MONEY_BDG'], 2); ?></td>
                                                                        <td rowspan="4" align="center" width="85px"><?php echo number_format($recChild['s_val'], 2); ?></td>
                                                                        <td align="center" width="40px" style="background:#bebebe" nowrap>แผนสะสม</td>
                                                                        <?php
                                                                        $ts[$recChild['PRJP_ID']] = 0;
                                                                        foreach ($rro as $key => $val) {
                                                                            if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                                $m_do = substr($val, 4, 2) * 1;
                                                                                $y_do = substr($val, 0, 4);
                                                                                //if($val<=$year_me){
                                                                        ?>
                                                                                <td align="right" style="background:#bebebe;width:102px">
                                                                                    <?php if ($mo[$key] == '') {
                                                                                        echo "-";
                                                                                    } else {
                                                                                        $sum_all[$recChild['PRJP_ID']] = 0;
                                                                                        $i = $fbs;
                                                                                        while ($i < $val) {
                                                                                            $smo = substr($i, 4, 2);
                                                                                            $syo = substr($i, 0, 4);
                                                                                            if ($smo * 1 == '12') {
                                                                                                $i = ($syo + 1) . "01";
                                                                                            } else {
                                                                                                $i++;
                                                                                            }
                                                                                            $sum_all[$recChild['PRJP_ID']] += $arr_pr[$recChild['PRJP_ID']][$syo][$smo * 1];
                                                                                        }
                                                                                        echo number_format($sum_all[$recChild['PRJP_ID']] + $arr_pro[$recChild['PRJP_ID']][$y_do][$m_do], 2);
                                                                                    } ?>
                                                                                </td>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                    <tr bgcolor="#FFFFFF">
                                                                        <td align="center" style="background:#afeeee">แผน</td>
                                                                        <?php
                                                                        foreach ($rro as $key => $val) {
                                                                            if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                                $m_do = substr($val, 4, 2) * 1;
                                                                                $y_do = substr($val, 0, 4);
                                                                                //if($val<=$year_me){
                                                                        ?>
                                                                                <td align="right" style="background:#afeeee">
                                                                                    <?php if ($mo[$key] == '') {
                                                                                        echo "-";
                                                                                    } else {
                                                                                    ?>
                                                                                    <?php echo @number_format($arr_pro[$recChild['PRJP_ID']][$y_do][$m_do], 2);
                                                                                    } ?></td>
                                                                                <?php     //}else{ 
                                                                                ?>
                                                                                <?php /*?><td align="center">-</td>	<?php */ ?>
                                                                        <?php
                                                                            } //if 
                                                                        } //foreach
                                                                        ?>
                                                                    </tr>
                                                                    <tr bgcolor="#FFFFFF">
                                                                        <td align="center" style="background:#bebebe">ผลสะสม</td>
                                                                        <?php

                                                                        $i = 1;
                                                                        foreach ($rro as $key => $val) {
                                                                            if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                                $m_do = substr($val, 4, 2) * 1;
                                                                                $y_do = substr($val, 0, 4);
                                                                        ?>
                                                                                <td align="right" id="psum_old_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe"></td>
                                                                        <?php
                                                                            }
                                                                            $i++;
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                    <tr bgcolor="#FFFFFF">
                                                                        <td align="center" style="background:#afeeee">ผล</td>
                                                                        <?php
                                                                        $k = 1;
                                                                        foreach ($rro as $key => $val) {
                                                                            if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                                $m_do = substr($val, 4, 2) * 1;
                                                                                $y_do = substr($val, 0, 4);
                                                                                //if($val<=$year_me){
                                                                                $mck = $y_d . sprintf("%'02d", $m_d);
                                                                                //if($mck>=$ymchk){
                                                                                //	$distxt = "disabled";
                                                                                //$bgdis = "background:#9F9;";
                                                                                //}else{
                                                                                //	$distxt = "";	
                                                                                //	$bgdis = "";
                                                                                //}
                                                                                //if($mck==$ymchk){
                                                                                //if(date("d") >=1 && date("d")<11){
                                                                                //$distxt = "";	
                                                                                //$bgdis = "";	
                                                                                //}else{
                                                                                //$distxt = "disabled";
                                                                                //$bgdis = "background:#9F9;";
                                                                                //}
                                                                                //}else{
                                                                                //	$distxt = "disabled";
                                                                                //	$bgdis = "background:#9F9;";
                                                                                //}
                                                                        ?>
                                                                                <td align="right" style="background:#afeeee">
                                                                                    <?php if ($mo[$key] == '') {
                                                                                        echo "-";
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="hidden" id="VCHK_YEAR_OLD_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR_OLD[]" value="<?php echo $mck; ?>">
                                                                                        <input name="YEAR[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="YEAR_<?php echo $key; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_do); ?>">
                                                                                        <input prjp-parent-id="<?php echo $recChild["PRJP_PARENT_ID"]; ?>" prjp-id="<?php echo $recChild['PRJP_ID']; ?>" month="<?php echo $m_do; ?>" year="<?php echo $y_do; ?>" name="BDG_VALUE_OLD[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="BDG_VALUE_OLD_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" type="text" size="5" class="form-control number_format PVO_<?php echo $ii; ?>_<?php echo $k; ?>" disabled value="<?php echo number_format($arr_rro[$recChild['PRJP_ID']][$y_do][$m_do], 2); ?>" onBlur="Chk_sval_old('<?php echo $c_arro; ?>',<?php echo $recChild['PRJP_ID'];  ?>); 
									cal_child(this); NumberFormat(this,2);" style="text-align:right;<?php //echo $bgdis; 
                                                                                                    ?>" <?php //echo $distxt; 
                                                                                                        ?>><?php } ?>
                                                                                </td>
                                                                        <?php } //if
                                                                            $k++;
                                                                        } //foreach 
                                                                        ?>
                                                                    </tr>
                                                                    <script>
                                                                        Chk_sval_old('<?php echo $c_arro; ?>', <?php echo $recChild['PRJP_ID']; ?>);
                                                                        Sum_result_old('<?php echo $c_arro; ?>', '<?php echo $num_rowso; ?>', '<?php echo $ymchk_js; ?>');
                                                                        Sum_result_old_v('<?php echo $c_arro; ?>', '<?php echo $num_rowso; ?>', '<?php echo $ymchk_js; ?>');
                                                                    </script>
                                                        <?php
                                                                    $ii++;
                                                                }  //while($recChild = $db->db_fetch_array($query)){
                                                                $ii++;
                                                            }  //while($rec = $db->db_fetch_array($query)){
                                                        } else {
                                                            echo "<tr><td align=\"center\" colspan=\"18\">ไม่พบข้อมูล</td></tr>";
                                                        }
                                                        ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php
                                                $mstarto = $mstarto + 12;
                                                $rowtbo = $rowtbo + 1;
                                            }
                                            ?>
                                            <label id="TB_BR"></label>
                                        <?php } ?>
                                        </div>

                                        <div class="row">
                                            <?php /////////////////////////////////// ปีปัจจุบัน ///////////////////////////////// 
                                            ?>
                                            <?php
                                            $mstart = 0;
                                            $mend = 11;
                                            $rowtb = 0;
                                            for ($row = 1; $row <= $row_wh; $row++) {
                                            ?>
                                                <div id="tb_<?php echo $row; ?>" class=" col-xs-12 col-sm-12 ">
                                                    <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                                        <thead>
                                                            <tr class="bgHead table-head-rmoney">
                                                                <th width="40px" rowspan="2">
                                                                    <div align="center"><strong>ลำดับ</strong></div>
                                                                </th>
                                                                <th width="230px" rowspan="2">
                                                                    <div align="center"><strong>ชื่อกิจกรรม</strong></div>
                                                                </th>
                                                                <th width="230px" rowspan="2">
                                                                    <div align="center"><strong>ประเภทค่าใช้จ่าย</strong></div>
                                                                </th>
                                                                <th width="85px" rowspan="2">
                                                                    <div align="center"><strong>เงินที่วางแผน</strong></div>
                                                                </th>
                                                                <th width="95px" rowspan="2">
                                                                    <div align="center"><strong>ยอดสะสม</strong></div>
                                                                </th>
                                                                <th rowspan="2">
                                                                    <div align="center"><strong></strong></div>
                                                                </th>
                                                                <th width="6%" colspan="12">
                                                                    <div align="center"><strong>ผลการใช้จ่ายเงินของกิจกรรม ปี <?php echo $_SESSION['year_round']; ?></strong></div>
                                                                </th>
                                                            </tr>
                                                            <tr class="bgHead">
                                                                <?php
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $smh = substr($val, 4, 2);
                                                                        $syh = substr($val, 2, 2);
                                                                ?>
                                                                        <th width="6%">
                                                                            <div align="center"><strong><?php echo $month[$smh * 1] . $syh; ?></strong></div>
                                                                        </th>
                                                                <?php }
                                                                } ?>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            //แผน
                                                            $sql_value_rp = "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR,
												((prjp_plan_money.BDG_VALUE/prjp_project.MONEY_BDG)*100)as per_val,prjp_project.MONEY_BDG
												FROM prjp_plan_money 
												JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
												WHERE prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "' ";
                                                            $query_value_rp = $db->query($sql_value_rp);

                                                            /*if($db->db_num_rows($query_value_rp) == 0){
													
													$fix_fetch = $db->get_data_rec("select SERVICE_main_project_id, YEAR_BDG from prjp_project where PRJP_PARENT_ID = '".$PRJP_ID."' ");
													$fix_pj_id = $fix_fetch['SERVICE_main_project_id'];
													$fix_bdg_year = $fix_fetch['YEAR_BDG'];
													
													if($fix_pj_id > 0){
														$url = "http://61.91.223.53/SME_BDG/api_ipa/task_plan_request.php?id=".$fix_pj_id;
														$ch = curl_init();
														curl_setopt($ch, CURLOPT_URL, $url);
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
														$sResponse = curl_exec($ch);
														curl_close($ch);
														$fix_data =  json_decode($sResponse, true);
														
														$url = "http://61.91.223.53/SME_BDG/api_ipa/task_month_request.php?id=".$fix_pj_id;
														$ch = curl_init();
														curl_setopt($ch, CURLOPT_URL, $url);
														curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
														curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
														$sResponse = curl_exec($ch);
														curl_close($ch);
														$fix_data2 =  json_decode($sResponse, true);
														
														foreach($fix_data as $m_task => $r_fix){
															$f_prjp_id = $db->get_data_field("select PRJP_ID from prjp_project where PRJP_PARENT_ID = '".$PRJP_ID."' and PRJP_NAME like '%".ctext($r_fix['name_plan'])."%' ","PRJP_ID");
															
															if($f_prjp_id > 0){
																$db->query(" UPDATE prjp_project set SERVICE_TASK_MONEY_ID = '".$m_task."' where PRJP_ID = '".$f_prjp_id."' ");
																if($fix_data2[$m_task]['money']){
																	foreach($fix_data2[$m_task]['money'] as $f_month=>$f_val){
																		$f_year = $f_month > 9 ? ($fix_bdg_year-1):$fix_bdg_year;
																		$add_fix = array(
																						'PRJP_ID'	=>	$f_prjp_id,
																						'MONTH'		=>	$f_month,
																						'YEAR'		=>	$f_year,
																						'BDG_VALUE'	=>	$f_val,
																						'TASK_ID'	=>	$m_task 
																					);
																		$db->db_insert("prjp_plan_money",$add_fix);
																	}
																}
															}
														}
														
														$sql_value_rp = "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR,
														((prjp_plan_money.BDG_VALUE/prjp_project.MONEY_BDG)*100)as per_val,prjp_project.MONEY_BDG
														FROM prjp_plan_money 
														JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
														WHERE prjp_project.PRJP_PARENT_ID = '".$PRJP_ID."' ";
														
													}
												}*/

                                                            $sum_pr = array();
                                                            while ($rec_value_rp = $db->db_fetch_array($query_value_rp)) {
                                                                $arr_pr[$rec_value_rp['PRJP_ID']][$rec_value_rp['YEAR']][$rec_value_rp['MONTH']] = $rec_value_rp['BDG_VALUE'];



                                                                $sql_value_rp_child = "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR,
																	((prjp_plan_money.BDG_VALUE/prjp_project.MONEY_BDG)*100)as per_val,prjp_project.MONEY_BDG
																	FROM prjp_plan_money 
																	JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
																	WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_value_rp['PRJP_ID'] . "'
																	";
                                                                $query_value_rp_child = $db->query($sql_value_rp_child);
                                                                while ($rec_value_rp_child = $db->db_fetch_array($query_value_rp_child)) {
                                                                    $arr_pr[$rec_value_rp_child['PRJP_ID']][$rec_value_rp_child['YEAR']][$rec_value_rp_child['MONTH']] = $rec_value_rp_child['BDG_VALUE'];
                                                                }




                                                                //$arr_mp[$rec_value_rp['PRJP_ID'][$rec_value_rp['YEAR'][$rec_value_rp['MONTH']]+=$rec_value_rp['BDG_VALUE'];
                                                                $sum_pr[$rec_value_rp['YEAR']][$rec_value_rp['MONTH']] += $rec_value_rp['BDG_VALUE'];
                                                            }
                                                            //ผล
                                                            $sql_r_value_rp = "select prjp_report_money.BDG_VALUE,prjp_report_money.MONTH,prjp_project.PRJP_ID,prjp_report_money.YEAR 
																		FROM prjp_report_money 
																		JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_money.PRJP_ID
																		WHERE prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "'";
                                                            $query_r_value_rp = $db->query($sql_r_value_rp);
                                                            while ($rec_r_value_rp = $db->db_fetch_array($query_r_value_rp)) {
                                                                $arr_rr[$rec_r_value_rp['PRJP_ID']][$rec_r_value_rp['YEAR']][$rec_r_value_rp['MONTH']] = $rec_r_value_rp['BDG_VALUE'];

                                                                $sql_r_value_rp_child = "select prjp_report_money.BDG_VALUE,prjp_report_money.MONTH,prjp_project.PRJP_ID,prjp_report_money.YEAR 
																		FROM prjp_report_money 
																		JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_money.PRJP_ID
																		WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_r_value_rp['PRJP_ID'] . "' ";
                                                                $query_r_value_rp_child = $db->query($sql_r_value_rp_child);
                                                                while ($rec_r_value_rp_child = $db->db_fetch_array($query_r_value_rp_child)) {
                                                                    $arr_rr[$rec_r_value_rp_child['PRJP_ID']][$rec_r_value_rp_child['YEAR']][$rec_r_value_rp_child['MONTH']] = $rec_r_value_rp_child['BDG_VALUE'];
                                                                }


                                                                $sum_rr[$rec_r_value_rp['YEAR']][$rec_r_value_rp['MONTH']] += $rec_r_value_rp['BDG_VALUE'];
                                                            }
                                                            ?>
                                                            <tr class="table-head2-rmoney">
                                                                <td colspan="5" rowspan="3" align="right">รวมแผนการใช้จ่ายเงิน (%)</td>
                                                                <td align="center" nowrap>แผนสะสม</td>
                                                                <?php
                                                                if ($row == 1) {
                                                                    $val_sum_pr_m = 0;
                                                                }
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $m_d = substr($val, 4, 2) * 1;
                                                                        $y_d = substr($val, 0, 4);
                                                                        $val_sum_pr_m += $sum_pr[$y_d][$m_d];

                                                                ?>
                                                                        <td align="right">
                                                                            <?php
                                                                            if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                                                if ((@($val_sum_pr_m / $rec_head['MONEY_BDG'])) * 100 > 100) {
                                                                                    echo "100.00";
                                                                            ?>
                                                                                    <input type="hidden" id="test_<?php echo $key + 1; ?>" value="<?php echo "100.00";  ?>">
                                                                                <?php
                                                                                } else {
                                                                                    echo @number_format(($val_sum_pr_m / $rec_head['MONEY_BDG']) * 100, 2);
                                                                                ?>
                                                                                    <input type="hidden" id="plan_<?php echo $key + 1; ?>" value="<?php echo @number_format(($val_sum_pr_m / $rec_head['MONEY_BDG']) * 100, 2); ?>">
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>
                                                            <tr class="table-head3-rmoney">
                                                                <td align="center">ผลสะสม</td>
                                                                <?php
                                                                if ($row == 1) {
                                                                    $val_sum_rr_m = 0;
                                                                }
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $m_d = substr($val, 4, 2) * 1;
                                                                        $y_d = substr($val, 0, 4);
                                                                        $val_sum_rr_m += $sum_rr[$y_d][$m_d];
                                                                ?>
                                                                        <td align="right" id="per_sum_<?php echo $key + 1; ?>">
                                                                            <?php //if(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100 > 100){echo "100.00";}else{ echo @number_format(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100,2);}
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>

                                                            <tr class="table-head6-rmoney">
                                                                <td align="center" nowrap>สถานะผลเทียบแผน</td>
                                                                <?php
                                                                if ($row == 1) {
                                                                    $val_sum_rr_m = 0;
                                                                }
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $m_d = substr($val, 4, 2) * 1;
                                                                        $y_d = substr($val, 0, 4);
                                                                        $val_sum_rr_m += $sum_rr[$y_d][$m_d];
                                                                ?>
                                                                        <td align="right" id="per_status_<?php echo $key + 1; ?>" nowrap>
                                                                            <?php //if(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100 > 100){echo "100.00";}else{ echo @number_format(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100,2);}
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>

                                                            <tr class="table-head4-rmoney">
                                                                <td colspan="5" rowspan="2" align="right">รวมแผนการใช้จ่ายเงิน</td>
                                                                <td align="center" nowrap>แผนสะสม</td>
                                                                <?php
                                                                if ($row == 1) {
                                                                    $val_sum_pr_mv = 0;
                                                                }
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $m_d = substr($val, 4, 2) * 1;
                                                                        $y_d = substr($val, 0, 4);
                                                                        $val_sum_pr_mv += $sum_pr[$y_d][$m_d];

                                                                ?>
                                                                        <td align="right">
                                                                            <?php
                                                                            if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                                                echo @number_format($val_sum_pr_mv, 2);
                                                                            }
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>
                                                            <tr class="table-head5-rmoney">
                                                                <td align="center">ผลสะสม</td>
                                                                <?php
                                                                if ($row == 1) {
                                                                    $val_sum_rr_m = 0;
                                                                }
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $m_d = substr($val, 4, 2) * 1;
                                                                        $y_d = substr($val, 0, 4);
                                                                        $val_sum_rr_m += $sum_rr[$y_d][$m_d];
                                                                ?>
                                                                        <td align="right" id="val_sum_<?php echo $key + 1; ?>">
                                                                            <?php //if(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100 > 100){echo "100.00";}else{ echo @number_format(($val_sum_rr_m/$rec_head['MONEY_BDG'])*100,2);}
                                                                            ?>
                                                                        </td>
                                                                <?php
                                                                    } //if 
                                                                } //foreach
                                                                ?>
                                                            </tr>

                                                            <?php
                                                            if ($num_rows > 0) {
                                                                $ii = 1;
                                                                $query = $db->query($sql);
                                                                while ($rec = $db->db_fetch_array($query)) {
                                                                    $msa = 10;
                                                                    $mea = substr($rec['EDATE_PRJP'], 5, 2);
                                                                    $yea = substr($rec['EDATE_PRJP'], 0, 4) + 543;
                                                                    $ysa = substr($rec['SDATE_PRJP'], 0, 4) + 543;

                                                                    $year_ms = $ysa . $msa;
                                                                    $year_me = $yea . $mea;
                                                                    $row_cola = (((12 - $msa) + 1) + ((($yea - $ysa) - 1) * 12) + (12 - (12 - $mea)));

                                                                    $sqlChildCount = "SELECT
																		count(prjp_id) totalChild
																	FROM
																		prjp_project
																	WHERE
																		PRJP_PARENT_ID = '" . $rec['PRJP_ID'] . "'";
                                                                    $queryChildCount = $db->query($sqlChildCount);
                                                                    $recTotalChild = $db->db_fetch_array($queryChildCount);
                                                                    $totalChild = $recTotalChild["totalChild"];

                                                            ?>
                                                                    <tr bgcolor="#FFFFFF" class="table-body-rmoney">
                                                                        <td align="center" rowspan="4" width="50px"><?php echo $rec['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $rec['PRJP_ID']; ?>"></td>
                                                                        <td rowspan="4" align="left" width="215px"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($rec['PRJP_NAME']); ?></textarea></td>
                                                                        <td rowspan="4" align="center"><?php echo $arr_bdg_cost[$rec['COST_TYPE']]; ?></td>
                                                                        <td rowspan="4" align="right" width="90px">
                                                                            <?php echo number_format($rec['MONEY_BDG'], 2); ?>
                                                                        </td>
                                                                        <td rowspan="4" align="center" width="85px">
                                                                            <?php echo number_format($rec['s_val'], 2); ?>
                                                                        </td>
                                                                        <td align="center" width="40px" style="background:#bebebe" nowrap>แผนสะสม</td>
                                                                        <?php
                                                                        $ts[$rec['PRJP_ID']] = 0;
                                                                        foreach ($rr as $key => $val) {
                                                                            if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                $m_d = substr($val, 4, 2) * 1;
                                                                                $y_d = substr($val, 0, 4);
                                                                                //if($val<=$year_me){
                                                                        ?>
                                                                                <td align="right" style="background:#bebebe;width:102px">
                                                                                    <?php if ($m[$key] == '') {
                                                                                        echo "";
                                                                                    } else {
                                                                                        $sum_all[$rec['PRJP_ID']] = 0;
                                                                                        $i = $fbs;
                                                                                        while ($i < $val) {
                                                                                            $sm = substr($i, 4, 2);
                                                                                            $sy = substr($i, 0, 4);
                                                                                            if ($sm * 1 == '12') {
                                                                                                $i = ($sy + 1) . "01";
                                                                                            } else {
                                                                                                $i++;
                                                                                            }
                                                                                            $sum_all[$rec['PRJP_ID']] += $arr_pr[$rec['PRJP_ID']][$sy][$sm * 1];
                                                                                        }
                                                                                        echo number_format($sum_all[$rec['PRJP_ID']] + $arr_pr[$rec['PRJP_ID']][$y_d][$m_d], 2);
                                                                                    } ?>
                                                                                </td>
                                                                        <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                    <tr bgcolor="#FFFFFF" class="table-body2-rmoney">
                                                                        <td align="center" style="background:#afeeee" ref="งบดำเนินงาน">แผน</td>
                                                                        <?php
                                                                        foreach ($rr as $key => $val) {
                                                                            if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                $m_d = substr($val, 4, 2) * 1;
                                                                                $y_d = substr($val, 0, 4);
                                                                                //if($val<=$year_me){
                                                                        ?>
                                                                                <td align="right" style="background:#afeeee">
                                                                                    <?php if ($m[$key] == '') {
                                                                                        echo "";
                                                                                    } else {
                                                                                    ?>
                                                                                    <?php echo @number_format($arr_pr[$rec['PRJP_ID']][$y_d][$m_d], 2);
                                                                                    } ?></td>
                                                                                <?php     //}else{ 
                                                                                ?>
                                                                                <?php /*?><td align="center">-</td>	<?php */ ?>
                                                                        <?php
                                                                            } //if 
                                                                        } //foreach
                                                                        ?>
                                                                    </tr>
                                                                    <!--tr bgcolor="#FFFFFF" class="table-body2-rmoney">
													<td align="center" style="background:#afeeee">งบลงทุน</td>
													<?php
                                                                    foreach ($rr as $key => $val) {
                                                                        if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                            $m_d = substr($val, 4, 2) * 1;
                                                                            $y_d = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                    ?>
													<td align="right" style="background:#afeeee">
													<?php if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                    ?>
													<?php echo @number_format($arr_pr_invest[$rec['PRJP_ID']][$y_d][$m_d], 2);
                                                                            } ?></td>
													<?php     //}else{ 
                                                    ?>
													  <?php /*?><td align="center">-</td>	<?php */ ?>
													<?php
                                                                        } //if 
                                                                    } //foreach
                                                    ?>
												</tr>
												<tr bgcolor="#FFFFFF" class="table-body2-rmoney">
													<td align="center" style="background:#afeeee">งบบุคลากร</td>
													<?php
                                                                    foreach ($rr as $key => $val) {
                                                                        if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                            $m_d = substr($val, 4, 2) * 1;
                                                                            $y_d = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                    ?>
													<td align="right" style="background:#afeeee">
													<?php if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                    ?>
													<?php echo @number_format($arr_pr_person[$rec['PRJP_ID']][$y_d][$m_d], 2);
                                                                            } ?></td>
													<?php     //}else{ 
                                                    ?>
													  <?php /*?><td align="center">-</td>	<?php */ ?>
													<?php
                                                                        } //if 
                                                                    } //foreach
                                                    ?>
												</tr-->

                                                                    <!--<tr bgcolor="#FFFFFF">
												  <td align="center" style="background:#FFFFFF">งบตั้งเบิก สงป.301</td>
													<?php
                                                                    $k = 1;
                                                                    foreach ($rr as $key => $val) {
                                                                        if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                            $m_d = substr($val, 4, 2) * 1;
                                                                            $y_d = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                            $mck = $y_d . sprintf("%'02d", $m_d);
                                                                            if ($mck >= $ymchk) {
                                                                                $distxt = "disabled";
                                                                                $bgdis = "background:#9F9;";
                                                                            } else {
                                                                                $distxt = "";
                                                                                $bgdis = "";
                                                                            }
                                                                            if ($_SESSION["sys_group_id"] != '5') {
                                                                                if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
                                                                                    $input = ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . date("d");
                                                                                    if ($input >= $chk_set_start && $input <= $chk_set_end) {
                                                                                        $distxt = "AAAA";
                                                                                        $bgdis = "";
                                                                                    } else {
                                                                                        $distxt = "readonly";
                                                                                        $bgdis = "background:#9F9;";
                                                                                    }
                                                                                } else {
                                                                                    if (($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) == ($ymchk)) {
                                                                                        if (in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                                                                            $distxt = "BBBB";
                                                                                            $bgdis = "";
                                                                                        } else {
                                                                                            $distxt = "readonly";
                                                                                            $bgdis = "background:#9F9;";
                                                                                        }
                                                                                    } else {
                                                                                        $distxt = "readonly";
                                                                                        $bgdis = "background:#9F9;";
                                                                                    }
                                                                                }
                                                                            }
                                                    ?>
													<td align="right" style="background:#FFFFFF">
													<?php if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                    ?>
													<input type="hidden" id="VCHK_YEAR_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR[]" value="<?php echo $mck; ?>">
													 <input name="YEAR[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" 
													 id="YEAR_<?php echo $key; ?>" type="hidden" size="5" 
													 class="form-control number_format" value="<?php echo ($y_d); ?>">
											   
											
														
													<?php
                                                                                $input_type = "text";
                                                                                $readonly = "";
                                                                                if ($totalChild > 0) {
                                                                                    $readonly = "";
                                                                                    $input_type = "hidden";
                                                                                } else {
                                                                                }
                                                    ?>
												   <input  <?php echo $readonly; ?>   prjp-parent-id="<?php echo $rec["PRJP_PARENT_ID"]; ?>"  
												   prjp-id="<?php echo $rec['PRJP_ID']; ?>"  month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" 
												   name="BDG_VAL[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_VAL_<?php echo $rec['PRJP_ID']; ?>_<?php echo $k; ?>" 
												   type="text" size="5" class="form-control PV_<?php echo $ii; ?>_<?php echo $k; ?>" 
												   value="<?php echo number_format($arr_rr[$rec['PRJP_ID']][$y_d][$m_d], 2); ?>" 
												   onBlur="Chk_sval('<?php echo $c_arr; ?>',<?php echo $rec['PRJP_ID']; ?>, this);
												   Sum_result('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');
												   Sum_result_v('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>', this);
												   NumberFormat(this,2);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>><?php } ?></td>
													<?php } //if
                                                                        $k++;
                                                                    } //foreach 
                                                    ?>
												</tr>-->
                                                                    <tr bgcolor="#FFFFFF" class="table-body3-rmoney">
                                                                        <td align="center" style="background:#bebebe">ผลสะสม</td>
                                                                        <?php

                                                                        $i = 1;
                                                                        foreach ($rr as $key => $val) {
                                                                            if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                $m_d = substr($val, 4, 2) * 1;
                                                                                $y_d = substr($val, 0, 4);
                                                                        ?>
                                                                                <td align="right" id="psum_<?php echo $rec['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe"></td>
                                                                        <?php
                                                                            }
                                                                            $i++;
                                                                        }
                                                                        ?>
                                                                    </tr>
                                                                    <tr bgcolor="#FFFFFF" class="table-body4-rmoney">
                                                                        <td align="center" style="background:#afeeee" ref="งบดำเนินงาน">ผล</td>
                                                                        <?php
                                                                        $k = 1;
                                                                        foreach ($rr as $key => $val) {
                                                                            if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                $m_d = substr($val, 4, 2) * 1;
                                                                                $y_d = substr($val, 0, 4);
                                                                                //if($val<=$year_me){
                                                                                $mck = $y_d . sprintf("%'02d", $m_d);
                                                                                if ($mck >= $ymchk) {
                                                                                    $distxt = "disabled";
                                                                                    $bgdis = "background:#9F9;";
                                                                                } else {
                                                                                    $distxt = "";
                                                                                    $bgdis = "";
                                                                                }
                                                                                if ($_SESSION["sys_group_id"] != '5') {
                                                                                    if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
                                                                                        $input = ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . date("d");
                                                                                        if ($input >= $chk_set_start && $input <= $chk_set_end) {
                                                                                            $distxt = 'comparewith="' . ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . ':' . $chk_set . '"';
                                                                                            $bgdis = "";
                                                                                        } else {
                                                                                            $distxt = "readonly";
                                                                                            $bgdis = "background:#9F9;";
                                                                                        }
                                                                                    } else {
                                                                                        if (($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) == ($ymchk)) {
                                                                                            if (in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                                                                                $distxt = "DDDD";
                                                                                                $bgdis = "";
                                                                                            } else {
                                                                                                $distxt = "readonly";
                                                                                                $bgdis = "background:#9F9;";
                                                                                            }
                                                                                        } else {
                                                                                            $distxt = "readonly";
                                                                                            $bgdis = "background:#9F9;";
                                                                                        }
                                                                                    }
                                                                                }
                                                                        ?>
                                                                                <td align="right" style="background:#afeeee">
                                                                                    <?php if ($m[$key] == '') {
                                                                                        echo "";
                                                                                    } else {
                                                                                    ?>
                                                                                        <input type="hidden" id="VCHK_YEAR_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR[]" value="<?php echo $mck; ?>">
                                                                                        <input name="YEAR[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="YEAR_<?php echo $key; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_d); ?>">



                                                                                        <?php
                                                                                        $input_type = "text";
                                                                                        $readonly = "";
                                                                                        if ($totalChild > 0) {
                                                                                            $readonly = "readonly";
                                                                                            $input_type = "hidden";
                                                                                        } else {
                                                                                        }
                                                                                        ?>
                                                                                        <input disabled prjp-parent-id="<?php echo $rec["PRJP_PARENT_ID"]; ?>" prjp-id="<?php echo $rec['PRJP_ID']; ?>" month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" name="BDG_VALUE[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_VALUE_<?php echo $rec['PRJP_ID']; ?>_<?php echo $k; ?>" type="text" size="5" class="form-control PV_<?php echo $ii; ?>_<?php echo $k; ?>" value="<?php echo number_format($arr_rr[$rec['PRJP_ID']][$y_d][$m_d], 2); ?>" onBlur="Chk_sval('<?php echo $c_arr; ?>',<?php echo $rec['PRJP_ID']; ?>, this);
																Sum_result('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');
                                                                status_result('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');
																Sum_result_v('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>', this);
																NumberFormat(this,2);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>><?php } ?>
                                                                                </td>
                                                                        <?php
                                                                            } //if
                                                                            $k++;
                                                                        } //foreach 
                                                                        ?>
                                                                    </tr>
                                                                    <!--tr bgcolor="#FFFFFF" class="table-body4-rmoney">
													<td align="center" style="background:#afeeee">งบลงทุน</td>
													<?php
                                                                    $k = 1;
                                                                    foreach ($rr as $key => $val) {
                                                                        if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                            $m_d = substr($val, 4, 2) * 1;
                                                                            $y_d = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                            $mck = $y_d . sprintf("%'02d", $m_d);
                                                                            if ($mck >= $ymchk) {
                                                                                $distxt = "disabled";
                                                                                $bgdis = "background:#9F9;";
                                                                            } else {
                                                                                $distxt = "";
                                                                                $bgdis = "";
                                                                            }
                                                                            if ($_SESSION["sys_group_id"] != '5') {
                                                                                if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
                                                                                    $input = ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . date("d");
                                                                                    if ($input >= $chk_set_start && $input <= $chk_set_end) {
                                                                                        $distxt = 'comparewith="' . ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . ':' . $chk_set . '"';
                                                                                        $bgdis = "";
                                                                                    } else {
                                                                                        $distxt = "readonly";
                                                                                        $bgdis = "background:#9F9;";
                                                                                    }
                                                                                } else {
                                                                                    if (($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) == ($ymchk)) {
                                                                                        if (in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                                                                            $distxt = "DDDD";
                                                                                            $bgdis = "";
                                                                                        } else {
                                                                                            $distxt = "readonly";
                                                                                            $bgdis = "background:#9F9;";
                                                                                        }
                                                                                    } else {
                                                                                        $distxt = "readonly";
                                                                                        $bgdis = "background:#9F9;";
                                                                                    }
                                                                                }
                                                                            }
                                                    ?>
															<td align="right" style="background:#afeeee">
																<?php if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                                                $input_type = "text";
                                                                                $readonly = "";
                                                                                if ($totalChild > 0) {
                                                                                    $readonly = "readonly";
                                                                                    $input_type = "hidden";
                                                                                } else {
                                                                                }
                                                                ?>
																<input  <?php echo $readonly; ?>   prjp-parent-id="<?php echo $rec["PRJP_PARENT_ID"]; ?>"  
																prjp-id="<?php echo $rec['PRJP_ID']; ?>"  month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" 
																name="BDG_INVEST_VALUE[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_INVEST_VALUE_<?php echo $rec['PRJP_ID']; ?>_<?php echo $k; ?>" 
																type="text" size="5" class="form-control PV_<?php echo $ii; ?>_<?php echo $k; ?>" 
																value="<?php echo number_format($arr_rr_invest[$rec['PRJP_ID']][$y_d][$m_d], 2); ?>" 
																onBlur="Chk_sval_invest('<?php echo $c_arr; ?>',<?php echo $rec['PRJP_ID']; ?>, this);
																Sum_result_invest('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');
																Sum_result_v_invest('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>', this);
																NumberFormat(this,2);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>><?php } ?>
															</td>
															<?php
                                                                        } //if
                                                                        $k++;
                                                                    } //foreach 
                                                            ?>
												</tr>
												<tr bgcolor="#FFFFFF" class="table-body4-rmoney">
													<td align="center" style="background:#afeeee">งบบุคลากร</td>
													<?php
                                                                    $k = 1;
                                                                    foreach ($rr as $key => $val) {
                                                                        if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                            $m_d = substr($val, 4, 2) * 1;
                                                                            $y_d = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                            $mck = $y_d . sprintf("%'02d", $m_d);
                                                                            if ($mck >= $ymchk) {
                                                                                $distxt = "disabled";
                                                                                $bgdis = "background:#9F9;";
                                                                            } else {
                                                                                $distxt = "";
                                                                                $bgdis = "";
                                                                            }
                                                                            if ($_SESSION["sys_group_id"] != '5') {
                                                                                if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
                                                                                    $input = ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . date("d");
                                                                                    if ($input >= $chk_set_start && $input <= $chk_set_end) {
                                                                                        $distxt = 'comparewith="' . ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . ':' . $chk_set . '"';
                                                                                        $bgdis = "";
                                                                                    } else {
                                                                                        $distxt = "readonly";
                                                                                        $bgdis = "background:#9F9;";
                                                                                    }
                                                                                } else {
                                                                                    if (($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) == ($ymchk)) {
                                                                                        if (in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                                                                            $distxt = "DDDD";
                                                                                            $bgdis = "";
                                                                                        } else {
                                                                                            $distxt = "readonly";
                                                                                            $bgdis = "background:#9F9;";
                                                                                        }
                                                                                    } else {
                                                                                        $distxt = "readonly";
                                                                                        $bgdis = "background:#9F9;";
                                                                                    }
                                                                                }
                                                                            }
                                                    ?>
															<td align="right" style="background:#afeeee">
																<?php if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                                                $input_type = "text";
                                                                                $readonly = "";
                                                                                if ($totalChild > 0) {
                                                                                    $readonly = "readonly";
                                                                                    $input_type = "hidden";
                                                                                } else {
                                                                                }
                                                                ?>
																<input  <?php echo $readonly; ?>   prjp-parent-id="<?php echo $rec["PRJP_PARENT_ID"]; ?>"  
																prjp-id="<?php echo $rec['PRJP_ID']; ?>"  month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" 
																name="BDG_PERSON_VALUE[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_PERSON_VALUE_<?php echo $rec['PRJP_ID']; ?>_<?php echo $k; ?>" 
																type="text" size="5" class="form-control PV_<?php echo $ii; ?>_<?php echo $k; ?>" 
																value="<?php echo number_format($arr_rr_person[$rec['PRJP_ID']][$y_d][$m_d], 2); ?>" 
																onBlur="Chk_sval_person('<?php echo $c_arr; ?>',<?php echo $rec['PRJP_ID']; ?>, this);
																Sum_result_person('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');
																Sum_result_v_person('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>', this);
																NumberFormat(this,2);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>><?php } ?>
															</td>
															<?php
                                                                        } //if
                                                                        $k++;
                                                                    } //foreach 
                                                            ?>
												</tr-->
                                                                    <script>
                                                                        Chk_sval('<?php echo $c_arr; ?>', <?php echo $rec['PRJP_ID']; ?>, this);
                                                                        Sum_result('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
                                                                        status_result('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
                                                                        Sum_result_v('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
                                                                    </script>
                                                                    <?php

                                                                    $sqlChild = "SELECT 	a.PRJP_PARENT_ID,a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.SDATE_PRJP,a.EDATE_PRJP,a.MONEY_BDG,a.ORDER_NO,
												(select sum(BDG_VALUE) from prjp_report_money where prjp_report_money.PRJP_ID = a.PRJP_ID)as s_val
												FROM prjp_project a 
												WHERE 1=1 AND a.PRJP_LEVEL = '3' AND a.PRJP_PARENT_ID = '" . $rec['PRJP_ID'] . "' 
												order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
												";

                                                                    $iii = 1;
                                                                    $queryChild = $db->query($sqlChild);
                                                                    while ($recChild = $db->db_fetch_array($queryChild)) {
                                                                        $msa = 10;
                                                                        $mea = substr($rec['EDATE_PRJP'], 5, 2);
                                                                        $yea = substr($rec['EDATE_PRJP'], 0, 4) + 543;
                                                                        $ysa = substr($rec['SDATE_PRJP'], 0, 4) + 543;

                                                                        $year_ms = $ysa . $msa;
                                                                        $year_me = $yea . $mea;
                                                                        $row_cola = (((12 - $msa) + 1) + ((($yea - $ysa) - 1) * 12) + (12 - (12 - $mea)));


                                                                    ?>
                                                                        <tr bgcolor="#FFFFFF" class="table-body-rmoney">
                                                                            <td align="center" rowspan="4" width="50px"><?php echo $recChild['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $recChild['PRJP_ID']; ?>"></td>
                                                                            <td rowspan="4" align="left" width="215px"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($recChild['PRJP_NAME']); ?></textarea></td>
                                                                            <td rowspan="4" align="right" width="90px"><?php echo number_format($recChild['MONEY_BDG'], 2); ?></td>
                                                                            <td rowspan="4" align="center" width="85px"><?php echo number_format($recChild['s_val'], 2); ?></td>
                                                                            <td align="center" width="40px" style="background:#bebebe" nowrap>แผนสะสม</td>
                                                                            <?php
                                                                            $ts[$recChild['PRJP_ID']] = 0;
                                                                            foreach ($rr as $key => $val) {
                                                                                if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                    $m_d = substr($val, 4, 2) * 1;
                                                                                    $y_d = substr($val, 0, 4);
                                                                                    //if($val<=$year_me){
                                                                            ?>
                                                                                    <td align="right" style="background:#bebebe;width:102px">
                                                                                        <?php if ($m[$key] == '') {
                                                                                            echo "";
                                                                                        } else {
                                                                                            $sum_all[$recChild['PRJP_ID']] = 0;
                                                                                            $i = $fbs;
                                                                                            while ($i < $val) {
                                                                                                $sm = substr($i, 4, 2);
                                                                                                $sy = substr($i, 0, 4);
                                                                                                if ($sm * 1 == '12') {
                                                                                                    $i = ($sy + 1) . "01";
                                                                                                } else {
                                                                                                    $i++;
                                                                                                }
                                                                                                $sum_all[$recChild['PRJP_ID']] += $arr_pr[$recChild['PRJP_ID']][$sy][$sm * 1];
                                                                                            }
                                                                                            echo number_format($sum_all[$recChild['PRJP_ID']] + $arr_pr[$recChild['PRJP_ID']][$y_d][$m_d], 2);
                                                                                        } ?>
                                                                                    </td>
                                                                            <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </tr>
                                                                        <tr bgcolor="#FFFFFF" class="table-body2-rmoney">
                                                                            <td align="center" style="background:#afeeee">แผน</td>
                                                                            <?php
                                                                            foreach ($rr as $key => $val) {
                                                                                if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                    $m_d = substr($val, 4, 2) * 1;
                                                                                    $y_d = substr($val, 0, 4);
                                                                                    //if($val<=$year_me){
                                                                            ?>
                                                                                    <td align="right" style="background:#afeeee">
                                                                                        <?php if ($m[$key] == '') {
                                                                                            echo "-";
                                                                                        } else {
                                                                                        ?>
                                                                                        <?php echo @number_format($arr_pr[$recChild['PRJP_ID']][$y_d][$m_d], 2);
                                                                                        } ?></td>
                                                                                    <?php     //}else{ 
                                                                                    ?>
                                                                                    <?php /*?><td align="center">-</td>	<?php */ ?>
                                                                            <?php
                                                                                } //if 
                                                                            } //foreach
                                                                            ?>
                                                                        </tr>
                                                                        <tr bgcolor="#FFFFFF" class="table-body3-rmoney">
                                                                            <td align="center" style="background:#bebebe">ผลสะสม</td>
                                                                            <?php

                                                                            $i = 1;
                                                                            foreach ($rr as $key => $val) {
                                                                                if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                    $m_d = substr($val, 4, 2) * 1;
                                                                                    $y_d = substr($val, 0, 4);
                                                                            ?>
                                                                                    <td align="right" id="psum_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe"></td>
                                                                            <?php
                                                                                }
                                                                                $i++;
                                                                            }
                                                                            ?>
                                                                        </tr>
                                                                        <tr bgcolor="#FFFFFF" class="table-body4-rmoney">
                                                                            <td align="center" style="background:#afeeee">ผล</td>
                                                                            <?php
                                                                            $k = 1;
                                                                            foreach ($rr as $key => $val) {
                                                                                if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                                    $m_d = substr($val, 4, 2) * 1;
                                                                                    $y_d = substr($val, 0, 4);
                                                                                    //if($val<=$year_me){
                                                                                    $mck = $y_d . sprintf("%'02d", $m_d);
                                                                                    if ($mck >= $ymchk) {
                                                                                        $distxt = "disabled";
                                                                                        $bgdis = "background:#9F9;";
                                                                                    } else {
                                                                                        $distxt = "";
                                                                                        $bgdis = "";
                                                                                    }
                                                                                    if ($_SESSION["sys_group_id"] != '5') {
                                                                                        if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
                                                                                            $input = ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . date("d");
                                                                                            if ($input >= $chk_set_start && $input <= $chk_set_end) {
                                                                                                $distxt = "EEEE";
                                                                                                $bgdis = "";
                                                                                            } else {
                                                                                                $distxt = "readonly";
                                                                                                $bgdis = "background:#9F9;";
                                                                                            }
                                                                                        } else {
                                                                                            if (($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) == ($ymchk)) {
                                                                                                if (in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                                                                                    $distxt = "FFFF";
                                                                                                    $bgdis = "";
                                                                                                } else {
                                                                                                    $distxt = "readonly";
                                                                                                    $bgdis = "background:#9F9;";
                                                                                                }
                                                                                            } else {
                                                                                                $distxt = "readonly";
                                                                                                $bgdis = "background:#9F9;";
                                                                                            }
                                                                                        }
                                                                                    }
                                                                            ?>
                                                                                    <td align="right" style="background:#afeeee">
                                                                                        <?php
                                                                                        if ($m[$key] == '') {
                                                                                            echo "";
                                                                                        } else {
                                                                                        ?>
                                                                                            <input type="hidden" id="VCHK_YEAR_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR[]" value="<?php echo $mck; ?>">
                                                                                            <input name="YEAR[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="YEAR_<?php echo $key; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_d); ?>">
                                                                                            <input prjp-parent-id="<?php echo $recChild["PRJP_PARENT_ID"]; ?>" prjp-id="<?php echo $recChild['PRJP_ID']; ?>" month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" name="BDG_VALUE[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_VALUE_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" type="text" disabled size="5" class="form-control " value="<?php echo number_format($arr_rr[$recChild['PRJP_ID']][$y_d][$m_d], 2); ?>" onBlur="Chk_sval('<?php echo $c_arr; ?>',<?php echo $recChild['PRJP_ID']; ?>, this); 
																	cal_child(this); NumberFormat(this,2);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>><?php
                                                                                                                                                                                } ?>
                                                                                    </td>
                                                                            <?php
                                                                                } //if
                                                                                $k++;
                                                                            } //foreach 
                                                                            ?>
                                                                        </tr>
                                                                        <script>
                                                                            Chk_sval('<?php echo $c_arr; ?>', <?php echo $recChild['PRJP_ID']; ?>, this);
                                                                            Sum_result('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
                                                                            Sum_result_v('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
                                                                        </script>
                                                            <?php
                                                                        $iii++;
                                                                    }  //while($recChild = $db->db_fetch_array($query)){
                                                                    $ii++;
                                                                }  //while($rec = $db->db_fetch_array($query)){
                                                            } else {
                                                                echo "<tr><td align=\"center\" colspan=\"18\">ไม่พบข้อมูล</td></tr>";
                                                            }
                                                            ?>
                                                        </tbody>

                                                    </table>



                                                    <?php //include("disp_project_send_act_task_copy.php");
                                                    ?>

                                                    <div style="text-align:center; bottom:0px;">
                                                        <?php //include($path."include/footer.php"); 
                                                        ?>
                                                    </div>

                                                </div>
                                            <?php
                                                $mstart = $mstart + 12;
                                                $rowtb = $rowtb + 1;
                                            }


                                            $mck = (date("Y") + 543) . (date("m"));
                                            //$ymchk;
                                            $distxt = "";
                                            $bgdis = "";
                                            if ($_SESSION["sys_group_id"] != '5') {
                                                if (($mck) == ($ymchk) && in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                                    $distxt = "";
                                                    $bgdis = "";
                                                } elseif ($rec_head['PRJP_SET_TIME_CHK'] == 1 && $mck . date("d") <= $chk_set . $de_set) {
                                                    $distxt = "";
                                                    $bgdis = "";
                                                } else {
                                                    $distxt = "readonly";
                                                    $bgdis = "background:#9F9;";
                                                }
                                            }
                                            ?>
                                            <div style="display:none;">
                                                <?php echo $rec_head['PRJP_SET_TIME_CHK']; ?><br />
                                                <?php echo $mck . date("d"); ?><br />
                                                <?php echo $de_set; ?><br />
                                            </div>
                                            <table width="" class="" cellpadding="2" border="0" style="max-width:auto">
                                                <input type="hidden" name="month_now" id="month_now" value="<?php echo (date("m") * 1); ?>">
                                                <input type="hidden" name="year_now" id="year_now" value="<?php echo (date("Y") + 543); ?>">
                                                <tr>
                                                    <td align="center" width="50px"></td>

                                                    <td align="left" colspan="3" width="200px"> </td>
                                                    <td align="left" width="300"> งบประมาณโครงการ </td>
                                                    <td align="left" width="200"><input name="BDG_VALUE[<?php echo (date("Y") + 543); ?>][<?php echo (date("m") * 1); ?>]" id="BDG_VALUE_<?php echo (date("Y") + 543); ?>_<?php echo (date("m") * 1); ?>" disabled type="text" size="5" class="form-control" value="<?php echo number_format($rec_head['MONEY_BDG'], 2); ?>" ; Sum_result('12','8','256109'); Sum_result_v('12','8','256109'); NumberFormat(this,2);" style="text-align:right; width: 150px" readonly></td>
                                                    <td align="center" width="70"></td>
                                                    <td align="left"></td>
                                                </tr>
                                                <tr>
                                                    <td align="center" width="50px"></td>
                                                    <?php $month =  date('m'); ?>
                                                    <td align="left" colspan="3" width="200px"> </td>
                                                    <td align="left" width=""> ผลการเบิกจ่าย ณ เดือน <?php echo $month_full[$MONTH2 * 1]; ?> </td>
                                                    <td align="left" width="200"><input name="DISBURSE_VALUE[<?php echo (date("Y") + 543); ?>][<?php echo (date("m") * 1); ?>]" id="DISBURSE_VALUE_<?php echo (date("Y") + 543); ?>_<?php echo (date("m") * 1); ?>" disabled type="text" size="5" class="form-control" value="<?php echo number_format($rec_r_value_now['sumnow'], 2); ?>" ; Sum_result('12','8','256109'); Sum_result_v('12','8','256109'); NumberFormat(this,2);" style="text-align:right; width: 150px" readonly></td>
                                                    <td align="right" width="" id="PERCENT_DISBURSE_VALUE"><?php echo @number_format(($rec_r_value_now['sumnow'] / $rec_head['MONEY_BDG']) * 100, 2); ?></td>
                                                    <td align="left">%</td>
                                                </tr>
                                                <tr>
                                                    <td align="center" width="50px"></td>

                                                    <td align="left" colspan="3" width="200px"> </td>
                                                    <td align="left" width=""> งบประมาณผูกพัน&nbsp;<font color="#FF0000">*</font>
                                                        <?php if ($_SESSION["sys_group_id"] == '5') { ?>
                                                            <a class="btn btn-info data-info-hover" onclick="dataBINDING('<?php echo $PRJP_ID; ?>');" data-toggle="modal" data-placement="top" data-title="" data-content="คลิกเพื่อดูข้อมูลการกรอกงบประมาณผูกพัน"><i class="fa fa-info" aria-hidden="true"></i></a>
                                                        <?php } ?>
                                                    </td>
                                                    <td align="left" width="200"><input name="BINDING_VALUE[<?php echo (date("Y") + 543); ?>][<?php echo (date("m") * 1); ?>]" id="BINDING_VALUE_<?php echo (date("Y") + 543); ?>_<?php echo (date("m") * 1); ?>" type="text" size="5" class="form-control number_format chk_empty" value="<?php echo empty($rec_binding['PRJP_NOW_ID']) ? '' : number_format($rec_binding['BINDING_VALUE'], 2); ?>" Onblur="sum_value(year_now.value,month_now.value);NumberFormat(this,2);" style="text-align:right; width: 150px;<?php echo $bgdis; ?>" disabled>
                                                    </td>
                                                    <td align="right" width="" id="PERCENT_BINDING_VALUE"><?php echo @number_format(($rec_binding['BINDING_VALUE'] / $rec_head['MONEY_BDG']) * 100, 2); ?></td>
                                                    <td align="left">%</td>
                                                </tr>
                                                <tr>
                                                    <td align="center" width="50px"></td>
                                                    <td align="left" colspan="3" width="200px"> </td>
                                                    <td align="left" width=""> </td>
                                                    <td align="left" colspan="3" style="color:red;" nowrap>หากไม่มีงบประมาณผูกพัน ให้กรอกข้อมูลเป็น "0" </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" width="50px"></td>

                                                    <td align="left" colspan="3" width="200px"> </td>
                                                    <td align="left" width=""> รวมงบประมาณเบิกจ่ายและผูกพัน </td>
                                                    <td align="left" width="200"><input name="SUM_VALUE[<?php echo (date("Y") + 543); ?>][<?php echo (date("m") * 1); ?>]" id="SUM_VALUE_<?php echo (date("Y") + 543); ?>_<?php echo (date("m") * 1); ?>" type="text" size="5" class="form-control number_format" value="<?php echo number_format($rec_binding['SUM_VALUE'], 2); ?>" ; Sum_result('12','8','256109'); Sum_result_v('12','8','256109'); NumberFormat(this,2);" style="text-align:right; width: 150px" readonly></td>
                                                    <td align="right" width="" id="PERCENT_SUM_VALUE"><?php echo @number_format(($rec_binding['SUM_VALUE'] / $rec_head['MONEY_BDG']) * 100, 2); ?></td>
                                                    <td align="left">%</td>
                                                </tr>
                                                <tr>
                                                    <td align="center" width="50px"></td>

                                                    <td align="left" colspan="3" width="200px"> </td>
                                                    <td align="left" width=""> งบประมาณคงเหลือ </td>
                                                    <td align="left" width="200"><input name="BALANCE_VALUE[<?php echo (date("Y") + 543); ?>][<?php echo (date("m") * 1); ?>]" id="BALANCE_VALUE_<?php echo (date("Y") + 543); ?>_<?php echo (date("m") * 1); ?>" type="text" size="5" class="form-control number_format" value="<?php echo number_format($rec_binding['BALANCE_VALUE'], 2); ?>" ; Sum_result('12','8','256109');<?php echo (date("Y") + 543); ?> Sum_result_v('12','8','256109'); NumberFormat(this,2);" style="text-align:right; width: 150px" readonly></td>
                                                    <td align="right" width="" id="PERCENT_BALANCE_VALUE"><?php echo @number_format(($rec_binding['BALANCE_VALUE'] / $rec_head['MONEY_BDG']) * 100, 2); ?></td>
                                                    <td align="left">%</td>
                                                </tr>
                                            </table>
                                        </div>

                                        <?php //echo endPaging("frm-search",$total_record); 
                                        ?>
                                        <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modal_contract_binding" tabindex="-1" role="dialog" aria-labelledby="modal_contract_label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document" style="width: 100%;max-width:650px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_contract_label"><i class="fa fa-edit" aria-hidden="true"></i> ข้อมูลการกรอกงบประมาณผูกพัน
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </h4>

                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i> ยกเลิก</button> -->
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var menu_id = "<?php echo $menu_id; ?>"
        var menu_sub_id = "<?php echo $menu_sub_id; ?>"

        function dataBINDING(PRJP_ID) {
            $('#modal_contract_binding .modal-body').load("disp_project_send_act_money_binding.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&PRJP_ID=" + PRJP_ID)
            $('#modal_contract_binding').modal('show');
            $('.data-info').html('');
        }
    </script>
</body>

</html>
<?php echo form_model('myModal1', 'เลือกวันที่ออกรายงาน', 'show_display', '', '', '1'); ?>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<div id="errorModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">ผลการตรวจสอบข้อมูล</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <span style="color:red;font-size:60px;" class="glyphicon glyphicon-remove-circle"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center" id="errorModal-data">
                    </div>
                </div>
            </div>
            <div class="modal-footer"></div>
        </div>

    </div>
</div>
<div class="modal fade" id="myModal1"></div>
<!-- /.modal -->