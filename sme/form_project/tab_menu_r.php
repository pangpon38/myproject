<?php 
//echo $proc;
$arr_data_tab = array(
	'0'=>'โครงการ',
	'1'=>'กิจกรรม(สสว.100)',
	'2'=>'100/1-2 ผลผลิต/ผลลัพธ์/แผนการดำเนินงานของกิจกรรม',
	//'3'=>'ผลลัพธ์(สสว.100/1)',
	//'4'=>'แผนการดำเนินงานของกิจกรรม(สสว.100/2)',
	'5'=>'แผนการใช้จ่ายเงินของกิจกรรม(สสว.100/3)',
	// '6'=>'ไฟล์แนบสถานะการดำเนินโครงการ',
	'7'=>'ผลสัมฤทธิ์ที่คาดว่าจะได้รับ',
	'8'=>'แนวทางในการบริหารความเสี่ยง',
);

$arr_data_link = array(
'0'=>$menu_sub_id=='324'?'form_project_r.php':'form_send_project.php',
'1'=>'disp_project_act_r.php',
'2'=>'disp_project_product_r.php',
//'3'=>'disp_project_result.php',
//'4'=>'disp_project_act_task.php',
'5'=>'disp_project_act_money_r.php',
// '6'=>'disp_attached_file.php',
'7'=>'disp_project_task_plan_result_r.php',
'8'=>'disp_project_task_plan_risk_r.php',
);

$CHK_BDG_TYPE_ID = !empty($rec_head['BDG_TYPE_ID']) ? $rec_head['BDG_TYPE_ID'] : $rec['BDG_TYPE_ID'];
if($CHK_BDG_TYPE_ID == 4){
	unset($arr_data_tab[5]);
	unset($arr_data_link[5]);
}

//active
${'tab'.$ACT}="active";
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id."&proc=".$proc."&PRJP_ID=".$PRJP_ID.$paramSearch;
?>
<ul class="nav nav-tabs visible-xs visible-sm visible-md visible-lg" >
	<?php 
	foreach($arr_data_tab as $key=>$val){ ?>
		<li class=" <?php echo ${'tab'.$key};?>"><a href="<?php echo $arr_data_link[$key];?>?<?php echo url2code($link."&ACT=".$key);?>"><?php echo $val;?></a></li>
			<?php }
	?>
	
	
</ul> 
