<?php
header ('Content-type: text/html; charset=utf-8');
$path = "../../../";
include($path."include/config_header_top.php");

$url_back = "../receipt_member_gl_disp_add.php";
 
$table = "ACC_TRANSEC";
$table_detail = "ACC_TRANSEC_DETAIl";
$$acc_gl_id = $_POST['acc_gl_id'];
$GL_YEAR = date('Y')+543;
$year_account = $db->get_data_field("SELECT year_account From ACC_YEAR Where active_status = '1'","year_account");
switch($proc){
	case "add" : {
		try{
			$db->db_begin();
			unset($fields_detail);
			unset($fields);
			
			if($tran_code_type == 1 || $tran_code_type ==""){
				$code = gen_max_code($_POST['tran_type'],$year_account,conv_date_db($_POST['tran_date']));
				$tran_code = $code['code'];
				$tran_no = $code['max_id'];
			}else{
				$tran_code = $tran_code;
			}
			//echo $tran_code;
			$fields = array(
				"tran_code" => ctext($tran_code),
				"tran_topic" => ctext($_POST['tran_topic']),
				"tran_date" => ctext(conv_date_db($_POST['tran_date'])),
				"post_status" => ctext($_POST['post_status']),
				"tran_type" => ctext($_POST['tran_type']),
				"tran_no" => ctext($tran_no),
				"tran_sum" => ctext(str_replace(",","",$_POST['SUM_DR'])),
				"year_account" => ctext($year_account),
				"delete_flag" => ctext("0"),
				"create_timestamp" =>$TIMESTAMP,
				"create_by" => ($USER_BY),
				"reference_no" => ctext($_POST['reference_no']),
				"reference_date" => conv_date_db($_POST['reference_date']),
			);	
			if($_POST['member_id'] > 0){
				$fields['member_id_all'] = $_POST['member_id'];
			}
		
				$tran_id=$db->db_insert($table,$fields,'y');
			
			
			if(count($_POST['chk_member_id'])>0){
				foreach ($_POST['chk_member_id'] as  $k => $v ) {
					
					unset($fields_up);
					$fields_up['approve_tran_id'] = $tran_id;

				$db->db_update('M_MEMBER',$fields_up," member_id = '".$v."' ");
					
				}
			}else{
				if($_POST['member_id'] >0){
					unset($fields_up1);
						$fields_up1['approve_tran_id'] = $tran_id;				
			
					$db->db_update('M_MEMBER',$fields_up1," member_id = '".$_POST['member_id']."' ");
				}
			}
		
		
			//SAVE D
			if(count($s_acc_gl_id)>0){
				foreach ($s_acc_gl_id as  $key=>$val) {
					if($val!=""){					
					unset($fields);
					$fields = array(
						"tran_id" => ctext($tran_id),
						"acc_gl_id" => ctext($val),
					);
					if(trim($DR[$key]) > 0){
						$fields['post_type'] = 'D';
						$fields['post_value'] = str_replace(",","",trim($DR[$key]));
					//	echo '<pre>'; print_r($fields); echo '</pre>';
			 
						$db->db_insert($table_detail,$fields);
						}
					if(trim($CR[$key]) > 0 ){
						$fields['post_type'] = 'C';
						$fields['post_value'] = str_replace(",","",trim($CR[$key]));
						//echo '<pre>'; print_r($fields); echo '</pre>';
						$db->db_insert($table_detail,$fields);
						}
							/*echo '<pre>';
							print_r($fields);
							echo '</pre>';*/
					}
		//echo '<pre>';
		//print_r($fields);
		//echo '</pre>';					
				}				
			}
 
 			$fields_log = array(
				"tran_id" => ($tran_id),
				"TRANSEC_DESCRIPTION" => ctext("บันทึก"),
				"create_timestamp" =>$TIMESTAMP,
				"create_by" => ($USER_BY),
				);
			$db->db_insert("ACC_TRANSEC_LOG",$fields_log);
			$text=$save_proc;
			$db->db_commit();
		}catch(Exception $e){
			$db->db_rollback();
			$text=$e->getMessage();
		}
	}break;
			case "IC_ID":

$sql   = "select income_id,income_code,income_name_th from  ACC_INCOME  where  active_status='1'   ";
	$query = $db->query($sql);
		while($rec = $db->db_fetch_array($query)){
			$arr_type_re[$rec['income_id']]  = text($rec['income_code']).' '.text($rec['income_name_th']);
		}
			?> 
            <select id="income_id<?php echo $_POST['id_tb'];?>"  name="income_id[]" class="selectbox form-control" placeholder="เลือกรายการ">
		 <option value="">เลือกรายการ</option>
            <?php
			if(count($arr_type_re)>0){
				foreach($arr_type_re as $key=>$val){
					?>
                    <option value="<?php echo $key;?>"><?php echo $val;?></option>
                    <?php
					}
				}
				?>
                 </select>
                <?php
	break;
	
	case "id_group_name":
				
				?>
				<select name="acc_gl_id[]" class="selectbox form-control" id="s_acc_gl_id_<?php echo $_POST['id_tb'];?>" placeholder="เลือกรายการจากผังบัญชี " >
				<option value=""></option>
				<?php
					foreach($arr_gl_confix as $key => $val){
						$gl= explode(" : ",$val);
				?>
				<option value="<?php echo $key?>" ><?php echo text($val)?></option>
					<?php } ?>
		   </select>
				<?php
	break;

	case "gen_code":  
		 echo FromGetCode($_POST['tran_type'],$year_account,$type,$rec["tran_id"],"",$proc,$code['code']);
	break;
	
	case "getGl":
		$sql="select * from ACC_AUTO_DETAIL where auto_post_id='".$_POST["auto_post_id"]."' order by auto_no ASC ";
		$query=$db->query($sql);
		while($row=$db->db_fetch_array($query)){
			 $json_data[]=array(  
				"acc_gl_id"=>$row['acc_gl_id'],  
				"post_type"=>$row['post_type'],  
			); 
		}
		$json= json_encode($json_data);  
		echo $json;
	break;
	case "id_group_type":
			$sql_gl="SELECT acc_gl_id,gl_code,gl_name FROM ACC_GL ORDER BY gl_code ASC";
			$exc_gl=$db->query($sql_gl);
			while($row_gl=$db->db_fetch_array($exc_gl)){
			$arr_gl[$row_gl['acc_gl_id']]=text($row_gl['gl_code']." : ".$row_gl['gl_name']);
			}//while
			?>
            <select name="s_acc_gl_id[]" class="selectbox_gl form-control" id="s_acc_gl_id_<?php echo "J_".$_POST['id_tb'];?>" placeholder="เลือกรายการจากผังบัญชี "  style="width:250px">
                            <option value=""></option>
                            <?php
                            foreach($arr_gl as $key => $val){
                                $gl= explode(" : ",$val);
                            ?>
                               <option value="<?php echo $key?>" label = '<?php echo $gl[0]?>' ><?php echo $val?></option>
                            <?php } ?>
                       </select>
            <?php
	break;
	 case"get_gl_name":
 			$sql_gl="SELECT * FROM ACC_GL where acc_gl_id='".$acc_gl_id."'";
			$exc_gl=$db->query($sql_gl);
			$row_gl=$db->db_fetch_array($exc_gl);
			echo text($row_gl['gl_name']);
 break;
 case "Post_All" : {
		try{
			$db->db_begin();
			unset($fields_detail);
			unset($fields);
			if($_POST['chk_rec_id'] > 0){
									
				$fields = array(
					"tran_code" => ctext($tran_code),
					"tran_type" => "RV",
					"tran_no" => ctext($tran_no),	
					
				);	
			//	$tran_id=$db->db_insert($table,$fields,'y');
		
		
			
			}
		exit;	
			$text=$save_proc;
			$db->db_commit();
		}catch(Exception $e){
			$db->db_rollback();
			$text=$e->getMessage();
		}
	}break;
}
if($proc=='add' || $proc=='Post_All' || $proc=='edit' || $proc=='delete' ){
?>

<meta charset="utf-8"  />
<form name='form_back' method="post" action="<?php echo $url_back; ?>">
	<input type="hidden" id="proc" name="proc" value="<?php echo $proc; ?>" />
    <input type="hidden" id="menu_id" name="menu_id" value="<?php echo $menu_id; ?>" />
    <input type="hidden" id="menu_sub_id" name="menu_sub_id" value="<?php echo $menu_sub_id ?>" />
	<input type="hidden" id="rec_id" name="rec_id" value="<?php echo $_POST["rec_id"]; ?>" />
	<input name="s_pay_no" type="hidden" id="s_pay_no" value="<?php echo $_POST['s_pay_no'];?>">    
	<input name="s_pay_date" type="hidden" id="s_pay_date" value="<?php echo $_POST['s_pay_date'];?>">    
	<input name="e_pay_date" type="hidden" id="e_pay_date" value="<?php echo $_POST['e_pay_date'];?>">
	<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
	<input type="hidden" id="hide_show" name="hide_show"  value="<?php echo $_POST['hide_show'];?>">
</form>
<script>
	alert('<?php echo $text; ?>');
	form_back.submit();
</script>
<?php }?>