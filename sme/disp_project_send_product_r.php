<?php
session_start();
$path = "../../";
$path_a = "../../fileupload/file_pr/";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$disables_txt = "disabled";
$readonly_txt = "readonly";
$ACT = '8';
if (!in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
    $ymchk = (date("Y") + 543) . date("m") + 1;
    $ymchk_js = (date("Y") + 543) . sprintf("%'02d", date("m") + 1);
} else {
    $ymchk = (date("Y") + 543) . date("m");
    $ymchk_js = (date("Y") + 543) . sprintf("%'02d", date("m"));
}
if ($_POST['PRJP_ID'] != '') {
    $PRJP_ID = $_POST['PRJP_ID'];
} else {
    $PRJP_ID = $PRJP_ID;
}
/*if(date("m")>=10){
	$select_ym = (date("Y")+543).sprintf("%'02d",date("m"));
}elseif(date("m")==01){
	$select_ym = (date("Y")+543).sprintf("%'02d",date("m"));	
}else{
	$select_ym = (date("Y")+543).sprintf("%'02d",(date("m")-1));	
}*/
if (date("m") == 01) {
    $select_ym = (date("Y") + 543 - 1) . '12';
} else {
    $select_ym = (date("Y") + 543) . sprintf("%'02d", (date("m") - 1));
}

$month = array("10" => "ต.ค.", "11" => "พ.ย.", "12" => "ธ.ค.", "1" => "ม.ค.", "2" => "ก.พ.", "3" => "มี.ค.", "4" => "เม.ย.", "5" => "พ.ค.", "6" => "มิ.ย.", "7" => "ก.ค.", "8" => "ส.ค.", "9" => "ก.ย.");
$month_full = array("1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");
$month_full_bdg = array("10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม", "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน");
$sql_head = "SELECT PRJP_CODE,PRJP_NAME,EDATE_PRJP,SDATE_PRJP,PRJP_CON_ID,PRJP_SET_STIME,PRJP_SET_ETIME,PRJP_SET_TIME_CHK,BDG_TYPE_ID
	FROM prjp_project 
	left join prjp_set_time on prjp_set_time.PRJP_ID = prjp_project.PRJP_ID
		AND '" . date('Y-m-d') . "' BETWEEN prjp_set_time.PRJP_SET_STIME AND prjp_set_time.PRJP_SET_ETIME
	WHERE prjp_project.PRJP_ID = '" . $PRJP_ID . "'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);
////////// เช็คสถานะการบันทึกย้อนหลัง ///////////////////
if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
    $ds_set = substr($rec_head['PRJP_SET_STIME'], 8, 2) * 1;
    $ms_set = substr($rec_head['PRJP_SET_STIME'], 5, 2) * 1;
    $ys_set = substr($rec_head['PRJP_SET_STIME'], 0, 4) + 543;
    $chk_set_start = $ys_set . sprintf("%'02d", $ms_set) . sprintf("%'02d", $ds_set);

    $de_set = substr($rec_head['PRJP_SET_ETIME'], 8, 2) * 1;
    $me_set = substr($rec_head['PRJP_SET_ETIME'], 5, 2) * 1;
    $ye_set = substr($rec_head['PRJP_SET_ETIME'], 0, 4) + 543;

    $chk_set = $ye_set . sprintf("%'02d", $me_set);
    $chk_set2 = $ye_set . sprintf("%'02d", $me_set) . sprintf("%'02d", $de_set);
    $chk_set_end = $ye_set . sprintf("%'02d", $me_set) . sprintf("%'02d", $de_set);
}
///////////////////////////////////////
$ms = substr($rec_head['SDATE_PRJP'], 5, 2) * 1;
$ys = substr($rec_head['SDATE_PRJP'], 0, 4) + 543;
$me = substr($rec_head['EDATE_PRJP'], 5, 2) * 1;
$ye = substr($rec_head['EDATE_PRJP'], 0, 4) + 543;

$yse = ((($ye - $ys) * 12)) - (12 - $me);
$row_col = (((12 - $ms) + 1) + ((($ye - $ys) - 1) * 12) + (12 - (12 - $me)));
$fbs = $ys . sprintf("%'02d", $ms);
$fbe = $ye . sprintf("%'02d", $me);

