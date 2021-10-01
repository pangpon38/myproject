<?php
$frontPath = "../CULTURE_MOVIE61/";
include '../include/comtop_user.php';
?>
<?php
	//$sql_gcode  = " SELECT   distinct  MEDIA_GCODE, MEDIA_TYPE  FROM   M_REQUEST_MEDIA    ";	
$sql_gcode  = " SELECT   *  FROM   M_MEDIA_GROUP  ORDER  BY  MEDIA_GROUP_CODE";
$query_gcode = db::query($sql_gcode); 
$ARR_GCODE = array();
while($rec_gcode = db::fetch_array($query_gcode)) {
			//array_push($ARR_GCODE, $rec_gcode[""]);	
	$ARR_GCODE[$rec_gcode["MEDIA_GROUP_CODE"]] = $rec_gcode["MEDIA_SHORT_NAME"];
		//	$ARR_GCODE[$rec_gcode["MEDIA_GCODE"]] = $rec_gcode["MEDIA_TYPE"];
}
$dir = '../culture_file_scan/vortor';
$sql_chk_lock  = " SELECT   *  FROM   M_MEDIA_GROUP   WHERE   MEDIA_SHORT_NAME = '".$_REQUEST["SHORT_NAME"]."' ";		
//	$sql_chk_lock  = " SELECT   *  FROM   M_REQUEST_MEDIA   WHERE   MEDIA_TYPE = '".$_REQUEST["SHORT_NAME"]."' ";		
$query_chk_lock = db::query($sql_chk_lock);
	//$num_chk_lock = db::num_rows($query_chk_lock);
$rec_chk_lock = db::fetch_array($query_chk_lock);

//	if(!$rec_chk_lock["MEDIA_GCODE"]) 
if(!$rec_chk_lock["MEDIA_GROUP_CODE"]){  
	echo " <script> alert(\"กรุณาระบุประเภทสื่อให้ถูกต้อง\");  </script>";
	redirect_joke("../workflow/index.php");
	exit;
}

