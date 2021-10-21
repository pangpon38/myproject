<?php
session_start();
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$disables_txt = "disabled";
$readonly_txt = "readonly";
$ACT = '29';

if ($_GET['proc'] == 'save_risk') {
	$report_detail = $_POST['report_detail'];
	if (count($report_detail) > 0) {
		foreach ($report_detail as $proj_id => $val) {
			$db->query(" delete from service_task_plan_risk_temp where main_project_id = '" . $proj_id . "' ");
			$db->query("insert into service_task_plan_risk_temp
						(project_id, risk_name, risk_grade, risk_manage, risk_response_by, risk_cost, main_project_id, task_risk_id, report_detail)
						(SELECT project_id, risk_name, risk_grade, risk_manage, risk_response_by, risk_cost, main_project_id, task_risk_id, '" . ctext($val) . "' 
						FROM service_task_plan_risk WHERE main_project_id = '" . $proj_id . "')");

			/*$fields = array('report_detail' => ctext($val) );
			$db->db_update("service_task_plan_risk", $fields, " project_id = '" . $proj_id . "' ");*/
		}
	}
	echo 'บันทึกข้อมูลเรียบร้อย';
	exit;
}

if ($_POST['PRJP_ID'] != '') {
	$PRJP_ID = $_POST['PRJP_ID'];
} else {
	$PRJP_ID = $PRJP_ID;
}
$month_full = array("1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม");
$month_full_bdg = array("10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม", "1" => "มกราคม", "2" => "กุมภาพันธ์", "3" => "มีนาคม", "4" => "เมษายน", "5" => "พฤษภาคม", "6" => "มิถุนายน", "7" => "กรกฎาคม", "8" => "สิงหาคม", "9" => "กันยายน");

$sql_head = "SELECT SERVICE_PROJECT_ID, SDATE_PRJP, EDATE_PRJP FROM prjp_project WHERE PRJP_ID = '" . $PRJP_ID . "' ";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$month = array("10" => "ต.ค.", "11" => "พ.ย.", "12" => "ธ.ค.", "1" => "ม.ค.", "2" => "ก.พ.", "3" => "มี.ค.", "4" => "เม.ย.", "5" => "พ.ค.", "6" => "มิ.ย.", "7" => "ก.ค.", "8" => "ส.ค.", "9" => "ก.ย.");

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

$sql = "SELECT * FROM service_task_plan_risk WHERE main_project_id = '" . $rec_head['SERVICE_PROJECT_ID'] . "' ";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);

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
				<li><a href="disp_send_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>">นำเข้าผลโครงการ</a></li>
				<li class="active">รายละเอียดแนวทางในการบริหารความเสี่ยง</li>
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

					<div class="row">
						<div class="col-xs-12 col-sm-12"><?php include("tab_menu2_r.php"); ?></div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12"> </div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 font-blue" align="center">
							<strong><?php echo $rec_head['PRJP_CODE'] . " " . text($rec_head['PRJP_NAME']) ?></strong>
						</div>
					</div>

					<div class="row page-prjp-money">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading row" style="">
									<div class="pull-left" style="">แนวทางในการบริหารความเสี่ยง</div>
								</div>
								<div class="panel-body epm-gradient">
									<?php
									$mstart = 0;
									$mend = 11;
									$rowtb = 0;
									for ($row = 1; $row <= $row_wh; $row++) { ?>
										<div id="tb_<?php echo $row; ?>" class=" col-xs-12 col-sm-12 htb" style="">
											<table width="22%" class="table table-bordered table-striped table-hover table-condensed">
												<thead>
													<tr class="bgHead table-head-money">
														<th width="40px">
															<div align="center"><strong>ลำดับ</strong></div>
														</th>
														<th width="">
															<div align="center"><strong>ความเสี่ยง</strong></div>
														</th>
														<th width="">
															<div align="center"><strong>เกรดที่ได้รับ</strong></div>
														</th>
														<th width="">
															<div align="center"><strong>แนวทางในการบริหารความเสี่ยง</strong></div>
														</th>
														<th width="">
															<div align="center"><strong>ผู้ที่รับผิดชอบต่อการนำแนวทางไปใช้</strong></div>
														</th>
														<th width="">
															<div align="center"><strong>ต้นทุนที่จะเกิดขึ้น จากการนำแนวทางมาใช้</strong></div>
														</th>
														<th width="">
															<div align="center"><strong>ข้อมูลการวิเคราะห์ความเสี่ยงของงาน</strong></div>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													if ($num_rows > 0) {
														$l = 1;
														$query = $db->query($sql);
														while ($rec = $db->db_fetch_array($query)) {
													?>
															<tr bgcolor="#FFFFFF" class="">
																<td align="center" width="40px"><?php echo $l; ?>.</td>
																<td align="left">
																	<textarea <?php echo $readonly_txt ?> rows="3" style="width:100%; min-width:100px;" class="prjp-name-show" disabled=""><?php echo text($rec['risk_name']); ?></textarea>
																</td>
																<td align="left" style="width:120px !important;">
																	<textarea <?php echo $readonly_txt ?> rows="3" style="width:100%; min-width:100px;" class="prjp-name-show" disabled=""><?php echo text($rec['risk_grade']); ?></textarea>
																</td>
																<td align="left">
																	<textarea <?php echo $readonly_txt ?> rows="3" style="width:100%; min-width:100px;" class="prjp-name-show" disabled=""><?php echo text($rec['risk_manage']); ?></textarea>
																</td>
																<td align="left">
																	<textarea <?php echo $readonly_txt ?> rows="3" style="width:100%; min-width:100px;" class="prjp-name-show" disabled=""><?php echo text($rec['risk_response_by']); ?></textarea>
																</td>
																<td align="left">
																	<textarea <?php echo $readonly_txt ?> rows="3" style="width:100%; min-width:100px;" class="prjp-name-show" disabled=""><?php echo text($rec['risk_cost']); ?></textarea>
																</td>
																<td align="left">
																	<textarea <?php echo $readonly_txt ?> rows="3" style="width:100%;" name="report_detail[<?php echo $rec['project_id'] ?>]" placeholder="ข้อมูลการวิเคราะห์ความเสี่ยงของงาน"><?php echo text($rec['report_detail']); ?></textarea>
																</td>
															</tr>
													<?php
															$l++;
														}
													} else {
														echo "<tr><td align=\"center\" colspan=\"5\">ไม่พบข้อมูล</td></tr>";
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
				</form>
			</div>
		</div>
		<?php include($path . "include/footer.php"); ?>
	</div>
</body>

</html>