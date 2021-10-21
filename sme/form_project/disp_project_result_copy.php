<!--<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
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
								<?php /* ?><option value="3">PDF</option><?php */ ?>
					
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
</div>-->
<?php 
if($_SESSION['sys_status_print']=='1'){	
	$print_form_r = "<a class=\"btn btn-info\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"Print_form_r('".$PRJP_ID."');\">".$img_print."  พิมพ์ผลลัพธ์ สสว.100/1</a> ";
}
?>
<div class="row div1-fix-width">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading row" style="">
				<div class="pull-left">ผลลัพธ์</div>  
				<div class="pull-right">สสว.100/1</div>
			</div>
			<div class="panel-body epm-gradient" >
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<?php if($_SESSION['sys_status_add']=='1' && ($rec_head['PRJP_STATUS']=='2' || $rec_head['PRJP_STATUS']=='3' || $rec_head['SERVICE_PROJECT_ID']=='0')){	 ?>
							<a data-toggle="modal" class="btn btn-default" data-backdrop="static" href="javascript:void(0);" onClick="addData_r(<?php echo $PRJP_ID; ?>);"><?php echo $img_save;?> เพิ่มผลลัพธ์</a>
						<?php } ?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<div class="">
							<table width="22%" class="table table-bordered table-striped table-hover table-condensed">
								<thead>
									<tr class="bgHead">
										<th width="2%"><div align="center"><strong>ลำดับ</strong></div></th>
										<th width="15%"><div align="center"><strong>ชื่อ/รายละเอียดผลลัพธ์</strong></div></th>
										<?php /*?><th width="15%"><div align="center"><strong>รายละเอียดผลลัพธ์</strong></div></th><?php */?>
										<th width="15%"><div align="center"><strong>ผลการส่งเสริม</strong></div></th>
										<th width="10%"><div align="center"><strong>เป้าหมาย</strong></div></th>
										<th width="10%"><div align="center"><strong>ประเภทหน่วยนับ</strong></div></th>
										<th width="10%"><div align="center"><strong>การจัดการ</strong></div></th>
									</tr>
							</thead>
							<tbody>
							<?php
									if($num_rowsr > 0){
									$r=1;
							$query_r = $db->query($sqlr);
							while($rec_r = $db->db_fetch_array($query_r)){
								$edit_r = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"editData_r('".$PRJP_ID."','".$rec_r['PRJP_RESULT_ID']."','".$rec_head['PRJP_CON_ID']."');\">".$img_edit." แก้ไข</a> ";
								if($_SESSION['sys_status_del']=='1' && ($rec_head['PRJP_STATUS']=='2' || $rec_head['PRJP_STATUS']=='3' || $rec_head['SERVICE_PROJECT_ID']=='0')){
									$delete_r = "<button type=\"button\" class=\"btn btn-default btn-xs\" onClick=\"delData_r('".$PRJP_ID."','".$rec_r['PRJP_RESULT_ID']."');\">".$img_del." ลบ</a> ";
								}
							?>
									<tr bgcolor="#FFFFFF">
										<td align="center"><?php echo $r; ?>.</td>
										<td align="left"><?php echo text($rec_r['PRJP_RESULT_NAME']);?></td>
										<td align="center"><?php if($rec_r['TYPE_RES_ID']!='9999'){ echo text($rec_r['TYPE_RES_NAME']);}else{echo  "อื่น ๆ";}?></td>
										<td align="right"><?php echo number_format($rec_r['GOAL_VALUE'],2);?></td>
										<td align="center"><?php if($rec_r['UNIT_ID']!=''){ echo text($rec_r['UNIT_NAME_TH']);}else{echo  text($rec_r['UNIT_RES_NAME']);}?></td>
								<td align="center"><?php echo $edit_r.$delete_r;?></td>
									</tr>
									<?php 
									$r++;
								} 
							}else{
									echo "<tr><td align=\"center\" colspan=\"7\">ไม่พบข้อมูล</td></tr>";
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
