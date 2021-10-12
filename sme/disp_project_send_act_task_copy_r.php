<?php

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

$sql = "SELECT 	a.PRJP_PARENT_ID,a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.UNIT_NAME,a.ORDER_NO,
				(select UNIT_NAME_TH from setup_unit where a.UNIT_ID = setup_unit.UNIT_ID) as UNIT_NAME_TH,
				a.SDATE_PRJP,a.EDATE_PRJP, a.COST_TYPE,
				(select sum(BDG_VALUE) from prjp_report_task where prjp_report_task.PRJP_ID = a.PRJP_ID)as s_val
		  		FROM prjp_project a 
				WHERE 1=1 AND a.PRJP_LEVEL = '2' AND a.PRJP_PARENT_ID = '" . $PRJP_ID . "' 
				order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3, CONVERT(int,ORDER_NO)
				";
// echo $sql;
$query = $db->query($sql);

$num_rows = $db->db_num_rows($query);

$sql_chk_join_act = "select * from prjp_join_act where PRJP_ID in (select PRJP_PARENT_ID from prjp_project where 1=1 AND PRJP_LEVEL = '2' AND PRJP_PARENT_ID = '" . $PRJP_ID . "' )";
$query_chk_join_act = $db->query($sql_chk_join_act);
while ($rec_chk_join_act = $db->db_fetch_array($query_chk_join_act)) {
	$arr_readonly[$rec_chk_join_act['PRJP_ID']] = "readonly";
}


