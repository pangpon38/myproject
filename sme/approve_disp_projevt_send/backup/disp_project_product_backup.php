<?php
session_start();
$path = "../../";
include($path."include/config_header_top.php");
$page_size = 1000;
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '2';
if($_POST['PRJP_ID']!=''){
$PRJP_ID = $_POST['PRJP_ID'];	
}else{
$PRJP_ID = $PRJP_ID;	
}
$month_full = array("1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน","7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
$month_full_bdg = array("10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม","1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน","7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน");
$sql_head="SELECT PRJP_CODE,PRJP_NAME,EDATE_PRJP,SDATE_PRJP,PROLONG_STATUS,MONEY_BDG,PRJP_CON_ID,BDG_TYPE_ID FROM prjp_project WHERE PRJP_ID = '".$PRJP_ID."'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$ms = substr($rec_head['SDATE_PRJP'],5,2)*1;
$ys = substr($rec_head['SDATE_PRJP'],0,4)+543;
$me = substr($rec_head['EDATE_PRJP'],5,2)*1;
$ye = substr($rec_head['EDATE_PRJP'],0,4)+543;

if($rec_head['PRJP_CON_ID']!=''){
	$sql_prjold = "SELECT prjp_product.PRJP_PRODUCT_ID,
				  prjp_product.PRJP_ID,
				  prjp_product.TYPE_PRO_ID,
				  prjp_product.PRJP_PRODUCT_NAME,
				  prjp_product.PRJP_PRODUCT_DESC,
				  prjp_product.GOAL_VALUE,
				  prjp_product.RESULT_VALUE,
				  prjp_product.UNIT_PRO_NAME,
				  setup_type_product.UNIT_ID,
				  (select TYPE_PRO_NAME FROM setup_type_product WHERE setup_type_product.TYPE_PRO_ID = prjp_product.TYPE_PRO_ID)as TYPE_PRO_NAME,
				  (select UNIT_NAME_TH FROM setup_unit WHERE setup_unit.UNIT_ID = prjp_product.UNIT_ID)as UNIT_NAME_TH
				  FROM prjp_product JOIN setup_type_product ON setup_type_product.TYPE_PRO_ID = prjp_product.TYPE_PRO_ID
				  WHERE 1=1 AND PRJP_ID = '".$rec_head['PRJP_CON_ID']."'			
				  order by prjp_product.PRJP_PRODUCT_ID
	";
	
	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////



////////////////////////////////////////////////////////////////////////////////////////////////////
$filter == "";
if($_POST['s_round_year_bud'] != ""){
	$filter .= " AND PRJP_NAME LIKE '%".$_POST['s_round_year_bud']."%' ";
	
}
$field = "a.PRJP_PRODUCT_ID,
		  a.PRJP_ID,
		  a.TYPE_PRO_ID,
		  a.PRJP_PRODUCT_NAME,
		  a.PRJP_PRODUCT_DESC,
		  a.GOAL_VALUE,
		  a.RESULT_VALUE,
		  a.UNIT_PRO_NAME,
		  a.UNIT_ID,
		  (select TYPE_PRO_NAME FROM setup_type_product WHERE setup_type_product.TYPE_PRO_ID = a.TYPE_PRO_ID)as TYPE_PRO_NAME,
		  (select UNIT_NAME_TH FROM setup_unit WHERE setup_unit.UNIT_ID = a.UNIT_ID)as UNIT_NAME_TH";
$table = " prjp_product a ";
$join = " JOIN setup_type_product b ON b.TYPE_PRO_ID = a.TYPE_PRO_ID";
$pk_id = "a.PRJP_PRODUCT_ID";

$wh = " 1=1 AND PRJP_ID = '".$PRJP_ID."' {$filter}";
$orderby = " order by a.PRJP_PRODUCT_ID ";
$groupby = " ";
//$sql = sqlpaging($field, $table." ".$join, $wh, $notInto, $groupby, $orderby, $page_size, $page, $pk_id ,$distinct = 0);
$sql = "SELECT ".$field." FROM ".$table." ".$join." WHERE ".$wh." ".$groupby." ".$orderby;
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);
$total_record = $db->db_num_rows($db->query("select ".$field." from ".$table." ".$join." where ".$wh."".$groupby));
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$fieldr = "a.PRJP_RESULT_ID,
		  a.PRJP_ID,
		  a.TYPE_RES_ID,
		  a.PRJP_RESULT_NAME,
		  a.PRJP_RESULT_DESC,
		  a.GOAL_VALUE,
		  a.RESULT_VALUE,
		  a.UNIT_ID,
		  a.UNIT_RES_NAME,
		  (select TYPE_RES_NAME FROM setup_type_result WHERE setup_type_result.TYPE_RES_ID = a.TYPE_RES_ID)as TYPE_RES_NAME,
		  (select UNIT_NAME_TH FROM setup_unit WHERE setup_unit.UNIT_ID = a.UNIT_ID)as UNIT_NAME_TH";
$tabler = " prjp_result a ";
$joinr = " JOIN setup_type_result b ON b.TYPE_RES_ID = a.TYPE_RES_ID";
$pk_idr = "a.PRJP_RESULT_ID";

$whr = " 1=1 AND PRJP_ID = '".$PRJP_ID."' {$filter}";
$orderbyr = " order by a.PRJP_RESULT_ID ";
$groupbyr = " ";
$sqlr = "SELECT ".$fieldr." FROM ".$tabler." ".$joinr." WHERE ".$whr." ".$groupbyr." ".$orderbyr;
//$sqlr = sqlpaging($fieldr, $tabler." ".$joinr, $whr, $notInto, $groupbyr, $orderbyr, $page_size, $page, $pk_idr ,$distinct = 0);
$queryr = $db->query($sqlr);
$num_rowsr = $db->db_num_rows($queryr);
$total_recordr = $db->db_num_rows($db->query("select ".$fieldr." from ".$tabler." ".$joinr." where ".$whr."".$groupbyr));
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
<head>
	<?php include($path."include/inc_main_top.php"); ?>
<script src="js/disp_project_product.js?<?php echo rand(); ?>"></script>
<script src="js/disp_project_result_copy.js?<?php echo rand(); ?>"></script>
<script type="text/javascript">  
function chk_pro_old(id){
	if(id==0){
		$("#hide_pro").val(1);
	}else if(id==1){
		$("#hide_pro").val(2);	
	}else if(id==2){
		$("#hide_pro").val(1);	
	}
	id = $("#hide_pro").val();
	show_pro_old(id);  
}
function show_pro_old(id){
	if(id==1){	
	$("#data_old").show();	
	$("#TB_BR").html("<br /><br /><br />");
	}else{
	 $("#data_old").hide();	
	 $("#TB_BR").html("");
	}	
}
</script>
</head>
<body style="display:inline-block">
<div class="container-full ">
	<div><?php include($path."include/header.php"); ?></div>
	<div class="col-xs-12 col-sm-12">
		<ol class="breadcrumb">
			<li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
			<li><a href="disp_project.php?<?php echo url2code("menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id);?>"><?php echo Showmenu($menu_sub_id);?></a></li>
			<li class="active">ผลผลิต</li>
		</ol>
	</div>
	
	<div class="col-xs-12 col-sm-12">
		<div class="groupdata" >
			<form id="frm-search" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
				<input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
				<input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
				<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
				<input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
				<input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
				<input type="hidden" id="PRJP_CON_ID" name="PRJP_CON_ID" value="<?php echo $rec_head['PRJP_CON_ID']; ?>">
				<input type="hidden" id="PRJP_PRODUCT_ID" name="PRJP_PRODUCT_ID">
				<input type="hidden" id="PRJP_RESULT_ID" name="PRJP_RESULT_ID">
				<input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />
				<input type="hidden" id="code_user" name="code_user" value="<?php echo $_SESSION['sys_dept_id']; ?>">
				<input type="hidden" id="YMIN" name="YMIN" value="<?php echo $ys; ?>">
				<input type="hidden" id="YMAX" name="YMAX" value="<?php echo $ye; ?>">

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
										<select id="S_TYPE" name="S_TYPE" class="selectbox form-control" placeholder="ประเภทรายงาน" style="width:150px;" >
											<option value="1">WORD</option>
											<option value="2">EXCEL</option>
											<?php /*?><option value="2">PDF</option><?php */?>
										</select>
									</div>
								</div>
								<div style="display:none;"><label>จาก</label></div>
								<div class="row" style="display:none;">
									<div class="col-md-12">
										<select id="S_MONTH" name="S_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;" >
											
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
										<select id="E_MONTH" name="E_MONTH" class="selectbox form-control" placeholder="เดือน" style="width:350px;" >
											
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
				<div class="row"><div class="col-xs-12 col-sm-12"><?php include("tab_menu_r.php");?></div></div>
				<div class="row"><div class="col-xs-12 col-sm-12 col-md-12"> </div></div>
				<?php  
				//$print_form_w = "<a data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"pdf_report_w('".$PRJP_ID."');\">".$img_print."  พิมพ์ สสว.100/1 Word</a> ";
				//$print_form_p = "<a data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"pdf_report_p('".$PRJP_ID."');\">".$img_print."  พิมพ์ สสว.100/1 PDF</a> ";
				?>
				<?php if($_SESSION['sys_status_print']=='1'){	
					// $print_form = "<a class=\"btn btn-info\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form('".$PRJP_ID."');get_sm('".$PRJP_ID."');get_em('".$PRJP_ID."');\">".$img_print."  พิมพ์ผลผลิต / ผลลัพธ์</a> ";
				} ?>
				<div class="row div1-fix-width"><div class="col-xs-12 col-sm-12 text-center"><strong class="font-blue"><?php echo $rec_head['PRJP_CODE']." ".text($rec_head['PRJP_NAME']) ?></strong></div></div>
				<div class="row div1-fix-width">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading row" style="">
								<div class="pull-left">ผลผลิต</div>  
								<div class="pull-right">สสว.100/1</div>
							</div>
							<div class="panel-body epm-gradient" >
								<?php if($rec_head['PRJP_CON_ID']!=''){ ?>  
									<div class="row">  
										<div class="col-xs-12 col-sm-12" align="center">
											<input type="hidden" id="hide_pro" name="hide_pro" value="0">
											<a href="javascript:void(0)" onClick="chk_pro_old(hide_pro.value);"><?php echo $img_save;?> ผลผลิตเก่า</a>
										</div>
									</div>  
									<div class="col-xs-12 col-sm-12" id="data_old" style="display:none">
										<div class="">
											<table width="22%" class="table table-bordered table-striped table-hover table-condensed">
												<thead>
													<tr class="bgHead">
														<th width="2%"><div align="center"><strong>ลำดับ</strong></div></th>
														<th width="15%"><div align="center"><strong>ชื่อ/รายละเอียดผลผลิต</strong></div></th>
														<?php /*?><th width="15%"><div align="center"><strong>รายละเอียดผลผลิต</strong></div></th><?php */?>
														<th width="15%"><div align="center"><strong>ผลการส่งเสริม</strong></div></th>
														<th width="10%"><div align="center"><strong>เป้าหมาย</strong></div></th>
														<th width="10%"><div align="center"><strong>ประเภทหน่วยนับ</strong></div></th>
														<!-- <th width="10%"><div align="center"><strong>การจัดการ</strong></div></th> -->
													</tr>
												</thead>
												<tbody>
													<?php
													$queryprjold = $db->query($sql_prjold);
													$num_rows_prjold = $db->db_num_rows($queryprjold);
													if($num_rows_prjold > 0){
														$i=1;
														while($rec_prjold = $db->db_fetch_array($queryprjold)){
															?>
															<tr bgcolor="#FFFFFF">
																<td align="center"><?php echo $i; ?>.</td>
																<td align="left"><?php echo text($rec_prjold['PRJP_PRODUCT_NAME']);?></td>
																<?php /*?><td align="left"><?php echo text($rec['PRJP_PRODUCT_DESC']);?></td><?php */?>
																<td align="center"><?php if($rec_prjold['TYPE_PRO_ID']!='9999'){ echo text($rec_prjold['TYPE_PRO_NAME']);}else{echo  "อื่น ๆ";}?></td>
																<td align="right"><?php echo number_format($rec_prjold['GOAL_VALUE'],2);?></td>
																<td align="center"><?php 
																if($rec_prjold['TYPE_PRO_ID']!='9999'){ 
																		echo text($rec_prjold['UNIT_NAME_TH']);}
																	else{
																		echo  text($rec_prjold['UNIT_PRO_NAME']);}?></td>
																<td align="center">-</td>
															</tr>
															<?php 
															$i++;
														}
													}else{
														echo "<tr><td align=\"center\" colspan=\"6\">ไม่พบข้อมูล</td></tr>";
													}
													?>
												</tbody>
											</table>
										</div>
									</div>	
									<label id="TB_BR"></label>
								<?php } ?>
								<?php //if(($_SESSION["sys_group_id"]=='5') ||
								//($_SESSION["sys_org_type_id"]=='5' && $_SESSION["sys_lv_dept"]=='2') || 
								//($_SESSION["sys_org_type_id"]!='5' && ($_SESSION["sys_lv_dept"]=='0' || $_SESSION["sys_lv_dept"]=='1'))){
								?>     
								<div class="row">  
									<div class="col-xs-12 col-sm-12">
										<?php if($_SESSION['sys_status_add']=='1'){	 ?>
											<!-- <a data-toggle="modal" class="btn btn-default" data-backdrop="static" href="javascript:void(0);" onClick="addData(<?php echo $PRJP_ID; ?>);"><?php echo $img_save;?> เพิ่มผลผลิต</a> -->
										<?php } ?>
										<?php echo $print_form; ?>
									</div>
								</div>
								<?php //} ?>
								<div class="col-xs-12 col-sm-12">
									<div class="">
										<table width="22%" class="table-fix-width table table-bordered table-striped table-hover table-condensed">
											<thead>
												<tr class="bgHead">
													<th width="2%"><div align="center"><strong>ลำดับ</strong></div></th>
													<th width="15%"><div align="center"><strong>ชื่อ/รายละเอียดผลผลิต</strong></div></th>
													<?php /*?><th width="15%"><div align="center"><strong>รายละเอียดผลผลิต</strong></div></th><?php */?>
													<th width="15%"><div align="center"><strong>ผลการส่งเสริม</strong></div></th>
													<th width="10%"><div align="center"><strong>เป้าหมาย</strong></div></th>
													<th width="10%"><div align="center"><strong>ประเภทหน่วยนับ</strong></div></th>
													<!-- <th width="10%"><div align="center"><strong>การจัดการ</strong></div></th> -->
												</tr>
											</thead>
											<tbody>
												<?php
												if($num_rows > 0){
													$i=1;
													$query = $db->query($sql);
													while($rec = $db->db_fetch_array($query)){
														$edit = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"editData('".$PRJP_ID."','".$rec['PRJP_PRODUCT_ID']."','".$rec_head['PRJP_CON_ID']."');\">".$img_edit." แก้ไข</a> ";
														if($_SESSION['sys_status_del']=='1'){	
															$delete = "<button type=\"button\" class=\"btn btn-default btn-xs\" onClick=\"delData('".$PRJP_ID."','".$rec['PRJP_PRODUCT_ID']."');\">".$img_del." ลบ</a> ";
														}
														?>
														<tr bgcolor="#FFFFFF">
															<td align="center"><?php echo $i; ?>.</td>
															<td align="left"><?php echo text($rec['PRJP_PRODUCT_NAME']);?></td>
															<?php /*?><td align="left"><?php echo text($rec['PRJP_PRODUCT_DESC']);?></td><?php */?>
															<td align="center"><?php if($rec['TYPE_PRO_ID']!='9999'){ echo text($rec['TYPE_PRO_NAME']);}else{echo  "อื่น ๆ";}?></td>
															<td align="right"><?php echo number_format($rec['GOAL_VALUE'],2);?></td>
															<td align="center"><?php if($rec['TYPE_PRO_ID']!='9999'){ echo text($rec['UNIT_NAME_TH']);}else{echo  text($rec['UNIT_PRO_NAME']);}?></td>
															<!-- <td align="center"><?php echo $edit.$delete;?></td> -->
														</tr>
														<?php 
														$i++;
													}
												}else{
													echo "<tr><td align=\"center\" colspan=\"6\">ไม่พบข้อมูล</td></tr>";
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
				
				<?php 
				if($rec_head['BDG_TYPE_ID'] != 4){
					include("disp_project_result_copy_r.php");
				}
				?>
				
				<?php include("disp_project_act_task_copy_r.php");?>
				
			</form>
		</div>
	</div>
	<div class="clearfix" align="center"></div>
	<?php include($path."include/footer.php"); ?>
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