<?php
DEFINE ('PRIVATE_KEY', 'H7krOjw6pA');
$YEAR_PRESENT = (date("Y")+543);//ปีปัจจุบัน
$YEAR_BUDGET = (date('m') < 10)?date("Y")+543:date("Y")+544;//ปีงบประมาณ
$A_CONFIG_YEAR = array();
for($YY=$YEAR_PRESENT;$YY>=2552;$YY--){//select ปี 
	$A_CONFIG_YEAR[$YY] = $YY;
}//for
//echo " YEAR_PRESENT : $YEAR_PRESENT<br>";
//exit;

$ARR_AREA = array("S"=>"เล็ก", "M"=>"กลาง", "L"=>"ใหญ่");

$month_full = array("10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม","1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน","7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน");
$mont_th_short = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");

$ARR_DAY_TH = array("1"=>"จันทร์", "2"=>"อังคาร", "3"=>"พุธ", "4"=>"พฤหัสบดี", "5"=>"ศุกร์", "6"=>"เสาร์", "7"=>"อาทิตย์");

function TA_INSERT($tb_name, $fields, $out_id="", $filter_max=""){		
		
		$fieldlist = '';
		$valuelist = '';
		
		if(trim($out_id) && $out_id!="y") {
				
				
				$sql_max ="SELECT  MAX(".$out_id.") AS max_id  FROM  ".$tb_name."  WHERE  1=1  $filter_max  ";
				$query_max = db::query($sql_max);
				$rec_max =  db::fetch_array($query_max);	
				$next_id = $rec_max["max_id"]+1;
				$fieldlist .= "$out_id, ";
				$valuelist .= "'".$next_id."', ";
		}
		
		while(list($key, $val) = each($fields)){
			$fieldlist .= "$key, ";
			switch (strtolower($val)) {
				case 'null':break;
				case '$set$':
					$f = "field_$key";
					$val = "'".($$f?implode(',',$$f):'')."'";
				break;
				default:
					if(is_numeric($val)) {
						$val = "'".$val."'";
					} else {
						$val = "'".htmlspecialchars(stripslashes($val), ENT_QUOTES)."'";
					}
				break;
	        }//swich
			if(empty($funcs[$key])){
				if(trim(str_replace("'",'',$val)) == ''){					
					$val = 'NULL';
				}//if		
				$valuelist .= "$val, ";
			}else{
				if(trim(str_replace("'",'',$val)) == ''){					
					$val = 'NULL';
				}//if
				$valuelist .= "$funcs[$key]($val), ";
			}//if
		}//while
		$fieldlist = preg_replace('/, $/', '', $fieldlist);
		$valuelist = preg_replace('/, $/', '', $valuelist);
		$sql = "INSERT INTO $tb_name($fieldlist) VALUES ($valuelist)"; 
 		$query_result = db::query($sql);
		
		if($out_id=='y' ){ 
			    $sql_id ="; SELECT SCOPE_IDENTITY() AS SCOPE_IDENTITY;";
				$query_id = db::query($sql_id);
				$rs =  db::fetch_array($query_id);
				 return $rs['SCOPE_IDENTITY'];
		}//if 
 
	}//function
	
	function TA_UPDATE($table , $fields,$cond){
		$valuelist = '';
		while(list($key, $val) = each($fields)){
			switch (strtolower($val)) {
				case 'null':
					break;
				case '$set$':
					$f = "field_$key";
					$val = "'".($$f?implode(',',$$f):'')."'";
					break;
				default:
					//$val = "'$val'";
					if(is_numeric($val)) {
						$val = "'".$val."'";
					} else {
						$val = "'".htmlspecialchars(stripslashes($val), ENT_QUOTES)."'";
					}
					break;
			}//swichk
			
			if(trim(str_replace("'",'',$val)) == ''){					
				$val = 'NULL';
			}//if
			
			$valuelist .= "$key = $val, ";
			
			if (!empty($funcs)){
				if(!empty($funcs[$key])){
					
					if(trim(str_replace("'",'',$val)) == ''){					
						$val = 'NULL';
					}//if
						
					$valuelist .= "$key = $funcs[$key]($val), ";
				}//if
			}//if
		}//while
		
		$valuelist = preg_replace('/, $/', '', $valuelist);
   
		$sql = "UPDATE $table SET $valuelist  where  1=1  and ".$cond; 
		// if($table=="request") {
		 //	echo $sql;
		 //	exit;
		// }
		db::query($sql);
	}//function
	
	function db_delete($table , $cond){	
		$sql = "DELETE FROM ".$table;		
		if( $cond)	{
			$sql .= " where  1=1 and".$cond;
		}//if
		db::query($sql);
	}//function
	

	function get_data_rec($sql){
		$query = db::query($sql);
		return $rec = db::fetch_array($query);
	}//function
	
function get_last_date($month,$year){
	if($month=="1" OR $month=="3" OR $month=="5" OR $month=="7" OR $month=="8" OR $month=="10" OR $month=="12" ){
				$l_date = "31";
			}elseif($month=="4" OR $month=="6" OR $month=="9" OR $month=="11"){
				$l_date =  "30";
			}else{
				if($year%4==0){
					$l_date = "29";
				}else{
					$l_date = "28";
				}
			}
	return $l_date;
}

