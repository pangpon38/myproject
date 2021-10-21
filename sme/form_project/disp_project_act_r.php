<?php
@session_start();
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '1';

$disables_txt = "disabled";
$readonly_txt = "readonly";

if ($_POST['PRJP_ID'] != '') {
	$PRJP_ID = $_POST['PRJP_ID'];
} else {
	$PRJP_ID = $PRJP_ID;
}
$sql_head = "SELECT PRJP_ID,PRJP_CODE,PRJP_NAME,PRJP_CON_ID,MONEY_BDG,BDG_TYPE_ID,PRJP_STATUS, ISNULL(SERVICE_PROJECT_ID, 0) as SERVICE_PROJECT_ID FROM prjp_project WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$filter == "";
if ($_POST['s_round_year_bud'] != "") {
	$filter .= " AND PRJP_NAME LIKE '%" . $_POST['s_round_year_bud'] . "%' ";
}
$field = "a.PRJP_ID,
			a.ORDER_NO,
			a.PRJP_CODE,
			a.PRJP_NAME,
			a.MONEY_BDG,
			a.UNIT_NAME,
			a.PRJP_LEVEL,
			(select RULE_NAME FROM setup_rule where setup_rule.RULE_ID = a.RULE_ID)as RULE_NAME,
			(select UNIT_NAME_TH FROM setup_unit where setup_unit.UNIT_ID = a.UNIT_ID)as UNIT_NAME_LIST,
			a.WEIGHT,
			a.TRAGET_VALUE,
			a.COST_TYPE
			";
$table = " prjp_project a ";
$join = " ";
$pk_id = "PRJP_ID";

$wh = " 1=1 AND PRJP_LEVEL = '2' AND PRJP_PARENT_ID = '" . $PRJP_ID . "' {$filter}";
$orderby = " order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO ";
$groupby = " ";
//$sql = sqlpaging($field, $table." ".$join, $wh, $notInto, $groupby, $orderby, $page_size, $page, $pk_id ,$distinct = 0);
$sql = "SELECT " . $field . " FROM " . $table . " " . $join . " WHERE " . $wh . " " . $groupby . " " . $orderby;
// echo $sql; exit;
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);
$total_record = $db->db_num_rows($db->query("select " . $field . " from " . $table . " " . $join . " where " . $wh . "" . $groupby));

