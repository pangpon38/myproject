<?php
session_start();
$path = "../../../";
define('FPDF_FONTPATH','font/');
include($path."include/config_header_top.php");
include($path."include\MPDF53/mpdf.php");
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";

$h = 10;
$pdf = new mPDF('th', 'A4', '0', 'THSaraban',15,15,$h,16,9,9,'');
$filter = " and  a.acc_close = '0' ";
$filter = " and  a.acc_close = '0' ";
$filter_compare = " and  a.acc_close  = '0' ";
if($s_format==4 || $s_format==''){ //วันที่

	if($s_format==4){
		 $s_year_compare  = substr($e_tran_date , -4);
		$filter .= "and tran_date between '".conv_date_db($s_tran_date)."' and '".conv_date_db($e_tran_date)."'";	
		$filter_compare .= "and tran_date between '".conv_date_db_compare($s_tran_date)."' and '".conv_date_db_compare($e_tran_date)."'"; 
	}else{
		 $start_date = $db->get_data_field("SELECT start_date From ACC_YEAR Where active_status = '1'","start_date");
		 $s_tran_date =  conv_date($start_date);
		 $e_tran_date = date("Y-m-d");
		$filter .= "and tran_date between '".($start_date)."' and '".($e_tran_date)."'";
		
		$s_year_compare  = date("Y")+543;
		
		 $e_tran_date = conv_date(date("Y-m-d"));
		
		$start_date_compare = $db->get_data_field("SELECT start_date From ACC_YEAR Where year_account= '".($s_year_compare-1)."'","start_date");
		$e_tran_date_compare = date("Y-m-d",mktime(0,0,0,date('m') , date('d') , date('Y')-1));
		
		$filter_compare .= "and tran_date between '".($start_date_compare)."' and '".($e_tran_date_compare)."'";
		
	}
}
if($s_format==1){ //เดือน
	$s_year_compare  = $s_year;
	if($S_MONTH !=''){
		 $filter .= " and MONTH(tran_date)  = '".str_pad($S_MONTH,2,0,STR_PAD_LEFT)."'";		 
		  $filter_compare .= " and MONTH(tran_date)  = '".str_pad($S_MONTH,2,0,STR_PAD_LEFT)."'";
	}
	if($s_year !=''){
		 $s_year_22 = $s_year-543;
		 $filter .= " and YEAR(tran_date)  = '".$s_year_22."'";
		 $filter_compare .= " and YEAR(tran_date)  = '".($s_year_22-1)."'";
	}
}
if($s_format==2){ //ไตรมาส
$s_year_compare  = $s_year2;
	$s_year_22 = $s_year2-543;
	if($s_quarter==1){
		$filter .= "and (MONTH(tran_date) between '01' and '03') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$filter_compare .= "and (MONTH(tran_date) between '01' and '03') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$month = '03';
	}
	if($s_quarter==2){
		$filter .= "and (MONTH(tran_date) between '04' and '06') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$filter_compare .= "and (MONTH(tran_date) between '04' and '06') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$month = '06';
	}
	if($s_quarter==3){
		$filter .= "and (MONTH(tran_date) between '07' and '09') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$filter_compare .= "and (MONTH(tran_date) between '07' and '09') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$month = '09';
	}
	if($s_quarter==4){
		$filter .= "and (MONTH(tran_date) between '10' and '12') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$filter_compare .= "and (MONTH(tran_date) between '10' and '12') AND YEAR(tran_date) = '".($s_year_22-1)."'   ";
		$month = '12';
	}
}
if($s_format==3){ //ไตรมาส
	 $s_year_compare  = $s_year3;
	$s_year_22 = $s_year3-543;
	$filter .= " and YEAR(tran_date)  = '".$s_year_22."'";
	$filter_compare .= " and YEAR(tran_date)  = '".($s_year_22-1)."'";
}


