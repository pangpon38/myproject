<?php include('comtop.php');
$path = "../";
include "../dashboard/index_data.php";

?>

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
						<h1 class="m-0 font-dark"> สถานะการเบิกจ่ายตามปีงบประมาณ </h1>
						<div class="mt-2">
							<div class="dot1 dot-green ml-2"></div> ยอดเบิกจ่าย
							<div class="dot1 dot-yellow ml-2"></div> ยอดผูกพัน
							<div class="dot1 dot-red ml-2"></div> ยอดคงเหลือ
						</div>
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
		<div class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12">


						<div class="card no-shadow">
							<div class="card-body">
								<!-- <h4 class="mb-3"> เลือกมุมมองการแสดงผล </h4> -->
								<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
									<li class="nav-item">
										<a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true"> มุมมอง % </a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false"> มุมมองเงิน </a>
									</li>
								</ul>
								<div class="tab-content" id="custom-content-below-tabContent">
									<!-- Open Tab 1 -->
									<div class="tab-pane fade show active pt-3" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
										<div class="card-title font18px">เลือกมุมมองการแสดง : </div>
										<div class="form-group row">
											<label hidden> เลือกการแสดงผลข้อมูล </label>
											<select class="form-control ml-3" style="max-width: 300px; height: 30px;" id="category" onchange="show()">
												<option value="select1"> 5 ปีปัจจุบัน </option>
												<option value="select2"> 4 ปีย้อนหลัง </option>
												<option value="select3"> 3 ปีย้อนหลัง </option>
												<option value="select4"> 2 ปีย้อนหลัง </option>
												<option value="select5"> 1 ปีย้อนหลัง </option>
												<option value="select6"> กำหนดปีการแสดงผล </option>
											</select>
										</div>
										<!-- Open Tab 1-1 -->
										<div class="" id="table1" style="display: block;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 5 years % -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_percent.php?mode=&PREV_YEAR=5" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 5;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 1;
																				$mode = '';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 5 years % -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<!-- Close Tab 1-1 -->

										<div class="" id="table2" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 4 years % -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_percent.php?mode=&PREV_YEAR=4" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 4;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 1;
																				$mode = '';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 4 years % -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table3" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 3 years % -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_percent.php?mode=&PREV_YEAR=3" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 3;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 1;
																				$mode = '';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 3 years % -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table4" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 2 years % -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_percent.php?mode=&PREV_YEAR=2" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 2;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 1;
																				$mode = '';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 2 years % -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table5" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 1 years % -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_percent.php?mode=&PREV_YEAR=1" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 1;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 1;
																				$mode = '';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 1 years % -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table6" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 6 % -->
																<div class="col-12">
																	<form>
																		<div class="row mb-5">
																			<div class="col-lg-2 col-md-2 col-sm-5 col-12">
																				<!-- text input -->
																				<div class="form-group">
																					<label> จาก </label>
																					<input type="text" id="column_stacked_start_year1" class="form-control" placeholder="กรอกเลขปี พ.ศ." value="2560">
																				</div>
																			</div>
																			<div class="col-lg-2 col-md-2 col-sm-5 col-12">
																				<!-- text input -->
																				<div class="form-group">
																					<label> ถึง </label>
																					<input type="text" id="column_stacked_end_year1" class="form-control" placeholder="กรอกเลขปี พ.ศ." value="2564">
																				</div>
																			</div>
																			<div class="col-lg-1 col-md-1 col-sm-1 col-1">
																				<button type="button" class="btn btn-primary" onclick="get_column_stacked(1);" style="margin-top: 31px;"> ค้นหา </button>
																			</div>
																		</div>
																	</form>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">

																	<iframe src="chart_dash1_3_percent.php??mode=&column_stacked_start_year=2560&column_stacked_end_year=2564" id="iframe_column_stacked1" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0" id="div_column_stacked1">

																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 6 years % -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>

									</div>
									<!-- Close Tab 1 -->


									<!----------------------------------------------------  มุมมองเงิน  ------------------------------------------------------>

									<!-- Open Tab 2 -->
									<div class="tab-pane fade pt-3" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
										<div class="card-title font18px">เลือกมุมมองการแสดง : </div>
										<div class="form-group row">
											<label hidden> เลือกการแสดงผลข้อมูล </label>
											<select class="form-control ml-3" style="max-width: 300px; height: 30px;" id="category2" onchange="show2()">
												<option value="select7"> 5 ปีปัจจุบัน </option>
												<option value="select8"> 4 ปีย้อนหลัง </option>
												<option value="select9"> 3 ปีย้อนหลัง </option>
												<option value="select10"> 2 ปีย้อนหลัง </option>
												<option value="select11"> 1 ปีย้อนหลัง </option>
												<option value="select12"> กำหนดปีการแสดงผล </option>
											</select>
										</div>

										<div class="" id="table7" style="display: block;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 5 years money -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_money.php?mode=BDG&PREV_YEAR=5" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<!-- /.card-header -->
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 5;
																			?>
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 0;
																				$mode = 'BDG';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>

																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 5 years money -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table8" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 4 years money -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_money.php?mode=BDG&PREV_YEAR=4" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 4;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 0;
																				$mode = 'BDG';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 4 years money -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table9" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 3 years money -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_money.php?mode=BDG&PREV_YEAR=3" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 3;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 0;
																				$mode = 'BDG';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 3 years money -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table10" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 2 years money -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_money.php?mode=BDG&PREV_YEAR=2" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 2;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 0;
																				$mode = 'BDG';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 2 years money -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table11" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<!-- Oepn Content 1 years money -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_money.php?mode=BDG&PREV_YEAR=1" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<?php
																			$YEAR_bdg_prev_max = $DASHBOARD_YEAR;
																			$YEAR_bdg_prev_min = $DASHBOARD_YEAR - 1;
																			?>
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0">
																				<?php
																				$no_bdg = 0;
																				$mode = 'BDG';
																				?>
																				<table class="table table-bordered text-nowrap">
																					<thead>
																						<tr class="h-table-color1">
																							<th class="text-center"> ปีงบประมาณ </th>
																							<?php if ($no_bdg != 1) { ?>
																								<th class="text-center"> งบประมาณ </th>
																							<?php } ?>
																							<th class="text-center"> ยอดเบิกจ่ายรวม </th>
																							<th class="text-center"> ยอดผูกพันรวม </th>
																							<th class="text-center"> ยอดคงเหลือ </th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						include('dash1_3_money.php');
																						?>
																					</tbody>
																				</table>
																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content 1 years money -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>
										<div class="" id="table12" style="display: none;">
											<div class="row" style="display: block;">
												<div class="col-lg-12 col-md-12 col-sm-12 col-12">
													<div class="card no-shadow border-box">
														<div class="card-body">
															<div class="row">
																<div class="col-12">
																	<form>
																		<div class="row mb-5">
																			<div class="col-lg-2 col-md-2 col-sm-5 col-12">
																				<!-- text input -->
																				<div class="form-group">
																					<label> จาก </label>
																					<input type="text" id="column_stacked_start_year2" class="form-control" placeholder="กรอกเลขปี พ.ศ." value="2560">
																				</div>
																			</div>
																			<div class="col-lg-2 col-md-2 col-sm-5 col-12">
																				<!-- text input -->
																				<div class="form-group">
																					<label> ถึง </label>
																					<input type="text" id="column_stacked_end_year2" class="form-control" placeholder="กรอกเลขปี พ.ศ." value="2564">
																				</div>
																			</div>
																			<div class="col-lg-1 col-md-1 col-sm-1 col-1">
																				<button type="button" class="btn btn-primary" onclick="get_column_stacked(2);" style="margin-top: 31px;"> ค้นหา </button>
																			</div>
																		</div>
																	</form>
																</div>
																<!-- Oepn Content money -->
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<iframe src="chart_dash1_3_money.php?mode=BDG&column_stacked_start_year=2560&column_stacked_end_year=2564" id="iframe_column_stacked2" style="border:none; width:100%; height:400px;    margin-top: -35px;"></iframe>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-12 col-12">
																	<div class="" id="yeardata1">
																		<div class="card no-shadow">
																			<!-- /.card-header -->
																			<div class="card-body table-responsive p-0" id="div_column_stacked2">

																			</div>
																			<!-- /.card-body -->
																		</div>
																		<!-- /.card -->
																	</div>
																</div>
																<!-- Close Content money -->
															</div>
														</div>
													</div>
													<!-- /.card -->
												</div>
												<!-- /.col-md-6 -->

											</div>
											<!-- /.row -->
										</div>

									</div>
									<!-- Close Tab 2 -->

								</div>
							</div>
						</div>

					</div>
				</div>






			</div>
			<!-- /.container-fluid -->
		</div>
		<!-- /.content -->
	</div>
	<!-- /.content-wrapper -->



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
			document.getElementById("table6").style.display = "none";
		}
		if (option == "select2") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "block";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "none";
			document.getElementById("table6").style.display = "none";
		}
		if (option == "select3") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "block";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "none";
			document.getElementById("table6").style.display = "none";
		}
		if (option == "select4") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "block";
			document.getElementById("table5").style.display = "none";
			document.getElementById("table6").style.display = "none";
		}
		if (option == "select5") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "block";
			document.getElementById("table6").style.display = "none";
		}
		if (option == "select6") {
			document.getElementById("table1").style.display = "none";
			document.getElementById("table2").style.display = "none";
			document.getElementById("table3").style.display = "none";
			document.getElementById("table4").style.display = "none";
			document.getElementById("table5").style.display = "none";
			document.getElementById("table6").style.display = "block";
			get_column_stacked(1);
		}

	}


	function show2() {
		var option = document.getElementById("category2").value;
		if (option == "select7") {
			document.getElementById("table7").style.display = "block";
			document.getElementById("table8").style.display = "none";
			document.getElementById("table9").style.display = "none";
			document.getElementById("table10").style.display = "none";
			document.getElementById("table11").style.display = "none";
			document.getElementById("table12").style.display = "none";
		}
		if (option == "select8") {
			document.getElementById("table7").style.display = "none";
			document.getElementById("table8").style.display = "block";
			document.getElementById("table9").style.display = "none";
			document.getElementById("table10").style.display = "none";
			document.getElementById("table11").style.display = "none";
			document.getElementById("table12").style.display = "none";
		}
		if (option == "select9") {
			document.getElementById("table7").style.display = "none";
			document.getElementById("table8").style.display = "none";
			document.getElementById("table9").style.display = "block";
			document.getElementById("table10").style.display = "none";
			document.getElementById("table11").style.display = "none";
			document.getElementById("table12").style.display = "none";
		}
		if (option == "select10") {
			document.getElementById("table7").style.display = "none";
			document.getElementById("table8").style.display = "none";
			document.getElementById("table9").style.display = "none";
			document.getElementById("table10").style.display = "block";
			document.getElementById("table11").style.display = "none";
			document.getElementById("table12").style.display = "none";
		}
		if (option == "select11") {
			document.getElementById("table7").style.display = "none";
			document.getElementById("table8").style.display = "none";
			document.getElementById("table9").style.display = "none";
			document.getElementById("table10").style.display = "none";
			document.getElementById("table11").style.display = "block";
			document.getElementById("table12").style.display = "none";
		}
		if (option == "select12") {
			document.getElementById("table7").style.display = "none";
			document.getElementById("table8").style.display = "none";
			document.getElementById("table9").style.display = "none";
			document.getElementById("table10").style.display = "none";
			document.getElementById("table11").style.display = "none";
			document.getElementById("table12").style.display = "block";
			get_column_stacked(2);
		}

	}