function cur_activity($id, $num)
{
	global $db, $img_save, $img_edit, $img_del, $PRJP_ID;

	$i = 1;
	$field3 = " * ";
	$table3 = " prjp_project ";

	$wh3 = " 1=1 and prjp_project.PRJP_PARENT_ID = '" . $id . "'";
	$sql3 = "select " . $field3 . " from " . $table3 . " where " . $wh3 . " order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO ";
	$query3 = $db->query($sql3);
	while ($row3 = $db->db_fetch_array($query3)) {
		//$add = "<a data-toggle=\"modal\"  data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"addData('".$PRJP_ID."','".$row3['PRJP_ID']."','".($row3['PRJP_LEVEL']+1)."');\">".$img_save." เพิ่มกิจกรรมภายใต้</a> ";
		$edit = "<a data-toggle=\"modal\"  data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"editData('" . $PRJP_ID . "','" . $row3['PRJP_ID'] . "','" . ($row3['PRJP_LEVEL']) . "');\">" . $img_edit . " แก้ไข</a> ";
		if ($_SESSION['sys_status_del'] == '1') {
			$delete = "<a onClick=\"delData('" . $PRJP_ID . "','" . $row3['PRJP_ID'] . "');\">" . $img_del . " ลบ</a> ";
		}

		echo '<tr bgcolor="#FFFFFF">
			  <td align="center">' . $row3['ORDER_NO'] . '</td>
			  <td align="left" style="padding-left:' . (($row3['PRJP_LEVEL'] - 1) * 20) . 'px">' . text($row3['PRJP_NAME']) . '</td>
			  <td align="right">' . text($row3['TRAGET_VALUE']) . '</td>
			  <td align="right">' . text($row3['UNIT_NAME']) . '</td>
			  <td align="right">' . number_format($row3['MONEY_BDG']) . '</td>
			  <td align="center">' . text($row3['WEIGHT']) . '</td>
			  <td align="center">
				<div>
					<div>
						<div class="btn-group">                 
								<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
									<span>เครื่องมือ </span>
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu pull-right" style="text-align:left;">
								  <li>' . $add . '</li>
								  <li>' . $edit . '</li>
								  <li>' . $delete . '</li>
								</ul>
						 </div>
					</div>
				</div>
			  </td>
			</tr>';

		$field4 = "*";
		$table4 = " prjp_project ";
		$wh4 = " 1=1 and PRJP_PARENT_ID = '" . $row3['PRJP_ID'] . "'";
		$sql4 = "select " . $field4 . " from " . $table4 . " where " . $wh4;
		$query4 = $db->query($sql4);
		$num4 = $db->db_num_rows($query4);
		if ($num4 > 0) {
			cur_activity($row3['PRJP_ID'], $num . '.' . $i);
		} //if

		$i++;
	} //while
} //function
///////////////////////// กิจกรรมเก่า ////////////////////////
if ($rec_head['PRJP_CON_ID'] != '') {
	$sql_head_old = "SELECT PRJP_ID,PRJP_CODE,PRJP_NAME FROM prjp_project WHERE PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "' AND PRJP_LEVEL = '1' ";
	$query_head_old = $db->query($sql_head_old);
	$rec_head_old = $db->db_fetch_array($query_head_old);

	$sql_act_old = "select 	PRJP_ID,
									ORDER_NO,
									PRJP_CODE,
									PRJP_NAME,
									MONEY_BDG,
									UNIT_NAME,
									PRJP_LEVEL,
									(select RULE_NAME FROM setup_rule where setup_rule.RULE_ID = prjp_project.RULE_ID)as RULE_NAME,
									(select UNIT_NAME_TH FROM setup_unit where setup_unit.UNIT_ID = prjp_project.UNIT_ID)as UNIT_NAME_LIST,
									WEIGHT,
									TRAGET_VALUE
							from prjp_project 	
							WHERE 1=1 AND PRJP_PARENT_ID = '" . $rec_head_old['PRJP_ID'] . "' AND PRJP_LEVEL = '2' order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO ";
	$query_act_old = $db->query($sql_act_old);
	$num_rows_act_old = $db->db_num_rows($query_act_old);

	function cur_activity_old($id, $num)
	{
		global $db, $img_save, $img_edit, $img_del, $PRJP_ID;

		$i = 1;
		$field3 = " * ";
		$table3 = " prjp_project ";

		$wh3 = " 1=1 and prjp_project.PRJP_PARENT_ID = '" . $id . "'";
		$sql3 = "select " . $field3 . " from " . $table3 . " where " . $wh3;
		$query3 = $db->query($sql3);
		while ($row3 = $db->db_fetch_array($query3)) {
			//$add = "<a data-toggle=\"modal\"  data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"addData('".$PRJP_ID."','".$row3['PRJP_ID']."','".($row3['PRJP_LEVEL']+1)."');\">".$img_save." เพิ่มกิจกรรมภายใต้</a> ";
			//$edit = "<a data-toggle=\"modal\"  data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"editData('".$PRJP_ID."','".$row3['PRJP_ID']."','".($row3['PRJP_LEVEL'])."');\">".$img_edit." แก้ไข</a> ";
			//$delete = "<a onClick=\"delData('".$PRJP_ID."','".$row3['PRJP_ID']."');\">".$img_del." ลบ</a> ";

			echo '<tr bgcolor="#FFFFFF">
                  <td align="center">' . $num . '.' . $i . '</td>
				  <td align="left" style="padding-left:' . (($row3['PRJP_LEVEL'] - 1) * 20) . 'px">' . text($row3['PRJP_NAME']) . '</td>
				  <td align="right">' . text($row3['TRAGET_VALUE']) . '</td>
				  <td align="right">' . text($row3['UNIT_NAME']) . '</td>
				  <td align="right">' . number_format($row3['MONEY_BDG']) . '</td>
				  <td align="center">' . text($row3['WEIGHT']) . '</td>
                </tr>';

			$field4 = "*";
			$table4 = " prjp_project ";
			$wh4 = " 1=1 and PRJP_PARENT_ID = '" . $row3['PRJP_ID'] . "'";
			$sql4 = "select " . $field4 . " from " . $table4 . " where " . $wh4;
			$query4 = $db->query($sql4);
			$num4 = $db->db_num_rows($query4);
			if ($num4 > 0) {
				cur_activity_old($row3['PRJP_ID'], $num . '.' . $i);
			} //if

			$i++;
		} //while

	} //function
}
?>
<!DOCTYPE html>
<html>