function diffDateAll($s_date, $e_date, $returnType = "1"){
		$s = explode("/",$s_date);
	$s_date = number_format($s[0],0,'.','');
	$s_month = number_format($s[1],0,'.','');
	$s_year = $s[2]-543;
	$e = explode("/",$e_date);
	$e_date = number_format($e[0],0,'.','');
	$e_month = number_format($e[1],0,'.','');
	$e_year = $e[2]-543;
	$num_day1=0;
	$num_day2=0;	
	
if($s_month == $e_month AND $s_year == $e_year){
//$num_day1 = $e_date-$s_date+1;	
}else{
if($s_date != "1"){

	$num_day_s = get_last_date($s_month,$s_year);
	$num_day1 = $num_day_s-$s_date+1; //หาเศษเดือนแรก
	$s_month++;
	if($s_month > 12){
		$s_month=1;
		$s_year++;
	}
	$s_date = 1;
}

$num_day_e = get_last_date($e_month,$e_year);
if($e_date != $num_day_e){
	$num_day2 = $e_date;
	$e_month--;
	if($e_month < 1){
		$e_month=12;
		$e_year--;
	}
	$e_date = get_last_date($e_month,$e_year);
}	
}

 
 $day_tmp = $num_day1+$num_day2;	
 if($day_tmp >= 30){
	$day_tmp = $day_tmp-30;
	$month_tmp = 1;
 }
	
	
	
	
	
	
		$start = $s_date."/".$s_month."/".($s_year);
		$end = $e_date."/".$e_month."/".($e_year);	
	
	
	
	
	
	$s = explode("/",$start);
	$s_date = $s[0];
	$s_month = $s[1];
	$s_year = $s[2];
	$e = explode("/",$end);
	$e_date = $e[0];
	$e_month = $e[1];
	$e_year = $e[2];
	
	$i = 1;
	while($s_year < $e_year OR ($s_year == $e_year AND $s_month < $e_month)){
		$b_date = $s_date; //begin
		$b_month = $s_month;
		$b_year = $s_year;
	
		$s_month++;
		if($s_month > 12){
			$s_month = 1;
			$s_year++;
		}
	
		$l_date = $s_date-1; //last
		if($l_date < 1){
			//$l_date = date("t", mktime(0, 0, 0, $b_month, 1, $b_year));
			
			//last_date
			$b_month = number_format($b_month,0);
			if($b_month=="1" OR $b_month=="3" OR $b_month=="5" OR $b_month=="7" OR $b_month=="8" OR $b_month=="10" OR $b_month=="12"){
				$l_date = "31";
			}elseif($b_month=="4" OR $b_month=="6" OR $b_month=="9" OR $b_month=="11"){
				$l_date =  "30";
			}else{
				if($b_year%4==3){
					$l_date = "29";
				}else{
					$l_date = "28";
				}
			}
			//$l_date = $this->last_date($b_month,$b_year);
			$l_month = $b_month;
			$l_year = $b_year;
		}else{
			$l_month = $s_month;
			$l_year = $s_year;
		}
		//echo "#".$i." => ".sprintf("%02d",$b_date)."/".sprintf("%02d",$b_month)."/".($b_year-543)." - ".sprintf("%02d",$l_date)."/".sprintf("%02d",$l_month)."/".($l_year-543);
		//echo "<hr>";
		
		$i++;
	}
	$i--;
	$year = floor($i/12);
	$month = $i%12;
	
	
		$b_date = $s_date;
		$b_month = sprintf("%02d",$s_month);
		$b_year = $s_year;
		// หา date diff ระหว่าง e_date กับ b_date
		//ถ้า มากกว่า 0 gen งวดสุดท้าย
	if($l_date == $e_date AND sprintf("%02d",$l_month) == $e_month AND $l_year == $e_year){
		$date = 0;
	}else{
		$i++;
		//หาวันด้วยนะ
		//echo "#".$i." => ".$b_date."/".$b_month."/".$b_year." - ".$e_date."/".$e_month."/".$e_year;
		$fractions_date = $e_date - $b_date+1;								////  หาวันคงเหลือ
		if($fractions_date < 0){	
		//// เงื่อนไขถ้าวันติดลบ
			//last_date
			$b_month = number_format($b_month,0);
			if($b_month=="1" OR $b_month=="3" OR $b_month=="5" OR $b_month=="7" OR $b_month=="8" OR $b_month=="10" OR $b_month=="12"){
				$mx_day_of_month = "31";
			}elseif($b_month=="4" OR $b_month=="6" OR $b_month=="9" OR $b_month=="11"){
				$mx_day_of_month =  "30";
			}else{
				if($b_year%4==3){
					$mx_day_of_month = "29";
				}else{
					$mx_day_of_month = "28";
				}
			}
		
			//$mx_day_of_month = $this->last_date($b_month,$b_year);
			$fractions_date2 = $fractions_date + $mx_day_of_month;				////    fractions แปลว่า เศษส่วน   เศษของวัน
		}else{
			$fractions_date2 = $fractions_date;
		}
		if($fractions_date2 > 26){											//// ถ้าครบหนึ่งเดือน  แต่อยู่ในคนละเดือน ให้+เดือนเข้าไป
			$month++;
			$date = 0;
			if($month == 12){
				$year++;
				$month = 0;
			}
		}else{
			$date = $fractions_date2;
		}		
	}
	$date += $day_tmp;
	$month += $month_tmp;
//	echo $year.",".$month.",".$date;

		if($returnType == "2")
		{
			$a_data="";
			if($year != 0)$a_data .= $year." ปี ";
			if($month != 0) $a_data .=$month." เดือน ";
			if($date != 0) $a_data .=$date." วัน";
		}
		else
		{
			$a_data['day'] = $date;
			$a_data['month'] = $month;
			$a_data['year'] = $year;
		} 
		return $a_data;
	}
	
