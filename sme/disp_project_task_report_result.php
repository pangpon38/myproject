<?php
session_start();
$path = "../../";
include($path."include/config_header_top.php");
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '30';

if($_GET['proc'] == 'save_result'){
	$result_value = $_POST['result_value'];
	if(count($result_value) > 0){
		foreach($result_value as $task => $arr){
			foreach($arr as $month => $val){
				
				$db->query(" delete from service_task_month_result_temp WHERE task_result_id = '" . $task . "' and task_month = '".$month."' ");
				$db->query(" insert into service_task_month_result_temp 
				(task_result_id, task_month, task_value, project_id, bdg_type, act_project_id, main_project_id, result_value )
				select task_result_id, task_month, task_value, project_id, bdg_type, act_project_id, main_project_id, '".str_replace(',','',$val)."' 
				FROM service_task_month_result 
				WHERE task_result_id = '" . $task . "' and task_month = '".$month."' ");
				
				/*$fields = array('result_value' => str_replace(',','',$val) );
				$db->db_update("service_task_month_result", $fields, " task_result_id = '" . $task . "' and task_month = '".$month."' ");*/
			}
		}
	}
	echo 'บันทึกข้อมูลเรียบร้อย';
	exit;
}

if($_POST['PRJP_ID']!=''){
$PRJP_ID = $_POST['PRJP_ID'];	
}else{
$PRJP_ID = $PRJP_ID;	
}
$month_full = array("1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน","7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
$month_full_bdg = array("10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม","1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน","7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน");

$sql_head="SELECT SERVICE_PROJECT_ID, SDATE_PRJP, EDATE_PRJP FROM prjp_project WHERE PRJP_ID = '".$PRJP_ID."' ";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$month = array("10"=>"ต.ค.","11"=>"พ.ย.","12"=>"ธ.ค.","1"=>"ม.ค.","2"=>"ก.พ.","3"=>"มี.ค.","4"=>"เม.ย.","5"=>"พ.ค.","6"=>"มิ.ย.","7"=>"ก.ค.","8"=>"ส.ค.","9"=>"ก.ย.");

$ms = substr($rec_head['SDATE_PRJP'],5,2)*1;
$ys = substr($rec_head['SDATE_PRJP'],0,4)+543;
$me = substr($rec_head['EDATE_PRJP'],5,2)*1;
$ye = substr($rec_head['EDATE_PRJP'],0,4)+543;
$yse = ((($ye-$ys)*12))-(12-$me);
$row_col = (((12-$ms)+1)+((($ye-$ys)-1)*12)+(12-(12-$me)));
$row_wh = ceil($row_col/12);
$row_round = (($row_wh*12)-$row_col);
$fbs = $ys.sprintf("%'02d",$ms);
$fbe = $ye.sprintf("%'02d",$me);

if($row_round==0){
	$row_round = 12;	
}elseif($row_round<12){
	$row_round = (12+$row_round);
}else{
	$row_round = $row_round ;		
}
$row_m = $me+$row_round;

$row_mn = ($row_m-12);
$ye_mn = ($ye+$row_wh);
if($row_mn<10){
	$fbe_r = $ye_mn."0".$row_mn;
}else{
	$fbe_r = $ye_mn.$row_mn;	
}
$k = $fbs;
while($k<=$fbe_r){
			$rr[] = $k;
			$smr = substr($k,4,2);
			$syr = substr($k,0,4);
			if($smr=='12'){
			$k = ($syr+1)."01";	
			}else{
			$k++;	
			}
}

$x = $fbs;
while($x<=$fbe){
			$m[] = $x;
			$sm = substr($x,4,2);
			$sy = substr($x,0,4);
			if($sm=='12'){
			$x = ($sy+1)."01";	
			}else{
			$x++;	
			}
}
$c_arr = count($m);

$sql = "SELECT * FROM service_task_plan_result WHERE main_project_id = '".$rec_head['SERVICE_PROJECT_ID']."' ";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);

////////////////////////////// end  ////////////////////////////////////
?>
<!DOCTYPE html>
<html>
<head>
	<?php include($path."include/inc_main_top.php"); ?>
<script src="js/disp_project_act_money.js?<?php echo rand(); ?>"></script>
<script type="text/javascript"> 

async function sum_month(task){
	var sum = 0;
	$.each($('input[id^=task_'+task+'_]'), await function(){
		var this_val = this.value;
		this_val = this_val.split(',').join('');
		if(isNaN(this_val) || this_val == ''){
			$(this).val('0.00');
			this_val = 0;
		}else{
			this_val = parseFloat(this_val);
		}
		sum += this_val;
	});
	//NumberFormat(this,2)
	$('#sum_task_'+task).html(number_format_txt(sum,2));

}

function chk_old(id){
	if(id==0){
		$("#hide_old").val(1);
	}else if(id==1){
		$("#hide_old").val(0);	
	}else if(id==2){
		$("#hide_old").val(0);	
	}
	id = $("#hide_old").val();
	show_old(id);  
}
function show_old(id){
	if(id==1){	
		$(".data_old").show();	
		$(".data_old2").show();	
		$("#tab_old").show();
		//$("#TB_BR").html("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
	}else{
		$(".data_old").hide();	
		$(".data_old2").hide();
		$("#tab_old").hide();
		//$("#TB_BR").html("");
	}
	stableo(id);
}
function stable(id){
	
	$(".htb").hide();	
	$(".htbv").hide();
	$("#tb_"+id).show();
	$("#tbv_"+id).show();	
}
function stableo(id){
	$(".htbo").hide();	
	$(".htbvo").hide();
	$("#tbo_"+id).show();
	$("#tbvo_"+id).show();	
}
</script>
</head>
<body style="display:inline-block">
<div class="container-full" >
	<div><?php include($path."include/header.php"); ?></div>
	<div class="col-xs-12 col-sm-12">
        <ol class="breadcrumb">
          <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
         <li><a href="disp_send_project.php?<?php echo url2code("menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id);?>"><?php echo Showmenu($menu_sub_id);?></a></li>
          <li class="active">ผลสัมฤทธิ์ที่คาดว่าจะได้รับ</li>
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
                <input type="hidden" id="code_user" name="code_user" value="<?php echo $_SESSION['sys_dept_id']; ?>">
                <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
				<input type="hidden" id="YMIN" name="YMIN" value="<?php echo $ys; ?>">
                <input type="hidden" id="YMAX" name="YMAX" value="<?php echo $ye; ?>">
                <input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />
                
                <div class="row"><div class="col-xs-12 col-sm-12"><?php include("tab_menu2.php");?></div></div>
				<div class="row"><div class="col-xs-12 col-sm-12 col-md-12"> </div></div>
				<div class="row">  
					<div class="col-xs-12 col-sm-12 font-blue" align="center">
						<strong><?php echo $rec_head['PRJP_CODE']." ".text($rec_head['PRJP_NAME']) ?></strong>
					</div>
				</div>
				
				<div class="row page-prjp-money">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading row" style="">
								<div class="pull-left" style="">ผลสัมฤทธิ์ที่คาดว่าจะได้รับ</div>
							</div>
							<div class="panel-body epm-gradient" >
								<?php 
								$mstart = 0;
								$mend = 11;
								$rowtb = 0;
								for($row=1;$row<=$row_wh;$row++){ ?>
									<div id="tb_<?php echo $row; ?>" class=" col-xs-12 col-sm-12 htb" style="">
										<table width="22%" class="table table-bordered table-striped table-hover table-condensed">
											<thead>
												<tr class="bgHead table-head-money">
													<th width="40px" ><div align="center"><strong>ลำดับ</strong></div></th>
													<th width="230px" colspan="2"><div align="center"><strong>ผลสัมฤทธิ์ที่คาดว่าจะได้รับ</strong></div></th>
													<th width="100px" ><div align="center"><strong>รวม (ล้านบาท)</strong></div></th>
													<?php 
													foreach($rr as $key => $val){ 
														if($key >= $mstart && $key <= ($mend*$row)+$rowtb){	
															$smh = substr($val,4,2);
															$syh = substr($val,2,2);
															?>
															<th width="110px"><div align="center"><strong><?php echo $month[$smh*1].$syh; ?></strong></div></th>
															<?php 
														}
													} ?>
												</tr>
											</thead>
											<tbody>
												<?php
												if($num_rows > 0){
													$l=1;
													$query = $db->query($sql);
													while($rec = $db->db_fetch_array($query)){
														$msa = 10;
														$mea = substr($rec['EDATE_PRJP'],5,2);
														$yea = substr($rec['EDATE_PRJP'],0,4)+543;
														$ysa = substr($rec['SDATE_PRJP'],0,4)+543;
														
														$year_ms = $ysa.$msa;
														$year_me = $yea.$mea;
														$row_cola = (((12-$msa)+1)+((($yea-$ysa)-1)*12)+(12-(12-$mea)));

														$sqlChild = " SELECT * FROM service_task_month_result WHERE task_result_id = '{$rec['task_result_id']}' ";
														$queryChild = $db->query($sqlChild);
														$arrChild = $arrChild2 = array();
														$totalChild = 0;
														while($recChild = $db->db_fetch_array($queryChild)){
															$arrChild[$recChild['task_month']] = $recChild['task_value'];
															$arrChild2[$recChild['task_month']] = $recChild['result_value'];
															$totalChild += $recChild['task_value'];
															$totalChild2+= $recChild['result_value'];
														}
														
														?>
														<tr bgcolor="#FFFFFF" class="">
															<td align="center" rowspan="2" width="50px"><?php echo $l; ?>.</td>
															<td align="left" rowspan="2" width="222px">
																<textarea rows="3" cols="15" class="prjp-name-show" disabled=""><?php echo text($rec['result_name']);?></textarea>
															</td>
															<td align="center" style="background:#bebebe">แผน</td>
															<td align="center" style="background:#eaeaea"><?php echo number_format($totalChild, 2);?></td>
															<?php 
															$i=1;
															foreach($rr as $key => $val){
																if($key >= $mstart && $key <= ($mend*$row)+$rowtb){	
																	$m_d = substr($val,4,2);
																	$y_d = substr($val,0,4);								
																	?>
																	<td align="right" style="background:#eaeaea"><?php echo number_format($arrChild[$m_d], 2);?></td>
																	<?php		
																}
																$i++;
															} 
															?>
														</tr>
														<tr> 
															<td align="center" style="background:#7fadad">ผล</td>
															<td align="center" style="background:#afeeee" id="sum_task_<?php echo $rec['task_result_id']; ?>"><?php echo number_format($totalChild2, 2);?></td>
															<?php 
															foreach($rr as $key => $val){
																if($key >= $mstart && $key <= ($mend*$row)+$rowtb){	
																	$m_d = substr($val,4,2);
																	$y_d = substr($val,0,4);								
																	?>
																	<td align="right" style="background:#afeeee">
																		<input name="result_value[<?php echo $rec['task_result_id']; ?>][<?php echo $m_d; ?>]" type="text" size="5" class="form-control text-right" value="<?php echo number_format($arrChild2[$m_d], 2); ?>" id="task_<?php echo $rec['task_result_id']."_".$m_d; ?>" onblur="NumberFormat(this,2);sum_month('<?php echo $rec['task_result_id'];?>');">
																	</td>
																	<?php		
																}
															} ?>
														</tr>
														<?php
														$l++;
													}
												}
												else{
													echo "<tr><td align=\"center\" colspan=\"14\">ไม่พบข้อมูล</td></tr>";
												}
												?>
											</tbody>
										</table>
									</div>
									
									<div class="clearfix" align="center">
										<?php if($num_rows > 0  && $_SESSION['sys_status_edit']=='1' && ($_SESSION["sys_group_id"] == '5' || $distxt != 'readonly')){ ?>
											<div class="row"><button type="button" class="btn btn-success" onClick=" if(confirm('ยืนยันการบันทึก ?')){ $.post('disp_project_task_report_result.php?proc=save_result', $('#frm-search').serialize(), function(rst){ alert(rst); }) } ">บันทึก</button></div>
										<?php } ?>
									</div>
									
									<?php
									$mstart=$mstart+12;
									$rowtb = $rowtb+1;
								}
								?>
							</div>
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