</script>

<script>
	function get_column_stacked(mode) {
		var column_stacked_start_year = $('#column_stacked_start_year' + mode).val();
		var column_stacked_end_year = $('#column_stacked_end_year' + mode).val();
		if (mode == '1') {
			$('input[type=text][id=column_stacked_start_year2]').val(column_stacked_start_year)
			$('input[type=text][id=column_stacked_end_year2]').val(column_stacked_end_year)
		} else {
			$('input[type=text][id=column_stacked_start_year1]').val(column_stacked_start_year)
			$('input[type=text][id=column_stacked_end_year1]').val(column_stacked_end_year)
		}

		//%
		$.ajax({
			url: 'dash1_3_percent_confix.php',
			type: 'POST',
			data: {
				column_stacked_start_year: column_stacked_start_year,
				column_stacked_end_year: column_stacked_end_year
			},
			success: function(result) {
				$('div[id="div_column_stacked1"]').html(result);
			}
		});

		document.getElementById('iframe_column_stacked1').src = "chart_dash1_3_percent.php?mode=&column_stacked_start_year=" + column_stacked_start_year + "&column_stacked_end_year=" + column_stacked_end_year;

		//เงิน
		$.ajax({
			url: 'dash1_3_money_confix.php',
			type: 'POST',
			data: {
				column_stacked_start_year: column_stacked_start_year,
				column_stacked_end_year: column_stacked_end_year
			},
			success: function(result) {
				$('div[id="div_column_stacked2"]').html(result);
			}
		});

		document.getElementById('iframe_column_stacked2').src = "chart_dash1_3_money.php?mode=BDG&column_stacked_start_year=" + column_stacked_start_year + "&column_stacked_end_year=" + column_stacked_end_year;
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