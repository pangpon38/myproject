<?php
session_start();
$path = "../../";
include($path."include/config_header_top.php");
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '16';
if($_POST['PRJP_ID']!=''){
$PRJP_ID = $_POST['PRJP_ID'];	
}else{
$PRJP_ID = $PRJP_ID;	
}

$month = array("10"=>"ต.ค.","11"=>"พ.ย.","12"=>"ธ.ค.","1"=>"ม.ค.","2"=>"ก.พ.","3"=>"มี.ค.","4"=>"เม.ย.","5"=>"พ.ค.","6"=>"มิ.ย.","7"=>"ก.ค.","8"=>"ส.ค.","9"=>"ก.ย.");
$month_full = array("1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน","7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
$sql_head="SELECT PRJP_CODE,PRJP_NAME,EDATE_PRJP,SDATE_PRJP,PRJP_CON_ID,PRJP_STATUS,BDG_TYPE_ID FROM prjp_project WHERE PRJP_ID = '".$PRJP_ID."'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);


///////////////////////////////////////////////////////////////////////////////////////

?>
<!DOCTYPE html>
<html>
<head>
	<?php include($path."include/inc_main_top.php"); ?>
<script src="js/disp_sme400.js?<?php echo rand(); ?>"></script>
<script type="text/javascript">  
function tab_list(id){
	  $(".tb_v").hide();
	  $(".re_act").removeClass("active");
	 
	  $("#show_rb_"+id).show();
	  $("#tablist_"+id).addClass("active");
  
  }
</script>
</head>
<body>
<div class="container-full">
	<div><?php include($path."include/header.php"); ?></div>
	<div class="col-xs-12 col-sm-12">
        <ol class="breadcrumb">
          <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
         <li><a href="disp_send_project.php?<?php echo url2code("menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id);?>"><?php echo Showmenu($menu_sub_id);?></a></li>
          <li class="active">ผลตัวชี้วัดของผลผลิต</li>
        </ol>
    </div>
   
	
	<div class="col-xs-12 col-sm-12">
		<div class="groupdata" >
			<form id="frm-search" method="post" action="#" enctype="multipart/form-data">
				<input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
				<input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
				<input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
				<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
				<input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                <input type="hidden" id="year_round" name="year_round" value="<?php echo $_SESSION['year_round']; ?>">
                <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
				<input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />
                <!-- Modal -->
				
        		<div class="row">
					<div class="col-xs-12 col-sm-12"><?php include("tab_menu2.php");?></div>
					<?php 
					if($_SESSION["sys_group_id"]=='5' || $_SESSION["sys_group_id"]=='9'){
					?>
					<div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php");?></div>
					<?php 
					}
					?>
				</div>
 		     
				<div class="row"><div class="col-xs-12 col-sm-12 col-md-12"> </div></div>
				<div class="row">  
					<div class="col-xs-12 col-sm-12 font-blue" align="center">
						<strong><?php echo $rec_head['PRJP_CODE']." ".text($rec_head['PRJP_NAME']) ?></strong>
					</div>
				</div>      
                
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading row" style="">
								<div class="pull-left" style="">แนบไฟล์รายชื่อบุคคลที่เกี่ยวข้องกับการส่งเสริม SME ทั้งภาครัฐ เอกชน รัฐวิสาหกิจ</div>
								<div class="pull-right" style=""></div>
							</div>
							<div class="panel-body epm-gradient" >
								<?php //$print_form = "<a data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form1('".$PRJP_ID."');\">".$img_print."  พิมพ์ สสว.300</a> ";?> 
								<div class="row"><div class="col-xs-12 col-sm-12"><?php echo $print_form; ?></div></div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 tb2_v" id="show_rb_99999">
										<div class="">
											<div  style="margin-bottom:10px;">
												<?php if($_SESSION['sys_status_add']=='1'){ ?>
													<a data-toggle="modal" class="btn btn-default" data-backdrop="static" href="javascript:void(0);" onClick="addrow_file();">
													<?php echo $img_save;?> เพิ่มข้อมูล</a>
												<?php } ?>
												
												<?php 
												 if($_SESSION['sys_status_print']=='1'){
													echo $print_form = "<a class=\"btn btn-info\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form4('".$PRJP_ID."');\">".$img_print."  พิมพ์ สสว.400</a> ";
												 }
												 ?>
											</div>
											<table width="22%" class="table table-bordered table-striped table-hover table-condensed" id="tb_file_prjp">
												<thead>
													<tr class="bgHead">
														<th width="5%" rowspan="2"><div align="center"><strong>ลำดับ</strong></div></th>
														<th width="20%" rowspan="2"><div align="center"><strong>กิจกรรม / ผลผลิต </strong></div></th>
														<th width="20%" rowspan="2"><div align="center"><strong>กิจกรรม / ผลผลิต (ที่ขอเปลี่ยนแปลง)</strong></div></th>
														<th width="20%" rowspan="2"><div align="center"><strong>ระยะเวลาการดำเนินงาน (ที่ขอเปลี่ยนแปลง)</strong></div></th>
														<th width="20%" rowspan="2"><div align="center"><strong>คำชี้แจงและเหตุผล</strong></div></th>
														<th width="5%" rowspan="2"><div align="center"><strong>จัดการ</strong></div></th>
													</tr>
												</thead>
												<tbody>
													<?php 
													$i=0;
													$sql_file = "select * from prjp_400 where PRJP_ID = '".$PRJP_ID."'";
													$query_file = $db->query($sql_file);
													while($rec_file = $db->db_fetch_array($query_file)){
														$i++;
														?>
														<tr>
															<td align="center"><?php echo $i; ?></td>
															<td>
																<input type="text" id="PRJP_ACT_<?php echo $i; ?>" name="PRJP_ACT[]" value="<?php echo $rec_file['PRJP_ACT']; ?>">
															</td>
															<td>
																<input type="text" id="PRJP_ACT_CHANGE_<?php echo $i; ?>" name="PRJP_ACT_CHANGE[]" value="<?php echo $rec_file['PRJP_ACT_CHANGE']; ?>">
															</td>
															<td>
																<input type="text" id="DESC_CHANGE_<?php echo $i; ?>" name="DESC_CHANGE[]" value="<?php echo $rec_file['DESC_CHANGE']; ?>">
															</td>
															<td>
																<input type="text" id="COMMENT_<?php echo $i; ?>" name="COMMENT[]" value="<?php echo $rec_file['COMMENT']; ?>">
															</td>
															<td align="center">
																<a data-toggle="modal" class="btn btn-default btn-xs" data-backdrop="static"  onClick="del_row(this);"><?php echo $img_del;?> ลบ</a>
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
				</div>
				
				<div class="clearfix" align="center">
					<?php if($_SESSION['sys_status_edit']=='1'){ ?>
						<button type="button" class="btn btn-primary" onClick="chkinput();">บันทึก</button>
					<?php } ?>
				</div>

				<?php //echo endPaging("frm-search",$total_record); ?>
				<div class="clearfix"></div>
				
				
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
						<div><label>ถึง</label></div>
						<div class="row">
							<div class="col-md-12">
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
							<div class="col-md-4" style="text-align:left;">
								<button type="button" class="btn btn-default" data-dismiss="modal" onClick="submitPrint();">พิมพ์</button>
							</div>
						</div>
						
					  </div>
					  <div class="modal-footer"></div>
					</div>

				  </div>
				</div>
			</form>
		</div>
	</div>
	<?php include($path."include/footer.php"); ?>
</div>
</body>
</html>
<?php //echo form_model('myModal','ปัญหา-อุปสรรค','show_display','','','1');?>
<?php //echo form_model1('myModal1','เลือกวันที่ออกรายงาน','show_display1','','','1');?>
<?php echo form_model('myModal1','เลือกวันที่ออกรายงาน','show_display','','','1');?>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<div class="modal fade" id="myModal1"></div>

<!-- /.modal -->