function convert_qoute_to_db($str) {  
	return htmlspecialchars(stripslashes($str), ENT_QUOTES);  // แปลง ' ให้เป็น &#039;
}

function convert_qoute_to_show($str_in, $quote="") {
    
   $str_in = str_replace("&amp;",'&',$str_in);
//   $str_in = str_replace("&",'&',$str_in);
   
   if($quote) {
    $str_in = str_replace('"',"&quot;",$str_in);  
   }
   $str_in = str_replace("&#039;","'",$str_in);   
   
   $str_in = str_replace("&lt;",'<',$str_in);
   $str_in = str_replace("&gt;",'>',$str_in);

   $str_in = str_replace("=&quot;",'="',$str_in); 
   $str_in = str_replace("&quot;>",'">',$str_in); 
    
   return $str_in;
}	

function ddw_list_selected($sql_str,$f_name,$f_value,$select_value="")
{  //global db;
	// echo "sql_str : $sql_str<br>";
   $exec = db::query($sql_str);
   while ($rec =  db::fetch_array($exec))
   {
	  if ($rec[$f_value] == $select_value)
		   $str_selected = "selected";
	  else
		   $str_selected = ""; 
	  echo "<option value='".$rec[$f_value]."' ".$str_selected.">".convert_qoute_to_show($rec[$f_name],0)."</option>";
   }
}

//แปลงตัวเลขเป็นเลขไทย
function toThaiNumber($number){
	$numthai = array("๑","๒","๓","๔","๕","๖","๗","๘","๙","๐");
	$numarabic = array("1","2","3","4","5","6","7","8","9","0");
	$str = str_replace($numarabic, $numthai, $number);
	return $str;
}//function

function convert_date_db_to_show($date_in, $out_type="", $empty="") {   // default show : dd/mm/yyyy (thai)
		global $month_full;
		
		if( strlen($date_in) >= 8 &&  preg_match( '/^[0-9\-]+$/i', $date_in)  ) {  // \/
				$arr_date = explode("-", $date_in);   
					// print_r($arr_date);  
					// exit; 
					//	$date = "1998-08-14";
					//	$newdate = strtotime ( '-3 year' , strtotime ( $date ) ) ;
					//	$newdate = date ( 'Y-m-j' , $newdate ); 
				  //	 date(strtotime(date("Y").' - 100 year'));
				  	$hundredYearsAgo = date("Y") - 100;
										
				if($arr_date[0]*1 >= $hundredYearsAgo) {  //  If  this Year is 2018  then  hundredYearsAgo  = 1918
						// ถ้ากรอกวันที่มา ไม่อดีตเกิน 100 ปี จึงจะเชื่อถือได้  ไม่เกิดจากการ default ด้วย DB
					 if($out_type=="FM") { // Full month name  
					 
						$date_out = ($arr_date[2]*1)." ".$month_full[$arr_date[1]*1]." ".($arr_date[0]*1 +543);
					 } else if($out_type=="DD") { //  date  num 
						$date_out = $arr_date[2];
					 } else if($out_type=="D") { //  date*1  num
						$date_out = ($arr_date[2]*1);
					 } else if($out_type=="MNAME") { //  month name   
						$date_out = $month_full[$arr_date[1]*1];
					 } else if($out_type=="MS") { //  short month name   
						$date_out = $mont_th_short[$arr_date[1]*1];
					 } else if($out_type=="Y") { // Thai Year
						$date_out = ($arr_date[0]*1 +543);
					 } else if($out_type=="YC") { // Eng Year
						$date_out = ($arr_date[0]*1);
					 } else {
							// default show : dd/mm/yyyy (thai)
						$date_out =  $arr_date[2]."/".$arr_date[1]."/".($arr_date[0]*1 +543);
					 } 
				} else {   
					$date_out = "";  // ถ้าวันที่ เป็นอดีต เกิน 100 ปีให้แสดงค่าว่าง  ( เพราะเราไม่ได้ทำระบบประวัติศาสตร์ )  
				}
		 } else { 
		 		$date_out = $empty; // can set NULL
		 }
	 return $date_out; 
}