$labelFilter = "";
			$filter = " MEDIA_GCODE = '".$rec_chk_lock["MEDIA_GROUP_CODE"]."' "; // MEDIA_GCODE
			$filterW1 = "";
			$filterW2 = "";
			$filterW3 = "";
			$filterW5 = "";
			$perpage = 10;
			
			if(!$_REQUEST["page"]) $_REQUEST["page"]=1;

			$start = ($_REQUEST["page"] - 1) * $perpage; 
			
			if($_REQUEST["keywords"]) {
				/* $filter .= " ( WFR_MOVIE_APPROVE_REQUEST1.R_MOVIE_NAME LIKE '%".$_REQUEST["keywords"]."%'  OR  WFR_MOVIE_APPROVE_REQUEST1.R_MOVIE_NAME_ENG  LIKE '%".$_REQUEST["keywords"]."%'  OR  WFR_MOVIE_APPROVE_REQUEST1.ACTOR_NAME  LIKE '%".$_REQUEST["keywords"]."%'  OR  WFR_MOVIE_APPROVE_REQUEST1.REQUEST_OWNER_NAME  LIKE '%".$_REQUEST["keywords"]."%'   OR  WFR_MOVIE_APPROVE_REQUEST1.REQUESTER  LIKE '%".$_REQUEST["keywords"]."%'  )  AND ";  */
				$filter .= " AND ( STORY_NAME LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%'  OR  DIRECTOR_ACTOR  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%'  OR   COPYRIGHT_BY LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%'   OR  REQUESTER  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  RECEIPT_NO  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  RATING_REQUEST  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  RATING_FINAL  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  TOPIC_NAME  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  SPEAK_LANGUAGE  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  SUBTITLE  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  MATERIAL  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  TIMING_REQUEST  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  TIMING_VERIFY  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  FEE  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  BOOK_NO  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  BILL_NO  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  STORY_TELLING  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  REQUEST_TYPE  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  REMARK1  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  REMARK2  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  REMARK3  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  MEDIA_GCODE  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  SUB_B_NO  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  BOARD_NO  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  WH_STATUS  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  WH_ATTACH_FILE  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  WH_FILE_NAME  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  RM_ID  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  RM_NAME  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  CONSOLE_NAME  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' 
				OR  CON_RES  LIKE '%".convert_qoute_to_db($_REQUEST["keywords"])."%' )   "; 
			}
			
			if($_REQUEST["REQUEST_DATE1"]) {
				$REQUEST_DATE1_DB = date2db($_REQUEST["REQUEST_DATE1"]);
				$filter .= " AND  REQUEST_DATE  >= '".$REQUEST_DATE1_DB."'  ";
			}
			if($_REQUEST["REQUEST_DATE2"]) {
				$REQUEST_DATE2_DB = date2db($_REQUEST["REQUEST_DATE2"]);
				$filter .= " AND  REQUEST_DATE  <= '".$REQUEST_DATE2_DB."'  ";
			}
			if($_REQUEST["S_LICENSE_NUMBER"]) {
				//$filter .= " AND  CONCAT(LICENSE_NO, '/', LICENSE_YEAR) LIKE '%".convert_qoute_to_db($_REQUEST["S_LICENSE_NUMBER"])."%'   ";
				$filter .= " AND  CONCAT(LICENSE_NO, '/', LICENSE_YEAR) = '".convert_qoute_to_db($_REQUEST["S_LICENSE_NUMBER"])."'   ";
			} 
			if($_REQUEST["S_RECEIPT_NUMBER"]) {
				$filter .= "  AND RECEIPT_NO  LIKE '%".convert_qoute_to_db($_REQUEST["S_RECEIPT_NUMBER"])."%'  ";
			}
			if($_REQUEST["R_M_NAME"]) {
				$filter .= "  AND  STORY_NAME  LIKE '%".convert_qoute_to_db($_REQUEST["R_M_NAME"])."%' ";
			}
			if($_REQUEST["S_YEAR_NUM"]) {
				$filter .= "  AND LICENSE_YEAR = '".$_REQUEST["S_YEAR_NUM"]."' ";
			}
			if($_REQUEST["S_NATION"]) {
				$filter .= " AND  CREATE_COUNTRY = '".$_REQUEST["S_NATION"]."'  ";
			}
			if($_REQUEST["S_RATING"]) {
				//$filter .= "  AND  RATING_LICENSE = '".$_REQUEST["S_RATING"]."' ";
				$filter .= "  AND  RATING_FINAL = '".$_REQUEST["S_RATING"]."' ";
			}

			if($_REQUEST["G_GAME_NAME"] != '') {
				$filter .= " AND ( LICENSE_ID  IN  
				(SELECT  distinct M_LICENSE_WAREHOUSE.LICENSE_ID FROM M_LICENSE_WAREHOUSE
				INNER JOIN M_MEDIA_GROUP ON M_LICENSE_WAREHOUSE.MEDIA_GCODE = M_MEDIA_GROUP.MEDIA_GROUP_CODE 
				INNER JOIN  WFR_VIDEO_APPROVE_REQUEST1 ON 
				( M_MEDIA_GROUP.MEDIA_SHORT_NAME + ' ' + M_LICENSE_WAREHOUSE.LICENSE_NO +'/'+M_LICENSE_WAREHOUSE.LICENSE_YEAR ) = WFR_VIDEO_APPROVE_REQUEST1.LICENSE_NUMBER  
				INNER JOIN FRM_MUSIC ON WFR_VIDEO_APPROVE_REQUEST1.WFR_ID = FRM_MUSIC.WFR_ID
				WHERE  FRM_MUSIC.SONG_NAME LIKE '%".$_REQUEST["G_GAME_NAME"]."%') OR 
				
				LICENSE_ID  IN  
				(SELECT  distinct M_LICENSE_WAREHOUSE.LICENSE_ID FROM M_LICENSE_WAREHOUSE
				INNER JOIN M_MEDIA_GROUP ON M_LICENSE_WAREHOUSE.MEDIA_GCODE = M_MEDIA_GROUP.MEDIA_GROUP_CODE 
				INNER JOIN  WFR_VIDEO_APPROVE_REQUEST1 ON 
				( M_MEDIA_GROUP.MEDIA_SHORT_NAME + ' ' + M_LICENSE_WAREHOUSE.LICENSE_NO +'/'+M_LICENSE_WAREHOUSE.LICENSE_YEAR ) = WFR_VIDEO_APPROVE_REQUEST1.LICENSE_NUMBER  
				INNER JOIN FRM_GAME_NAME ON WFR_VIDEO_APPROVE_REQUEST1.WFR_ID = FRM_GAME_NAME.WFR_ID
				WHERE  FRM_GAME_NAME.G_GAME_NAME LIKE '%".$_REQUEST["G_GAME_NAME"]."%'))";
			}

			$arr_video_type = array("1"=>"เกมส์","2"=>"คาราโอเกะ");
			?>
			<link rel="stylesheet" type="text/css" href="../assets/plugins/data-table/css/dataTables.bootstrap4.min.css">
			<style>
				ul.pagination-s li {
					display:inline;
					padding: 5px;
				}
				.td_remove {
					display:none;
				}
			</style>
			<script src="<?php echo $frontPath;?>js/paging.js"></script>
			<div class="content-wrapper">
				<div class="container-fluid">
					<!-- Row Starts -->
					<div class="row" id="animationSandbox">
						<div class="col-sm-12">
							<div class="main-header">
								<h4> <img src="../icon/icon8.png"> ข้อมูล<?php echo $rec_chk_lock["MEDIA_GROUP_NAME"];?></h4>
								<ol class="breadcrumb breadcrumb-title breadcrumb-arrow"></ol>
								<div class="f-right">
									<form method="post" id="form_export" name="form_export" action="report_video_excel.php">
										<a class="btn btn-danger waves-effect waves-light" href="../workflow/index.php" role="button"><i class="icofont icofont-home"></i> กลับหน้าหลัก</a>
										<a class="btn btn-success waves-effect waves-light" href="#" role="button" onClick="$('#page').val('ALL'); $('#frm-search').submit();"> แสดงทั้งหมด</a>
										<input type="hidden" name="export_content" id="export_content" />
										<input type="hidden" name="SHORT_NAME" id="SHORT_NAME" value="<?php echo $_REQUEST["SHORT_NAME"] ?>" />
										<input type="hidden" name="REQUEST_DATE1" id="REQUEST_DATE1" value="<?php echo $_REQUEST["REQUEST_DATE1"] ?>" />
										<input type="hidden" name="REQUEST_DATE2" id="REQUEST_DATE2" value="<?php echo $_REQUEST["REQUEST_DATE2"] ?>" />
										<input type="hidden" name="S_LICENSE_NUMBER" id="S_LICENSE_NUMBER" value="<?php echo $_REQUEST["S_LICENSE_NUMBER"] ?>" />
										<input type="hidden" name="S_RECEIPT_NUMBER" id="S_RECEIPT_NUMBER" value="<?php echo $_REQUEST["S_RECEIPT_NUMBER"] ?>" />
										<input type="hidden" name="S_YEAR_NUM" id="S_YEAR_NUM" value="<?php echo $_REQUEST["S_YEAR_NUM"] ?>" />
										<input type="hidden" name="R_M_NAME" id="R_M_NAME" value="<?php echo $_REQUEST["R_M_NAME"] ?>" />
										<input type="hidden" name="S_NATION" id="S_NATION" value="<?php echo $_REQUEST["S_NATION"] ?>" />
										<input type="hidden" name="S_RATING" id="S_RATING" value="<?php echo $_REQUEST["S_RATING"] ?>" />
										<input type="hidden" name="keywords" id="keywords" value="<?php echo $_REQUEST["keywords"] ?>" />
										<input type="submit" name="btn_submit" class="btn btn-info waves-effect waves-light" value="ส่งออก Excel">
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-block">
									<form name="frm-search" id="frm-search" method="POST"><input type="hidden" name="SHORT_NAME" id="SHORT_NAME" value="<?php echo $_REQUEST["SHORT_NAME"];?>">
										<h4><i class="icofont icofont-search-alt-2"></i> ค้นหา</h4>
										<div class="form-group row"><input type="hidden" name="page" id="page" ></div>
										<div class="form-group row">
											<!--วันที่ยื่นคำขอ-->
											<div class="col- col-md-2">
												<label for="REQUEST_DATE1" class="form-control-label wf-right">วันที่ยื่นคำขอ</label>
											</div>
											<div class="col-md-3 wf-left">
												<label class="input-group"><input name="REQUEST_DATE1" id="REQUEST_DATE1" value="<?php echo $_REQUEST["REQUEST_DATE1"];?>" class="form-control datepicker" placeholder="วว/ดด/ปปปป"><span class="input-group-addon bg-primary"><span class="icofont icofont-ui-calendar"></span></span></label>
											</div>
											<!--ถึง-->
											<div class="col- col-md-2">
												<label for="REQUEST_DATE2" class="form-control-label wf-right">ถึง</label>
											</div>
											<div class="col-md-3 wf-left">
												<label class="input-group"><input name="REQUEST_DATE2" id="REQUEST_DATE2" value="<?php echo $_REQUEST["REQUEST_DATE2"];?>" class="form-control datepicker" placeholder="วว/ดด/ปปปป"><span class="input-group-addon bg-primary"><span class="icofont icofont-ui-calendar"></span></span></label>
											</div>
										</div>
										<div class="form-group row">
											<!--ปี พ.ศ.-->
											<div class="col- col-md-2 ">
												<label for="S_YEAR_NUM" class="form-control-label wf-right">ปี พ.ศ. (ใบอนุญาต)</label>
											</div>
											<div class="col-md-3 wf-left R_M_NAME_BSF_AREA"> 
												<select name="S_YEAR_NUM" id="S_YEAR_NUM" class="form-control select2">
													<option value=""> ทุกปี </option>
													<?php
													foreach ($A_CONFIG_YEAR as $yearTH => $yy) {
														$selected = (($yy)==$_REQUEST["S_YEAR_NUM"])? "selected":"";
														echo "<option value=\"".($yy)."\" $selected>$yearTH</option>";
													}
													?>
												</select>
												<small id="DUP_R_M_NAME_ALERT" class="form-text text-danger" style="display:none"></small>
											</div>
											<!--หมายเลขรหัส-->
											<div class="col- col-md-2 R_NUMBER_BSF_AREA">
												<label for="S_LICENSE_NUMBER" class="form-control-label wf-right" title="(เลขที่ใบอนุญาต)">หมายเลขรหัส</label>
											</div>
											<div  class="col-md-3 wf-left R_NUMBER_BSF_AREA">
												<input type="text" name="S_LICENSE_NUMBER" id="S_LICENSE_NUMBER" class="form-control" value="<?php echo $_REQUEST["S_LICENSE_NUMBER"];?>">
												<small id="DUP_S_LICENSE_NUMBER_ALERT" class="form-text text-danger" style="display:none"></small>
											</div>
										</div>
										<div class="form-group row">
											<!--เลขที่รับเรื่อง-->
											<div  class="col- col-md-2 R_M_NAME_BSF_AREA">
												<label for="S_RECEIPT_NUMBER" class="form-control-label wf-right">เลขที่รับเรื่อง</label>
											</div>
											<div class="col-md-3 wf-left R_M_NAME_BSF_AREA">
												<input type="text" name="S_RECEIPT_NUMBER" id="S_RECEIPT_NUMBER" class="form-control" value="<?php echo $_REQUEST["S_RECEIPT_NUMBER"];?>">
												<small id="DUP_S_RECEIPT_NUMBER_ALERT" class="form-text text-danger" style="display:none"></small>
											</div>
											<!--ชื่อเรื่อง-->
											<div  class="col- col-md-2 R_M_NAME_BSF_AREA">
												<label for="keywords" class="form-control-label wf-right">คำค้น</label><!--R_M_NAME  ชื่อเรื่อง  -->
											</div>
											<div class="col-md-3 wf-left wf-left R_M_NAME_BSF_AREA">
												<input type="text" name="keywords" id="keywords" class="form-control" placeholder="ชื่อเรื่อง/ผู้กำกับ/นักแสดง" value="<?php echo $_REQUEST["keywords"];?>">
												<small id="DUP_R_M_NAME_ALERT" class="form-text text-danger" style="display:none"></small>
											</div>
										</div>
										<div class="form-group row">
											<!--เรตติ้งที่ได้-->
								<div  class="col- col-md-2 R_M_NAME_BSF_AREA">
									<label for="S_RATING" class="form-control-label wf-right">เรตติ้งที่ได้</label>
								</div>
								<div class="col-md-3 wf-left wf-left">
									<select id="S_RATING" name="S_RATING" class="form-control select2">
									<option value="" >เลือกเรตติ้ง</option>
									<?php
										$sql_media_type = "SELECT  MR_ID,  MOVIE_RATING  FROM   M_MOVIE_RATE";
										ddw_list_selected($sql_media_type, "MOVIE_RATING", "MOVIE_RATING", $_REQUEST["S_RATING"]);
									?>
									</select>
									<small id="DUP_R_M_NAME_ALERT" class="form-text text-danger" style="display:none"></small>
								</div>
								<!--ประเทศที่สร้าง-->
								<!--<div class="col- col-md-2"><label for="S_NATION" class="form-control-label wf-right">ประเทศที่สร้าง</label></div>
								<div class="col-md-3 wf-left">
									<select id="S_NATION" name="S_NATION" class="form-control select2">
										<option value="" >เลือกประเทศ</option>
										<?php
										/*$sql_media_type = "SELECT  L_ID,  L_NAME  FROM   M_LANGUAGE";
										ddw_list_selected($sql_media_type, "L_NAME", "L_ID", $_REQUEST["S_NATION"]);  // COUN_PRODUCTION
										*/?>
									</select>
									<small id="DUP_R_M_NAME_ALERT" class="form-text text-danger" style="display:none"></small>
								</div>-->
								<div class="col- col-md-2"><label for="S_NATION" class="form-control-label wf-right">ประเทศที่สร้าง</label></div>
								<div class="col-md-3 wf-left">
									<select id="S_NATION" name="S_NATION" class="form-control select2">
										<option value="" >เลือกประเทศ</option>
										<?php
										$sql_media_type = "SELECT  COUNTRY_ID,  COUNTRY_NAME  FROM  M_COUNTRY";
										ddw_list_selected($sql_media_type, "COUNTRY_NAME", "COUNTRY_NAME", $_REQUEST["S_NATION"]);  // COUN_PRODUCTION
										?>
									</select>
									<small id="DUP_R_M_NAME_ALERT" class="form-text text-danger" style="display:none"></small>
								</div>
								<!--ชื่อเกม/ชื่อคาราโอเกะ-->

								<!--<div  class="col- col-md-2 R_M_NAME_BSF_AREA">
									<label for="G_GAME_NAME" class="form-control-label wf-right">ชื่อเกม/คาราโอเกะ</label>
								</div>
								<div class="col-md-3 wf-left wf-left R_M_NAME_BSF_AREA">
									<input type="text" name="G_GAME_NAME" id="G_GAME_NAME" class="form-control" value="<?php echo $_REQUEST["G_GAME_NAME"];?>">
									<small id="DUP_R_M_NAME_ALERT" class="form-text text-danger" style="display:none"></small>
								</div>-->
							</div>
							<div class="form-group row">
								<div class="col-md-12 text-center">
									<button type="submit" name="wf_search" id="wf_search"  class="btn btn-info"><i class="icofont icofont-search-alt-2"></i> ค้นหา</button>
									&nbsp;&nbsp;
									<button type="button" name="wf_reset" id="wf_reset"  class="btn btn-warning" onClick="window.location.href='<?php echo $wf_link; ?>';"><i class="zmdi zmdi-refresh-alt"></i> Reset</button>
									<input type="hidden" name="W" id="W" value="<?php echo $W; ?>"><input type="hidden" name="WF_SEARCH" id="WF_SEARCH" value="Y">
								</div>
							</div>
						</form>
						<?php
						$field="  *  ";         
						$table = " 	M_LICENSE_WAREHOUSE "; 
						$pk_id=" LICENSE_ID ";	

						$orderby=" ORDER BY  LICENSE_YEAR DESC,  CAST(LICENSE_NO AS int) DESC   "; 
						
						$sql_ALL = " SELECT   $pk_id  FROM   M_LICENSE_WAREHOUSE   WHERE  $filter ".$orderby;
					//echo "$sql_ALL<br>"; 
						$query_ALL = db::query($sql_ALL);
						$total_rows = db::num_rows($query_ALL);  

	 			 //$num_rows = db::num_rows($query_list);  
						$total_pages = ceil($total_rows/$perpage);  

						if($total_rows > 0) {
							?>
							<div class="row">
								<div class="col col-md-12"  id="divCaption1"><?php
							 echo "พบทั้งหมด : ".number_format($total_rows)." รายการ  "; // <span style=\"color:#FFF\">
							 ?>
							</div>
						</div>
						<div class="card-block table-responsive" id="export_data">
							<div class="showborder">
								<?php 

								if($_REQUEST["page"]=="ALL") {
									$sql_list  = " SELECT   *  FROM   M_LICENSE_WAREHOUSE   WHERE  $filter ".$orderby;
									$i = 0;
								} else {
						  $i = $start; // 0;    

						  $notin=$filter." and ".$pk_id." not in (select top ".($start)." ".$pk_id." from ".$table." where ".$filter." ".$orderby.") ".$orderby;

						  $sql_list = "select top {$perpage} ".$field." from ".$table." where ".$notin;
							//echo "$sql_list<br>"; 
						}
						 
						$query_list = db::query($sql_list); 
						
						?>
						<table cellspacing="0" id="tech-companies-1" class="table table-bordered table-striped sorted_table">
							<thead>
								<tr class="bg-primary">
									<th style="width:3%;" class="text_subhead">ลำดับ1</th>
									<th style="width:3%;" class="text_subhead">ตัวย่อรหัส</th>
									<th style="width:3%;" class="text_subhead">หมายเลขรหัส</th>
									<th style="width:3%;" class="text_subhead">วันที่ออกเลขรหัส</th>
									<th style="width:5%;" class="text_subhead">วันที่ยื่นคำขอ</th>
									<th style="width:3%;" class="text_subhead">วันที่รับคำขอ</th>
									<th style="width:5%;" class="text_subhead">วันที่ลงมติกก.</th>
									<th style="width:3%;" class="text_subhead">ประเภทคำขอ</th>
									<th style="width:5%;" class="text_subhead">ชื่อเรื่อง</th>
									<th style="width:5%;" class="text_subhead">ผู้ยื่นคำขอ</th>
									<!--<th style="width:5%;" class="text_subhead">ผู้รับมอบอำนาจ</th>-->
									<th style="width:5%;" class="text_subhead">เจ้าของลิขสิทธิ์</th>
									<th style="width:5%;" class="text_subhead">เรตติ้งที่ได้</th>
									<th style="width:3%;" class="text_subhead">คณะอนุกรรมการชุดที่</th>
									<th style="width:3%;" class="text_subhead">คณะกรรมการชุดที่</th>
									<th style="width:5%;" class="text_subhead">เลขที่รับเรื่อง</th>
									<th style="width:5%;" class="text_subhead">เนื้อหา</th>
									<th style="width:5%;" class="text_subhead">ประเทศที่สร้าง</th>
									<th style="width:5%;" class="text_subhead">เสียงพากย์</th>
									<th style="width:5%;" class="text_subhead">บรรยาย</th>
									<th style="width:5%;" class="text_subhead">เครื่องเล่น</th>
									<th style="width:5%;" class="text_subhead">ประเภทวัสดุ/จำนวน(แผ่น)</th>
									<th style="width:5%;" class="text_subhead">ความยาวที่ยื่นตรวจ</th>
									<th style="width:5%;" class="text_subhead">ความยาวที่ตรวจ</th>
									<th style="width:5%;" class="text_subhead">ค่าธรรมเนียม</th>
									<th style="width:5%;" class="text_subhead">เลขที่ใบเสร็จ</th>
									<th style="width:3%;" class="text_subhead">วันที่ชำระค่าธรรมเนียม</th>
									<th style="width:3%;" class="text_subhead">เรตติ้งสิ้นสุด</th>
									<th style="width:3%;" class="text_subhead">ไฟล์เอกสารแนบ</th>
									<th style="width:3%;" class="td_remove"></th>
								</tr>
							</thead>
							<tbody>
								<!--request1-->
								<?php
									$i = $start; // 0;     
									while($data_list = db::fetch_array($query_list)){
										$i++;
											//	if($data_list["CON_RES"] == '1'){
											//		$rating = $data_list["MOVIE_RATING"];
											//	}else if($data_list["CON_RES"] == '2'){
												//	$rating = $data_list["RESULT_RATE"];
											//	}
										?>
										<tr class="text_body">
											<td style="text-align:center">
												<?php if($_SESSION["DEP_ID"]=='1' || $_SESSION["DEP_ID"] == '4' or true) {
													echo "<a href=\"javascript:editItem('".$data_list['LICENSE_ID']."');\" target=\"_blank\">$i</a>";
												} else { 
													echo $i; 
												} ?>
											</td>
											<td style="text-align:center" ><?php echo $ARR_GCODE[$data_list["MEDIA_GCODE"]];?></td>
											<td style="text-align:center" class="class_text_no"><?php echo $data_list["LICENSE_NO"].'/'.$data_list["LICENSE_YEAR"];?></td>
											<td style="text-align:center; white-space:nowrap"><?php echo db2date_show($data_list["NUM_RELEASE_DATE"]);?></td>
											<td style="text-align:center; white-space:nowrap"><?php echo db2date_show($data_list["REQUEST_DATE"]);?></td>
											<td style="text-align:center; white-space:nowrap"><?php echo db2date_show($data_list["CHECK_DATE"]);?></td>
											<td style="text-align:center; white-space:nowrap" ><?php echo db2date_show($data_list["LICENSE_DATE"]);?></td>
											<td style="text-align:center;" ><?php echo $data_list["RM_NAME"];?></td>
											<td style="text-align:left; white-space:nowrap" >
												<?php 
												echo $data_list["STORY_NAME"];
												if( trim($data_list["STORY_NAME_ENG"]) ){
													echo " / ".$data_list["STORY_NAME_ENG"];
												} ?>
											</td>
											<td style="text-align:left; white-space:nowrap"><?php 
											echo $data_list["REQUESTER"];?></td>
													<!--<td style="text-align:left; white-space:nowrap" ><?php 
													
													//echo $data_list["T_NAME_TH"].' '.$data_list["REQUEST_FNAME"].'  '.$data_list["REQUEST_LNAME"];?></td>-->
													<td style="text-align:left; white-space:nowrap" ><?php echo $data_list["COPYRIGHT_BY"];?></td>													 
													<td style="text-align:center; white-space:nowrap"><?php echo $data_list["RATING_LICENSE"];?></td><!--เรตติ้งที่ได้-->
													<td style="text-align:center"><?php echo $data_list["SUB_B_NO"];?></td>
													<td style="text-align:center"><?php echo $data_list["BOARD_NO"];?></td>
													<td style="text-align:center"><?php echo $data_list["RECEIPT_NO"];?></td>
													<td style="text-align:center"><?php echo $data_list["TOPIC_NAME"];?></td>
													<td style="text-align:center"><?php echo $data_list["CREATE_COUNTRY"];?></td>												 
													<td style="text-align:center"><?php  echo $data_list["SPEAK_LANGUAGE"]; ?></td>
													<td style="text-align:center"><?php  echo $data_list["SUBTITLE"]; ?></td>
													<td style="text-align:center"><?php  echo $data_list["CONSOLE_NAME"]; ?> </td>
													<td style="text-align:center"><?php  echo $data_list["MATERIAL"]; ?> </td>
													<td style="text-align:center"><?php echo $data_list["TIMING_REQUEST"];?></td>
													<td style="text-align:center"><?php echo $data_list["TIMING_VERIFY"];?></td>
													<td style="text-align:center"><?php echo $data_list["FEE"];?></td>
													<td style="text-align:center"><?php echo $data_list["BOOK_NO"]."/".$data_list["BILL_NO"];?></td>
													<td style="text-align:center" class="td_remove">
														<a class="btn btn-info btn-mini" href="#!" onclick="PopupCenter('../workflow/workflow_step.php?W=<?php echo $data_list["WF_MAIN_ID"];?>&WFR=<?php echo $data_list["WFR_ID"];?>', 'ขั้นตอนการทำงาน', (window.innerWidth-60), window.innerHeight) ;" role="button"><i class="typcn typcn-th-list"></i></a>
													</td>
													<td style="text-align:center; white-space:nowrap" ><?php echo db2date_show($data_list["BILL_PAY_DATE"]);?></td>
													<td style="text-align:center; white-space:nowrap" ><?php echo $data_list["RATING_FINAL"];?></td>
													
													<td style="text-align:center">
														<?php if($data_list["WH_STATUS"]!='O'){ 
														if(!preg_match("/\/w\//i",$data_list["WH_ATTACH_FILE"])) {
															echo '<a href="'.$data_list["WH_ATTACH_FILE"].'" target="_blank">'.$data_list["WH_FILE_NAME"].'</a><br/>';	
														}
														?><a class="btn btn-info btn-mini" href="#!" onclick="PopupCenter('../report/show_all_file_doc.php?RN=<?php echo $data_list["RECEIPT_NO"];?>&SHORT_NAME=<?php echo $_REQUEST["SHORT_NAME"];?>', 'แสดงไฟล์ทั้งหมด', (window.innerWidth-500), window.innerHeight-300) ;" role="button"><i class="typcn typcn-th-list"></i>
														</a>
													<?php }else{
														if ($data_list["WH_ATTACH_FILE"]) {															
															echo '<a href="' . preg_replace('/..\/culture_file_scan\/.*\//i',$dir.'/', $data_list["WH_ATTACH_FILE"]) . '" target="_blank">' . $data_list["WH_FILE_NAME"] . '</a>';
														} else {
														
                                                            $filename = "_" . $data_list["LICENSE_NO"] . "_" . substr($data_list["LICENSE_YEAR"], -2);
                                                            if ($handle = opendir($dir)) { // here add your directory
																$count_entry = 0; 
                                                                //$keyword = "module_"; // your keyword
                                                                while (false !== ($entry = readdir($handle))) {
                                                                    // (preg_match('/\.txt$/', $entry)) {
                                                                    if (preg_match('/' . $filename . '/i', $entry)) {
																		$entry_encode = iconv("TIS-620", "UTF-8",$entry);
                                                                        
                                                                        echo '<a href="'.$dir.'/'.$entry_encode.'" target="_blank">'.$entry_encode.'</a>';
																		$count_entry++;
                                                                        //echo "$entry\n";
                                                                    }
                                                                }
																if ($count_entry == 0) {?>
																	<a class="btn btn-info btn-mini" href="#!" onclick="PopupCenter('../report/add_file_doc.php?RN=<?php echo $data_list["LICENSE_ID"]; ?>&SHORT_NAME=<?php echo $_REQUEST["SHORT_NAME"]; ?>', 'แสดงไฟล์ทั้งหมด', (window.innerWidth-500), window.innerHeight-300) ;" role="button"><i class="typcn typcn-th-list"></i>
																	</a>
													<?php          }
                                                                closedir($handle);
                                                            }
														}
                      	
													} ?>
													</td>

											</tr>
										<?php } // end while ?> 
									</tbody>
								</table>   <!-- PAGINATION --> 
								<!-- <span class="container"> -->
									<span>
										<div class="row " id="pagination" align="center">
											<ul class="pagination pagination-s"  >
											</ul>
										</div>
									</span>
									<!-- </span> -->
									<!-- PAGINATION -->  
								</div>
							</div>
							<?php
               }  // end  if($total_rows > 0) 
               else {
               	echo "<center>ไม่พบข้อมูล</center>";
               }  

               ?>
           </div>
       </div>
   </div>
