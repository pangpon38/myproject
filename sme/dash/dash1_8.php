<?php include('comtop.php');
@set_time_limit(0);
$NoChk = 1;
$path = "../";
include($path . "include/config_header_top.php");

//ตรวจสอบเดือนล่าสุดของปีงบประมาณ
if (date("m") > 9) {
	$YEAR_CHECK = (date("Y") + 543) + 1;
} else {
	$YEAR_CHECK = (date("Y") + 543);
}

if ($_GET['year']) {
	$_SESSION['year_round'] = $_GET['year'];
} elseif (empty($_SESSION['year_round'])) {
	$_SESSION['year_round'] = $YEAR_CHECK;
}

$DASHBOARD_YEAR = $_SESSION['year_round'];

$sql_year = "select YEAR_BDG FROM plan_round ORDER BY YEAR_BDG DESC ";
$query_year = $db->query($sql_year);
while ($rec_y = $db->db_fetch_array($query_year)) {
	$arr_y[$rec_y['YEAR_BDG']] = $rec_y['YEAR_BDG'];
}
$default_year_round = array(
	'10' => (($DASHBOARD_YEAR - 1) * 100) + 10,
	'11' => (($DASHBOARD_YEAR - 1) * 100) + 11,
	'12' => (($DASHBOARD_YEAR - 1) * 100) + 12,
	'01' => ($DASHBOARD_YEAR * 100) + 1,
	'02' => ($DASHBOARD_YEAR * 100) + 2,
	'03' => ($DASHBOARD_YEAR * 100) + 3,
	'04' => ($DASHBOARD_YEAR * 100) + 4,
	'05' => ($DASHBOARD_YEAR * 100) + 5,
	'06' => ($DASHBOARD_YEAR * 100) + 6,
	'07' => ($DASHBOARD_YEAR * 100) + 7,
	'08' => ($DASHBOARD_YEAR * 100) + 8,
	'09' => ($DASHBOARD_YEAR * 100) + 9,
);