/////  P Joke Functions /////
function print_pre_joke($data)
{
  echo '<pre>';
  print_r($data);
  echo '</pre>';
}

function randomPassword($var_maxlength = "8")
{
  $var_password = "";
  $var_possible = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdfghijklmnopqrstuvwxyz";
  while(($i < $var_maxlength)&&(mb_strlen($var_possible) > 0))
  {
    $i++;
    $var_character = mb_substr($var_possible, mt_rand(0, mb_strlen($var_possible)-1), 1);
    $var_possible = preg_replace("/$var_character/", "", $var_possible);
    $var_password .= $var_character;
  }
  return $var_password;
}
 
function encryptPassword($str_password, $str_key)
{
  $str_source = md5($str_password);
  $str_pass = PRIVATE_KEY . $str_source . $str_key;
  $str_pass = hash('sha256', $str_pass);
  return $str_pass;
}

function encrypt($data, $key)
{
  	
  if(is_array($data))
    $array=$data;
  else
    parse_str($data, $array);
  $string = json_encode($array);
  $result = '';
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)+ord($keychar));
    $result.=$char;
  }
  return urlencode(base64_encode($result));
}

function decrypt($string, $key)
{
  $result = '';
  $string = base64_decode(str_replace(' ', '+', urldecode($string)));
  for($i=0; $i<strlen($string); $i++) {
    $char = substr($string, $i, 1);
    $keychar = substr($key, ($i % strlen($key))-1, 1);
    $char = chr(ord($char)-ord($keychar));
    $result.=$char;
  }
  return json_decode($result, true);
}
 

function redirect_joke($s_url)
{
	  if (!headers_sent())    //If headers not sent yet... then do php redirect
	  {
		header('Location: '.$s_url);
		exit;
	  }
	  else //If headers are sent... do java redirect... if java disabled, do html redirect.
	  {
		echo '<script type="text/javascript">';
		echo 'window.location.href="'.$s_url.'";';
		echo '</script>';
		echo '<noscript>';
		echo '<meta http-equiv="refresh" content="0;url='.$s_url.'" />';
		echo '</noscript>';
		exit;
	  }
}
/////  end of P Joke Functions /////

//echo " TYEAR_PRESENT : $TYEAR_PRESENT<br>";
//exit;

function get_province($province_code)
{
	if ($province_code != ''){
		$sql_check_province = db::query("SELECT PROVINCE_NAME FROM G_PROVINCE WHERE PROVINCE_CODE = '".$province_code."'");
		$province = db::fetch_array($sql_check_province);
		if($province_code != '10'){
			$str = 'จังหวัด';
		}
		
		echo $str.$province["PROVINCE_NAME"];
	}
}
function get_amphur($amphur_code)
{
	if ($amphur_code != ''){
		$sub_province = substr($amphur_code,0,2);
		$sub_amphur = substr($amphur_code,2,2);
		$sql_check_amphur = db::query("SELECT AMPHUR_NAME FROM G_AMPHUR WHERE PROVINCE_CODE = '".$sub_province."' AND AMPHUR_CODE = '".$sub_amphur."'");
		$amphur = db::fetch_array($sql_check_amphur);
		if($sub_province == '10'){
			$str = ' เขต';
		}
		else{$str = ' อำเภอ';}
		
		echo $str.$amphur["AMPHUR_NAME"];
	}
}
function get_tambon($tambon_code)
{
	if ($tambon_code != ''){
		$sub_province = substr($tambon_code,0,2);
		$sub_amphur = substr($tambon_code,2,2);
		$sub_tambon = substr($tambon_code,4,2);
		$sql_check_tambon = db::query("SELECT TAMBON_NAME FROM G_TAMBON WHERE PROVINCE_CODE = '".$sub_province."' AND AMPHUR_CODE = '".$sub_amphur."' AND TAMBON_CODE = '".$sub_tambon."'");
		$tambon = db::fetch_array($sql_check_tambon);
		if($sub_province == '10'){
			$str = ' แขวง';
		}
		else{$str = ' ตำบล';}
		
		echo $str.$tambon["TAMBON_NAME"];
	}
}
function get_prefix_n($prefix_name){
	$sql_check_prefix_name = db::query("SELECT T_NAME_TH FROM M_TITLE_NAME WHERE T_ID = '".$prefix_name."'");
	$prefix_n = db::fetch_array($sql_check_prefix_name);
		
	echo $prefix_n["T_NAME_TH"];
}
// ประเภทวัสดุ ภย.
function matter_type($type_m){
	$sql_matter_type = db::query("SELECT MATTER_THAI_NAME FROM M_TYPE_MATTER WHERE TM_ID = '".$type_m."' AND MATTER_ID = '1'");
	$matter_t = db::fetch_array($sql_matter_type);
	
	echo $matter_t["MATTER_THAI_NAME"];
}
// ประเภทสื่อโฆษณา ภย.
function media_type($type_me){
	$sql_media_type = db::query("SELECT MEDIA_TYPE FROM M_MEDIA_TYPE WHERE MT_ID = '".$type_me."' AND V_TYPE = '1'");
	$media_t = db::fetch_array($sql_media_type);
	
	echo $media_t["MEDIA_TYPE"];
}
//เรตติ้ง ex. 15+, 18+
function rate_movie($rate_m){
	$sql_rate_movie = db::query("SELECT MOVIE_RATING FROM M_MOVIE_RATE WHERE MR_ID = '".$rate_m."'");
	$rate_m = db::fetch_array($sql_rate_movie);
	
	//echo $rate_m["MOVIE_RATING"];
	echo toThaiNumber($rate_m["MOVIE_RATING"]); 
}

