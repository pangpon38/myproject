<?php
session_start();
header("Content-type: application/vnd.ms-word");
header('Content-Disposition: attachment; filename="รายงานผลการส่งเสริม SMEs จำแนกตามประเภทและรายหน่วยงาน.doc"');#ชื่อไฟล์
$path = "../../";
include($path."include/config_header_top.php");

$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);

$filter == "";
$filter1 == "";
if($_POST['s_round_year_bud'] != ""){
	$filter .= " AND a.YEAR_BDG = '".$_POST['s_round_year_bud']."' ";
	$b_year = ($_POST['s_round_year_bud']-1);
	$n_year = $_POST['s_round_year_bud'];
}
if($_POST['s_org_id'] != ""){
	$filter .= " AND a.ORG_ID = '".$_POST['s_org_id']."' ";
}
if($_POST['s_org_type']!=""){
	$filter .=" AND ot.ORG_TYPE_ID = '".$_POST['s_org_type']."'";	
}
if($_POST['s_tr'] == "2"){
	$filter1 .= " AND o.ORG_ID = '".$_SESSION['sys_dept_id']."' ";
}else{
	$filter1 .= " ";	
}
$sql_val_desc = "select b.TYPE_RES_ID,b.PRJP_RESULT_NAME,b.PRJP_RESULT_ID,sum(c.PLAN_VALUE)as S_VAL_DESC 
				FROM prjp_project a
					JOIN prjp_result b on b.PRJP_ID = a.PRJP_ID 
					JOIN prjp_report_result c on c.PRJP_RESULT_ID = b.PRJP_RESULT_ID 
					JOIN setup_org o on o.ORG_ID = a.ORG_ID
					JOIN setup_org_type ot on ot.ORG_TYPE_ID = o.ORG_TYPE_ID
				WHERE 1=1 AND c.TYPE_RES_ID IN('15') {$filter} {$filter1}
				GROUP BY b.PRJP_RESULT_NAME
				";
$query_val_desc = $db->query($sql_val_desc);
$num_rows_desc = $db->db_num_rows($query_val_desc);
while($rec_desc = $db->db_fetch_array($query_val_desc)){
	$arr_val_desc[$rec_desc['PRJP_RESULT_ID']] =  $rec_desc['PRJP_RESULT_NAME']." ".number_format($rec_desc['S_VAL_DESC']);	
}
$sql_val_desc1 = "select b.TYPE_PRO_ID,b.PRJP_PRODUCT_NAME,b.PRJP_PRODUCT_ID,sum(c.PLAN_VALUE)as S_VAL_DESC 
				FROM prjp_project a
					JOIN prjp_product b on b.PRJP_ID = a.PRJP_ID 
					JOIN prjp_report_product c on c.PRJP_PRODUCT_ID = b.PRJP_PRODUCT_ID 
					JOIN setup_org o on o.ORG_ID = a.ORG_ID
					JOIN setup_org_type ot on ot.ORG_TYPE_ID = o.ORG_TYPE_ID
				WHERE 1=1 AND c.TYPE_PRO_ID IN('15') {$filter} {$filter1}
				GROUP BY b.PRJP_PRODUCT_NAME
				";
$query_val_desc1 = $db->query($sql_val_desc1);
$num_rows_desc1 = $db->db_num_rows($query_val_desc1);
while($rec_desc1 = $db->db_fetch_array($query_val_desc1)){
	$arr_val_desc1[$rec_desc1['PRJP_PRODUCT_ID']] =  $rec_desc1['PRJP_PRODUCT_NAME']." ".number_format($rec_desc['S_VAL_DESC']);	
}/////////////////////////////////////////////////////////////
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="language" content="en" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<style type="text/css">
  body{
    font-family: "TH SarabunPSK";
    font-size: 18px;
  }

  table{
    font-family: "TH SarabunPSK";
    font-size: 18px;
  }
  td{
    font-family: "TH SarabunPSK";
    font-size: 18px;
  }
  tr{
    font-family: "TH SarabunPSK";
    font-size: 18px;
  }
</style>
</head>
<body>
<center><h4><strong><u>รายงานสรุปผลการส่งเสริม SMEs ตามประเภทโครงการ (เชิงปริมาณ) ปีงบประมาณ <?php echo $_POST['s_round_year_bud']; ?></u></strong></h4></center>
<br>

        <table width="98%" border="1" bordercolor="#000000" cellpadding="3">
			  <tbody>
              	<tr>
					<td><br>
                     <?php 
					if(count($arr_val_desc)>0 || count($arr_val_desc1)>0){
					if(count($arr_val_desc)>0){
					foreach($arr_val_desc as $key_desc => $val_desc){ ?>
                    <ul>
                 		<li><?php echo text($val_desc); ?></li>
                    </ul>
                    <?php }} ?>
                    <?php 
					if(count($arr_val_desc1)>0){
					foreach($arr_val_desc1 as $key_desc => $val_desc){ ?>
                    <ul>
                 		<li><?php echo text($val_desc); ?></li>
                    </ul>
                    <?php }}}else{echo "ไม่พบข้อมูล";} ?><br>
                    </td>
                </tr>
              </tbody>
            </table>
</body>
</html>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<!-- /.modal -->