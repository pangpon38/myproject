<?php
session_start();
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '5';
if ($_POST['PRJP_ID'] != '') {
    $PRJP_ID = $_POST['PRJP_ID'];
} else {
    $PRJP_ID = $PRJP_ID;
}
$month_full = array("1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");
$month_full_bdg = array("10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม", "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน");
$sql_head = "SELECT PRJP_CODE,PRJP_NAME,EDATE_PRJP,SDATE_PRJP,PROLONG_STATUS,MONEY_BDG,PRJP_CON_ID FROM prjp_project WHERE PRJP_ID = '" . $PRJP_ID . "' order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO ";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);
if ($rec_head['PROLONG_STATUS'] == 1) {
    $shead = "(ขอขยายเวลา)";
} else {
    $shead = "";
}
$month = array("10" => "ต.ค.", "11" => "พ.ย.", "12" => "ธ.ค.", "1" => "ม.ค.", "2" => "ก.พ.", "3" => "มี.ค.", "4" => "เม.ย.", "5" => "พ.ค.", "6" => "มิ.ย.", "7" => "ก.ค.", "8" => "ส.ค.", "9" => "ก.ย.");

//$sql_date = "SELECT MAX(EDATE_PRJP)as H_DATE,MIN(SDATE_PRJP)as L_MIN FROM prjp_project WHERE 1=1 AND PRJP_LEVEL = '2' AND PRJP_PARENT_ID = '".$PRJP_ID."' ";
//$query_date = $db->query($sql_date);
//$rec_date = $db->db_fetch_array($query_date);
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

$sql = "SELECT 	a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.SDATE_PRJP,a.EDATE_PRJP,a.MONEY_BDG,a.ORDER_NO,
				(select sum(BDG_VALUE) from prjp_plan_money where prjp_plan_money.PRJP_ID = a.PRJP_ID)as s_val
		  		FROM prjp_project a 
				WHERE 1=1 AND a.PRJP_LEVEL = '2' AND a.PRJP_PARENT_ID = '" . $PRJP_ID . "' 
				order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
				";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);
/////////////////////////////// แผนเก่า //////////////////////////////////
if ($rec_head['PRJP_CON_ID'] != '') {
    $sql_dataold = "SELECT EDATE_PRJP,SDATE_PRJP FROM prjp_project WHERE PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . " '";
    $query_dataold = $db->query($sql_dataold);
    $rec_dataold = $db->db_fetch_array($query_dataold);
    $mso = substr($rec_dataold['SDATE_PRJP'], 5, 2) * 1;
    $yso = substr($rec_dataold['SDATE_PRJP'], 0, 4) + 543;
    $meo = substr($rec_dataold['EDATE_PRJP'], 5, 2) * 1;
    $yeo = substr($rec_dataold['EDATE_PRJP'], 0, 4) + 543;

    $yseo = ((($yeo - $yso) * 12)) - (12 - $meo);
    $row_colo = (((12 - $mso) + 1) + ((($yeo - $yso) - 1) * 12) + (12 - (12 - $meo)));
    $row_who = ceil($row_colo / 12); ///กำหนดรอบ
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

    $sqlo = "SELECT 	a.PRJP_ID,a.PRJP_NAME,a.ORDER_NO,a.MONEY_BDG,
				(select sum(BDG_VALUE) from prjp_plan_money where prjp_plan_money.PRJP_ID = a.PRJP_ID)as s_val
		  		FROM prjp_project a 
				WHERE 1=1 AND a.PRJP_LEVEL = '2' AND a.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "' 
				order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
				";
    $queryo = $db->query($sqlo);
    $num_rowso = $db->db_num_rows($queryo);
    $rec_preo = $db->db_fetch_array($queryo);
}
////////////////////////////// end  ////////////////////////////////////
?>
<!DOCTYPE html>
<html>