//  backup ตัวแปร ก่อน ถูกทับ ใน loop  auto  update ไม่มาอุทธรณ์ ภายใน 15 วัน 
$BACKUP_VARS["W"] = $_REQUEST["W"];
$BACKUP_VARS["WFR_ID"] = $WF["WFR_ID"];
$BACKUP_VARS["R_MOVIE"] = $WF["R_MOVIE"];
$BACKUP_VARS["WFR"] = $WFR;

		$sql_w = " SELECT  *  FROM  WF_MAIN  WHERE  WF_MAIN_ID = '".$_REQUEST["W"]."'   ";
		//echo "$sql_w<br>";
		$query_w = db::query($sql_w);
		$rec_w = @db::fetch_array($query_w);
		
		//echo "<!--";
		//print_r($rec_w);
		//echo "-->";
		$W = $_REQUEST["W"];
		
		if($rec_w["WF_MAIN_SHORTNAME"]=="WFR_MOVIE_APPROVE_REQUEST1" && $W > 0 ) {   // ถ้า ส่ง W ของ ภย. 1 มา ค่อย check  
		
				$sql_list = " SELECT
									WFR_MOVIE_APPROVE_REQUEST1.WFR_ID,
									WFR_MOVIE_APPROVE_REQUEST1.WF_DET_STEP,
									WFR_MOVIE_APPROVE_REQUEST1.WF_DET_NEXT,
									WFR_MOVIE_APPROVE_REQUEST1.R_MOVIE,
									WFR_MOVIE_APPROVE_REQUEST1.CON_RES,
									WFR_MOVIE_APPROVE_REQUEST1.VERIFY_DATE,
									CONVERT(char(10), GETDATE(),126) AS TODAY_DATE,
									DATEADD(dy, 15, VERIFY_DATE) AS  APPEAL_TIMEOUT_DATE,
									WFR_MOVIE_APPROVE_REQUEST1.APPEAL_DATE,
									WFR_MOVIE_APPROVE_REQUEST1.APPEAL_STATUS,
									WFR_MOVIE_APPROVE_REQUEST1.TO_WAREHOUSE
								 FROM
									WFR_MOVIE_APPROVE_REQUEST1
								 WHERE CON_RES > '1'  AND  YEAR(DATE_PAID) > 1900  AND ( APPEAL_STATUS IS NULL OR APPEAL_STATUS = '' ) AND CONVERT(char(10), GETDATE(),126) > DATEADD(dy, 15, VERIFY_DATE) ";
				
				$query_list = db::query($sql_list);
				while($rec_list = @db::fetch_array($query_list)) {
					  
					
					$WF["WFR_ID"]=$rec_list["WFR_ID"];
					$WFR = $rec_list["WFR_ID"];
					$_POST["APPEAL_STATUS"]=2;
					$WF["R_MOVIE"]=$rec_list["R_MOVIE"];  
					
					$sql_ta = " SELECT  *  FROM  M_REQUEST_MEDIA  WHERE  REQUEST_TYPR = '$W' AND R_MOVIE = '".$rec_list["R_MOVIE"]."' ";
					//echo "$sql_ta<br>";
					//echo "<!--$sql_ta-->";
					$query_ta = db::query($sql_ta);
					$rec_ta = @db::fetch_array($query_ta);   
					
					// หาค่า  $rec_ta["MEDIA_TYPE"] ส่งไปยัง 	movie_uthon_warehouse_1.php	เพราะบางครั้ง ใน 	movie_uthon_warehouse_1.php มันดึง ภย. / ภยส. ไม่ออก
					
					@require("../save/movie_uthon_warehouse_1.php");
					
					$arr_fields = array();
					
					if($rec_list["CON_RES"]=='2') { 
						$arr_fields["LICENSE_NUMBER"] = $update_wf["LICENSE_NUMBER"];
					}
					
					$arr_fields["APPEAL_STATUS"]=2; // รอยื่นเกิน 15 วัน  ถือว่าไม่ยื่นอุทธรณ์ 
					$arr_fields["APPEAL_DATE"]=$rec_list["TODAY_DATE"];
					$arr_fields["WF_DET_STEP"]=75; //   ไปขั้นตอน ดำเนินการเสร็จสิ้น
					$arr_fields["WF_DET_NEXT"]=0;
					
					$cond_custom = array( "WFR_ID" => $rec_list["WFR_ID"]);
					
					db::db_update("WFR_MOVIE_APPROVE_REQUEST1", $arr_fields, $cond_custom);
					
					//@require_once("../save/movie_uthon_warehouse_1.php");
				}
		} // end if ภย. 1
		
		if($rec_w["WF_MAIN_SHORTNAME"]=="WFR_MOVIE_APPROVE_REQUEST3" && $W > 0 ) {   // ถ้า ส่ง W ของ ภย. 3 มา ค่อย check  
				$sql_list = " SELECT
									WFR_MOVIE_APPROVE_REQUEST3.WFR_ID,
									WFR_MOVIE_APPROVE_REQUEST3.WF_DET_STEP,
									WFR_MOVIE_APPROVE_REQUEST3.WF_DET_NEXT,
									WFR_MOVIE_APPROVE_REQUEST3.R_MOVIE_NAME,
									WFR_MOVIE_APPROVE_REQUEST3.R_MOVIE,
									WFR_MOVIE_APPROVE_REQUEST3.CON_RES,
									WFR_MOVIE_APPROVE_REQUEST3.VERIFY_DATE,
									CONVERT(char(10), GETDATE(),126) AS TODAY_DATE,
									DATEADD(dy, 15, VERIFY_DATE) AS  APPEAL_TIMEOUT_DATE,
									WFR_MOVIE_APPROVE_REQUEST3.APPEAL_DATE,
									WFR_MOVIE_APPROVE_REQUEST3.APPEAL_STATUS,
									WFR_MOVIE_APPROVE_REQUEST3.TO_WAREHOUSE
								 FROM
									WFR_MOVIE_APPROVE_REQUEST3
								 WHERE CON_RES > '1' AND  YEAR(DATE_PAID) > 1900  AND ( APPEAL_STATUS IS NULL OR APPEAL_STATUS = '' ) AND CONVERT(char(10), GETDATE(),126) > DATEADD(dy, 15, VERIFY_DATE) ";
				
				$query_list = db::query($sql_list);
				while($rec_list = @db::fetch_array($query_list)) {
					  					
					$WF["WFR_ID"]=$rec_list["WFR_ID"];
					$WFR = $rec_list["WFR_ID"];
					$_POST["APPEAL_STATUS"]=2;
					$WF["R_MOVIE"]=$rec_list["R_MOVIE"];  
																			
					@require("../save/movie_uthon_warehouse_3.php");
					
					$arr_fields = array();
					/*   ดูจากโค้ดเก่า  ที่น้องๆเขียน  ภย. 3  ไม่มี การอนุญาต แบบไม่ตรงเรท   และไม่มีห้ามเผยแพร่  เพราะส่งออกนอก
					if($rec_list["CON_RES"]=='2') { 
						$arr_fields["LICENSE_NUMBER"] = $update_wf["LICENSE_NUMBER"];
					}
					*/
					
					$arr_fields["APPEAL_STATUS"]=2; // รอยื่นเกิน 15 วัน  ถือว่าไม่ยื่นอุทธรณ์ 
					$arr_fields["APPEAL_DATE"]=$rec_list["TODAY_DATE"];
					$arr_fields["WF_DET_STEP"]=74; //   ไปขั้นตอน ดำเนินการเสร็จสิ้น
					$arr_fields["WF_DET_NEXT"]=0;
					
					$cond_custom = array( "WFR_ID" => $rec_list["WFR_ID"]);
					
					db::db_update("WFR_MOVIE_APPROVE_REQUEST3", $arr_fields, $cond_custom);
										 
				}
		} // end if ภย. 3
		
		if($rec_w["WF_MAIN_SHORTNAME"]=="WFR_MOVIE_APPROVE_REQUEST2" && $W > 0 ) {   // ถ้า ส่ง W ของ ภย.2 มา ค่อย check  
		
				$sql_list = " SELECT
									WFR_MOVIE_APPROVE_REQUEST2.WFR_ID,
									WFR_MOVIE_APPROVE_REQUEST2.WF_DET_STEP,
									WFR_MOVIE_APPROVE_REQUEST2.WF_DET_NEXT,
									WFR_MOVIE_APPROVE_REQUEST2.MEDIA_TYPE,
									WFR_MOVIE_APPROVE_REQUEST2.CON_RES,
									WFR_MOVIE_APPROVE_REQUEST2.VERIFY_DATE,
									CONVERT(char(10), GETDATE(),126) AS TODAY_DATE,
									DATEADD(dy, 15, VERIFY_DATE) AS  APPEAL_TIMEOUT_DATE,
									WFR_MOVIE_APPROVE_REQUEST2.APPEAL_DATE,
									WFR_MOVIE_APPROVE_REQUEST2.APPEAL_STATUS,
									WFR_MOVIE_APPROVE_REQUEST2.TO_WAREHOUSE
								 FROM
									WFR_MOVIE_APPROVE_REQUEST2
								 WHERE CON_RES > '1' AND  YEAR(DATE_PAID) > 1900  AND ( APPEAL_STATUS IS NULL OR APPEAL_STATUS = '' ) AND CONVERT(char(10), GETDATE(),126) > DATEADD(dy, 15, VERIFY_DATE) ";
				
				$query_list = db::query($sql_list);
				while($rec_list = @db::fetch_array($query_list)) {
					  
					
					$WF["WFR_ID"]=$rec_list["WFR_ID"];
					$WFR = $rec_list["WFR_ID"];
					$_POST["APPEAL_STATUS"]=2;					
					 
					@require("../save/movie_uthon_warehouse_2.php");
					
					$arr_fields = array();
					 
					
					$arr_fields["APPEAL_STATUS"]=2; // รอยื่นเกิน 15 วัน  ถือว่าไม่ยื่นอุทธรณ์ 
					$arr_fields["APPEAL_DATE"]=$rec_list["TODAY_DATE"];
					$arr_fields["WF_DET_STEP"]=78; //   ไปขั้นตอน ดำเนินการเสร็จสิ้น
					$arr_fields["WF_DET_NEXT"]=0;
					
					$cond_custom = array( "WFR_ID" => $rec_list["WFR_ID"]);
					
					db::db_update("WFR_MOVIE_APPROVE_REQUEST2", $arr_fields, $cond_custom);
					 
				}
		} // end if ภย. 2
		
		 if($rec_w["WF_MAIN_SHORTNAME"]=="WFR_VIDEO_APPROVE_REQUEST1" && $W > 0 ) {   // ถ้า ส่ง W ของ วท 1 มา ค่อย check  
		
				$sql_list = " SELECT
								WFR_VIDEO_APPROVE_REQUEST1.WFR_ID,
								WFR_VIDEO_APPROVE_REQUEST1.WF_DET_STEP,
								WFR_VIDEO_APPROVE_REQUEST1.WF_DET_NEXT,
								WFR_VIDEO_APPROVE_REQUEST1.R_VIDEO_NAME,
								WFR_VIDEO_APPROVE_REQUEST1.CON_RESOLUTION,
								WFR_VIDEO_APPROVE_REQUEST1.VERIFY_DATE,
								CONVERT(char(10), GETDATE(),126),
								DATEADD(dy, 15, VERIFY_DATE) AS  APPEAL_TIMEOUT_DATE,
								WFR_VIDEO_APPROVE_REQUEST1.APPEAL_DATE,
								WFR_VIDEO_APPROVE_REQUEST1.APPEAL_STATUS,
								WFR_VIDEO_APPROVE_REQUEST1.TO_WAREHOUSE
							 FROM
								WFR_VIDEO_APPROVE_REQUEST1
							 WHERE CON_RESOLUTION > '1'  AND  YEAR(DATE_PAID) > 1900  AND ( APPEAL_STATUS IS NULL OR APPEAL_STATUS = '' )  AND CONVERT(char(10), GETDATE(),126) > DATEADD(dy, 15, VERIFY_DATE) ";
				
				$query_list = db::query($sql_list);
				while($rec_list = @db::fetch_array($query_list)) {
					  
					
					$WF["WFR_ID"]=$rec_list["WFR_ID"];
					$WFR = $rec_list["WFR_ID"];
					$_POST["APPEAL_STATUS"]=2; 
					
					//movie_uthon_warehouse_1.php (เดิม)
					//  video_confirm_warehouse_1
					//video_record_warehouse_1.php					
					@require("../save/video_uthon_warehouse_1.php");  ///// เลย์ไปหาชื่อไฟล์  โยนค่า  เข้า warehouse ของ วท.1 (ใส่แล้ว) 
					
					$arr_fields = array(); 
					$arr_fields["APPEAL_STATUS"]=2; // รอยื่นเกิน 15 วัน  ถือว่าไม่ยื่นอุทธรณ์ 
					$arr_fields["APPEAL_DATE"]=$rec_list["TODAY_DATE"];
					$arr_fields["WF_DET_STEP"]= 80;      	//75;        //   ไปขั้นตอน ดำเนินการเสร็จสิ้น       /////   เลย์ไปหา id ขั้นตอน ดำเนินการเสร็จสิ้น ของ วท.1 มาใส่      (ใส่แล้ว)
					$arr_fields["WF_DET_NEXT"]=0;
					 
					$cond_custom = array( "WFR_ID" => $rec_list["WFR_ID"]);
					
					db::db_update("WFR_VIDEO_APPROVE_REQUEST1", $arr_fields, $cond_custom);
					 
				}
		} // end if วท.1
		
		
		 if($rec_w["WF_MAIN_SHORTNAME"]=="WFR_VIDEO_APPROVE_REQUEST3" && $W > 0 ) {   // ถ้า ส่ง W ของ วท 3 มา ค่อย check  
		
				$sql_list = " SELECT
								WFR_VIDEO_APPROVE_REQUEST3.WFR_ID,
								WFR_VIDEO_APPROVE_REQUEST3.WF_DET_STEP,
								WFR_VIDEO_APPROVE_REQUEST3.WF_DET_NEXT,
								WFR_VIDEO_APPROVE_REQUEST3.R_VIDEO_NAME,
								WFR_VIDEO_APPROVE_REQUEST3.CON_RESOLUTION,
								WFR_VIDEO_APPROVE_REQUEST3.VERIFY_DATE,
								CONVERT(char(10), GETDATE(),126),
								DATEADD(dy, 15, VERIFY_DATE) AS  APPEAL_TIMEOUT_DATE,
								WFR_VIDEO_APPROVE_REQUEST3.APPEAL_DATE,
								WFR_VIDEO_APPROVE_REQUEST3.APPEAL_STATUS,
								WFR_VIDEO_APPROVE_REQUEST3.TO_WAREHOUSE
							 FROM
								WFR_VIDEO_APPROVE_REQUEST3
							 WHERE CON_RESOLUTION > '1'  AND  YEAR(DATE_PAID) > 1900  AND ( APPEAL_STATUS IS NULL OR APPEAL_STATUS = '' )  AND CONVERT(char(10), GETDATE(),126) > DATEADD(dy, 15, VERIFY_DATE) ";
				
				$query_list = db::query($sql_list);
				while($rec_list = @db::fetch_array($query_list)) {
					  
					
					$WF["WFR_ID"]=$rec_list["WFR_ID"];
					$WFR = $rec_list["WFR_ID"];
					$_POST["APPEAL_STATUS"]=2;  
				 		
					@require("../save/video_uthon_warehouse_3.php");   
					
					$arr_fields = array(); 
					$arr_fields["APPEAL_STATUS"]=2; // รอยื่นเกิน 15 วัน  ถือว่าไม่ยื่นอุทธรณ์ 
					$arr_fields["APPEAL_DATE"]=$rec_list["TODAY_DATE"];
					$arr_fields["WF_DET_STEP"]= 85;      	//75;        //   ไปขั้นตอน ดำเนินการเสร็จสิ้น     
					$arr_fields["WF_DET_NEXT"]=0;
					 
					$cond_custom = array( "WFR_ID" => $rec_list["WFR_ID"]);
					
					db::db_update("WFR_VIDEO_APPROVE_REQUEST3", $arr_fields, $cond_custom);
					 
				}
		} // end if วท.3
		
		 if($rec_w["WF_MAIN_SHORTNAME"]=="WFR_VIDEO_APPROVE_REQUEST2" && $W > 0 ) {   // ถ้า ส่ง W ของ วท 2 มา ค่อย check  
		
				$sql_list = " SELECT
								WFR_VIDEO_APPROVE_REQUEST2.WFR_ID,
								WFR_VIDEO_APPROVE_REQUEST2.WF_DET_STEP,
								WFR_VIDEO_APPROVE_REQUEST2.WF_DET_NEXT,
								WFR_VIDEO_APPROVE_REQUEST2.R_VIDEO_NAME,
								WFR_VIDEO_APPROVE_REQUEST2.CON_RESOLUTION,
								WFR_VIDEO_APPROVE_REQUEST2.VERIFY_DATE,
								CONVERT(char(10), GETDATE(),126),
								DATEADD(dy, 15, VERIFY_DATE) AS  APPEAL_TIMEOUT_DATE,
								WFR_VIDEO_APPROVE_REQUEST2.APPEAL_DATE,
								WFR_VIDEO_APPROVE_REQUEST2.APPEAL_STATUS,
								WFR_VIDEO_APPROVE_REQUEST2.TO_WAREHOUSE
							 FROM
								WFR_VIDEO_APPROVE_REQUEST2
							 WHERE CON_RESOLUTION > '1'  AND  YEAR(DATE_PAID) > 1900  AND ( APPEAL_STATUS IS NULL OR APPEAL_STATUS = '' )  AND CONVERT(char(10), GETDATE(),126) > DATEADD(dy, 15, VERIFY_DATE) ";
				
				$query_list = db::query($sql_list);
				while($rec_list = @db::fetch_array($query_list)) {
					  					
					$WF["WFR_ID"]=$rec_list["WFR_ID"];
					$WFR = $rec_list["WFR_ID"];
					$_POST["APPEAL_STATUS"]=2;  
				 		
					@require("../save/video_uthon_warehouse_2.php");   
					
					$arr_fields = array(); 
					$arr_fields["APPEAL_STATUS"]=2; // รอยื่นเกิน 15 วัน  ถือว่าไม่ยื่นอุทธรณ์ 
					$arr_fields["APPEAL_DATE"]=$rec_list["TODAY_DATE"];
					$arr_fields["WF_DET_STEP"]= 83;     //   ไปขั้นตอน ดำเนินการเสร็จสิ้น     
					$arr_fields["WF_DET_NEXT"]=0;
					 
					$cond_custom = array( "WFR_ID" => $rec_list["WFR_ID"]);
					
					db::db_update("WFR_VIDEO_APPROVE_REQUEST2", $arr_fields, $cond_custom);
					 
				}
		} // end if วท.2
	
//  นำตัวแปร  backup ต่างๆ คืนค่ากลับไปใช้ ใน Flow ปกติ
	
$_REQUEST["W"] = $BACKUP_VARS["W"];
$WF["WFR_ID"] = $BACKUP_VARS["WFR_ID"];
$WF["R_MOVIE"] = $BACKUP_VARS["R_MOVIE"];
$WFR = $BACKUP_VARS["WFR"];
?>