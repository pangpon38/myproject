<?php 
//echo $proc;
$arr_data_tab = array(
'8'=>'200/1-2 ข้อมูลการรายงานผลผลิต/ผลลัพธ์/ผลการดำเนินงานของกิจกรรม',
//'9'=>'ข้อมูลการรายงานผลลัพธ์(สสว.200/1)',					  
//'6'=>'ผลการดำเนินงานของกิจกรรม(สสว.200/2)',
'7'=>'200/3 ผลการใช้จ่ายเงินของกิจกรรม',
//'10'=>'300 แนบไฟล์ที่เกี่ยวข้องกับโครงการ/รูปภาพที่เกี่ยวข้อง',	
//'14'=>'แนบไฟล์รายชื่อบุคคลที่เกี่ยวข้องกับการส่งเสริม SME ทั้งภาครัฐ เอกชน รัฐวิสาหกิจ',
'16'=>'สสว.400',
'27'=>'ข้อมูลมูลค่าทางเศรษฐกิจ',
'15'=>'เพิ่มสัญญา',
'28'=>'สถานะการดำเนินงาน',
'30'=>'ผลสัมฤทธิ์ที่คาดว่าจะได้รับ',
'29'=>'แนวทางในการบริหารความเสี่ยง',
'31'=>'ปัญหาอุปสรรคจากการดำเนินงาน/โครงการ (อ้างอิง สสว.300)',
);

$arr_data_link = array(
'8'=>'disp_project_send_product.php',
//'9'=>'disp_project_send_result.php',
//'6'=>'disp_project_send_act_task.php',
'7'=>'disp_project_send_act_money.php',
'10'=>'disp_file_project.php',
'14'=>'disp_file_business.php',
'16'=>'disp_sme400.php',
'27'=>'economic_value.php',
'15'=>'form_project_act_contract.php',
'28'=>'form_project_status.php',
'30'=>'disp_project_task_report_result.php',
'29'=>'disp_project_task_report_risk.php',
'31'=>'form_project_risk.php',
);

$CHK_BDG_TYPE_ID = !empty($rec_head['BDG_TYPE_ID']) ? $rec_head['BDG_TYPE_ID'] : $rec['BDG_TYPE_ID'];
if($CHK_BDG_TYPE_ID == 4){
	unset($arr_data_tab[7]);
	unset($arr_data_link[7]);
	
	unset($arr_data_tab[10]);
	unset($arr_data_link[10]);
	
	unset($arr_data_tab[14]);
	unset($arr_data_link[14]);
}

$PRJP_ID = $PRJP_ID;
//active
${'tab'.$ACT}="active";
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id."&proc=".$proc."&PRJP_ID=".$PRJP_ID;
?>
<ul class="nav nav-tabs visible-xs visible-sm visible-md visible-lg" >
<?php 
foreach($arr_data_tab as $key=>$val){
	?>
	<li class=" <?php echo ${'tab'.$key};?>"><a href="<?php echo $arr_data_link[$key];?>?<?php echo url2code($link."&ACT=".$key);?>" <?php echo $tar;?>><?php echo $val;?></a></li>
	<?php 
}
?>
</ul>