<head>
    <?php include($path . "include/inc_main_top.php"); ?>
    <script src="js/disp_project_act_money.js?<?php echo rand(); ?>"></script>
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
                <li><a href="disp_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
                <li class="active">แผนการใช้จ่ายเงินของกิจกรรม</li>
            </ol>
        </div>


        <div class="col-xs-12 col-sm-12">
            <div class="groupdata">
                <form id="frm-search" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="code_user" name="code_user" value="<?php echo $_SESSION['sys_dept_id']; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <input type="hidden" id="YMIN" name="YMIN" value="<?php echo $ys; ?>">
                    <input type="hidden" id="YMAX" name="YMAX" value="<?php echo $ye; ?>">
                    <input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />

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
                                                <?php /* ?><option value="3">PDF</option><?php */ ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div style="display:none;"><label>จาก</label></div>
                                    <div class="row" style="display:none;">
                                        <div class="col-md-12">
                                            <select id="S_MONTH" name="S_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;">

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="display:none;">
                                        <div class="col-md-12">
                                            ถึง
                                        </div>
                                    </div>
                                    <div class="row" style="display:none;">
                                        <div class="col-md-12">
                                            <select id="E_MONTH" name="E_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;">

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
                        <div class="col-xs-12 col-sm-12"><?php include("tab_menu_r.php"); ?></div>
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
                    //$print_form_w = "<a data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"pdf_report_w('".$PRJP_ID."');\">".$img_print."  พิมพ์ สสว.100/3 Word</a> ";
                    //$print_form_p = "<a data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"pdf_report_p('".$PRJP_ID."');\">".$img_print."  พิมพ์ สสว.100/3 PDF</a> ";
                    ?>

                    <div class="row page-prjp-money">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading row" style="">
                                    <div class="pull-left" style="">แผนการใช้จ่ายเงินของกิจกรรม</div>
                                    <div class="pull-right" style="">สสว.100/3</div>
                                </div>
                                <div class="panel-body epm-gradient">
                                    <?php if ($rec_head['PRJP_CON_ID'] != '') { ?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12" align="center">
                                                <input type="hidden" id="hide_old" name="hide_old" value="0">
                                                <a href="javascript:void(0)" onClick="chk_old(hide_old.value);"><?php echo $img_save; ?> แผนการใช้จ่ายเงินเก่า</a>
                                            </div>
                                        </div>

                                        <div class="row" id="tab_old" style="display:none;">
                                            <table boder='1' align='left'>
                                                <tr>
                                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <==== <?php for ($row_tbo = 1; $row_tbo <= $row_who; $row_tbo++) { ?> <a href="javascript:void(0);" onClick="stableo('<?php echo $row_tbo; ?>')">&nbsp;<font size='5'><?php echo $row_tbo; ?></font>&nbsp;</a>
                                                        <?php  } ?>
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
                                                        <tr class="bgHead table-head-money">
                                                            <th width="40px" rowspan="2">
                                                                <div align="center"><strong>ลำดับ</strong></div>
                                                            </th>
                                                            <th width="230px" rowspan="2">
                                                                <div align="center"><strong>ชื่อกิจกรรม</strong></div>
                                                            </th>
                                                            <th width="100px" rowspan="2">
                                                                <div align="center"><strong>เงินที่วางแผน</strong></div>
                                                            </th>
                                                            <?php /*?><th width="95px" rowspan="2"><div align="center"><strong>ยอดสะสม</strong></div></th><?php */ ?>
                                                            <th rowspan="2">
                                                                <div align="center"><strong></strong></div>
                                                            </th>
                                                            <th colspan="12">
                                                                <div align="center"><strong>แผนการใช้จ่ายงบประมาณประจำปี <?php echo $_SESSION['year_round']; ?></strong> (บาท)</div>
                                                            </th>
                                                        </tr>
                                                        <tr class="bgHead ">
                                                            <?php
                                                            foreach ($rro as $key => $val) {
                                                                if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                    $smho = substr($val, 4, 2);
                                                                    $syho = substr($val, 2, 2);
                                                            ?>
                                                                    <th width="110px">
                                                                        <div align="center"><strong><?php echo $month[$smho * 1] . $syho; ?></strong></div>
                                                                    </th>
                                                            <?php }
                                                            } ?>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php



                                                        $sql_value_rpo = "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR
											FROM prjp_plan_money 
											JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
											WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "'
											";
                                                        $query_value_rpo = $db->query($sql_value_rpo);
                                                        $sum_pro = array();
                                                        $arr_pro = array();
                                                        while ($rec_value_rpo = $db->db_fetch_array($query_value_rpo)) {
                                                            $arr_pro[$rec_value_rpo['PRJP_ID']][$rec_value_rpo['YEAR']][$rec_value_rpo['MONTH']] = $rec_value_rpo['BDG_VALUE'];

                                                            $sql_value_pt_childo =  "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR
																	FROM prjp_plan_money 
																	JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
																	WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_value_rp["PRJP_ID"] . "'
																	";
                                                            $query_value_pt_childo = $db->query($sql_value_pt_childo);
                                                            while ($rec_value_pt_childo = $db->db_fetch_array($query_value_pt_childo)) {
                                                                $arr_pro[$rec_value_pt_childo['PRJP_ID']][$rec_value_pt_childo['YEAR']][$rec_value_pt_childo['MONTH']] = $rec_value_pt_childo['BDG_VALUE'];
                                                            }



                                                            $sum_pro[$rec_value_rpo['YEAR']][$rec_value_rpo['MONTH']] += $rec_value_rpo['BDG_VALUE'];
                                                        }
                                                        ?>
                                                        <tr class="table-head2-money">
                                                            <td colspan="3" rowspan="2" align="right">รวมแผนการใช้จ่ายเงิน (%)</td>
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
                                                        <tr class="table-head3-money">
                                                            <td align="center">แผน</td>
                                                            <?php
                                                            foreach ($rro as $key => $val) {
                                                                if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                    $m_do = substr($val, 4, 2) * 1;
                                                                    $y_do = substr($val, 0, 4);
                                                            ?>
                                                                    <td id="pre_<?php echo $y_do; ?>_<?php echo $m_do; ?>" align="right">
                                                                        <?php
                                                                        if ($mo[$key] == '') {
                                                                            echo "";
                                                                        } else {
                                                                            echo @number_format(($sum_pro[$y_do][$m_do] / $rec_head['MONEY_BDG']) * 100, 2);
                                                                        }
                                                                        ?></td>
                                                            <?php
                                                                } //if 
                                                            } //foreach
                                                            ?>
                                                        </tr>
                                                        <tr class="table-head4-money">
                                                            <td colspan="3" rowspan="2" align="right">รวมแผนการใช้จ่ายเงิน</td>
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
                                                        <tr class="table-head5-money">
                                                            <td align="center">แผน</td>
                                                            <?php
                                                            foreach ($rro as $key => $val) {
                                                                if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                    $m_do = substr($val, 4, 2) * 1;
                                                                    $y_do = substr($val, 0, 4);
                                                            ?>
                                                                    <td id="pre_<?php echo $y_do; ?>_<?php echo $m_do; ?>" align="right">
                                                                        <?php
                                                                        if ($mo[$key] == '') {
                                                                            echo "";
                                                                        } else {
                                                                            echo number_format($sum_pro[$y_do][$m_do], 2);
                                                                        }
                                                                        ?></td>
                                                            <?php
                                                                } //if 
                                                            } //foreach
                                                            ?>
                                                        </tr>
                                                    </tbody>

                                                    <?php
                                                    if ($num_rowso > 0) {
                                                        $l = 1;
                                                        $queryo = $db->query($sqlo);
                                                        while ($reco = $db->db_fetch_array($queryo)) {
                                                            $msao = 10;
                                                            $meao = substr($rec['EDATE_PRJP'], 5, 2);
                                                            $yeao = substr($rec['EDATE_PRJP'], 0, 4) + 543;
                                                            $ysao = substr($rec['SDATE_PRJP'], 0, 4) + 543;

                                                            $year_mso = $ysao . $msao;
                                                            $year_meo = $yeao . $meao;
                                                            $row_colao = (((12 - $msao) + 1) + ((($yeao - $ysao) - 1) * 12) + (12 - (12 - $meao)));

                                                            $sqlChildCount = "SELECT
																count(prjp_id) as totalChild
															FROM
																prjp_project
															WHERE
																PRJP_PARENT_ID = '" . $rec['PRJP_ID'] . "'";
                                                            $queryChildCount = $db->query($sqlChildCount);
                                                            $recTotalChild = $db->db_fetch_array($queryChildCount);
                                                            $totalChild = $recTotalChild["totalChild"];

                                                    ?>
                                                            <tr bgcolor="#FFFFFF" class="table-body-money">
                                                                <td align="center" rowspan="2" width="50px"><?php echo $reco['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $reco['PRJP_ID']; ?>"></td>
                                                                <td align="left" rowspan="2" width="222px">
                                                                    <textarea rows="3" cols="15" class="prjp-name-show" disabled=""><?php echo text($reco['PRJP_NAME']); ?></textarea>
                                                                </td>
                                                                <td align="right" rowspan="2" width="105px"><?php echo number_format($reco['MONEY_BDG'], 2); ?></td>
                                                                <?php /*?><td align="right" rowspan="2" width="90px"><?php echo number_format($rec['s_val'],2);?></td><?php */ ?>
                                                                <td align="center" width="37px" style="background:#bebebe" nowrap>แผนสะสม</td>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($rro as $key => $val) {
                                                                    if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                        $m_do = substr($val, 4, 2) * 1;
                                                                        $y_do = substr($val, 0, 4);
                                                                ?>
                                                                        <td align="right" id="psum_<?php echo $reco['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;width:110px"></td>
                                                                <?php
                                                                    }
                                                                    $i++;
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="table-body2-money">
                                                                <td align="center" style="background:#afeeee">แผน</td>
                                                                <?php
                                                                $k = 1;
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
                                                                                <input name="YEAR[<?php echo $reco['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="YEAR_<?php echo $y_do . $m_do; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_do); ?>">

                                                                                <?php
                                                                                $input_type = "text";
                                                                                $readonly = "";
                                                                                if ($totalChild > 0) {
                                                                                    $readonly = "";
                                                                                    //$input_type = "hidden";
                                                                                } else {
                                                                                }
                                                                                ?>

                                                                                <input <?php echo $readonly; ?> prjp-parent-id="<?php echo $reco["PRJP_PARENT_ID"]; ?>" month="<?php echo $m_do; ?>" year="<?php echo $y_do; ?>" prjp-id="<?php echo $reco['PRJP_ID']; ?>" name="BDG_VALUE[<?php echo $reco['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="BDG_VALUE_<?php echo $reco['PRJP_ID']; ?>_<?php echo $k; ?>" type="hidden" size="5" class="form-control number_format chk_v_<?php echo $reco['PRJP_ID']; ?>_<?php echo $y_do; ?>_<?php echo $m_do; ?> 
											sum_vt_<?php echo $reco['PRJP_ID']; ?> prec_<?php echo $y_do; ?>_<?php echo $m_do; ?>" value="<?php echo number_format($arr_pro[$reco['PRJP_ID']][$y_do][$m_do], 2); ?>" onBlur="Chk_val(this.value,'<?php echo $reco['MONEY_BDG']; ?>',<?php echo $reco['PRJP_ID']; ?>,'<?php echo $y_do; ?>','<?php echo $m_do; ?>');
											Chk_sval('<?php echo $c_arro; ?>',<?php echo $reco['PRJP_ID']; ?>);
											pre_val('<?php echo $y_do; ?>','<?php echo $m_do ?>','<?php echo $rec_head['MONEY_BDG']; ?>');
											NumberFormat(this,2);" style="text-align:right">
                                                                                <?php echo number_format($arr_pro[$reco['PRJP_ID']][$y_do][$m_do], 2); ?>
                                                                            <?php } ?>
                                                                        </td>
                                                                <?php } //if
                                                                    $k++;
                                                                } //foreach 
                                                                ?>
                                                            </tr>
                                                            <script>
                                                                Chk_sval('<?php echo $c_arro; ?>', <?php echo $reco['PRJP_ID']; ?>);
                                                            </script>
                                                            <?php


                                                            $sqlChild = "SELECT
																*
															FROM
																prjp_project
															WHERE
																PRJP_PARENT_ID = '" . $reco['PRJP_ID'] . "'";
                                                            $queryChild = $db->query($sqlChild);
                                                            while ($recChild = $db->db_fetch_array($queryChild)) {
                                                            ?>
                                                                <tr bgcolor="#FFFFFF" class="table-body-money">
                                                                    <td align="center" rowspan="2" width="55px"><?php echo $recChild['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $recChild['PRJP_ID']; ?>"></td>
                                                                    <td align="left" rowspan="2" width="210px">
                                                                        <textarea rows="3" cols="15" class="prjp-name-show" disabled=""><?php echo text($recChild['PRJP_NAME']); ?></textarea>
                                                                    </td>
                                                                    <td align="right" rowspan="2" width="105px"><?php echo number_format($recChild['MONEY_BDG'], 2); ?></td>

                                                                    <td align="center" style="background:#bebebe" width="" nowrap>แผนสะสม</td>
                                                                    <?php
                                                                    $i = 1;
                                                                    foreach ($rro as $key => $val) {
                                                                        if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                            $m_do = substr($val, 4, 2) * 1;
                                                                            $y_do = substr($val, 0, 4);
                                                                    ?>
                                                                            <td align="right" id="psum_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;width:80px"></td>
                                                                    <?php
                                                                        }
                                                                        $i++;
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr class="table-body2-money">
                                                                    <td align="center" style="background:#afeeee">แผน</td>
                                                                    <?php
                                                                    $k = 1;
                                                                    foreach ($rro as $key => $val) {
                                                                        if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
                                                                            $m_do = substr($val, 4, 2) * 1;
                                                                            $y_do = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                    ?>
                                                                            <td align="center" style="background:#afeeee">
                                                                                <?php if ($mo[$key] == '') {
                                                                                    echo "-";
                                                                                } else {
                                                                                ?>
                                                                                    <input name="VAL_PER[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="VAL_PER_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $y_d; ?>_<?php echo $m_d; ?>" type="hidden" size="5" class="form-control number_format sper_<?php echo $y_do; ?>_<?php echo $m_do; ?>_<?php echo $l; ?>" value="<?php echo number_format(($arr_po[$recChild['PRJP_ID']][$y_do][$m_do] / $recChild['MONEY_BDG']) * $recChild['WEIGHT'], 2); ?>">

                                                                                    <input name="YEAR[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="YEAR_<?php echo $y_do . $m_do; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_do); ?>">

                                                                                    <?php
                                                                                    $input_type = "text";
                                                                                    $readonly = "";
                                                                                    ?>
                                                                                    <input <?php echo $readonly; ?> prjp-parent-id="<?php echo $recChild["PRJP_PARENT_ID"]; ?>" month="<?php echo $m_do; ?>" year="<?php echo $y_do; ?>" prjp-id="<?php echo $recChild['PRJP_ID']; ?>" onBlur="Chk_val(this.value,'<?php echo $recChild['MONEY_BDG']; ?>',<?php echo $recChild['PRJP_ID']; ?>,'<?php echo $y_do; ?>','<?php echo $m_do; ?>');
																 Chk_sval('<?php echo $c_arro; ?>',<?php echo $recChild['PRJP_ID']; ?>);
																 pre_val('<?php echo $y_do; ?>','<?php echo $m_do ?>','<?php echo $recChild['WEIGHT']; ?>','<?php echo $recChild['MONEY_BDG']; ?>','<?php echo $recChild['PRJP_ID']; ?>','<?php echo $num_rowso; ?>'); 
																 cal_child(this);  
																 NumberFormat(this,2);" name="BDG_VALUE[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="BDG_VALUE_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" type="hidden" size="5" class="form-control number_format sum_vt_<?php echo $recChild['PRJP_ID']; ?> 
																 chk_v_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $y_do; ?>_<?php echo $m_do; ?>" value="<?php echo number_format($arr_pr[$recChild['PRJP_ID']][$y_do][$m_do], 2); ?>" style="text-align:right">
                                                                                    <?php echo number_format($arr_pr[$recChild['PRJP_ID']][$y_do][$m_do], 2); ?>
                                                                                <?php } ?>
                                                                            </td>
                                                                    <?php } //if


                                                                        $k++;
                                                                    } //foreach 
                                                                    ?>
                                                                </tr>

                                                                <script>
                                                                    Chk_sval('<?php echo $c_arro; ?>', <?php echo $recChild['PRJP_ID']; ?>);
                                                                    per_all();
                                                                </script>
                                                    <?php
                                                            }     //while($recChild = $db->db_fetch_array($queryChild)){		

                                                            $l++;
                                                        }
                                                    } else {
                                                        echo "<tr><td align=\"center\" colspan=\"14\">ไม่พบข้อมูล</td></tr>";
                                                    }
                                                    ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                    <?php
                                            $mstarto = $mstarto + 12;
                                            $rowtbo = $rowtbo + 1;
                                        }
                                    }
                                    ?>
                                    <?php //} ///// เช็คผลเก่า 
                                    ?>

                                    <?php
                                    if ($_SESSION['sys_status_print'] == '1') {
                                        // $print_form = "<a class=\"btn btn-info\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form('" . $PRJP_ID . "');get_sm('" . $PRJP_ID . "');get_em('" . $PRJP_ID . "');\">" . $img_print . "  พิมพ์ สสว.100/3</a> ";
                                    }
                                    ?>
                                    <div class="col-xs-12 col-sm-12 col-md-12"><?php echo $print_form; ?></div>
                                    <?php /////////////////////////////////// ปีปัจจุบัน ///////////////////////////////// 
                                    ?>

                                    <?php
                                    $mstart = 0;
                                    $mend = 11;
                                    $rowtb = 0;
                                    for ($row = 1; $row <= $row_wh; $row++) { ?>
                                        <div id="tb_<?php echo $row; ?>" class=" col-xs-12 col-sm-12 htb" style="">
                                            <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                                <thead>
                                                    <tr class="bgHead table-head-money">
                                                        <th width="40px" rowspan="2">
                                                            <div align="center"><strong>ลำดับ</strong></div>
                                                        </th>
                                                        <th width="230px" rowspan="2">
                                                            <div align="center"><strong>ชื่อกิจกรรม</strong></div>
                                                        </th>
                                                        <th width="100px" rowspan="2">
                                                            <div align="center"><strong>เงินที่วางแผน</strong></div>
                                                        </th>
                                                        <?php /*?><th width="95px" rowspan="2"><div align="center"><strong>ยอดสะสม</strong></div></th><?php */ ?>
                                                        <th rowspan="2">
                                                            <div align="center"><strong></strong></div>
                                                        </th>
                                                        <th colspan="12">
                                                            <div align="center"><strong>แผนการใช้จ่ายงบประมาณประจำปี <?php echo $_SESSION['year_round']; ?></strong> (บาท)</div>
                                                        </th>
                                                    </tr>
                                                    <tr class="bgHead ">
                                                        <?php
                                                        foreach ($rr as $key => $val) {
                                                            if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                $smh = substr($val, 4, 2);
                                                                $syh = substr($val, 2, 2);
                                                        ?>
                                                                <th width="110px">
                                                                    <div align="center"><strong><?php echo $month[$smh * 1] . $syh; ?></strong></div>
                                                                </th>
                                                        <?php }
                                                        } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php



                                                    $sql_value_rp = "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR
												FROM prjp_plan_money 
												JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
												WHERE prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "'
												";
                                                    $query_value_rp = $db->query($sql_value_rp);
                                                    $sum_pr = array();
                                                    while ($rec_value_rp = $db->db_fetch_array($query_value_rp)) {
                                                        $arr_pr[$rec_value_rp['PRJP_ID']][$rec_value_rp['YEAR']][$rec_value_rp['MONTH']] = $rec_value_rp['BDG_VALUE'];

                                                        $sql_value_pt_child =  "select prjp_plan_money.BDG_VALUE,prjp_plan_money.MONTH,prjp_project.PRJP_ID,prjp_plan_money.YEAR
																		FROM prjp_plan_money 
																		JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_money.PRJP_ID
																		WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_value_rp["PRJP_ID"] . "'
																		";
                                                        $query_value_pt_child = $db->query($sql_value_pt_child);
                                                        while ($rec_value_pt_child = $db->db_fetch_array($query_value_pt_child)) {
                                                            $arr_pr[$rec_value_pt_child['PRJP_ID']][$rec_value_pt_child['YEAR']][$rec_value_pt_child['MONTH']] = $rec_value_pt_child['BDG_VALUE'];
                                                        }


                                                        $sum_pr[$rec_value_rp['YEAR']][$rec_value_rp['MONTH']] += $rec_value_rp['BDG_VALUE'];
                                                    }
                                                    ?>
                                                    <tr class="table-head2-money">
                                                        <td colspan="3" rowspan="2" align="right">รวมแผนการใช้จ่ายเงิน (%)</td>
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
                                                                        } else {
                                                                            echo @number_format(($val_sum_pr_m / $rec_head['MONEY_BDG']) * 100, 2);
                                                                        }
                                                                    }
                                                                    ?>
                                                                </td>
                                                        <?php
                                                            } //if 
                                                        } //foreach
                                                        ?>
                                                    </tr>
                                                    <tr class="table-head3-money">
                                                        <td align="center">แผน</td>
                                                        <?php
                                                        foreach ($rr as $key => $val) {
                                                            if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                $m_d = substr($val, 4, 2) * 1;
                                                                $y_d = substr($val, 0, 4);
                                                        ?>
                                                                <td id="pre_<?php echo $y_d; ?>_<?php echo $m_d; ?>" align="right">
                                                                    <?php
                                                                    if ($m[$key] == '') {
                                                                        echo "";
                                                                    } else {
                                                                        echo @number_format(($sum_pr[$y_d][$m_d] / $rec_head['MONEY_BDG']) * 100, 2);
                                                                    }
                                                                    ?></td>
                                                        <?php
                                                            } //if 
                                                        } //foreach
                                                        ?>
                                                    </tr>
                                                    <tr class="table-head4-money">
                                                        <td colspan="3" rowspan="2" align="right">รวมแผนการใช้จ่ายเงิน</td>
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
                                                    <tr class="table-head5-money">
                                                        <td align="center">แผน</td>
                                                        <?php
                                                        //if($row==1){
                                                        $val_sum_pr_mvp = 0;
                                                        //}
                                                        foreach ($rr as $key => $val) {
                                                            if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                $m_d = substr($val, 4, 2) * 1;
                                                                $y_d = substr($val, 0, 4);
                                                                $val_sum_pr_mvp = $sum_pr[$y_d][$m_d];
                                                        ?>
                                                                <td id="pre_<?php echo $y_d; ?>_<?php echo $m_d; ?>" align="right">
                                                                    <?php
                                                                    if ($m[$key] == '') {
                                                                        echo "";
                                                                    } else {
                                                                        echo number_format($val_sum_pr_mvp, 2);
                                                                    }
                                                                    ?></td>
                                                        <?php
                                                            } //if 
                                                        } //foreach
                                                        ?>
                                                    </tr>
                                                    <?php
                                                    if ($num_rows > 0) {
                                                        $l = 1;
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
																	count(prjp_id) as totalChild
																FROM
																	prjp_project
																WHERE
																	PRJP_PARENT_ID = '" . $rec['PRJP_ID'] . "'
																GROUP BY ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
																
																	";
                                                            $queryChildCount = $db->query($sqlChildCount);
                                                            $recTotalChild = $db->db_fetch_array($queryChildCount);
                                                            $totalChild = $recTotalChild["totalChild"];

                                                    ?>
                                                            <tr bgcolor="#FFFFFF" class="table-body-money">
                                                                <td align="center" rowspan="2" width="50px"><?php echo $rec['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $rec['PRJP_ID']; ?>"></td>
                                                                <td align="left" rowspan="2" width="222px">
                                                                    <textarea rows="3" cols="15" class="prjp-name-show" disabled=""><?php echo text($rec['PRJP_NAME']); ?></textarea>
                                                                </td>
                                                                <td align="right" rowspan="2" width="105px"><?php echo number_format($rec['MONEY_BDG'], 2); ?></td>
                                                                <?php /*?><td align="right" rowspan="2" width="90px"><?php echo number_format($rec['s_val'],2);?></td><?php */ ?>
                                                                <td align="center" width="37px" style="background:#bebebe" nowrap>แผนสะสม</td>
                                                                <?php
                                                                $i = 1;
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $m_d = substr($val, 4, 2) * 1;
                                                                        $y_d = substr($val, 0, 4);
                                                                ?>
                                                                        <td align="right" id="psum_<?php echo $rec['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;width:110px"></td>
                                                                <?php
                                                                    }
                                                                    $i++;
                                                                }
                                                                ?>
                                                            </tr>
                                                            <tr class="table-body2-money">
                                                                <td align="center" style="background:#afeeee">แผน</td>
                                                                <?php
                                                                $k = 1;
                                                                foreach ($rr as $key => $val) {
                                                                    if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                        $m_d = substr($val, 4, 2) * 1;
                                                                        $y_d = substr($val, 0, 4);
                                                                        //if($val<=$year_me){

                                                                ?>
                                                                        <td align="center" style="background:#afeeee">
                                                                            <?php if ($m[$key] == '') {
                                                                                echo "";
                                                                            } else {
                                                                            ?>
                                                                                <input name="YEAR[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="YEAR_<?php echo $y_d . $m_d; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_d); ?>">

                                                                                <?php
                                                                                $input_type = "text";
                                                                                $readonly = "";
                                                                                if ($totalChild > 0) {
                                                                                    $readonly = "readonly";
                                                                                    //$input_type = "hidden";
                                                                                } else {
                                                                                }
                                                                                ?>

                                                                                <input <?php echo $readonly; ?> prjp-parent-id="<?php echo $rec["PRJP_PARENT_ID"]; ?>" month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" prjp-id="<?php echo $rec['PRJP_ID']; ?>" name="BDG_VALUE[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_VALUE_<?php echo $rec['PRJP_ID']; ?>_<?php echo $k; ?>" type="text" size="5" class="form-control chk_v_<?php echo $rec['PRJP_ID']; ?>_<?php echo $y_d; ?>_<?php echo $m_d; ?> 
												sum_vt_<?php echo $rec['PRJP_ID']; ?> prec_<?php echo $y_d; ?>_<?php echo $m_d; ?>" value="<?php echo number_format($arr_pr[$rec['PRJP_ID']][$y_d][$m_d], 2); ?>" onBlur="Chk_val(this.value,'<?php echo $rec['MONEY_BDG']; ?>',<?php echo $rec['PRJP_ID']; ?>,'<?php echo $y_d; ?>','<?php echo $m_d; ?>');
												Chk_sval('<?php echo $c_arr; ?>',<?php echo $rec['PRJP_ID']; ?>);
												pre_val('<?php echo $y_d; ?>','<?php echo $m_d ?>','<?php echo $rec_head['MONEY_BDG']; ?>');
												NumberFormat(this,2);" style="text-align:right"><?php } ?>
                                                                        </td>
                                                                <?php } //if
                                                                    $k++;
                                                                } //foreach 
                                                                ?>
                                                            </tr>
                                                            <script>
                                                                Chk_sval('<?php echo $c_arr; ?>', <?php echo $rec['PRJP_ID']; ?>);
                                                            </script>
                                                            <?php


                                                            $sqlChild = "SELECT
																	*
																FROM
																	prjp_project
																WHERE
																	PRJP_PARENT_ID = '" . $rec['PRJP_ID'] . "'";
                                                            $queryChild = $db->query($sqlChild);
                                                            while ($recChild = $db->db_fetch_array($queryChild)) {
                                                            ?>
                                                                <tr bgcolor="#FFFFFF" class="table-body-money">
                                                                    <td align="center" rowspan="2" width="55px"><?php echo $recChild['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $recChild['PRJP_ID']; ?>"></td>
                                                                    <td align="left" rowspan="2" width="210px">
                                                                        <textarea rows="3" cols="15" class="prjp-name-show" disabled=""><?php echo text($recChild['PRJP_NAME']); ?></textarea>
                                                                    </td>
                                                                    <td align="right" rowspan="2" width="105px"><?php echo number_format($recChild['MONEY_BDG'], 2); ?></td>

                                                                    <td align="center" style="background:#bebebe" width="" nowrap>แผนสะสม</td>
                                                                    <?php
                                                                    $i = 1;
                                                                    foreach ($rr as $key => $val) {
                                                                        if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                            $m_d = substr($val, 4, 2) * 1;
                                                                            $y_d = substr($val, 0, 4);
                                                                    ?>
                                                                            <td align="right" id="psum_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;width:80px"></td>
                                                                    <?php
                                                                        }
                                                                        $i++;
                                                                    }
                                                                    ?>
                                                                </tr>

                                                                <tr class="table-body2-money">
                                                                    <td align="center" style="background:#afeeee">แผน</td>
                                                                    <?php
                                                                    $k = 1;
                                                                    foreach ($rr as $key => $val) {
                                                                        if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
                                                                            $m_d = substr($val, 4, 2) * 1;
                                                                            $y_d = substr($val, 0, 4);
                                                                            //if($val<=$year_me){
                                                                    ?>
                                                                            <td align="center" style="background:#afeeee">
                                                                                <?php if ($m[$key] == '') {
                                                                                    echo "";
                                                                                } else {
                                                                                ?>
                                                                                    <input name="VAL_PER[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="VAL_PER_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $y_d; ?>_<?php echo $m_d; ?>" type="hidden" size="5" class="form-control number_format sper_<?php echo $y_d; ?>_<?php echo $m_d; ?>_<?php echo $l; ?>" value="<?php echo number_format(($arr_p[$recChild['PRJP_ID']][$y_d][$m_d] / $recChild['MONEY_BDG']) * $recChild['WEIGHT'], 2); ?>">

                                                                                    <input name="YEAR[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="YEAR_<?php echo $y_d . $m_d; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_d); ?>">

                                                                                    <?php
                                                                                    $input_type = "text";
                                                                                    $readonly = "";
                                                                                    ?>
                                                                                    <input <?php echo $readonly; ?> prjp-parent-id="<?php echo $recChild["PRJP_PARENT_ID"]; ?>" month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" prjp-id="<?php echo $recChild['PRJP_ID']; ?>" onBlur="Chk_val(this.value,'<?php echo $recChild['MONEY_BDG']; ?>',<?php echo $recChild['PRJP_ID']; ?>,'<?php echo $y_d; ?>','<?php echo $m_d; ?>');
																	 Chk_sval('<?php echo $c_arr; ?>',<?php echo $recChild['PRJP_ID']; ?>);
																	 pre_val('<?php echo $y_d; ?>','<?php echo $m_d ?>','<?php echo $recChild['WEIGHT']; ?>','<?php echo $recChild['MONEY_BDG']; ?>','<?php echo $recChild['PRJP_ID']; ?>','<?php echo $num_rows; ?>'); 
																	 cal_child(this);  
																	 NumberFormat(this,2);" name="BDG_VALUE[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_VALUE_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" type="text" size="5" class="form-control sum_vt_<?php echo $recChild['PRJP_ID']; ?> 
																	 chk_v_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $y_d; ?>_<?php echo $m_d; ?>" value="<?php echo number_format($arr_pr[$recChild['PRJP_ID']][$y_d][$m_d], 2); ?>" style="text-align:right"><?php } ?>
                                                                            </td>
                                                                    <?php } //if


                                                                        $k++;
                                                                    } //foreach 
                                                                    ?>
                                                                </tr>

                                                                <script>
                                                                    Chk_sval('<?php echo $c_arr; ?>', <?php echo $recChild['PRJP_ID']; ?>);
                                                                    per_all();
                                                                </script>
                                                    <?php
                                                            }     //while($recChild = $db->db_fetch_array($queryChild)){		

                                                            $l++;
                                                        }
                                                    } else {
                                                        echo "<tr><td align=\"center\" colspan=\"14\">ไม่พบข้อมูล</td></tr>";
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>

                                        </div>

                                    <?php
                                        $mstart = $mstart + 12;
                                        $rowtb = $rowtb + 1;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix" align="center">
                        <?php if ($_SESSION['sys_status_edit'] == '1') { ?>
                            <!-- <div class="row"><button type="button" class="btn btn-primary" onClick="chkinput();">บันทึก</button></div> -->
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
        <?php include($path . "include/footer.php"); ?>
    </div>

</body>
<script>
    var fields = document.getElementById("frm-search").getElementsByTagName('*');
    for (var i = 0; i < fields.length; i++) {
        fields[i].disabled = true;
    }
</script>

</html>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<!-- /.modal -->