</div>
</div>
</div>
<script type="text/javascript" src="../assets/plugins/data-table/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/plugins/data-table/js/dataTables.bootstrap4.min.js"></script>
<script>
	MakePagination('<?php echo $total_rows;?>', '<?php echo $_REQUEST["page"];?>', '<?php echo $total_pages;?>', '<?php echo $perpage;?>', "pagination", "frm-search", "page");

	$(document).ready(function(e) {
	 // $('#divCaption1').html("<strong>ทั้งหมด <?php echo number_format($i);?> รายการ</strong>");
	 $(document).on("blur", "#REQUEST_DATE1", function() {
	 	if($(this).val().search(/\//) != -1  && $("#REQUEST_DATE2").val().search(/\//) == -1 ) {
	 		$("#REQUEST_DATE2").val($(this).val());	  
	 	}
	 });
	 /* $('#tech-companies-1').dataTable({
			"searching" : false,
			"bLengthChange": false,
			"bFilter": true,
			"bInfo": false
	  });
	  */
	});
</script>
<form method="post" id="form_export" name="form_export" target="_blank"  action="export_report.php">
	<input type="hidden" name="export_content" id="export_content" />
	<input type="hidden" name="export_type" id="export_type" value="" />
	<input type="hidden" name="margin_left" id="margin_left" value="<?php echo ($margin_left ? $margin_left : "15"); ?>">
	<input type="hidden" name="margin_right" id="margin_right" value="<?php echo ($margin_right ? $margin_right : "15"); ?>">
	<input type="hidden" name="margin_top" id="margin_top" value="<?php echo ($margin_top ? $margin_top : "16"); ?>">
	<input type="hidden" name="margin_bottom" id="margin_bottom" value="<?php echo ($margin_bottom ? $margin_bottom : "16"); ?>">
	<input type="hidden" name="margin_header" id="margin_header " value="<?php echo ($margin_header ? $margin_header : "16"); ?>">
	<input type="hidden" name="margin_footer" id="margin_footer" value="<?php echo ($margin_footer ? $margin_footer : "9"); ?>"> 
	<input type="hidden" name="header_pdf" id="header_pdf" value="<?php echo ($header_pdf ? $header_pdf : ""); ?>">
	<input type="hidden" name="R_SET_FONT" id="R_SET_FONT" value="<?php echo $FONT; ?>">
</form>	
<!-- Default bootstrap modal example -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">แก้ไขข้อมูล</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิด</button>
				<button type="button" onClick="$('#frmSave').submit();" class="btn btn-primary">บันทึก</button>
			</div>
		</div>
	</div>
</div>
<script>
	function editItem(LICENSE_ID){
		$("#editModal").modal('show').find(".modal-body").html('').load("video_treasury_form.php?LICENSE_ID="+LICENSE_ID);
	}
	
</script>
<?php include '../include/combottom_js_user.php'; 
include '../include/combottom_user.php'; ?>