$arr_quarter=array('1'=>"ไตรมาสที่ 1",'2'=>"ไตรมาสที่ 2",'3'=>"ไตรมาสที่ 3",'4'=>"ไตรมาสที่ 4");
$sql_head ="SELECT DEP_NAME FROM SYS_BASIC";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$content = ob_start();
?>
<!DOCTYPE html>
<html  xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
        <meta charset="UTF-8">
        <meta name="language" content="en" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<style type='text/css'>
				.font_1 {
					font-size:15pt;
				}
				body{
					font-size:9pt;
				}
				table {
					border-collapse:collapse;
				}
				td {
					padding:5px;
				}
				
			</style>

<!--[if gte mso 9]>
<xml>
<w:WordDocument>
<w:View>Print</w:View>
<w:Zoom>100</w:Zoom>
<w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
  </w:Compatibility>
  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
</w:WordDocument>
</xml>
<![endif]-->

        </head>
<body>
    <div class="row">
		<!--<table width="50%" border="0" align="center">	
		</table> -->        
		<div class="row">
            <table width="50%" border="0" align="center">
              <tr>
                <td align="center"><?php echo text($rec_head['DEP_NAME']);?></td>
              </tr>
              <tr>
                <td align="center">งบรายได้-รายจ่าย</td>
              </tr>
              <tr>
                <td align="center"><?php
					if($s_format=='1'){
						$l_day =  date( "t", mktime(0,0,0,$S_MONTH,1,$s_year ));	
					
						echo "สำหรับงวดสิ้นสุดวันที่ " .$l_day."  ".$mont_th[$S_MONTH]." ปี ".$s_year."";
					}else if($s_format=='2'){
					$e_tran_date =  date( "t", mktime("01/".sprintf('%02d',$s_quarter)."/".($s_year2-543)) );	
						echo "สำหรับงวดสิ้นสุดวันที่  ".$e_tran_date." ".$arr_quarter[$s_quarter]." ปี ".$s_year2."";
					} else if($s_format=='3'){
						echo "สำหรับงวดสิ้นสุดวันที่ " .$s_year3."";
					} else if($s_format=='4' || $s_format==''){
						echo "สำหรับงวดสิ้นสุดวันที่ ".conv_date(conv_date_db($e_tran_date),'long');
					}
				 ?></td>
              </tr>
            </table>
         </div>
		<table width="100%" border="0" align="center">

		    <tr>
		      <td colspan="3"><U>รายได้จาการดำเนินงาน </U> : </td>
              <td  style='text-align:right;'><span class="col-xs-12 col-sm-2"><strong>ปี <?php echo  $s_year_compare ;?></strong></span></td>
              <td  style='text-align:right;'><span class="col-xs-12 col-sm-2"><strong>ปี <?php echo  $s_year_compare-1 ;?></strong></span></td>
	        </tr>
		    <?php 
				if($_POST['s_have_money']==1){
					
					//$filter =" and  (debit_value > 0  OR   credit_value > 0 )";
					
				}
					$sql_main = "SELECT * FROM ACC_GL WHERE gl_code LIKE '4%' AND gl_code NOT IN('4000') ORDER BY gl_code ASC ";
					$query_main = $db->query($sql_main);
					$Sum_all = 0;
					
					while($rec_main = $db->db_fetch_array($query_main)){
						
						/////////////////ปกติ
						$sqlSumTime = "select sum(debit_value) as sum_debit,sum(credit_value) as sum_credit 
						from V_ACC_TRANSEC_DETAIL a
						LEFT JOIN bdg_project b on a.project_id  =b.project_id
						where acc_gl_id = '".$rec_main["acc_gl_id"]."' {$filter} ";
						$querySumTime = $db->query($sqlSumTime);
						$recSumTime = $db->db_fetch_array($querySumTime);
							
						if($recSumTime["sum_debit"] > $recSumTime["sum_credit"] ){
							$SumM = $recSumTime["sum_debit"] - $recSumTime["sum_credit"]; 
						}
						if($recSumTime["sum_credit"] > $recSumTime["sum_debit"] ){
							$SumM = $recSumTime["sum_credit"] - $recSumTime["sum_debit"];
						}
						//echo $SumDrAdd.'=='.$SumCrAdd.'<br>';
						$Sum_all += $SumM;
						
						/////////////////เปรียบเทียบ
						 $sqlSumTime = "select sum(debit_value) as sum_debit,sum(credit_value) as sum_credit 
						from V_ACC_TRANSEC_DETAIL a
						LEFT JOIN bdg_project b on a.project_id  =b.project_id
						where acc_gl_id = '".$rec_main["acc_gl_id"]."' {$filter_compare} "; 
						
						$querySumTime = $db->query($sqlSumTime);
						$recSumTime = $db->db_fetch_array($querySumTime);
							
						if($recSumTime["sum_debit"] > $recSumTime["sum_credit"] ){
							$SumM_compare = $recSumTime["sum_debit"] - $recSumTime["sum_credit"]; 
						}
						if($recSumTime["sum_credit"] > $recSumTime["sum_debit"] ){
							$SumM_compare = $recSumTime["sum_credit"] - $recSumTime["sum_debit"];
						}
						
						
						$Sum_all_compare += $SumM_compare;
						
						
						
					if($_POST['s_have_money']==1){
						if( $SumM > 0  ||  $SumM_compare  > 0  ){  
							
				?>
		    <tr>
		      <td width="15%">&nbsp;</td>
		      <td width="30%"><?php echo text($rec_main['gl_name']);?></td>
		      <td width="12%" >&nbsp;</td>
		      <td width="32%" style='text-align:right;'><?php echo number_format($SumM,2); $SumM=0;?></td>
		      <td width="11%" style='text-align:right;'><?php echo number_format($SumM_compare,2); $SumM_compare=0;?></td>
	        </tr>
		    <?php
						}
					}else{
					?>
		    <tr>
		      <td width="15%">&nbsp;</td>
		      <td width="30%"><?php echo text($rec_main['gl_name']);?></td>
		      <td >&nbsp;</td>
		      <td style='text-align:right;'><?php echo number_format($SumM,2); $SumM=0;?></td>
		      <td style='text-align:right;'><?php echo number_format($SumM_compare,2); $SumM_compare=0;?></td>
	        </tr>
		    <?php	
					}
					}
				?>
		    <tr>
		      <td colspan="2"><strong>รวมรายได้ </strong></td>
		      <td >&nbsp;</td>
		      <td style='text-align:right;'><strong><?php echo number_format($Sum_all,2);?></strong></td>
		      <td style='text-align:right;'><strong><?php echo number_format($Sum_all_compare,2);?></strong></td>
	        </tr>
		    <tr>
		      <td colspan="5"><U>ค่าใช้จ่ายในการดำเนินงาน </U> :</td>
	        </tr>
		    <?php 
				$sql_main_2 = "SELECT * FROM ACC_GL WHERE gl_code LIKE '5%' AND gl_code NOT IN('5000') ORDER BY gl_code ASC ";
					$query_main_2 = $db->query($sql_main_2);
					$Sum_all_2=0;
					while($rec_main_2 = $db->db_fetch_array($query_main_2)){
						$sqlSumTime_2 = "select sum(debit_value) as sum_debit,sum(credit_value) as sum_credit 
						from V_ACC_TRANSEC_DETAIL a
						LEFT JOIN bdg_project b on a.project_id  =b.project_id
						where acc_gl_id = '".$rec_main_2["acc_gl_id"]."' {$filter}";
						$querySumTime_2 = $db->query($sqlSumTime_2);
						$recSumTime_2 = $db->db_fetch_array($querySumTime_2);
						if($recSumTime_2["sum_debit"] > $recSumTime_2["sum_credit"] ){
							$SumM_2 = $recSumTime_2["sum_debit"] - $recSumTime_2["sum_credit"]; 
						}
						if($recSumTime_2["sum_credit"] > $recSumTime_2["sum_debit"] ){
							$SumM_2 = $recSumTime_2["sum_credit"] - $recSumTime_2["sum_debit"];
						}
						$Sum_all_2 += $SumM_2;
						
						//////////////เปรียบเทียบ/////////////////
						
						$sqlSumTime_2 = "select sum(debit_value) as sum_debit,sum(credit_value) as sum_credit 
						from V_ACC_TRANSEC_DETAIL a
						LEFT JOIN bdg_project b on a.project_id  =b.project_id
						where acc_gl_id = '".$rec_main_2["acc_gl_id"]."' {$filter_compare}";
						$querySumTime_2 = $db->query($sqlSumTime_2);
						$recSumTime_2 = $db->db_fetch_array($querySumTime_2);
						if($recSumTime_2["sum_debit"] > $recSumTime_2["sum_credit"] ){
							$SumM_compare_2 = $recSumTime_2["sum_debit"] - $recSumTime_2["sum_credit"]; 
						}
						if($recSumTime_2["sum_credit"] > $recSumTime_2["sum_debit"] ){
							$SumM_compare_2 = $recSumTime_2["sum_credit"] - $recSumTime_2["sum_debit"];
						}
						$Sum_all_compare_2 += $SumM_compare_2;
						
						
						
						if($_POST['s_have_money']==1){
						if( $SumM_2 > 0 || $SumM_compare_2 > 0){   
						
				?>
		    <tr>
		      <td >&nbsp;</td>
		      <td ><?php echo text($rec_main_2['gl_name']);?></td>
		      <td >&nbsp;</td>
		      <td style='text-align:right;'><?php echo number_format($SumM_2,2); $SumM_2 = 0;?></td>
		      <td style='text-align:right;'><?php echo number_format($SumM_compare_2,2); $SumM_compare_2 = 0;?></td>
	        </tr>
		    <?php
						}
					}else{
					?>
		    <tr>
		      <td >&nbsp;</td>
		      <td ><?php echo text($rec_main_2['gl_name']);?></td>
		      <td >&nbsp;</td>
		      <td style='text-align:right;'><?php echo number_format($SumM_2,2); $SumM_2 = 0;?></td>
		      <td style='text-align:right;'><?php echo number_format($SumM_compare_2,2); $SumM_compare_2 = 0;?></td>
	        </tr>
		    <?php	
					}
					}
				?>
		    <tr>
		      <td colspan="2"><strong>รวมค่าใช้จ่าย </strong></td>
		      <td >&nbsp;</td>
		      <td style='text-align:right;'><strong><?php echo number_format($Sum_all_2,2);?></strong></td>
		      <td style='text-align:right;'><strong><?php echo number_format($Sum_all_compare_2,2);?></strong></td>
	        </tr>
		    <tr>
		      <td colspan="2"><strong>กำไรสุทธิ (ขาดทุนสุทธิ) </strong></td>
		      <td >&nbsp;</td>
		      <td style='text-align:right;'><strong>
		        <?php 
							if(($Sum_all-$Sum_all_2) < 0 ){
								 echo "(".number_format( abs($Sum_all-$Sum_all_2),2).")"  ;
							}else{
								 echo number_format($Sum_all-$Sum_all_2,2)  ;
							};
						?>
		      </strong></td>
		      <td style='text-align:right;'><strong>
		        <?php 
							if(($Sum_all_compare-$Sum_all_compare_2) < 0 ){
								 echo "(".number_format( abs($Sum_all_compare-$Sum_all_compare_2),2).")"  ;
							}else{
								 echo number_format($Sum_all_compare-$Sum_all_compare_2,2)  ;
							};
						?>
		      </strong></td>
	        </tr>

</table>
</body>
</html>
<?php  
$content = ob_get_clean();
ob_end_clean();
$pdf->SetHTMLFooter($footer);
$pdf->WriteHTML($stylesheet);
$pdf->WriteHTML($content);

$pdf->Output("report_expenses_compare.pdf","I");
?>