if ($select_ym > $fbe) {
    $select_ym = $fbe;
} else {
    $select_ym = $select_ym;
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

$sql = "SELECT 	a.PRJP_PRODUCT_ID,
				a.PRJP_ID,
				a.TYPE_PRO_ID,
				a.PRJP_PRODUCT_NAME,
				a.GOAL_VALUE,
				a.UNIT_ID,
				a.UNIT_PRO_NAME,
				(select TYPE_PRO_NAME FROM setup_type_product WHERE setup_type_product.TYPE_PRO_ID = a.TYPE_PRO_ID)as TYPE_PRO_NAME,
				(select UNIT_NAME_TH FROM setup_unit WHERE setup_unit.UNIT_ID = a.UNIT_ID)as UNIT_NAME_TH,
				c.PRJP_PRODUCT_OLD_ID
		  		FROM prjp_product a 
				JOIN setup_type_product b ON b.TYPE_PRO_ID = a.TYPE_PRO_ID
				LEFT JOIN product_join c on c.PRJP_ID = a.PRJP_ID AND a.PRJP_PRODUCT_ID = c.PRJP_PRODUCT_ID
				WHERE 1=1 AND a.PRJP_ID = '" . $PRJP_ID . "' 
				order by a.PRJP_PRODUCT_ID
				";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);

///////// value  ////////////
$sql_val = "select * from prjp_report_product where 1=1 and PRJP_ID = '" . $PRJP_ID . "' ";
$query_val = $db->query($sql_val);
while ($rec_val = $db->db_fetch_array($query_val)) {
    $ks = $rec_val['YEAR'] . sprintf("%'02d", $rec_val['MONTH']);
    $arr_pval[$rec_val['PRJP_PRODUCT_ID']][$ks] = $rec_val['PLAN_VALUE'];
    $arr_pval_s[$rec_val['PRJP_PRODUCT_ID']] += $rec_val['PLAN_VALUE'];
}
//////////// desc  ///////////////
$sql_desc = "select * from product_desc where 1=1 and PRJP_ID = '" . $PRJP_ID . "' ";
$query_desc = $db->query($sql_desc);
while ($rec_desc = $db->db_fetch_array($query_desc)) {
    $ks = $rec_desc['YEAR'] . sprintf("%'02d", $rec_desc['MONTH']);
    $arr_desc[$rec_desc['PRJP_PRODUCT_ID']][$ks] = $rec_desc['DESC_NAME'];
    $arr_rick[$rec_desc['PRJP_PRODUCT_ID']][$ks] = $rec_desc['RICK_NAME'];
    $arr_solution[$rec_desc['PRJP_PRODUCT_ID']][$ks] = $rec_desc['SOLUTION_NAME'];
}
//////////////////////////////////
if ($rec_head['PRJP_CON_ID'] != '') {
    ////////////////////////////////////////// ผลผลิตเก่า ////////////////////////////////////
    $sql_heado = "SELECT PRJP_CODE,PRJP_NAME,EDATE_PRJP,SDATE_PRJP FROM prjp_project WHERE PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "'";
    $query_heado = $db->query($sql_heado);
    $rec_heado = $db->db_fetch_array($query_heado);

    $mso = substr($rec_heado['SDATE_PRJP'], 5, 2) * 1;
    $yso = substr($rec_heado['SDATE_PRJP'], 0, 4) + 543;
    $meo = substr($rec_heado['EDATE_PRJP'], 5, 2) * 1;
    $yeo = substr($rec_heado['EDATE_PRJP'], 0, 4) + 543;

    $yseo = ((($yeo - $yso) * 12)) - (12 - $meo);
    $row_colo = (((12 - $ms) + 1) + ((($ye - $ys) - 1) * 12) + (12 - (12 - $me)));
    $fbso = $yso . sprintf("%'02d", $mso);
    $fbeo = $yeo . sprintf("%'02d", $meo);

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
    $sqlo = "SELECT 	a.PRJP_PRODUCT_ID,
				a.PRJP_ID,
				a.TYPE_PRO_ID,
				a.PRJP_PRODUCT_NAME,
				a.GOAL_VALUE,
				a.UNIT_ID,
				a.UNIT_PRO_NAME,
				(select TYPE_PRO_NAME FROM setup_type_product WHERE setup_type_product.TYPE_PRO_ID = a.TYPE_PRO_ID)as TYPE_PRO_NAME,
				(select UNIT_NAME_TH FROM setup_unit WHERE setup_unit.UNIT_ID = a.UNIT_ID)as UNIT_NAME_TH
		  		FROM prjp_product a 
				JOIN setup_type_product b ON b.TYPE_PRO_ID = a.TYPE_PRO_ID
				WHERE 1=1 AND PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "' 
				order by a.PRJP_PRODUCT_ID
				";
    $queryo = $db->query($sqlo);
    $num_rowso = $db->db_num_rows($queryo);

    ///////// value old  ////////////
    $sql_valo = "select * from prjp_report_product where 1=1 and PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "' ";
    $query_valo = $db->query($sql_valo);
    while ($rec_valo = $db->db_fetch_array($query_valo)) {
        $kso = $rec_valo['YEAR'] . sprintf("%'02d", $rec_valo['MONTH']);
        $arr_pvalo[$rec_valo['PRJP_PRODUCT_ID']][$kso] = $rec_valo['PLAN_VALUE'];
        $arr_pval_so[$rec_valo['PRJP_PRODUCT_ID']] += $rec_valo['PLAN_VALUE'];
    }
    ///////// value old now ////////////
    //echo $fbs;
    $sql_val_now = "select * from prjp_report_product where 1=1 and YEAR*100+MONTH< '" . $fbs . "' and PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "'  ";
    $query_val_now = $db->query($sql_val_now);
    while ($rec_val_now = $db->db_fetch_array($query_val_now)) {
        $kson = $rec_val_now['YEAR'] . sprintf("%'02d", $rec_val_now['MONTH']);
        $arr_pval_son[$rec_val_now['PRJP_PRODUCT_ID']] += $rec_val_now['PLAN_VALUE'];
    }
    //////////// desc  ///////////////
    $sql_desco = "select * from product_desc where 1=1 and PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "' ";
    $query_desco = $db->query($sql_desco);
    while ($rec_desco = $db->db_fetch_array($query_desco)) {
        $kso = $rec_desco['YEAR'] . sprintf("%'02d", $rec_desco['MONTH']);
        $arr_desco[$rec_desco['PRJP_PRODUCT_ID']][$kso] = $rec_desco['DESC_NAME'];
        $arr_ricko[$rec_desco['PRJP_PRODUCT_ID']][$kso] = $rec_desco['RICK_NAME'];
        $arr_solutiono[$rec_desco['PRJP_PRODUCT_ID']][$kso] = $rec_desco['SOLUTION_NAME'];
    }

    ///////////////////////////////////////////////////////////////////////////////////////
}



/////////query_file_include////////
$sql_val_include_result = "select * from prjp_report_result where 1=1 and PRJP_ID = '" . $PRJP_ID . "' ";
$query_val_include_result = $db->query($sql_val_include_result);
while ($rec_val_include_result = $db->db_fetch_array($query_val_include_result)) {
    $ks_include_result = $rec_val_include_result['YEAR'] . sprintf("%'02d", $rec_val_include_result['MONTH']); //ks
    $arr_pval_include_result[$rec_val_include_result['PRJP_RESULT_ID']][$ks_include_result] = $rec_val_include_result['PLAN_VALUE']; //$arr_pval
    $arr_pval_s_include_result[$rec_val_include_result['PRJP_RESULT_ID']] += $rec_val_include_result['PLAN_VALUE']; //$arr_pval_s
}
//echo "<pre>";print_r($arr_pval_include_result);echo "</pre>";
//////////// desc  ///////////////
$sql_desc_include_result = "select * from result_desc where 1=1 and PRJP_ID = '" . $PRJP_ID . "' ";
$query_desc_include_result = $db->query($sql_desc_include_result);
while ($rec_desc_include_result = $db->db_fetch_array($query_desc_include_result)) {
    $ks_include_result = $rec_desc_include_result['YEAR'] . sprintf("%'02d", $rec_desc_include_result['MONTH']); //ks
    $arr_desc_include_restule[$rec_desc_include_result['PRJP_RESULT_ID']][$ks_include_result] = $rec_desc_include_result['DESC_NAME']; //arr_desc
    $arr_rick_include_result[$rec_desc_include_result['PRJP_RESULT_ID']][$ks_include_result] = $rec_desc_include_result['RICK_NAME']; //arr_rick
    $arr_solution_include_result[$rec_desc_include_result['PRJP_RESULT_ID']][$ks_include_result] = $rec_desc_include_result['SOLUTION_NAME']; //arr_solution

}
/////////////////////////////////////


?>
<!DOCTYPE html>
<html>

<head>
    <?php include($path . "include/inc_main_top.php"); ?>
    <script src="js/disp_project_send_product.js?<?php echo rand(); ?>"></script>

    <script type="text/javascript">
        function tab_list(id) {
            $(".tb_v").hide();
            $(".re_act").removeClass("active");

            $("#show_rb_" + id).show();
            $("#tablist_" + id).addClass("active");

        }

        function tab_list_old(id) {
            $(".tb_v_old").hide();
            $(".re_act_old").removeClass("active");

            $("#show_rb_old_" + id).show();
            $("#tablist_old_" + id).addClass("active");

        }

        function chk_pro_old(id) {
            if (id == 0) {
                $("#hide_pro").val(1);
            } else if (id == 1) {
                $("#hide_pro").val(2);
            } else if (id == 2) {
                $("#hide_pro").val(1);
            }
            id = $("#hide_pro").val();
            show_pro_old(id);
        }

        function show_pro_old(id) {
            if (id == 1) {
                $("#data_old").show();
                //$("#TB_BR").html("<br><br><br>");
            } else {
                $("#data_old").hide();
                //$("#TB_BR").html("");
            }
        }


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
    <style>
        textarea {
            width: 100%;
        }
    </style>
</head>

<body style="display:inline-block">
    <div class="container-full">
        <div><?php include($path . "include/header.php"); ?></div>

        <div class="col-xs-12 col-sm-12">
            <ol class="breadcrumb">
                <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
                <li><a href="disp_send_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>">รายละเอียด</a></li>
                <li class="active">ผลตัวชี้วัดของผลผลิต</li>
            </ol>
        </div>

        <div class="col-xs-12 col-sm-12 page-data-report">
            <div class="groupdata">
                <form id="frm-search" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="year_round" name="year_round" value="<?php echo $_SESSION['year_round']; ?>">
                    <input type="hidden" id="code_user" name="code_user" value="<?php echo $_SESSION['sys_dept_id']; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <input type="hidden" id="YMIN" name="YMIN" value="<?php echo $ys; ?>">
                    <input type="hidden" id="YMAX" name="YMAX" value="<?php echo $ye; ?>">
                    <input type="hidden" id="fbs" name="fbs" value="<?php echo $select_ym; ?>">
                    <input type="hidden" id="fbso" name="fbso" value="<?php echo $fbso; ?>">
                    <input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />
                    <?php
                    /*if($_SESSION["sys_group_id"]=='5' || $_SESSION["sys_group_id"]=='9'){
				?>
                <div class="col-xs-12 col-sm-12"><?php include("tab_menu.php");?></div><br>
				<?php 
						}
				?>
        		<div class="col-xs-12 col-sm-12"><?php include("tab_menu2.php");?></div><br>
				<?php 
				if($_SESSION["sys_group_id"]=='5' || $_SESSION["sys_group_id"]=='9'){
				?>
				<div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php");?></div><br><br>
				<?php 
				}*/
                    ?>
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
							<div class="col-md-4">
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
							<div class="col-md-1">ถึง</div>
							<div class="col-md-4">
								<select id="E_MONTH" name="E_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;" >
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
						</div><?php */ ?>
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
                        <div class="col-xs-12 col-sm-12"><?php include("tab_menu2_r.php"); ?></div>
                        <?php
                        if ($_SESSION["sys_group_id"] == '5' || $_SESSION["sys_group_id"] == '9') {
                        ?>
                            <div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php"); ?></div>
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
                    <?php if ($rec_head['PRJP_CON_ID'] != '') { ?>
                        <div class="row">

                            <div class="col-xs-12 col-sm-12" align="center">
                                <input type="hidden" id="hide_pro" name="hide_pro" value="0">
                                <a href="javascript:void(0)" onClick="chk_pro_old(hide_pro.value);"><?php echo $img_save; ?> ข้อมูลรายงานผลผลิตเก่า</a>
                            </div>
                        </div>

                        <div id="data_old" style="display:none">
                            <div class="col-xs-12 col-sm-12">
                                <ul class="nav nav-tabs visible-md visible-lg">
                                    <?php
                                    foreach ($mo as $key => $val) {
                                        $smho = substr($val, 4, 2);
                                        $syho = substr($val, 2, 2);
                                    ?>
                                        <li id="tablist_old_<?php echo $val; ?>" class="re_act_old"><a onClick="tab_list_old(<?php echo $val; ?>)"><?php echo $month[$smho * 1] . $syho; ?></a></li>
                                    <?php
                                    }
                                    ?>
                                    <li id="tablist_old_99999" class="re_act_old"><a onClick="tab_list_old('99999')">แนบไฟล์เพิ่มเติม</a></li>
                                </ul>
                            </div>
                            <br><br>
                            <?php
                            foreach ($mo as $key => $val) {
                                $smho = substr($val, 4, 2);
                                $syho = substr($val, 0, 4);
                                $mcko = $syho . sprintf("%'02d", $smho);

                                if ($mck > $ymchk) {
                                    $distxt = "disabled";
                                    $bgdis = "background:#9F9;";
                                } else {
                                    $distxt = "";
                                    $bgdis = "";
                                }
                                if ($mcko == $ymchko) {
                                    if (in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                        $distxt = "";
                                        $bgdis = "";
                                    } else {
                                        $distxt = "disabled";
                                        $bgdis = "background:#9F9;";
                                    }
                                } else {
                                    $distxt = "disabled";
                                    $bgdis = "background:#9F9;";
                                }
                            ?>
                                <div class="col-xs-12 col-sm-12 tb_v_old" id="show_rb_old_<?php echo $val; ?>">
                                    <div class="">
                                        <table width="100%" class="table table-bordered table-striped table-hover table-condensed">
                                            <thead>
                                                <tr class="bgHead">
                                                    <th width="1%" nowrap rowspan="2">
                                                        <div align="center"><strong>ลำดับ</strong></div>
                                                    </th>
                                                    <th width="1%" nowrap rowspan="2">
                                                        <div align="center"><strong>ชื่อผลผลิต</strong></div>
                                                    </th>
                                                    <th width="1%" nowrap rowspan="2">
                                                        <div align="center"><strong>ผลการส่งเสริม</strong></div>
                                                    </th>
                                                    <th width="1%" nowrap rowspan="2">
                                                        <div align="center"><strong>เป้าหมาย</strong></div>
                                                    </th>
                                                    <th width="1%" nowrap rowspan="2">
                                                        <div align="center"><strong>หน่วยนับ</strong></div>
                                                    </th>
                                                    <th width="1%" nowrap rowspan="2">
                                                        <div align="center"><strong></strong></div>
                                                    </th>
                                                    <th width="1%">
                                                        <div align="center"><strong>ผลที่ได้ของปี&nbsp;<?php echo $_SESSION['year_round']; ?></strong></div>
                                                    </th>
                                                    <th width="" nowrap>
                                                        <div align="center"><strong>รายละเอียดการดำเนินงาน/ปัญหาและอุปสรรค</strong></div>
                                                    </th>
                                                </tr>
                                                <tr class="bgHead">
                                                    <th width="1%" nowrap colspan="2">
                                                        <div align="center"><strong><?php echo $month[$smho * 1] . $syho; ?></strong></div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if ($num_rowso > 0) {
                                                    $i = 1;
                                                    $queryo = $db->query($sqlo);
                                                    while ($reco = $db->db_fetch_array($queryo)) {
                                                ?>
                                                        <tr bgcolor="#FFFFFF">
                                                            <td align="center"><?php echo $i; ?>.
                                                            </td>
                                                            <td align="left"><?php echo text($reco['PRJP_PRODUCT_NAME']); ?>
                                                            </td>
                                                            <td align="left"><?php if ($reco['UNIT_ID'] != '') {
                                                                                    echo text($reco['TYPE_PRO_NAME']);
                                                                                } else {
                                                                                    echo  "อื่น ๆ";
                                                                                } ?> <?php echo "(" . number_format($arr_pval_so[$reco['PRJP_PRODUCT_ID']], 2) . ")"; ?>
                                                            </td>
                                                            <td align="left"><?php echo number_format($reco['GOAL_VALUE'], 2); ?></td>
                                                            <td align="left"><?php if ($reco['UNIT_ID'] != '') {
                                                                                    echo text($reco['UNIT_NAME_TH']);
                                                                                } else {
                                                                                    echo text($reco['UNIT_PRO_NAME']);
                                                                                } ?>
                                                            <td align="center">ผล</td>
                                                            <td align="center">
                                                                <?php echo number_format($arr_pvalo[$reco['PRJP_PRODUCT_ID']][$val], 2); ?></td>
                                                            <td align="left">
                                                                รายละเอียดการดำเนินงาน :
                                                                <br />
                                                                <?php echo text($arr_desco[$reco['PRJP_PRODUCT_ID']][$val]); ?><br>
                                                                ปัญหาและอุปสรรค :
                                                                <br />
                                                                <?php echo text($arr_ricko[$reco['PRJP_PRODUCT_ID']][$val]); ?><br>
                                                                แนวทางแก้ไข :
                                                                <br />
                                                                <?php echo text($arr_solutiono[$reco['PRJP_PRODUCT_ID']][$val]); ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                        $i++;
                                                    }
                                                } else {
                                                    echo "<tr><td align=\"center\" colspan=\"14\">ไม่พบข้อมูล</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                            <br>

                            <div class="col-xs-12 col-sm-12 tb_v" id="show_rb_old_99999">
                                <div class="">
                                    <div style="margin-bottom:10px;"></div>
                                    <table width="22%" class="table table-bordered table-striped table-hover table-condensed" id="tb_file_product_old">
                                        <thead>
                                            <tr class="bgHead">
                                                <th width="2%" rowspan="2">
                                                    <div align="center"><strong>ลำดับ</strong></div>
                                                </th>
                                                <th width="20%" rowspan="2">
                                                    <div align="center"><strong>ชื่อไฟล์</strong></div>
                                                </th>
                                                <th width="20%" rowspan="2">
                                                    <div align="center"><strong>ไฟล์</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2">
                                                    <div align="center"><strong>จัดการ</strong></div>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 0;
                                            $sql_file = "select * from prjp_product_file where PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "' order by PRODUCT_FILE_ID ASC";
                                            $query_file = $db->query($sql_file);
                                            while ($rec_file = $db->db_fetch_array($query_file)) {
                                                $i++;
                                            ?>
                                                <tr>
                                                    <td align="center"><?php echo $i; ?></td>
                                                    <td>
                                                        <?php echo text($rec_file['PRODUCT_FILE_NAME']); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $rec_file['PRODUCT_FILE_NAME_TEMP']; ?>

                                                    </td>
                                                    <td align="center">
                                                        <a href="<?php echo $path_a . $rec_file['PRODUCT_FILE_NAME_TEMP']; ?>" target="_blank" title="donwload_file"><?php echo $img_download; ?></a>&nbsp;&nbsp;
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading row" style="">
                                    <div class="pull-left" style="">ข้อมูลการรายงานผลผลิต/ผลลัพธ์</div>
                                    <div class="pull-right" style="">สสว.200/1</div>
                                </div>
                                <div class="panel-body epm-gradient">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12">
                                            <ul class="nav nav-tabs visible-md visible-lg">
                                                <?php
                                                foreach ($m as $key => $val) {
                                                    $smh = substr($val, 4, 2);
                                                    $syh = substr($val, 2, 2);
                                                ?>
                                                    <li id="tablist_<?php echo $val; ?>" class="re_act"><a onClick="tab_list(<?php echo $val; ?>); reAct(<?php echo $val; ?>);"><?php echo $month[$smh * 1] . $syh; ?></a></li>
                                                    <input type="hidden" id="month" value="<?php echo $val; ?>">
                                                    <input type="hidden" id="chk_value" value="">
                                                <?php
                                                }
                                                ?>
                                                <li id="tablist_99999" class="re_act"><a onClick="tab_list('99999')">แนบไฟล์เพิ่มเติม</a></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <?php
                                    if ($_SESSION['sys_status_print'] == '1') {
                                        $print_form = "<a class=\"btn btn-info\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form1('" . $PRJP_ID . "');\">" . $img_print . "  พิมพ์ข้อมูลการรายงานผลผลิต สสว.200/1</a> ";
                                    }
                                    ?>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12"><?php echo $print_form; ?></div>
                                    </div>
                                    <?php
                                    foreach ($m as $key => $val) {
                                        $smh = substr($val, 4, 2);
                                        $syh = substr($val, 0, 4);
                                        $mck = $syh . sprintf("%'02d", $smh);

                                        if ($mck >= $ymchk) {
                                            $distxt = "disabled";
                                            $bgdis = "background:#9F9;";
                                        } else {
                                            $distxt = "";
                                            $bgdis = "";
                                        }
                                        //$chk_set
                                        if ($_SESSION["sys_group_id"] != '5') {
                                            if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
                                                $input = ($smh == 12 ? ($syh + 1) . '01' : $mck + 1) . date("d");
                                                if ($input >= $chk_set_start && $input <= $chk_set_end) {
                                                    $distxt = "";
                                                    $bgdis = "";
                                                } else {
                                                    $distxt = "readonly";
                                                    $bgdis = "background:#9F9;";
                                                }
                                            } else {
                                                if (($smh == 12 ? ($syh + 1) . '01' : $mck + 1) == ($ymchk)) {
                                                    if (in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                                                        $distxt = "";
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

                                        $distxt .= " {$de_set} ";
                                    ?>
                                        <div class="col-xs-12 col-sm-12 tb_v" id="show_rb_<?php echo $val; ?>">
                                            <div class="">
                                                <input name="YEAR_C[<?php echo $val; ?>]" id="YEAR_C_<?php echo $val; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($val); ?>">
                                                <table width="100%" class="table table-bordered table-striped table-hover table-condensed">
                                                    <thead>
                                                        <tr class="bgHead">
                                                            <th width="1%" nowrap rowspan="2">
                                                                <div align="center"><strong>ลำดับ</strong></div>
                                                            </th>
                                                            <th width="1%" nowrap rowspan="2">
                                                                <div align="center"><strong>ชื่อผลผลิต</strong></div>
                                                            </th>
                                                            <th width="1%" nowrap rowspan="2">
                                                                <div align="center"><strong>ผลการส่งเสริม</strong></div>
                                                            </th>
                                                            <th width="1%" nowrap rowspan="2">
                                                                <div align="center"><strong>เป้าหมาย</strong></div>
                                                            </th>
                                                            <th width="1%" nowrap rowspan="2">
                                                                <div align="center"><strong>ผลสะสม</strong></div>
                                                            </th>
                                                            <th width="1%" nowrap rowspan="2">
                                                                <div align="center"><strong>หน่วยนับ</strong></div>
                                                            </th>
                                                            <th width="1%" nowrap rowspan="2">
                                                                <div align="center"><strong></strong></div>
                                                            </th>
                                                            <th width="1%" nowrap>
                                                                <div align="center"><strong>ผลที่ได้ของปี&nbsp;<?php echo $_SESSION['year_round']; ?></strong></div>
                                                            </th>
                                                            <th width="" nowrap>
                                                                <div align="center"><strong>รายละเอียดการดำเนินงาน/ปัญหาและอุปสรรค</strong></div>
                                                            </th>

                                                        </tr>
                                                        <tr class="bgHead">
                                                            <th width="1%" nowrap colspan="2">
                                                                <div align="center"><strong><?php echo $month[$smh * 1] . $syh; ?></strong></div>
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="body">
                                                        <?php
                                                        if ($num_rows > 0) {
                                                            $i = 1;
                                                            $query = $db->query($sql);
                                                            while ($rec = $db->db_fetch_array($query)) {

                                                        ?>
                                                                <tr bgcolor="#FFFFFF">
                                                                    <td align="center"><?php echo $i; ?>.
                                                                        <input type="hidden" id="PRJP_PRODUCT_ID_<?php echo $val; ?>" name="PRJP_PRODUCT_ID[<?php echo $val; ?>][<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" value="<?php echo $rec['PRJP_PRODUCT_ID']; ?>">
                                                                        <input type="hidden" id="PRJP_PRODUCT_ID_DEL" name="PRJP_PRODUCT_ID_DEL[]" value="<?php echo $rec['PRJP_PRODUCT_ID']; ?>">
                                                                    </td>
                                                                    <td align="left"><textarea rows="9" cols="15" style="border:none;background: transparent;resize: none; width:auto;" disabled><?php echo text($rec['PRJP_PRODUCT_NAME']); ?></textarea>
                                                                        <input type="hidden" id="PRJP_PRODUCT_NAME[]" name="PRJP_PRODUCT_NAME[<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" value="<?php echo text($rec['PRJP_PRODUCT_NAME']); ?>">
                                                                    </td>
                                                                    <td align="left"><?php echo text($rec['TYPE_PRO_NAME']); ?>
                                                                        <input type="hidden" id="TYPE_PRO_ID[]" name="TYPE_PRO_ID[<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" value="<?php echo $rec['TYPE_PRO_ID']; ?>">
                                                                    </td>

                                                                    <td align="left"><?php echo number_format($rec['GOAL_VALUE'], 2); ?></td>
                                                                    <td align="center"><?php echo number_format($arr_pval_s[$rec['PRJP_PRODUCT_ID']] + $arr_pval_son[$rec['PRJP_PRODUCT_OLD_ID']], 2); ?></td>
                                                                    <td align="left"><?php if ($rec['UNIT_ID'] != '') {
                                                                                            echo text($rec['UNIT_NAME_TH']);
                                                                                        } else {
                                                                                            echo text($rec['UNIT_PRO_NAME']);
                                                                                        } ?>
                                                                        <input type="hidden" id="UNIT_ID[]" name="UNIT_ID[<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" value="<?php echo $rec['UNIT_ID']; ?>">
                                                                    <td align="center">ผล</td>
                                                                    <td align="center">

                                                                        <input name="YEAR[<?php echo $val; ?>][<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" id="YEAR_<?php echo $val; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($syh); ?>">
                                                                        <input <?php echo $disables_txt; ?> name="PLAN_VALUE[<?php echo $val; ?>][<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" id="PLAN_VALUE_<?php echo $val; ?>" type="text" size="5" class="form-control number_format" value="<?php echo number_format($arr_pval[$rec['PRJP_PRODUCT_ID']][$val], 2); ?>" onBlur="NumberFormat(this, 2);" style="text-align:right">
                                                                    </td>
                                                                    <td align="left">
                                                                        รายละเอียดการดำเนินงาน :
                                                                        <br />
                                                                        <textarea name="DESC_NAME[<?php echo $val; ?>][<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" <?php echo $readonly_txt; ?> id="DESC_NAME_<?php echo $val; ?>" rows="3"><?php echo text($arr_desc[$rec['PRJP_PRODUCT_ID']][$val]); ?></textarea>
                                                                        <br />
                                                                        ปัญหาและอุปสรรค :
                                                                        <br />
                                                                        <textarea name="RICK_NAME[<?php echo $val; ?>][<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" <?php echo $readonly_txt; ?> id="RICK_NAME_<?php echo $val; ?>" cols="50" rows="3"><?php echo text($arr_rick[$rec['PRJP_PRODUCT_ID']][$val]); ?></textarea>
                                                                        <br />
                                                                        แนวทางแก้ไข :
                                                                        <br />
                                                                        <textarea name="SOLUTION_NAME[<?php echo $val; ?>][<?php echo $rec['PRJP_PRODUCT_ID']; ?>]" <?php echo $readonly_txt; ?> id="SOLUTION_NAME_<?php echo $val; ?>" cols="50" rows="3"><?php echo text($arr_solution[$rec['PRJP_PRODUCT_ID']][$val]); ?></textarea>

                                                                    </td>
                                                                </tr>
                                                        <?php
                                                                $i++;
                                                            }
                                                        } else {
                                                            echo "<tr><td align=\"center\" colspan=\"14\">ไม่พบข้อมูล</td></tr>";
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <?php
                                                if ($CHK_BDG_TYPE_ID != 4) {
                                                    include("disp_project_send_result_copy_r.php");
                                                }
                                                ?>


                                            </div>
                                        </div>
                                    <?php } ?>




                                    <div class="col-xs-12 col-sm-12 tb_v" id="show_rb_99999">
                                        <div class="">
                                            <table width="22%" class="table table-bordered table-striped table-hover table-condensed" id="tb_file_product">
                                                <thead>
                                                    <tr class="bgHead">
                                                        <th width="2%" rowspan="2">
                                                            <div align="center"><strong>ลำดับ</strong></div>
                                                        </th>
                                                        <th width="20%" rowspan="2">
                                                            <div align="center"><strong>ชื่อไฟล์</strong></div>
                                                        </th>
                                                        <th width="20%" rowspan="2">
                                                            <div align="center"><strong>ไฟล์</strong></div>
                                                        </th>
                                                        <th width="5%" rowspan="2">
                                                            <div align="center"><strong>จัดการ</strong></div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    $sql_file = "select * from prjp_product_file where PRJP_ID = '" . $PRJP_ID . "' order by PRODUCT_FILE_ID ASC";
                                                    $query_file = $db->query($sql_file);
                                                    while ($rec_file = $db->db_fetch_array($query_file)) {
                                                        $i++;
                                                    ?>
                                                        <tr>
                                                            <td align="center"><?php echo $i; ?></td>
                                                            <td>
                                                                <?php echo text($rec_file['PRODUCT_FILE_NAME']); ?>

                                                                <input type="hidden" id="PRODUCT_FILE_NAME_<?php echo $i; ?>" name="PRODUCT_FILE_NAME[]" value="<?php echo text($rec_file['PRODUCT_FILE_NAME']); ?>">
                                                                <input type="hidden" id="PRODUCT_FILE_SIZE_<?php echo $i; ?>" name="PRODUCT_FILE_SIZE[]" value="<?php echo $rec_file['PRODUCT_FILE_SIZE']; ?>">
                                                                <input type="hidden" id="PRODUCT_FILE_TYPE_<?php echo $i; ?>" name="PRODUCT_FILE_TYPE[]" value="<?php echo $rec_file['PRODUCT_FILE_TYPE']; ?>">
                                                            </td>
                                                            <td>

                                                                <input type="file" id="PRODUCT_FILE_<?php echo $i; ?>" name="PRODUCT_FILE[]" class="form-control">
                                                                <input type="hidden" id="OLD_PRODUCT_FILE_<?php echo $i; ?>" name="OLD_PRODUCT_FILE[]" value="<?php echo $rec_file['PRODUCT_FILE_NAME_TEMP']; ?>">

                                                            </td>
                                                            <td align="center">
                                                                <a class="btn btn-info" href="<?php echo $path_a . $rec_file['PRODUCT_FILE_NAME_TEMP']; ?>" target="_blank" title="donwload_file"><?php echo $img_download; ?> ดาวน์โหลด</a>&nbsp;&nbsp;
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php include("disp_project_send_act_task_copy_r.php");

                    if (!empty($chk_set) || in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])) {
                        $distxt =  '';
                    } else {
                        $distxt =  'readonly';
                    }
                    // if(!empty($chk_set2)){
                    // if(($date_now <= $chk_set2) || in_array(date('d'), $ARR_CHK_REPORT_MONTH_DATE[date('m')])){
                    // $distxt = '';
                    // }else{
                    // $distxt =  'readonly';
                    // }
                    // }else{
                    // $distxt =  'readonly';
                    // }
                    ?>

                   

                </form>
            </div>
        </div>
        <?php include($path . "include/footer.php"); ?>
    </div>
</body>
</html>
<?php //echo form_model('myModal','ปัญหา-อุปสรรค','show_display','','','1');
?>
<?php //echo form_model1('myModal1','เลือกวันที่ออกรายงาน','show_display1','','','1');
?>
<?php echo form_model('myModal1', 'เลือกวันที่ออกรายงาน', 'show_display', '', '', '1'); ?>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<div class="modal fade" id="myModal1"></div>

<!-- /.modal -->