<head>
	<?php include($path . "include/inc_main_top.php"); ?>
	<script src="js/disp_project_act.js?<?php echo rand(); ?>"></script>
	<script type="text/javascript">
		function chk_old(id) {
			if (id == 0) {
				$("#hide_old").val(1);
			} else if (id == 1) {
				$("#hide_old").val(2);
			} else if (id == 2) {
				$("#hide_old").val(1);
			}
			id = $("#hide_old").val();
			show_old(id);
		}

		function show_old(id) {
			if (id == 1) {
				$("#data_old").show();
				$("#data_old2").show();
				$("#TB_BR").html("<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />");
			} else {
				$("#data_old").hide();
				$("#data_old2").hide();
				$("#TB_BR").html("");
			}

		}
	</script>
</head>

<body>
	<div class="container-full">
		<div><?php include($path . "include/header.php"); ?></div>
		<div class="col-xs-12 col-sm-12">
			<ol class="breadcrumb">
				<li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
				<li><a href="disp_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
				<li class="active">รายละเอียดกิจกรรม</li>
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
					<input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
					<input type="hidden" id="PRJP_STATUS" name="PRJP_STATUS" value="<?php echo $rec_head['PRJP_STATUS']; ?>">
					<input type="hidden" id="SERVICE_PROJECT_ID" name="SERVICE_PROJECT_ID" value="<?php echo $rec_head['SERVICE_PROJECT_ID']; ?>">
					<input type="hidden" id="PRJP_PARENT_ID" name="PRJP_PARENT_ID">
					<input type="hidden" id="PRJP_ROOT_ID" name="PRJP_ROOT_ID" value="<?php echo $rec_head['PRJP_ID']; ?>">
					<input type="hidden" id="PRJP_LEVEL" name="PRJP_LEVEL">
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
									<!--<div class="row">
					<div class="col-md-12">
					<select id="S_MONTH" name="S_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;" >
					<?php
					foreach ($month_full_bdg as $key_mfull => $val_mfull) {
					?>
					<option value="<?php echo $key_mfull; ?>"><?php echo $val_mfull; ?></option>
					<?php
					}
					?>
					</select>
					</div>
					</div>-->
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
					<?php
					/*if($_SESSION["sys_group_id"]=='5' || $_SESSION["sys_group_id"]=='9'){
				?>
                <div class="col-xs-12 col-sm-12"><?php include("tab_menu2.php");?></div><br />
				<div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php");?></div><br /><br />
				<?php 
				}*/
					?>
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
							<div class="col-xs-12 col-sm-12 col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading" style="">
										<div class="pull-left" style="">กิจกรรมเก่า</div>
										<div class="pull-right" style=""></div>
									</div>
									<div class="panel-body epm-gradient">
										<div class="row">
											<div class="col-xs-12 col-sm-12" align="center">
												<input type="hidden" id="hide_old" name="hide_old" value="0">
												<a href="#" onClick="chk_old(hide_old.value);"><?php echo $img_save; ?> กิจกรรมเก่า</a>
											</div>
										</div>
										<div class=" col-xs-12 col-sm-12" style="display:none;" id="data_old">
											<table width="100%">
												<tr>
													<td align="center">
														<font size="+1"><?php echo $rec_head_old['PRJP_CODE'] . " " . text($rec_head_old['PRJP_NAME']) ?></font>
													</td>
												</tr>
											</table>
											<br />

											<div class="col-xs-12 col-sm-12">
												<div class="">
													<table width="22%" class="table table-bordered table-striped table-hover table-condensed">
														<thead>
															<tr class="bgHead">
																<th width="2%">
																	<div align="center"><strong>ลำดับ</strong></div>
																</th>
																<th width="25%">
																	<div align="center"><strong>กิจกรรม</strong></div>
																</th>
																<th width="5%">
																	<div align="center"><strong>เป้าหมาย</strong></div>
																</th>
																<th width="5%">
																	<div align="center"><strong>หน่วยนับ</strong></div>
																</th>
																<th width="10%">
																	<div align="center"><strong>วงเงินงบประมาณกิจกรรม</strong></div>
																</th>
																<th width="5%">
																	<div align="center"><strong>น้ำหนัก(%)</strong></div>
																</th>
															</tr>
														</thead>
														<tbody>
															<?php
															if ($num_rows_act_old > 0) {
																$tg_old = 0;
																$mg_old = 0;
																$wg_old = 0;
																$i = 1;
																while ($rec_act_old = $db->db_fetch_array($query_act_old)) {
															?>
																	<tr bgcolor="#FFFFFF">
																		<td align="center"><?php echo $rec_act_old['ORDER_NO']; ?>.</td>
																		<td align="left"><?php echo text($rec_act_old['PRJP_CODE']) . " " . text($rec_act_old['PRJP_NAME']); ?></td>
																		<td align="right"><?php echo number_format($rec_act_old['TRAGET_VALUE']); ?></td>
																		<td align="right"><?php echo text($rec_act_old['UNIT_NAME']); ?></td>
																		<td align="right"><?php echo number_format($rec_act_old['MONEY_BDG']); ?></td>
																		<td align="center"><?php echo ($rec_act_old['WEIGHT']); ?></td>
																	</tr>
																<?php
																	cur_activity_old($rec_act_old['PRJP_ID'], $i);
																	$tg_old += $rec_act_old['TRAGET_VALUE'];
																	$mg_old += $rec_act_old['MONEY_BDG'];
																	$wg_old += $rec_act_old['WEIGHT'];
																	$i++;
																}
																?>
																<tr bgcolor="#FFFFFF">
																	<td align="right" colspan="2">รวม</td>
																	<td align="right">&nbsp;</td>
																	<td align="right">&nbsp;</td>
																	<td align="right"><?php echo number_format($mg_old); ?></td>
																	<td align="center"><?php echo number_format($wg_old); ?></td>
																</tr>
															<?php

															} else {
															?>
																<tr>
																	<td colspan="7" align="center">ไม่พบข้อมูล</td>
																</tr>
															<?php }
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading row" style="">
									<div class="pull-left" style="">กิจกรรม</div>
									<div class="pull-right" style="">สสว. 100</div>
								</div>
								<div class="panel-body epm-gradient">
									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<?php if ($_SESSION['sys_status_add'] == '1') { ?>
												<?php if ($rec_head['PRJP_STATUS'] == '2' || $rec_head['PRJP_STATUS'] == '3' || $rec_head['SERVICE_PROJECT_ID'] == '0') { ?>
													<a data-toggle="modal" class="btn btn-default" data-backdrop="static" href="javascript:void(0);" onClick="addData(<?php echo $PRJP_ID; ?>,<?php echo $PRJP_ID; ?>,2);"><?php echo $img_save; ?> เพิ่มกิจกรรม</a>
												<?php } ?>
												<a class="btn btn-primary" href="javascript:void(0);" onClick="Print_form('<?php echo $PRJP_ID; ?>');"><?php echo $img_save; ?> พิมพ์ สสว.100</a>
											<?php } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<div class="">
												<table width="22%" class="table table-bordered table-striped table-hover table-condensed">
													<thead>
														<tr class="bgHead">
															<th width="" nowrap>
																<div align="center"><strong>ลำดับ</strong></div>
															</th>
															<th width="100%" nowrap>
																<div align="center"><strong>กิจกรรม</strong></div>
															</th>
															<th>
																<div align="center"><strong>ประเภทค่าใช้จ่าย</strong></div>
															</th>
															<th width="" nowrap>
																<div align="center"><strong>เป้าหมาย</strong></div>
															</th>
															<th width="" nowrap>
																<div align="center"><strong>หน่วยนับ</strong></div>
															</th>
															<th width="" nowrap>
																<div align="center"><strong>วงเงิน<br />งบประมาณ<br />กิจกรรม</strong></div>
															</th>
															<th width="" nowrap>
																<div align="center"><strong>น้ำหนัก(%)</strong></div>
															</th>
														</tr>
													</thead>
													<tbody>
														<?php
														if ($num_rows > 0) {
															$tg = 0;
															$mg = 0;
															$wg = 0;
															$i = 1;
															$query = $db->query($sql);
															while ($rec = $db->db_fetch_array($query)) {
																if ($_SESSION['sys_status_add'] == '1' && ($rec_head['PRJP_STATUS'] == '2' || $rec_head['PRJP_STATUS'] == '3' || $rec_head['SERVICE_PROJECT_ID'] == '0')) {
																	$add_sub = "<a class=\"btn btn-primary\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"addData('" . $PRJP_ID . "','" . $rec['PRJP_ID'] . "','3');\">" . $img_save . " เพิ่มกิจกรรมภายใต้</a> ";
																}

																$edit = "<a class=\"btn btn-warning\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"editData('" . $PRJP_ID . "','" . $rec['PRJP_ID'] . "','" . $rec['PRJP_LEVEL'] . "');\">" . $img_edit . " แก้ไข</a> ";

																if ($_SESSION['sys_status_del'] == '1' && ($rec_head['PRJP_STATUS'] == '2' || $rec_head['PRJP_STATUS'] == '3' || $rec_head['SERVICE_PROJECT_ID'] == '0')) {
																	$delete = "<a class=\"btn btn-danger\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"delData('" . $PRJP_ID . "','" . $rec['PRJP_ID'] . "');\">" . $img_del . " ลบ</a>";
																}
														?>
																<tr bgcolor="#FFFFFF">
																	<td align="center" nowrap><?php echo $rec['ORDER_NO']; ?>.</td>
																	<td align="left"><?php echo text($rec['PRJP_CODE']) . " " . text($rec['PRJP_NAME']); ?></td>
																	<td align="center" nowrap><?php echo $arr_bdg_cost[$rec['COST_TYPE']] ?></td>
																	<td align="right" nowrap><?php echo number_format($rec['TRAGET_VALUE']); ?></td>
																	<td align="right" nowrap><?php echo text($rec['UNIT_NAME']); ?></td>
																	<td align="right" nowrap><?php echo number_format($rec['MONEY_BDG']); ?></td>
																	<td align="center" nowrap><?php echo ($rec['WEIGHT']); ?></td>
																</tr>
															<?php
																cur_activity($rec['PRJP_ID'], $i);
																$tg += $rec['TRAGET_VALUE'];
																$mg += $rec['MONEY_BDG'];
																$wg += $rec['WEIGHT'];
																$i++;
															}
															if ($rec_head['MONEY_BDG'] < $mg) {
																$color = 'style = "color:red" title = "เกินวงเงินงบประมาณ ' . number_format($mg - $rec_head['MONEY_BDG'], 2) . ' บาท"';
															}
															?>
															<tr bgcolor="#FFFFFF">
																<td align="right" colspan="2">รวม</td>
																<td align="right">&nbsp;</td>
																<td align="right">&nbsp;</td>
																<td align="right">&nbsp;</td>
																<td align="right" <?php echo $color; ?>><?php echo number_format($mg); ?></td>
																<td align="center"><?php echo number_format($wg, 1); ?></td>
																<td align="center">&nbsp;</td>
															</tr>
														<?php

														} else {
														?>
															<tr>
																<td colspan="7" align="center">ไม่พบข้อมูล</td>
															</tr>
														<?php }
														?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="clearfix"></div>
										<?php //echo endPaging("frm-search",$total_record); 
										?>
										<div class="clearfix"></div>
									</div>
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
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<!-- /.modal -->