if ($rec_head['PRJP_CON_ID'] != '') {
	/////////////////////////////////////// ผลการดำเนินงาน เก่า //////////////////////////////////////////////
	$sql_dataold = "SELECT EDATE_PRJP,SDATE_PRJP FROM prjp_project WHERE PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . " '";
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
	$sqlo = "SELECT 	a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.UNIT_NAME,a.ORDER_NO,
				(select UNIT_NAME_TH from setup_unit where a.UNIT_ID = setup_unit.UNIT_ID)as UNIT_NAME_TH,
				a.SDATE_PRJP,a.EDATE_PRJP,
				(select sum(BDG_VALUE) from prjp_report_task where prjp_report_task.PRJP_ID = a.PRJP_ID)as s_val
		  		FROM prjp_project a 
				WHERE 1=1 AND a.PRJP_LEVEL = '2' AND a.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "' 
				order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
				";
	$queryo = $db->query($sqlo);

	$num_rowso = $db->db_num_rows($queryo);
	//////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading row" style="">
				<div class="pull-left" style="">ผลการดำเนินงานของกิจกรรม</div>
				<div class="pull-right" style="">สสว.200/2</div>
			</div>
			<div class="panel-body epm-gradient">
				<?php
				$print_form = "<a class=\"btn btn-info\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form2('" . $PRJP_ID . "');\">" . $img_print . "  พิมพ์ข้อมูลการรายงานผลการดำเนินงานของกิจกรรม สสว.200/2</a> ";
				?>
				<div class="row">
					<div class="col-xs-12 col-sm-12"><?php echo $print_form; ?></div>
				</div>
				<!-------------------------------------------->
				<?php if ($rec_head['PRJP_CON_ID'] != '') { ?>
					<div class="row">
						<div class="col-xs-12 col-sm-12" align="center">
							<input type="hidden" id="hide_old" name="hide_old" value="0">
							<a href="javascript:void(0)" onClick="chk_old(hide_old.value);"><?php echo $img_save; ?> ผลการดำเนินงานเก่า</a>
						</div>
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
							<table class="table table-bordered table-striped table-hover table-condensed">
								<thead>
									<tr class="bgHead">
										<th rowspan="2">
											<div align="center"><strong>ลำดับ</strong></div>
										</th>
										<th rowspan="2">
											<div align="center"><strong>ชื่อกิจกรรม</strong></div>
										</th>
										<th rowspan="2" nowrap>
											<div align="center"><strong>% ถ่วง<br />น้ำหนัก</strong></div>
										</th>
										<th rowspan="2">
											<div align="center"><strong>เป้าหมาย</strong></div>
										</th>
										<th rowspan="2">
											<div align="center"><strong>ยอดสะสม</strong></div>
										</th>
										<th rowspan="2">
											<div align="center"><strong>หน่วยนับ</strong></div>
										</th>
										<th rowspan="2">
											<div align="center"><strong></strong></div>
										</th>
										<th colspan="12">
											<div align="center"><strong>ระยะเวลาการดำเนินงาน-โครงการ/กิจกรรม ปี <?php echo $_SESSION['year_round']; ?></strong></div>
										</th>
									</tr>
									<tr class="bgHead">
										<?php
										foreach ($rro as $key => $val) {
											if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
												$smho = substr($val, 4, 2);
												$syho = substr($val, 2, 2);
										?>
												<th>
													<div align="center"><strong><?php echo $month[$smho * 1] . $syho; ?></strong></div>
												</th>
										<?php }
										} ?>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql_preo = "SELECT a.PRJP_ID,
					(select sum(CAST(WEIGHT AS DECIMAL(10,2))) from prjp_project b where b.PRJP_PARENT_ID = a.PRJP_ID and PRJP_LEVEL = '2')as SW,
					(select sum(TRAGET_VALUE) from prjp_project b where b.PRJP_PARENT_ID = a.PRJP_ID and PRJP_LEVEL = '2')as ST
				FROM prjp_project a WHERE PRJP_ID = '" . $rec_head['PRJP_CON_ID'] . "'
					";
									$query_preo = $db->query($sql_preo);
									$rec_preo = $db->db_fetch_array($query_preo);
									$sum_po = array();
									$sum_ro = array();
									$sql_value_pto = "select prjp_plan_task.MONTH,prjp_plan_task.YEAR,prjp_plan_task.BDG_VALUE ,prjp_project.PRJP_ID,((((prjp_plan_task.BDG_VALUE/prjp_project.TRAGET_VALUE)*100)*prjp_project.WEIGHT)/100)as per_val
					FROM prjp_plan_task 
					JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
					WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "'
					";
									$query_value_pto = $db->query($sql_value_pto);
									while ($rec_value_pto = $db->db_fetch_array($query_value_pto)) {
										$arr_po[$rec_value_pto['PRJP_ID']][$rec_value_pto['YEAR']][$rec_value_pto['MONTH']] = $rec_value_pto['BDG_VALUE'];

										$sql_value_pt_childo = "select prjp_plan_task.MONTH,prjp_plan_task.YEAR,prjp_plan_task.BDG_VALUE ,prjp_project.PRJP_ID,((((prjp_plan_task.BDG_VALUE/prjp_project.TRAGET_VALUE)*100)*prjp_project.WEIGHT)/100)as per_val
										FROM prjp_plan_task 
										JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
										WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_value_pt['PRJP_ID'] . "'
										";

										$query_value_pt_childo = $db->query($sql_value_pt_childo);
										$num_rows_vpco = $db->db_num_rows($query_value_pt_childo);
										while ($rec_value_pt_childo = $db->db_fetch_array($query_value_pt_childo)) {
											$arr_po[$rec_value_pt_childo['PRJP_ID']][$rec_value_pt_childo['YEAR']][$rec_value_pt_childo['MONTH']] = $rec_value_pt_childo['BDG_VALUE'];
										}

										$sum_pvo[$rec_value_pto['YEAR']][$rec_value_pto['MONTH']] += $rec_value_pto['BDG_VALUE'];
										if ($num_rows_vpco == 0) {
											$sum_po[$rec_value_pto['YEAR']][$rec_value_pto['MONTH']] += $rec_value_pto['per_val'];
										}
									}

									$sql_value_pto = "select prjp_project.PRJP_ID
					
					FROM prjp_plan_task 
					JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
					WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "'
					GROUP BY prjp_project.PRJP_ID
					";
									$query_value_pto = $db->query($sql_value_pto);
									while ($rec_sum_po = $db->db_fetch_array($query_value_pto)) {
										$sql_sum_p_childo = "select prjp_plan_task.MONTH,prjp_plan_task.YEAR,prjp_plan_task.BDG_VALUE ,prjp_project.PRJP_ID,
										((prjp_plan_task.BDG_VALUE/prjp_project.TRAGET_VALUE)*prjp_project.WEIGHT)as pper
										FROM prjp_plan_task 
										JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
										WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_sum_p['PRJP_ID'] . "'";
										$query_sum_p_childo = $db->query($sql_sum_p_childo);
										while ($rec_sum_p_childo = $db->db_fetch_array($query_sum_p_childo)) {
											$sum_po[$rec_sum_p_childo['YEAR']][$rec_sum_p_childo['MONTH']] += $rec_sum_p_childo['pper'];
										}
									}

									$sql_value_pro = "select prjp_report_task.MONTH,prjp_report_task.YEAR,prjp_report_task.BDG_VALUE ,prjp_project.PRJP_ID
					FROM prjp_report_task 
					JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_task.PRJP_ID
					WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_head['PRJP_CON_ID'] . "'
					";
									$query_value_pro = $db->query($sql_value_pro);
									while ($rec_value_pro = $db->db_fetch_array($query_value_pro)) {
										$arr_ro[$rec_value_pro['PRJP_ID']][$rec_value_pro['YEAR']][$rec_value_pro['MONTH']] = $rec_value_pro['BDG_VALUE'];


										$sql_value_pr_childo = "select prjp_report_task.MONTH,prjp_report_task.YEAR,prjp_report_task.BDG_VALUE ,prjp_project.PRJP_ID
									FROM prjp_report_task 
									JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_task.PRJP_ID
									WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_value_pro['PRJP_ID'] . "'
									";
										$query_value_pr_childo = $db->query($sql_value_pr_childo);
										while ($rec_value_pr_childo = $db->db_fetch_array($query_value_pr_childo)) {
											$arr_ro[$rec_value_pr_childo['PRJP_ID']][$rec_value_pr_childo['YEAR']][$rec_value_pr_childo['MONTH']] = $rec_value_pr_childo['BDG_VALUE'];
										}



										$sum_ro[$rec_value_pro['YEAR']][$rec_value_pro['MONTH']] += $rec_value_pro['BDG_VALUE'];
									}
									?>
									<tr>
										<td colspan="6" rowspan="2" align="right">รวมแผน/ผลความคืบหน้า (%)</td>
										<td align="center" nowrap>แผนสะสม</td>
										<?php
										if ($rowo == 1) {
											$val_sum_p_mo = 0;
										}
										foreach ($rro as $key => $val) {
											if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
												$m_do = substr($val, 4, 2) * 1;
												$y_do = substr($val, 0, 4);
												$val_sum_p_mo += $sum_po[$y_do][$m_do];
										?>
												<td align="right" id="per_result_<?php echo $y_do; ?>_<?php echo $m_do; ?>">
													<?php //echo number_format($sum_per[$y_d][$m_d]/100,2); 
													?>

													<?php
													if ($mo[$key] == '') {
														echo "";
													} else {
														if ($val_sum_p_mo > 100) {
															echo "100.00";
														} else {
															echo @number_format($val_sum_p_mo, 2);
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
										<td align="center" nowrap>ผลสะสม</td>
										<?php
										$val_sum_r_mo = 0;
										foreach ($rro as $key => $val) {
											if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
												$m_do = substr($val, 4, 2) * 1;
												$y_do = substr($val, 0, 4);
												$val_sum_r_mo += $sum_ro[$y_do][$m_do];
										?>
												<td align="right" id="per_old_sum_<?php echo $key + 1; ?>">

												</td>
										<?php
											} //if
										} //foreach
										?>
									</tr>
									<?php
									if ($num_rowso > 0) {
										$ii = 1;
										$queryo = $db->query($sqlo);
										while ($reco_task = $db->db_fetch_array($queryo)) {
											$msao = 10;
											$meao = substr($reco_task['EDATE_PRJP'], 5, 2);
											$yeao = substr($reco_task['EDATE_PRJP'], 0, 4) + 543;
											$ysao = substr($reco_task['SDATE_PRJP'], 0, 4) + 543;

											$year_mso = $ysao . $msao;
											$year_meo = $yeao . $meao;
											$row_colao = (((12 - $msao) + 1) + ((($yeao - $ysao) - 1) * 12) + (12 - (12 - $meao)));



											$sqlChildCount = "SELECT
											count(prjp_id) totalChild
										FROM
											prjp_project
										WHERE
											PRJP_PARENT_ID = '" . $reco_task['PRJP_ID'] . "'";
											$queryChildCount = $db->query($sqlChildCount);
											$recTotalChild = $db->db_fetch_array($queryChildCount);
											$totalChild = $recTotalChild["totalChild"];


									?>
											<tr bgcolor="#FFFFFF">
												<td align="center" rowspan="4"><?php echo $reco_task['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $reco_task['PRJP_ID']; ?>"></td>
												<td rowspan="4" align="left"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($reco_task['PRJP_NAME']); ?></textarea></td>
												<td rowspan="4" align="center"><?php echo $reco_task['WEIGHT']; ?></td>
												<td rowspan="4" align="center"><?php echo number_format($reco_task['TRAGET_VALUE']); ?></td>
												<td rowspan="4" align="center"><?php echo number_format($reco_task['s_val']); ?></td>
												<td rowspan="4" align="center"><?php echo text($reco_task['UNIT_NAME']); ?></td>
												<td align="center" style="background:#bebebe" width="43px" nowrap>แผนสะสม</td>
												<?php
												foreach ($rro as $key => $val) {
													if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
														$m_do = substr($val, 4, 2) * 1;
														$y_do = substr($val, 0, 4);
												?>
														<td align="right" style="background:#bebebe">
															<?php if ($mo[$key] == '') {
																echo "-";
															} else {
																$sum_all[$reco_task['PRJP_ID']] = 0;
																$i = $fbso;
																while ($i < $val) {
																	$smo = substr($i, 4, 2);
																	$syo = substr($i, 0, 4);
																	if ($smo * 1 == '12') {
																		$i = ($syo + 1) . "01";
																	} else {
																		$i++;
																	}
																	$sum_all[$reco_task['PRJP_ID']] += $arr_po[$rec['PRJP_ID']][$syo][$smo * 1];
																}
																echo @number_format($sum_all[$reco_task['PRJP_ID']] + $arr_po[$reco_task['PRJP_ID']][$y_do][$m_do], 2);
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
												?>
														<td align="right" style="background:#afeeee">
															<?php if ($mo[$key] == '') {
																echo "-";
															} else {
															?>
															<?php echo @number_format($arr_po[$reco_task['PRJP_ID']][$y_do][$m_do], 2);
															} ?></td>
												<?php
													} //if 
												} //foreach
												?>
											</tr>
											<tr bgcolor="#FFFFFF">
												<td align="center" style="background:#bebebe" nowrap>ผลสะสม</td>
												<?php
												$i = 1;
												foreach ($rro as $key => $val) {
													if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
														$m_do = substr($val, 4, 2) * 1;
														$y_do = substr($val, 0, 4);
												?>
														<td align="right" id="psum_act_<?php echo $reco_task['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;">

														</td>
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
														$mcko = $y_do . sprintf("%'02d", $m_do);
												?>
														<td align="right" style="">
															<?php if ($mo[$key] == '') {
																echo "-";
															} else {
															?>
																<input type="hidden" id="VCHK_YEAR_OLD_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR_OLD[]" value="<?php echo $mcko; ?>">
																<input type="hidden" id="WEIGHT_OLD_<?php echo $ii; ?>_<?php echo $k; ?>" name="WEIGHT_OLD[]" value="<?php echo $reco_task['WEIGHT']; ?>">
																<input type="hidden" id="TRAGET_VALUE_OLD_<?php echo $ii; ?>_<?php echo $k; ?>" name="TRAGET_VALUE_OLD[]" value="<?php echo $reco_task['TRAGET_VALUE']; ?>">
																<input name="YEAR[<?php echo $reco_task['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="YEAR_<?php echo $y_do . $m_do; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_do); ?>">

																<?php
																$input_type = "text";
																$readonly = "";
																if ($totalChild > 0) {
																	$readonly = "readonly";
																} else {
																}
																?>

																<input <?php echo $readonly; ?> prjp-parent-id="<?php echo $reco_task["PRJP_PARENT_ID"]; ?>" prjp-act-id="<?php echo $reco_task['PRJP_ID']; ?>" month="<?php echo $m_do; ?>" name="BDG_VALUE[<?php echo $reco_task['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="BDG_VALUE_<?php echo $reco_task['PRJP_ID']; ?>_<?php echo $k; ?>" type="hidden" size="5" class="form-control number_format PVO_<?php echo $ii; ?>_<?php echo $k; ?>" value="<?php echo number_format($arr_ro[$reco_task['PRJP_ID']][$y_do][$m_do], 2); ?>" onBlur="Chk_sval('<?php echo $c_arro; ?>','<?php echo $reco_task['PRJP_ID']; ?>','<?php echo $rec_head['SW']; ?>','<?php echo $rec_head['ST']; ?>','<?php echo $y_do; ?>','<?php echo $m_do; ?>');
			Sum_result_old('<?php echo $c_arro; ?>','<?php echo $num_rowso; ?>','<?php echo $ymchk_js; ?>');NumberFormat(this,2);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>>
																<?php echo number_format($arr_ro[$reco_task['PRJP_ID']][$y_do][$m_do], 2); ?>
															<?php } ?>
														</td>
												<?php } //if
													$k++;
												} //foreach 
												?>
											</tr>
											<script>
												Chk_sval('<?php echo $c_arro; ?>', '<?php echo $reco_task['PRJP_ID']; ?>', '<?php echo $rec_head['SW']; ?>', '<?php echo $rec_head['ST']; ?>');
												Sum_result_old('<?php echo $c_arro; ?>', '<?php echo $num_rowso; ?>', '<?php echo $ymchk_js; ?>');
											</script>
											<?php




											$sqlChild = "SELECT 	a.PRJP_PARENT_ID,a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.UNIT_NAME,a.ORDER_NO,
									(select UNIT_NAME_TH from setup_unit where a.UNIT_ID = setup_unit.UNIT_ID)as UNIT_NAME_TH,
									a.SDATE_PRJP,a.EDATE_PRJP,
									(select sum(BDG_VALUE) from prjp_report_task where prjp_report_task.PRJP_ID = a.PRJP_ID)as s_val
									FROM prjp_project a 
									WHERE 1=1 AND a.PRJP_LEVEL = '3' AND a.PRJP_PARENT_ID = '" . $rec['PRJP_ID'] . "' 
									order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
					";

											$iii = 1;
											$queryChild = $db->query($sqlChild);
											while ($recChild = $db->db_fetch_array($queryChild)) {
												$msao = 10;
												$meao = substr($sqlChild['EDATE_PRJP'], 5, 2);
												$yeao = substr($sqlChild['EDATE_PRJP'], 0, 4) + 543;
												$ysao = substr($sqlChild['SDATE_PRJP'], 0, 4) + 543;

												$year_mso = $ysao . $msao;
												$year_meo = $yeao . $meao;
												$row_colao = (((12 - $msao) + 1) + ((($yeao - $ysao) - 1) * 12) + (12 - (12 - $meao)));


											?>
												<tr bgcolor="#FFFFFF">
													<td align="center" rowspan="4"><?php echo $recChild['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $recChild['PRJP_ID']; ?>"></td>
													<td rowspan="4" align="left"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($recChild['PRJP_NAME']); ?></textarea></td>
													<td rowspan="4" align="center"><?php echo $recChild['WEIGHT']; ?></td>
													<td rowspan="4" align="center"><?php echo number_format($recChild['TRAGET_VALUE']); ?></td>
													<td rowspan="4" align="center"><?php echo number_format($recChild['s_val']); ?></td>
													<td rowspan="4" align="center"><?php echo text($recChild['UNIT_NAME']); ?></td>
													<td align="center" style="background:#bebebe" nowrap>แผนสะสม</td>
													<?php

													foreach ($rro as $key => $val) {
														if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
															$m_do = substr($val, 4, 2) * 1;
															$y_do = substr($val, 0, 4);
													?>
															<td align="right" style="background:#bebebe">
																<?php if ($mo[$key] == '') {
																	echo "-";
																} else {
																	$sum_all[$recChild['PRJP_ID']] = 0;
																	$i = $fbso;
																	while ($i < $val) {
																		$smo = substr($i, 4, 2);
																		$syo = substr($i, 0, 4);
																		if ($smo * 1 == '12') {
																			$i = ($syo + 1) . "01";
																		} else {
																			$i++;
																		}
																		$sum_all[$recChild['PRJP_ID']] += $arr_po[$recChild['PRJP_ID']][$syo][$smo * 1];
																	}
																	echo @number_format($sum_all[$recChild['PRJP_ID']] + $arr_po[$recChild['PRJP_ID']][$y_do][$m_do], 2);
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
													foreach ($rr as $key => $val) {
														if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
															$m_do = substr($val, 4, 2) * 1;
															$y_do = substr($val, 0, 4);
													?>
															<td align="right" style="background:#afeeee">
																<?php if ($mo[$key] == '') {
																	echo "-";
																} else {

																?>
																<?php echo @number_format($arr_po[$recChild['PRJP_ID']][$y_do][$m_do], 2);
																} ?></td>
													<?php
														} //if 
													} //foreach
													?>
												</tr>
												<tr bgcolor="#FFFFFF">
													<td align="center" style="background:#bebebe" nowrap>ผลสะสม</td>
													<?php
													$i = 1;
													foreach ($rro as $key => $val) {
														if ($key >= $mstarto && $key <= ($mendo * $rowo) + $rowtbo) {
															$m_do = substr($val, 4, 2) * 1;
															$y_do = substr($val, 0, 4);
													?>
															<td align="right" id="psum_act_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;width:">

															</td>
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
															$mcko = $y_do . sprintf("%'02d", $m_do);
													?>
															<td align="right" style="">
																<?php if ($mo[$key] == '') {
																	echo "-";
																} else {
																?>
																	<input type="hidden" id="VCHK_YEAR_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR[]" value="<?php echo $mcko; ?>">
																	<input type="hidden" id="WEIGHT_ACT_<?php echo $ii; ?>_<?php echo $k; ?>" name="WEIGHT[]" value="<?php echo $recChild['WEIGHT']; ?>">
																	<input type="hidden" id="TRAGET_VALUE_ACT_<?php echo $ii; ?>_<?php echo $k; ?>" name="TRAGET_VALUE[]" value="<?php echo $recChild['TRAGET_VALUE']; ?>">
																	<input name="YEAR[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="YEAR_<?php echo $y_do . $m_do; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_do); ?>">
																	<input prjp-parent-id="<?php echo $recChild["PRJP_PARENT_ID"]; ?>" prjp-act-id="<?php echo $recChild['PRJP_ID']; ?>" month="<?php echo $m_do; ?>" name="BDG_VALUE[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_do; ?>][<?php echo $m_do; ?>]" id="BDG_VALUE_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" type="hidden" size="5" class="form-control number_format PVO_<?php echo $ii; ?>_<?php echo $k; ?>" value="<?php echo number_format($arr_ro[$recChild['PRJP_ID']][$y_do][$m_do], 2); ?>" onBlur="Chk_sval('<?php echo $c_arro; ?>','<?php echo $recChild['PRJP_ID']; ?>','<?php echo $rec_head['SW']; ?>','<?php echo $rec_head['ST']; ?>','<?php echo $y_do; ?>','<?php echo $m_do; ?>');
						Sum_result_old('<?php echo $c_arro; ?>','<?php echo $num_rowso; ?>','<?php echo $ymchk_js; ?>');
						NumberFormat(this,2); cal_child(this);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>>
																	<?php echo number_format($arr_ro[$recChild['PRJP_ID']][$y_do][$m_do], 2); ?>
																<?php } ?>
															</td>
													<?php } //if
														$k++;
													} //foreach 
													?>
												</tr>
												<script>
													Chk_sval('<?php echo $c_arro; ?>', '<?php echo $recChild['PRJP_ID']; ?>', '<?php echo $rec_head['SW']; ?>', '<?php echo $rec_head['ST']; ?>');
													Sum_result_old('<?php echo $c_arro; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
												</script>
									<?php

												$iii++;
											}
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

					<?php //} ///// เช็คผลเก่า 
					?>
					<?php /*if($rec_head['PRJP_CON_ID']!=''){ ?>
		  <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br> <br>
		  <?php } */ ?>

					<label id="TB_BR"></label>
				<?php }

				//////////////////////////////////////////////////////////////////////////////////////////////////////////////
				?>

				<?php
				$mstart = 0;
				$mend = 11;
				$rowtb = 0;
				for ($row = 1; $row <= $row_wh; $row++) {
				?>

					<div id="tb_<?php echo $row; ?>" class=" col-xs-12 col-sm-12 ">
						<table class="table table-bordered table-striped table-hover table-condensed" id="TestTable">
							<thead id="thead">
								<tr class="bgHead tr-head-rtask" id="tr">
									<th rowspan="2">
										<div align="center"><strong>ลำดับ</strong></div>
									</th>
									<th rowspan="2">
										<div align="center"><strong>ชื่อกิจกรรม</strong></div>
									</th>
									<th rowspan="2">
										<div align="center"><strong>ประเภทค่าใช้จ่าย</strong></div>
									</th>
									<th rowspan="2" nowrap>
										<div align="center"><strong>% ถ่วง<br />น้ำหนัก</strong></div>
									</th>
									<th rowspan="2">
										<div align="center"><strong>เป้าหมาย</strong></div>
									</th>
									<th rowspan="2">
										<div align="center"><strong>ยอดสะสม</strong></div>
									</th>
									<th rowspan="2">
										<div align="center"><strong>หน่วยนับ</strong></div>
									</th>
									<th rowspan="2">
										<div align="center"><strong></strong></div>
									</th>
									<th colspan="12">
										<div align="center"><strong>ระยะเวลาการดำเนินงาน-โครงการ/กิจกรรม ปี <?php echo $_SESSION['year_round']; ?></strong></div>
									</th>
								</tr>
								<tr class="bgHead tr-head2-rtask">
									<?php
									foreach ($rr as $key => $val) {
										if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
											$smh = substr($val, 4, 2);
											$syh = substr($val, 2, 2);
									?>
											<th>
												<div align="center"><strong><?php echo $month[$smh * 1] . $syh; ?></strong></div>
											</th>
									<?php }
									} ?>
								</tr>
							</thead>
							<tbody>
								<?php
								$sql_pre = "SELECT  a.PRJP_ID,
										(select sum(CAST(WEIGHT AS DECIMAL(10,2))) 
										from    prjp_project b 
										where   b.PRJP_PARENT_ID = a.PRJP_ID and 
												PRJP_LEVEL = '2') as SW,
										(select sum(TRAGET_VALUE) 
										from    prjp_project b 
										where   b.PRJP_PARENT_ID = a.PRJP_ID and 
										PRJP_LEVEL = '2') as ST
								FROM    prjp_project a 
								WHERE   PRJP_ID = '" . $PRJP_ID . "'";
								$query_pre = $db->query($sql_pre);
								$rec_pre = $db->db_fetch_array($query_pre);

								//แผน
								$sum_p = array();
								$sql_value_pt = "select prjp_plan_task.MONTH,
											prjp_plan_task.YEAR,
											prjp_plan_task.BDG_VALUE,
											prjp_project.PRJP_ID,
											((((prjp_plan_task.BDG_VALUE/prjp_project.TRAGET_VALUE)*100)*prjp_project.WEIGHT)/100) as per_val
									FROM    prjp_plan_task 
									JOIN    prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
									WHERE   prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "'";
								$query_value_pt = $db->query($sql_value_pt);

								/*if($db->db_num_rows($query_value_pt) == 0){
						$fixed_qry = $db->query(" select PRJP_ID, SERVICE_TASK_ID, YEAR_BDG from prjp_project where PRJP_PARENT_ID = '".$PRJP_ID."' and isnull(SERVICE_TASK_ID,0) > 0 ");
						while($r_fix = $db->db_fetch_array($fixed_qry)){
							$tk_id = $r_fix['SERVICE_TASK_ID'];

							$url = "http://61.91.223.53/SME_BDG/api_ipa/task_month_req_indicators.php?id=".$tk_id;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
							$sResponse = curl_exec($ch);
							curl_close($ch);
							$fix_data =  json_decode($sResponse, true);
							foreach($fix_data as $rec_fix){
								$f_year = (int)$rec_fix['task_month']>9? ($r_fix['YEAR_BDG']-1): $r_fix['YEAR_BDG'];
								$add_fix = array(
												'PRJP_ID'					=>	$r_fix['PRJP_ID'],
												'MONTH'						=>	$rec_fix['task_month'],
												'YEAR'						=>	$f_year,
												'BDG_VALUE'					=>	$rec_fix['task_value'],
												'SERVICE_act_project_id'	=>	$rec_fix['act_project_id']
											);
								$db->db_insert("prjp_plan_task",$add_fix);
							}
						}
						
						$sql_value_pt = "select prjp_plan_task.MONTH,
											prjp_plan_task.YEAR,
											prjp_plan_task.BDG_VALUE,
											prjp_project.PRJP_ID,
											((((prjp_plan_task.BDG_VALUE/prjp_project.TRAGET_VALUE)*100)*prjp_project.WEIGHT)/100) as per_val
									FROM    prjp_plan_task 
									JOIN    prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
									WHERE   prjp_project.PRJP_PARENT_ID = '".$PRJP_ID."'";
						$query_value_pt = $db->query($sql_value_pt);
						
					}*/

								while ($rec_value_pt = $db->db_fetch_array($query_value_pt)) {
									$arr_p[$rec_value_pt['PRJP_ID']][$rec_value_pt['YEAR']][$rec_value_pt['MONTH']] = $rec_value_pt['BDG_VALUE'];

									$sql_value_pt_child = "select   prjp_plan_task.MONTH,
														prjp_plan_task.YEAR,
														prjp_plan_task.BDG_VALUE,
														prjp_project.PRJP_ID,
														((((prjp_plan_task.BDG_VALUE/prjp_project.TRAGET_VALUE)*100)*prjp_project.WEIGHT)/100) as per_val
												FROM    prjp_plan_task 
														JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
												WHERE   prjp_project.PRJP_PARENT_ID = '" . $rec_value_pt['PRJP_ID'] . "'";

									$query_value_pt_child = $db->query($sql_value_pt_child);
									$num_rows_vpc = $db->db_num_rows($query_value_pt_child);
									while ($rec_value_pt_child = $db->db_fetch_array($query_value_pt_child)) {
										$arr_p[$rec_value_pt_child['PRJP_ID']][$rec_value_pt_child['YEAR']][$rec_value_pt_child['MONTH']] = $rec_value_pt_child['BDG_VALUE'];
									} //while

									$sum_pv[$rec_value_pt['YEAR']][$rec_value_pt['MONTH']] += $rec_value_pt['BDG_VALUE'];
									if ($num_rows_vpc == 0) {
										$sum_p[$rec_value_pt['YEAR']][$rec_value_pt['MONTH']] += $rec_value_pt['per_val'];
									} //if
								} //while

								$sql_value_pt = "select prjp_project.PRJP_ID
									  FROM  prjp_plan_task 
											JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
									WHERE   prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "'
								   GROUP BY prjp_project.PRJP_ID";
								$query_value_pt = $db->query($sql_value_pt);
								while ($rec_sum_p = $db->db_fetch_array($query_value_pt)) {
									$sql_sum_p_child = "select  prjp_plan_task.MONTH,
													prjp_plan_task.YEAR,
													prjp_plan_task.BDG_VALUE,
													prjp_project.PRJP_ID,
													((prjp_plan_task.BDG_VALUE/prjp_project.TRAGET_VALUE)*prjp_project.WEIGHT)as pper
											FROM    prjp_plan_task 
													JOIN prjp_project ON prjp_project.PRJP_ID = prjp_plan_task.PRJP_ID
											WHERE   prjp_project.PRJP_PARENT_ID = '" . $rec_sum_p['PRJP_ID'] . "'";
									$query_sum_p_child = $db->query($sql_sum_p_child);
									while ($rec_sum_p_child = $db->db_fetch_array($query_sum_p_child)) {
										$sum_p[$rec_sum_p_child['YEAR']][$rec_sum_p_child['MONTH']] += $rec_sum_p_child['pper'];
									} //while
								} //while

								//ผล
								$sql_value_pr = "select prjp_report_task.MONTH,prjp_report_task.YEAR,prjp_report_task.BDG_VALUE ,prjp_project.PRJP_ID
						FROM prjp_report_task 
						JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_task.PRJP_ID
						WHERE prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "'
						";
								$query_value_pr = $db->query($sql_value_pr);
								while ($rec_value_pr = $db->db_fetch_array($query_value_pr)) {
									$arr_r[$rec_value_pr['PRJP_ID']][$rec_value_pr['YEAR']][$rec_value_pr['MONTH']] = $rec_value_pr['BDG_VALUE'];


									$sql_value_pr_child = "select prjp_report_task.MONTH,prjp_report_task.YEAR,prjp_report_task.BDG_VALUE ,prjp_project.PRJP_ID
										FROM prjp_report_task 
										JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_task.PRJP_ID
										WHERE prjp_project.PRJP_PARENT_ID = '" . $rec_value_pr['PRJP_ID'] . "'
										";
									$query_value_pr_child = $db->query($sql_value_pr_child);
									while ($rec_value_pr_child = $db->db_fetch_array($query_value_pr_child)) {
										$arr_r[$rec_value_pr_child['PRJP_ID']][$rec_value_pr_child['YEAR']][$rec_value_pr_child['MONTH']] = $rec_value_pr_child['BDG_VALUE'];
									}

									$sum_r[$rec_value_pr['YEAR']][$rec_value_pr['MONTH']] += $rec_value_pr['BDG_VALUE'];
								}


								?>
								<tr class="tr-head3-rtask">
									<td colspan="7" rowspan="3" align="right">รวมแผน/ผลความคืบหน้า (%)</td>
									<td align="center" nowrap>แผนสะสม</td>
									<?php
									if ($row == 1) {
										$val_sum_p_m = 0;
									}
									foreach ($rr as $key => $val) {
										if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
											$m_d = substr($val, 4, 2) * 1;
											$y_d = substr($val, 0, 4);
											$val_sum_p_m += $sum_p[$y_d][$m_d];
									?>
											<td align="right" id="per_result_<?php echo $y_d; ?>_<?php echo $m_d; ?>">

												<?php
												if ($m[$key] == '') {
													echo "";
												} else {
													if ($val_sum_p_m > 100) {
														echo "100.00";
												?>
														<input type="hidden" id="plan_<?php echo $key + 1 ?>" value="<?php echo "100.00"; ?>">
													<?php
													} else {
														echo @number_format($val_sum_p_m, 2);
													?>
														<input type="hidden" id="plan_<?php echo $key + 1 ?>" value="<?php echo @number_format($val_sum_p_m, 2); ?>">
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
								<tr class="tr-head4-rtask">
									<td align="center" nowrap>ผลสะสม</td>
									<?php
									$val_sum_r_m = 0;

									foreach ($rr as $key => $val) {
										if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
											$m_d = substr($val, 4, 2) * 1;
											$y_d = substr($val, 0, 4);
											$val_sum_r_m += $sum_r[$y_d][$m_d];

									?>
											<td align="right" id="per_sum_act_<?php echo $key + 1; ?>">

											</td>
									<?php
										} //if
									} //foreach
									?>
								</tr>

								<tr class="tr-head5-rtask">
									<td align="center" nowrap>สถานะผลเทียบแผน</td>
									<?php
									$val_sum_r_m = 0;

									foreach ($rr as $key => $val) {
										if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
											$m_d = substr($val, 4, 2);
											$y_d = substr($val, 0, 4);
											$val_sum_r_m += $sum_r[$y_d][$m_d];

									?>
											<td align="right" id="status_act_<?php echo $key + 1; ?>" month="<?php echo $m_d; ?>" year="<?php echo $y_d; ?>" nowrap>

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
										<tr class="tr-body-rtask" bgcolor="#FFFFFF">
											<td align="center" rowspan="4"><?php echo $rec['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $rec['PRJP_ID']; ?>"></td>
											<td rowspan="4" align="left"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($rec['PRJP_NAME']); ?></textarea></td>
											<td rowspan="4" align="center"><?php echo $arr_bdg_cost[$rec['COST_TYPE']]; ?></td>
											<td rowspan="4" align="center"><?php echo $rec['WEIGHT']; ?></td>
											<td rowspan="4" align="center"><?php echo number_format($rec['TRAGET_VALUE']); ?></td>
											<td rowspan="4" align="center"><?php echo number_format($rec['s_val']); ?></td>
											<td rowspan="4" align="center"><?php echo text($rec['UNIT_NAME']); ?></td>
											<td align="center" style="background:#bebebe" nowrap>แผนสะสม</td>
											<?php
											foreach ($rr as $key => $val) {
												if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
													$m_d = substr($val, 4, 2) * 1;
													$y_d = substr($val, 0, 4);
											?>
													<td align="right" style="background:#bebebe">
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
																$sum_all[$rec['PRJP_ID']] += $arr_p[$rec['PRJP_ID']][$sy][$sm * 1];
															}
															echo @number_format($sum_all[$rec['PRJP_ID']] + $arr_p[$rec['PRJP_ID']][$y_d][$m_d], 2);
														} ?>
													</td>
											<?php
												}
											}
											?>
										</tr>
										<tr class="tr-body2-rtask" bgcolor="#FFFFFF">
											<td align="center" style="background:#afeeee">แผน</td>
											<?php

											foreach ($rr as $key => $val) {
												if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
													$m_d = substr($val, 4, 2) * 1;
													$y_d = substr($val, 0, 4);
											?>
													<td align="right" style="background:#afeeee">
														<?php if ($m[$key] == '') {
															echo "";
														} else {
														?>
														<?php echo @number_format($arr_p[$rec['PRJP_ID']][$y_d][$m_d], 2);
														} ?></td>
											<?php
												} //if 
											} //foreach
											?>
										</tr>
										<tr class="tr-body3-rtask" bgcolor="#FFFFFF">
											<td align="center" style="background:#bebebe" nowrap>ผลสะสม</td>
											<?php
											$i = 1;
											foreach ($rr as $key => $val) {
												if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
													$m_d = substr($val, 4, 2) * 1;
													$y_d = substr($val, 0, 4);
											?>
													<td align="right" id="psum_act_<?php echo $rec['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;">

													</td>
											<?php
												}
												$i++;
											}
											?>
										</tr>
										<tr class="tr-body4-rtask" bgcolor="#FFFFFF">
											<td align="center" style="background:#afeeee">ผล</td>
											<?php
											$k = 1;
											foreach ($rr as $key => $val) {
												if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
													$m_d = substr($val, 4, 2) * 1;
													$y_d = substr($val, 0, 4);
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
																$distxt = "";
																$bgdis = "";
															} else {
																$distxt = "readonly";
																$bgdis = "background:#9F9;";
															}
														} else {
															if (($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) == ($ymchk)) {
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
											?>
													<td align="right" style="">
														<?php if ($m[$key] == '') {
															echo "";
														} else {
														?>
															<input type="hidden" id="VCHK_YEAR_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR[]" value="<?php echo $mck; ?>">
															<input type="hidden" id="WEIGHT_ACT_<?php echo $ii; ?>_<?php echo $k; ?>" name="WEIGHT[]" value="<?php echo $rec['WEIGHT']; ?>">
															<input type="hidden" id="TRAGET_VALUE_ACT_<?php echo $ii; ?>_<?php echo $k; ?>" name="TRAGET_VALUE[]" value="<?php echo $rec['TRAGET_VALUE']; ?>">
															<input name="YEAR[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="YEAR_<?php echo $y_d . $m_d; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_d); ?>">

															<?php
															$input_type = "text";
															$readonly = "";
															if ($totalChild > 0) {
																$readonly = "readonly";
															} else {
															}
															?>

															<input <?php echo $arr_readonly[$rec['PRJP_ID']]; ?> <?php echo $disables_txt; ?> prjp-parent-id="<?php echo $rec["PRJP_PARENT_ID"]; ?>" prjp-act-id="<?php echo $rec['PRJP_ID']; ?>" month="<?php echo $m_d; ?>" name="BDG_VALUE[<?php echo $rec['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_VALUE_<?php echo $rec['PRJP_ID']; ?>_<?php echo $k; ?>" type="text" size="5" class="form-control PV_ACT_<?php echo $ii; ?>_<?php echo $k; ?>" value="<?php echo number_format($arr_r[$rec['PRJP_ID']][$y_d][$m_d], 2); ?>" onBlur="Chk_sval('<?php echo $c_arr; ?>','<?php echo $rec['PRJP_ID']; ?>','<?php echo $rec_head['SW']; ?>','<?php echo $rec_head['ST']; ?>','<?php echo $y_d; ?>','<?php echo $m_d; ?>');
		Sum_result_act('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');
        status_result_act('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');
        NumberFormat(this,2);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>><?php } ?>
													</td>
											<?php } //if
												$k++;
											} //foreach 
											?>
										</tr>
										<script>
											Chk_sval('<?php echo $c_arr; ?>', '<?php echo $rec['PRJP_ID']; ?>', '<?php echo $rec_head['SW']; ?>', '<?php echo $rec_head['ST']; ?>');
											Sum_result_act('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
											status_result_act('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
											test_chkInput('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
										</script>
										<?php




										$sqlChild = "SELECT 	a.PRJP_PARENT_ID,a.PRJP_ID,a.PRJP_CODE,a.PRJP_NAME,a.UNIT_ID,a.WEIGHT,a.TRAGET_VALUE,a.UNIT_NAME,a.ORDER_NO,
								(select UNIT_NAME_TH from setup_unit where a.UNIT_ID = setup_unit.UNIT_ID)as UNIT_NAME_TH,
								a.SDATE_PRJP,a.EDATE_PRJP,
								(select sum(BDG_VALUE) from prjp_report_task where prjp_report_task.PRJP_ID = a.PRJP_ID)as s_val
								FROM prjp_project a 
								WHERE 1=1 AND a.PRJP_LEVEL = '3' AND a.PRJP_PARENT_ID = '" . $rec['PRJP_ID'] . "' 
								order by ORDER_ROW_1,ORDER_ROW_2,ORDER_ROW_3,ORDER_NO
				";

										$iii = 1;
										$queryChild = $db->query($sqlChild);
										while ($recChild = $db->db_fetch_array($queryChild)) {
											$msa = 10;
											$mea = substr($recChild['EDATE_PRJP'], 5, 2);
											$yea = substr($recChild['EDATE_PRJP'], 0, 4) + 543;
											$ysa = substr($recChild['SDATE_PRJP'], 0, 4) + 543;

											$year_ms = $ysa . $msa;
											$year_me = $yea . $mea;
											$row_cola = (((12 - $msa) + 1) + ((($yea - $ysa) - 1) * 12) + (12 - (12 - $mea)));


										?>
											<tr bgcolor="#FFFFFF" class="tr-body-rtask">
												<td align="center" rowspan="4"><?php echo $recChild['ORDER_NO']; ?>. <input type="hidden" id="PRJP_ACT_ID[]" name="PRJP_ACT_ID[]" value="<?php echo $recChild['PRJP_ID']; ?>"></td>
												<td rowspan="4" align="left"><textarea rows="6" cols="10" class="prjp-name-show" disabled><?php echo text($recChild['PRJP_NAME']); ?></textarea></td>
												<td rowspan="4" align="center"><?php echo $recChild['WEIGHT']; ?></td>
												<td rowspan="4" align="center"><?php echo number_format($recChild['TRAGET_VALUE']); ?></td>
												<td rowspan="4" align="center"><?php echo number_format($recChild['s_val']); ?></td>
												<td rowspan="4" align="center"><?php echo text($recChild['UNIT_NAME']); ?></td>
												<td align="center" style="background:#bebebe" nowrap>แผนสะสม</td>
												<?php

												foreach ($rr as $key => $val) {
													if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
														$m_d = substr($val, 4, 2) * 1;
														$y_d = substr($val, 0, 4);
												?>
														<td align="right" style="background:#bebebe">
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
																	$sum_all[$recChild['PRJP_ID']] += $arr_p[$recChild['PRJP_ID']][$sy][$sm * 1];
																}
																echo @number_format($sum_all[$recChild['PRJP_ID']] + $arr_p[$recChild['PRJP_ID']][$y_d][$m_d], 2);
															} ?>
														</td>
												<?php
													}
												}
												?>
											</tr>
											<tr bgcolor="#FFFFFF" class="tr-body2-rtask">
												<td align="center" style="background:#afeeee">แผน</td>
												<?php
												foreach ($rr as $key => $val) {
													if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
														$m_d = substr($val, 4, 2) * 1;
														$y_d = substr($val, 0, 4);
												?>
														<td align="right" style="background:#afeeee">
															<?php if ($m[$key] == '') {
																echo "";
															} else {

															?>
															<?php echo @number_format($arr_p[$recChild['PRJP_ID']][$y_d][$m_d], 2);
															} ?></td>
												<?php
													} //if 
												} //foreach
												?>
											</tr>
											<tr bgcolor="#FFFFFF" class="tr-body3-rtask">
												<td align="center" style="background:#bebebe" nowrap>ผลสะสม</td>
												<?php
												$i = 1;
												foreach ($rr as $key => $val) {
													if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
														$m_d = substr($val, 4, 2) * 1;
														$y_d = substr($val, 0, 4);
												?>
														<td align="right" id="psum_act_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $i; ?>" style="background:#bebebe;">

														</td>
												<?php
													}
													$i++;
												}
												?>
											</tr>
											<tr bgcolor="#FFFFFF" class="tr-body4-rtask">
												<td align="center" style="background:#afeeee">ผล</td>
												<?php
												$k = 1;
												foreach ($rr as $key => $val) {
													if ($key >= $mstart && $key <= ($mend * $row) + $rowtb) {
														$m_d = substr($val, 4, 2) * 1;
														$y_d = substr($val, 0, 4);
														$mck = $y_d . sprintf("%'02d", $m_d);
														if ($_SESSION["sys_group_id"] != '5') {
															if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
																$input = ($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) . date("d");
																if ($input >= $chk_set_start && $input <= $chk_set_end) {
																	$distxt = "";
																	$bgdis = "";
																} else {
																	$distxt = "readonly";
																	$bgdis = "background:#9F9;";
																}
															} else {
																if (($m_d == 12 ? ($y_d + 1) . '01' : $mck + 1) == ($ymchk)) {
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
												?>
														<td align="right" style="">
															<?php if ($m[$key] == '') {
																echo "";
															} else {
															?>
																<input type="hidden" id="VCHK_YEAR_<?php echo $ii; ?>_<?php echo $k; ?>" name="VCHK_YEAR[]" value="<?php echo $mck; ?>">
																<input type="hidden" id="WEIGHT_S_ACT_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" name="WEIGHT[]" value="<?php echo $recChild['WEIGHT']; ?>">
																<input type="hidden" id="TRAGET_VALUE_S_ACT_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" name="TRAGET_VALUE[]" value="<?php echo $recChild['TRAGET_VALUE']; ?>">
																<input name="YEAR[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="YEAR_<?php echo $y_d . $m_d; ?>" type="hidden" size="5" class="form-control number_format" value="<?php echo ($y_d); ?>">
																<input <?php echo $arr_readonly[$recChild['PRJP_ID']]; ?> prjp-parent-id="<?php echo $recChild["PRJP_PARENT_ID"]; ?>" prjp-act-id="<?php echo $recChild['PRJP_ID']; ?>" month="<?php echo $m_d; ?>" name="BDG_VALUE[<?php echo $recChild['PRJP_ID']; ?>][<?php echo $y_d; ?>][<?php echo $m_d; ?>]" id="BDG_VALUE_<?php echo $recChild['PRJP_ID']; ?>_<?php echo $k; ?>" type="text" size="5" class="form-control PV_ACT_<?php echo $ii; ?>_<?php echo $k; ?>" value="<?php echo number_format($arr_r[$recChild['PRJP_ID']][$y_d][$m_d], 2); ?>" onBlur="Chk_sval('<?php echo $c_arr; ?>','<?php echo $recChild['PRJP_ID']; ?>','<?php echo $rec_head['SW']; ?>','<?php echo $rec_head['ST']; ?>','<?php echo $y_d; ?>','<?php echo $m_d; ?>');NumberFormat(this,2); cal_child(this);" style="text-align:right;<?php echo $bgdis; ?>" <?php echo $distxt; ?>><?php } ?>
														</td>
												<?php } //if
													$k++;
												} //foreach 
												?>
											</tr>
											<script>
												Chk_sval('<?php echo $c_arr; ?>', '<?php echo $recChild['PRJP_ID']; ?>', '<?php echo $rec_head['SW']; ?>', '<?php echo $rec_head['ST']; ?>');
												Sum_result_act('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');
												test_chkInput('<?php echo $c_arr; ?>', '<?php echo $num_rows; ?>', '<?php echo $ymchk_js; ?>');


												<?php /*?>Sum_result_v('<?php echo $c_arr; ?>','<?php echo $num_rows; ?>','<?php echo $ymchk_js; ?>');<?php */ ?>
											</script>
								<?php

											$iii++;
										}

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
					$mstart = $mstart + 12;
					$rowtb = $rowtb + 1;
				}
				?>
			</div>
		</div>
	</div>
</div>