if ($DASHBOARD_YEAR < $YEAR_CHECK) {
	$YEAR_NOW = $DASHBOARD_YEAR;
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
$MAX_MONTH_OF_YEAR = $YEAR_MONTH_NOW > $DASHBOARD_YEAR . '09' ? '09' : $MONTH_NOW;
$MAX_YEAR_OF_YEAR = $YEAR_MONTH_NOW > $DASHBOARD_YEAR . '09' ? $DASHBOARD_YEAR : $YEAR_NOW;
$MAX_YEAR_MONTH = $MAX_YEAR_OF_YEAR . $MAX_MONTH_OF_YEAR;
if ($MAX_YEAR_MONTH > $YEAR_MONTH_NOW) {
	$MAX_YEAR_MONTH = $YEAR_MONTH_NOW;
}
$MIN_YEAR_MONTH = ($DASHBOARD_YEAR - 1) . '10';

$arr_year_month = array();
$arr_year = array();
for ($y_m = $MIN_YEAR_MONTH; $y_m <= $MAX_YEAR_MONTH; $y_m++) {
	$y = substr($y_m, 0, 4);
	$m = substr($y_m, 4, 2);

	$arr_year_month[$y_m]['Y'] = $y;
	$arr_year_month[$y_m]['M'] = $m;
	$arr_year_month[$y_m]['Y_SH'] = substr($y, 2, 2);
	$arr_year_month[$y_m]['M_T'] = $arr_month_short[$m];

	$arr_year[$y]++;

	if ($m == 12) {
		$y_m = ($y + 1) . sprintf('%02d', '00');
	}
}

$dash_closed_status_type[1]['color'] = 'red';
$dash_closed_status_type[2]['color'] = 'yellow';
$dash_closed_status_type[3]['color'] = 'green';
$dash_closed_status_type[1]['icon'] = 'file-alt';
$dash_closed_status_type[2]['icon'] = 'list';
$dash_closed_status_type[3]['icon'] = 'check-square';

// ---------------------------------------------------------------------------------------------------
// ตารางที่ 1 ความคืบหน้าการปิด งาน/โครงการ
// ---------------------------------------------------------------------------------------------------
$sql = "SELECT a.PRJP_ID, a.PRJP_CODE, a.PRJP_NAME, a.EDATE_PRJP, b.ORG_SHORTNAME
			, CAST(c.UPDATE_TIMESTAMP AS DATE) AS last_datetime, ISNULL(c.PRJP_CLOSED_ID, 1) AS [type], ISNULL(d.CLOSED_STATUS_ORDER, 0) AS [order], c.PRJP_CLOSED_DATE AS status_date
			, e.AE_FNAME, e.AE_LNAME
		FROM prjp_project a
			LEFT JOIN setup_org_bu b ON b.ORG_ID = a.ORG_ID
			LEFT JOIN prjp_closed_status c ON c.PRJP_ID = a.PRJP_ID AND c.ACTIVE_STATUS = 1
			LEFT JOIN dash_closed_status d ON d.CLOSED_STATUS_ID = c.PRJP_CLOSED_SUB_ID AND d.CLOSED_STATUS_TYPE = c.PRJP_CLOSED_ID
			LEFT JOIN prjp_ae e ON e.PRJP_ID = a.PRJP_ID
		WHERE a.PRJP_LEVEL = 1 AND a.PRJP_STATUS_SHOW = 1 AND a.YEAR_BDG = '{$DASHBOARD_YEAR}'
		ORDER BY RIGHT(a.PRJP_CODE, 3) ASC, a.PRJP_CODE ASC,a.PRJP_ID ASC
			, d.CLOSED_STATUS_TYPE ASC, d.CLOSED_STATUS_ORDER ASC
		";
$query = $db->query($sql);
$arr_prjp_closed = array();
$last_datetime = '';
while ($rec = $db->db_fetch_array($query)) {
	$type = $rec['type'];
	$order = $rec['order'];
	$CLOSED_STATUS = $type . '.' . $order;
	$arr_prjp_closed[$rec['PRJP_ID']]['statusorder'][$CLOSED_STATUS] = $rec;
	$arr_prjp_closed[$rec['PRJP_ID']]['status2index'] = $rec;

	$date_check = $rec['last_datetime'];
	if ($date_check > $last_datetime) {
		$last_datetime = $date_check;
	}
}
$project_count = count($arr_prjp_closed);
$last_datetime = empty($last_datetime) ? date('Y-m-d') : $last_datetime;

// ---------------------------------------------------------------------------------------------------
// กราฟที่ 1 road map
// ---------------------------------------------------------------------------------------------------
$arr_prjp_closed_status = array();
if (count($arr_prjp_closed) > 0) {
	foreach ($arr_prjp_closed as $PRJP_ID => $arrStatus) {
		$rec = $arrStatus['status2index'];
		$type = $rec['type'];
		$order = $rec['order'];
		$CLOSED_STATUS = $type . '.' . $order;
		$arr_prjp_closed_status[$CLOSED_STATUS][$PRJP_ID] = "{$rec['PRJP_NAME']} / {$rec['ORG_SHORTNAME']}";
		$dash_closed_status_type[$type]['count']++;
	}
}

$sql = "SELECT
			* 
		FROM
			(
			SELECT
				CLOSED_STATUS_TYPE_ID as type,
				concat ( CLOSED_STATUS_TYPE_ID, '.0' ) AS [order],
				CLOSED_STATUS_TYPE_NAME AS name,
				1 AS [level] 
			FROM
				dash_closed_status_type UNION ALL
			SELECT
				CLOSED_STATUS_TYPE as type,
				concat ( CLOSED_STATUS_TYPE, '.', CLOSED_STATUS_ORDER ) AS [order],
				CLOSED_STATUS_NAME AS name,
				2 AS [level] 
			FROM
				dash_closed_status 
			) TB 
		ORDER BY
			[order]
		";
$query = $db->query($sql);
$dash_closed_status = array();
while ($rec = $db->db_fetch_array($query)) {
	$dash_closed_status[$rec['order']] = $rec;
}

// ---------------------------------------------------------------------------------------------------
// ตารางที่ 2 ความคืบหน้าการดำเนิน งาน/โครงการ
// ---------------------------------------------------------------------------------------------------

// ประเภทการดำเนินงาน
$sql = "SELECT STATUS_TYPE AS [type], STATUS_TYPE_NAME AS [name] FROM dash_prjp_status_type ";
$query = $db->query($sql);
$arr_dash_prjp_status_type = array();
while ($rec = $db->db_fetch_array($query)) {
	$arr_dash_prjp_status_type[$rec['type']] = $rec['name'];
}
// รายการการดำเนินงาน
$sql = "SELECT STATUS_TYPE AS [type], STATUS_NAME AS [name], STATUS_ID AS [id] FROM dash_prjp_status ";
$query = $db->query($sql);
$arr_dash_prjp_status = array();
while ($rec = $db->db_fetch_array($query)) {
	$arr_dash_prjp_status[$rec['type']][$rec['id']] = $rec['name'];
}

// ความคืบหน้าการดำเนิน งาน/โครงการ
$sql = "SELECT a.PRJP_ID, a.PRJP_CODE, a.PRJP_NAME, a.EDATE_PRJP
			, e.AE_FNAME, e.AE_LNAME
			, c.PRJP_STATUS_DETAIL_ID
			, ISNULL(d.PRJP_STATUS_DETAIL_TYPE, 1) AS [type], ISNULL(d.PRJP_STATUS_SUB_ID, 0) AS [status_id], d.PRJP_STATUS_SUB_DATE AS status_date
		FROM prjp_project a
			LEFT JOIN prjp_status_detail c ON c.PRJP_ID = a.PRJP_ID 
			LEFT JOIN prjp_status_sub_detail d ON d.PRJP_STATUS_DETAIL_ID = c.PRJP_STATUS_DETAIL_ID AND d.ACTIVE_STATUS = 1
			LEFT JOIN prjp_ae e ON e.PRJP_ID = a.PRJP_ID
		WHERE a.PRJP_LEVEL = 1 AND a.PRJP_STATUS_SHOW = 1 AND a.YEAR_BDG = '{$DASHBOARD_YEAR}'
		ORDER BY RIGHT(a.PRJP_CODE, 3) ASC, a.PRJP_CODE ASC,a.PRJP_ID ASC
			, d.PRJP_STATUS_DETAIL_TYPE ASC, d.PRJP_STATUS_SUB_ID ASC
		";
$query = $db->query($sql);
$arr_prjp_status_detail = array();
$arr_prjp_status_count = array();
while ($rec = $db->db_fetch_array($query)) {
	$type = $rec['type'];
	$status_id = $rec['status_id'];
	$status_date = $rec['status_date'];
	$pk_id = $rec['PRJP_ID'];
	$pk_id2 = $rec['PRJP_STATUS_DETAIL_ID'];
	$arr_prjp_status_detail[$pk_id]['code'] = $rec['PRJP_CODE'];
	$arr_prjp_status_detail[$pk_id]['name'] = $rec['PRJP_NAME'];
	$arr_prjp_status_detail[$pk_id]['edate'] = $rec['EDATE_PRJP'];
	$arr_prjp_status_detail[$pk_id]['ae'] = $rec['AE_FNAME'] . ' ' . $rec['AE_LNAME'];

	if ($pk_id2 != '') {
		$arr_prjp_status_detail[$pk_id]['byStatus'][$type][$status_id][$pk_id2] = $status_date;
		$arr_prjp_status_detail[$pk_id]['byPK'][$pk_id2] = $status_date;

		$old_date = $arr_prjp_status_detail[$pk_id]['max_date'][$type][$status_id];
		if ($status_date > $old_date) {
			$arr_prjp_status_detail[$pk_id]['max_date'][$type][$status_id] = $status_date;
		}

		$arr_prjp_status_count[$type][$status_id]++;
	}
}
// print_arr($arr_prjp_status_detail); exit;
?>

<!-- Open 20211018 -->
<!-- Style Timeline -->
<link rel="stylesheet" href="css/map_css.css">
<!-- Close 20211018 -->
<style>
	.wrapper {
		max-width: unset;
	}

	.timeline-box {
		max-width: 400px;
	}
</style>
<div class="wrapper">
	<?php include('navtop.php'); ?>
	<?php include('menu_sidebar.php'); ?>

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<div class="content-header">
			<div class="container-fluid">
				<div class="row mb-2">
					<div class="col-md-12 col-sm-12 col-12">
						<h1 class="m-0 font-dark"> สถานะปิดงาน/คก. ปี <?php echo $DASHBOARD_YEAR; ?> จำนวน <?php echo $project_count; ?> โครงการ </h1>
						<div class=""> (ข้อมูล ณ <?php echo conv_date($last_datetime, 'short'); ?>) </div>
					</div><!-- /.col -->
					<!--
				  <div class="col-md-6 col-sm-12 col-12">
					<ol class="breadcrumb float-sm-right">
					  <li class="breadcrumb-item"><a href="#">กองทุน สสว.</a></li>
					  <li class="breadcrumb-item active">ภาพรวม</li>
					</ol>
				  </div>-->
					<!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.container-fluid -->
		</div>
		<!-- /.content-header -->



		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">

				<!-- Open 20211018 -->
				<!-- Timelime example  -->
				<div class="dis-pc-timeline">
					<div class="row">
						<div class="col-lg-12">
							<div class="card no-shadow border-box">
								<div class="card-header border-0">

									<section class="about-timeline">
										<div class="wrapper inner-wrapper-padding ">

											<div class="start-point">
												<div class="black-dot"></div>
												<div class="corner bl"></div>
											</div>


											<div class="timeline-main">
												<div class="timeline-row">
													<?php
													$i = 1;
													$firstCorner = 1;
													$c = 1;
													foreach ($dash_closed_status as $order => $statusDTL) {
														$type = $statusDTL['type'];
														$color = $dash_closed_status_type[$type]['color'];
														$level = $statusDTL['level'];
														$name = $statusDTL['name'];
														$count = count($arr_prjp_closed_status[$order]);
														$number = $level == 1 ? $type : $order;
													?>
														<div class="timeline-box img-right timeline-content-img">
															<?php if ($level == 1) { ?>
																<div class="start-point point-road" style="">
																	<h4 class="point-<?php echo $color; ?>"><?php echo $number; ?> <?php echo text($name); ?> <?php echo (int)$dash_closed_status_type[$type]['count']; ?> โครงการ</h4>
																</div>
															<?php } ?>
															<div class="timeline-box-wrap">
																<h6 class="num-location num-<?php echo $color; ?>" style="<?php echo $level == 2 ? 'padding-left:0px;' : ''; ?>"><?php echo $number; ?></h6>
																<img src="images/location-<?php echo $color; ?>.png" class="location" title="location" alt="location">
																<div class="timeline-content">
																	<div class="timeline-content-txt">
																		<p>
																			<a href="#" class="num-<?php echo $color; ?>">
																				<?php echo text($name); ?>
																			</a>
																			(<?php echo $count; ?>) โครงการ
																			<?php
																			if ($count > 0) { ?>
																		<ol class="pad-l-25px mar-t--25 mar-b-30">
																			<?php
																				foreach ($arr_prjp_closed_status[$order] as $pID => $pName) {
																			?>
																				<li class="font15px text-dark">
																					<a href="popup_dash1.php?type=1&id=<?php echo $pID; ?>" target="_blank" class="text-dark"> <?php echo text($pName); ?> </a>
																				</li>
																			<?php
																				}
																			?>
																		</ol>
																	<?php
																			}
																	?>
																	</p>
																	</div>
																</div>
															</div>
														</div>

														<?php
														if ($c == count($dash_closed_status)) {
														?>
															<div class="horizontal-line"></div>
														<?php
														} elseif ($i == 6) { ?>
															<div class="horizontal-line"></div>
															<div class="verticle-line"></div>
															<div class="corner top corner-l-t"></div>
															<div class="corner bottom corner-l-b"></div>
												</div>
												<div class="timeline-row">
												<?php
															$i = 0;
														} elseif ($i == 3) { ?>
													<div class="horizontal-line <?php echo $firstCorner == 1 ? 'first-corner' : ''; ?>"></div>
													<div class="verticle-line"></div>
													<div class="corner top corner-r-t"></div>
													<div class="corner bottom corner-r-b"></div>
												</div>
												<div class="timeline-row">
												<?php
															$firstCorner = 2;
														} ?>
											<?php
														$i++;
														$c++;
													}
											?>
												</div>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Timelime example  -->




				<div class="dis-mobile-timeline">
					<div class="row">
						<div class="col-md-12">
							<!-- The time line -->
							<div class="timeline">
								<?php
								foreach ($dash_closed_status as $order => $statusDTL) {
									$type = $statusDTL['type'];
									$color = $dash_closed_status_type[$type]['color'];
									$icon = $dash_closed_status_type[$type]['icon'];
									$level = $statusDTL['level'];
									$name = $statusDTL['name'];
									$count = count($arr_prjp_closed_status[$order]);
									$number = $level == 1 ? $type . '.' : $order;
								?>
									<?php if ($level == 1) { ?>
										<!-- timeline time label -->
										<div class="time-label">
											<span class="bg-<?php echo $color; ?> px-3"> <?php echo $number; ?> <?php echo text($name); ?> <?php echo (int)$dash_closed_status_type[$type]['count']; ?> โครงการ </span>
										</div>
										<!-- /.timeline-label -->
									<?php
									} else { ?>
										<!-- timeline item -->
										<div>
											<i class="far fa-<?php echo $icon; ?> bg-<?php echo $color; ?>"></i>
											<div class="timeline-item">
												<h3 class="timeline-header"><a href="#" class="num-<?php echo $color; ?>"> <?php echo $number; ?> <?php echo text($name); ?> </a> (<?php echo $count; ?>) โครงการ </h3>
												<?php if ($count > 0) { ?>
													<div class="timeline-body">
														<ol>
															<?php foreach ($arr_prjp_closed_status[$order] as $pID => $pName) { ?>
																<li>
																	<a href="popup_dash1.php?type=1&id=<?php echo $pID; ?>" target="_blank" class="text-dark"> <?php echo text($pName); ?> </a>
																</li>
															<?php } ?>
														</ol>
													</div>
												<?php } ?>
											</div>
										</div>
										<!-- END timeline item -->
								<?php
									}
								}
								?>
								<div>
									<i class="fas fa-clock bg-gray"></i>
								</div>
							</div>
						</div>
						<!-- /.col -->
					</div>
				</div>

				<!-- Close 20211018 -->

				<div class="row">
					<div class="col-lg-12">
						<div class="card no-shadow border-box">
							<div class="card-header border-0">
								<div class="d-flex justify-content-between">
									<h3 class="card-title"> ความคืบหน้าการปิด งาน/โครงการ </h3>
									<!--<a href="#"> <em class="fas fa-cogs"></em> </a>-->
								</div>
								<div class="mt-2">
									<div class="dot1 dot-green ml-2 p-2"></div> สีเขียว ดำเนินการแล้ว
									<!--<div class="dot1 data-3 ml-3 p-2"></div> สีมากเขียว-เทาน้อย อนุมัติหรือแนบไฟล์แล้วใกล้ครบ-->
									<!--<div class="dot1 data-half ml-3 p-2"></div> สีครึ่งเขียว-เทา อนุมัติหรือแนบไฟล์แล้ว-->
									<!--<div class="dot1 data-1 ml-3 p-2"></div> สีน้อยเขียว-เทามาก อนุมัติหรือแนบไฟล์แล้วบางส่วน-->
									<div class="dot1 data-gray ml-3 p-2"></div> สีเทา ยังไม่ดำเนินการ
								</div>
							</div>
							<div class="card-body">

								<div class="table-responsive">
									<table id="example2" class="table table-bordered font13px">
										<thead class="bg-table-top-3">
											<tr>
												<th class="vertical-top"> โครงการ </th>
												<?php
												if (count($dash_closed_status) > 0) {
													foreach ($dash_closed_status as $order => $statusDTL) {
														$level = $statusDTL['level'];
														if ($level == 1) {
															continue;
														}
														$name = $statusDTL['name'];
												?>
														<th class="vertical-top"> <?php echo text($name); ?> </th>
												<?php
													}
												}
												?>
												<th class="vertical-top"> AE </th>
											</tr>
										</thead>
										<tbody>
											<?php
											if (count($arr_prjp_closed) > 0) {
												foreach ($arr_prjp_closed as $PRJP_ID => $arrDTL) {
													$rec1 = $arrDTL['status2index'];
													$pName = $rec1['PRJP_NAME'];
													$aeName = $rec1['AE_FNAME'] . ' ' . $rec1['AE_LNAME'];
													$edate = $rec1['EDATE_PRJP'];
													$edate_color = $edate >= date('Y-m-d') ? 'green' : 'red';

													$rec = $arrDTL['statusorder'];
													$type = $rec['type'];
													$order = $rec['order'];
													$CLOSED_STATUS = $type . '.' . $order;

											?>
													<tr>
														<td>
															<a href="popup_dash1.php?type=1&id=<?php echo $pID; ?>" target="_blank" class="text-dark">
																<?php echo text($pName); ?>
															</a>
															<div class="num-<?php echo $edate_color; ?> pt-3"> วันสิ้นสุดโครงการ <?php echo conv_date($edate); ?> </div>
														</td>
														<?php
														if (count($dash_closed_status) > 0) {
															foreach ($dash_closed_status as $order => $statusDTL) {
																$level = $statusDTL['level'];
																if ($level == 1) {
																	continue;
																}
																$name = $statusDTL['name'];
																$hasData = isset($rec[$order]) ? 1 : 0;
																$status_date = $rec[$order]['status_date'];
														?>
																<td class="text-center">
																	<?php if ($hasData == 1) { ?>
																		<a href="popup_dash1.php?type=1&id=<?php echo $pID; ?>" target="_blank">
																			<div class="dot1 dot-green p-3"></div>
																		</a>
																		<div> <?php echo $status_date; ?> </div>
																	<?php } else { ?>
																		<div class="text-dark p-line"> - </div>
																	<?php } ?>
																</td>
														<?php
															}
														}
														?>
														<td class="text-center">
															<div> <?php echo text($aeName); ?> </div>
														</td>
													</tr>
											<?php
												}
											}
											?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<!-- /.card -->
					</div>
					<!-- /.col-md-6 -->


					<div class="col-lg-12">
						<div class="card no-shadow border-box">
							<div class="card-header border-0">
								<div class="d-flex justify-content-between">
									<h3 class="card-title"> ความคืบหน้าการดำเนิน งาน/โครงการ </h3>
									<!--<a href="#" class="float-right"> <em class="fas fa-cogs"></em> </a>-->
								</div>
								<div class="mt-2">
									<div class="dot1 dot-green ml-2 p-2"></div> สีเขียว ดำเนินการแล้ว
									<!--<div class="dot1 data-3 ml-3 p-2"></div> สีมากเขียว-เทาน้อย อนุมัติหรือแนบไฟล์แล้วใกล้ครบ-->
									<!--<div class="dot1 data-half ml-3 p-2"></div> สีครึ่งเขียว-เทา อนุมัติหรือแนบไฟล์แล้ว-->
									<!--<div class="dot1 data-1 ml-3 p-2"></div> สีน้อยเขียว-เทามาก อนุมัติหรือแนบไฟล์แล้วบางส่วน-->
									<div class="dot1 data-gray ml-3 p-2"></div> สีเทา ยังไม่ดำเนินการ
								</div>
							</div>
							<div class="card-body">

								<div class="table-responsive">
									<table class="table table-bordered font13px">
										<thead class="bg-table-top-3">
											<tr>
												<th colspan="2" rowspan="2" class="vertical-top"> โครงการ </th>
												<?php
												if (count($arr_dash_prjp_status_type) > 0) {
													foreach ($arr_dash_prjp_status_type as $type => $name) {
														$status_count = count($arr_dash_prjp_status[$type]);
														if ($status_count == 0) {
															continue;
														}
												?>
														<th colspan="<?php echo $status_count; ?>" class="vertical-top text-center"> <?php echo text($name); ?> </th>
												<?php
													}
												}
												?>
												<th rowspan="3" class="vertical-top"> AE </th>
											</tr>
											<tr>
												<?php
												if (count($arr_dash_prjp_status_type) > 0) {
													foreach ($arr_dash_prjp_status_type as $type => $name) {
														$arr2 = $arr_dash_prjp_status[$type];
														if (count($arr2) > 0) {
															foreach ($arr2 as $id => $name2) {
												?>
																<th class="vertical-top text-center"> <?php echo text($name2); ?> </th>
												<?php
															}
														}
													}
												}
												?>
											</tr>
											<tr>
												<th colspan="2" class="vertical-top"> รวมจำนวนสัญญาที่ดำเนินการแล้ว </th>
												<?php
												if (count($arr_dash_prjp_status_type) > 0) {
													foreach ($arr_dash_prjp_status_type as $type => $name) {
														$arr2 = $arr_dash_prjp_status[$type];
														if (count($arr2) > 0) {
															foreach ($arr2 as $id => $name2) {
																$count = $arr_prjp_status_count[$type][$id];
																$cText = $count > 0 ? $count . ' สัญญา' : '-';
												?>
																<th class="vertical-top text-center"> <?php echo $cText; ?> </th>
												<?php
															}
														}
													}
												}
												?>
											</tr>
										</thead>
										<tbody>
											<?php
											if (count($arr_prjp_status_detail) > 0) {
												foreach ($arr_prjp_status_detail as $pID => $rec) {
													$pName = $rec['name'];
													$aeName = $rec['ae'];
													$edate = $rec['edate'];
													$edate_color = $edate >= date('Y-m-d') ? 'green' : 'red';
													$countbyPK = count($rec['byPK']);
											?>
													<tr>
														<td>
															<a href="popup_dash1.php?type=1&id=<?php echo $pID; ?>" target="_blank" class="text-dark">
																<?php echo text($pName); ?>
															</a>
															<div class="num-<?php echo $edate_color; ?> pt-3"> วันสิ้นสุดโครงการ <?php echo conv_date($edate); ?> </div>
														</td>
														<td class="text-center">
															<?php if ($countbyPK > 0) { ?>
																<div class="text-dark p-line"> <?php echo $countbyPK; ?> สัญญา </div>
															<?php } else { ?>
																<div class="text-dark p-line"> - </div>
															<?php } ?>
														</td>
														<?php
														if (count($arr_dash_prjp_status_type) > 0) {
															foreach ($arr_dash_prjp_status_type as $type => $name) {
																$arr2 = $arr_dash_prjp_status[$type];
																if (count($arr2) > 0) {
																	foreach ($arr2 as $id => $name2) {
																		$countbyStatus = count($rec['byStatus'][$type][$id]);
																		$date = $rec['max_date'][$type][$id];
														?>
																		<td class="text-center">
																			<?php if ($countbyStatus > 0) { ?>
																				<a href="popup_dash1.php?type=1&id=<?php echo $pID; ?>" target="_blank">
																					<div class="dot1 data-green p-3"></div>
																				</a>
																				<div class="font12px"> ดำเนินการแล้ว <?php echo $countbyStatus; ?> สัญญา </div>
																				<div> <?php echo $date; ?> </div>
																			<?php } else { ?>
																				<div class="text-dark p-line"> - </div>
																			<?php } ?>
																		</td>
														<?php
																	}
																}
															}
														}
														?>
														<td class="text-center">
															<div> <?php echo text($aeName); ?> </div>
														</td>
													</tr>
											<?php
												}
											}
											?>
										</tbody>
									</table>
								</div>

							</div>
						</div>
						<!-- /.card -->
					</div>
					<!-- /.col-md-6 -->


				</div>
				<!-- /.row -->




			</div>
			<!-- /.timeline -->

		</section>
		<!-- /.content -->


	</div>
	<!-- /.content-wrapper -->

	<!-- Control Sidebar -->
	<aside class="control-sidebar control-sidebar-dark">
		<!-- Control sidebar content goes here -->
	</aside>
	<!-- /.control-sidebar -->

	<?php include('main_footer.php'); ?>
</div>
<!-- ./wrapper -->


<!-- Script Listbox Onchange -->
<script>
	function show() {
		var option = document.getElementById("category").value;
		if (option == "select1") {
			document.getElementById("table1").style.display = "block";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "none";
		}
		if (option == "select2") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "block";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "none";
		}
		if (option == "select3") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "block";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "none";
		}
		if (option == "select4") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "block";
			document.getElementById("table5").style.display = "none";
		}
		if (option == "select5") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "block";
		}
	}
</script>


<!-- Script Table -->
<script>
	$('.container tr:not(.header)').slideToggle(0, function() {});

	$('.header').click(function() {
		$(this).find('span').text(function(_, value) {
			return value == '-' ? '+' : '-'
		});
		$(this).nextUntil('tr.header').find('tr').slideToggle(0);
		$(this).nextUntil('tr.header').slideToggle(10, function() {});
	});

	$(document).ready(function() {
		$('.first').nextUntil('tr.header').slideToggle(0);
	});
</script>

<!-- REQUIRED SCRIPTS -->

<?php include('combottom.php'); ?>