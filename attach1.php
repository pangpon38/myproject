<?php
$frontPath = "../CULTURE_MOVIE61/";
$HIDE_HEADER = "P";
include '../include/comtop_user.php';
?>
<?php
 	//$sql_gcode  = " SELECT   distinct  MEDIA_GCODE, MEDIA_TYPE  FROM   M_REQUEST_MEDIA    ";	
$sql_gcode  = " SELECT   *  FROM   M_MEDIA_GROUP  ORDER  BY  MEDIA_GROUP_CODE";
$query_gcode = db::query($sql_gcode); 
$ARR_GCODE = array();
while($rec_gcode = db::fetch_array($query_gcode)) {
			//array_push($ARR_GCODE, $rec_gcode[""]);	
	$ARR_GCODE[$rec_gcode["MEDIA_GROUP_CODE"]] = $rec_gcode["MEDIA_SHORT_NAME"];
		//	$ARR_GCODE[$rec_gcode["MEDIA_GCODE"]] = $rec_gcode["MEDIA_TYPE"];
}
	/*	  ลองเขียน code การรับค่า p ที่ถูก encrypt ด้วย php จากหน้าอื่น   แต่พบว่า  ระบบ BizSmartFlow  ยังไม่รองรับ การกำหนด Link แบบ Dynamic ด้วย PHP ( คือ กำหนดตรงๆ แบบ Static ได้เท่านั้น ) 
 		 จึงไม่สามารถ ใช้วิธี decrypt ได้   ต้องยกเลิก วิธีการที่ security ดีแบบนี้ ไปก่อน
	if($_REQUEST["p"]) {
		$arr_get = decrypt($_REQUEST["p"], PRIVATE_KEY);
		
		$sql_chk_lock  = " SELECT   *  FROM   M_REQUEST_MEDIA   WHERE   MEDIA_TYPE = '".$arr_get["SHORT_NAME"]."' ";		
		$query_chk_lock = db::query($sql_chk_lock);
		$num_chk_lock = db::num_rows($query_chk_lock);
    }
	
	if(!$num_chk_lock) {
		  echo " กรุณาระบุประเภทใบอนุญาต ";
		  exit;
	} else {
		$_REQUEST["SHORT_NAME"]=$arr_get["SHORT_NAME"];		
	}
	
	if($_REQUEST["SHORT_NAME"]) {
		$filter .= " R_NAME_SHORT = '".$_REQUEST["SHORT_NAME"]."'  AND ";
		//$filter .= " R_NAME_SHORT LIKE '%".$_REQUEST["SHORT_NAME"]."%'  AND ";
    }
	*/
    $sql_chk_lock  = " SELECT   *  FROM   M_MEDIA_GROUP   WHERE   MEDIA_SHORT_NAME = '".$_REQUEST["SHORT_NAME"]."' ";			
    $query_chk_lock = db::query($sql_chk_lock);
    $rec_chk_lock = db::fetch_array($query_chk_lock);

    if(!$rec_chk_lock["MEDIA_GROUP_CODE"]){  
    	echo " <script> alert(\"กรุณาระบุประเภทสื่อให้ถูกต้อง\");  </script>";
    	redirect_joke("../workflow/index.php");
    	exit;
    }

//	if(!$rec_chk_lock["MEDIA_GCODE"]) 

    ?>
    <link rel="stylesheet" type="text/css" href="../assets/plugins/data-table/css/dataTables.bootstrap4.min.css">
    <style>
    	ul.pagination-s li {
    		display:inline;
    		padding: 5px;
    	}
    	.td_remove {
    		display:none;
    	}
    </style>
    <script src="<?php echo $frontPath;?>js/paging.js"></script>
    <div class="content-wrapper">
    	<div class="container-fluid">
    		<div class="row" id="animationSandbox">
    			<div class="col-sm-12">
    				<div class="main-header">
    					<h4> <img src="../icon/icon8.png"> ไฟล์<?php echo $rec_chk_lock["MEDIA_GROUP_NAME"];?></h4>
    					<ol class="breadcrumb breadcrumb-title breadcrumb-arrow"></ol>
    					<div class="f-right">

    					</div>
    				</div>
    			</div>
    		</div>
    		<!-- Row Starts -->
    		<div class="row">
    			<div class="col-md-12">
    				<div class="card">
    					<div class="card-block">
    						<?php 

    						if($rec_chk_lock["MEDIA_GROUP_CODE"]=='11' || $rec_chk_lock["MEDIA_GROUP_CODE"] =='33'){
    							$table_check = "WFR_MOVIE_APPROVE_REQUEST1";
    							$wf_main = "1";
    						}
    						else if($rec_chk_lock["MEDIA_GROUP_CODE"]=='44'){
    							$table_check = "WFR_MOVIE_APPROVE_REQUEST2";
    							$wf_main = "26";
    						}
    						else if($rec_chk_lock["MEDIA_GROUP_CODE"]=='22'){
    							$table_check = "WFR_VIDEO_APPROVE_REQUEST1";
    							$wf_main = "20";
    						}
    						else if($rec_chk_lock["MEDIA_GROUP_CODE"]=='55'){
    							$table_check = "WFR_VIDEO_APPROVE_REQUEST2";
    							$wf_main = "21";
    						}
    						$sql_wfr = "SELECT WFR_ID FROM $table_check WHERE RECEIPT_NUMBER = '".$_REQUEST["RN"]."' ";
    						$query_wfr = db::query($sql_wfr); 
    						$rec_wfr = db::fetch_array($query_wfr);
    						
    						$sql_doc = "SELECT
    						FRM_DOC_RECORD.WFR_ID AS WFR_DOC,
    						FRM_MOVIE_DOC.WFR_ID AS WFR_MOVIE,
    						WF_FILE.FILE_ID,
    						WF_FILE.FILE_NAME,
    						WF_FILE.FILE_SAVE_NAME,
    						WF_FILE.WF_MAIN_ID,
    						WF_FILE.FILE_STATUS,
    						WF_FILE.WFS_FIELD_NAME,
    						WF_FILE.WFR_ID AS F_ID,
    						FRM_MOVIE_DOC.F_CREATE_DATE,
    						FRM_DOC_RECORD.F_CREATE_DATE,
    						WF_FILE.FILE_DATE

    						FROM  WF_FILE
    						LEFT JOIN FRM_MOVIE_DOC ON FRM_MOVIE_DOC.F_ID = WF_FILE.WFR_ID AND WF_FILE.WF_MAIN_ID = '3630' 
    						AND FRM_MOVIE_DOC.WF_MAIN_ID = '$wf_main'
    						LEFT JOIN FRM_DOC_RECORD ON FRM_DOC_RECORD.F_ID = WF_FILE.WFR_ID AND WF_FILE.WF_MAIN_ID = '3782' 
    						AND FRM_DOC_RECORD.WF_MAIN_ID = '$wf_main'
    						WHERE (FRM_MOVIE_DOC.WFR_ID = '".$rec_wfr["WFR_ID"]."'  OR  FRM_DOC_RECORD.WFR_ID = '".$rec_wfr["WFR_ID"]."') AND WF_FILE.FILE_STATUS = 'Y'  ";
    						$query_doc = db::query($sql_doc);
    							$i=0;
    							while($rec_doc = db::fetch_array($query_doc)) {
    								$i++;
    								$attach_folder = "../attach/w".$rec_doc["WF_MAIN_ID"]."/";

    								echo '<a href="'.$attach_folder.$rec_doc["FILE_SAVE_NAME"].'" target="_blank" ><h5>เอกสารไฟล์ที่ : '.$i.'.'.$rec_doc["FILE_NAME"].'</h5></a><br/>';
    							}
    							if($i==0){ 
    								echo "<h1>ไม่พบเอกสารแนบเพิ่มเติม</h1>"; // ในระบบ ?>
    						<?}
    						?>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
    <script type="text/javascript" src="../assets/plugins/data-table/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../assets/plugins/data-table/js/dataTables.bootstrap4.min.js"></script>
    <?php 
    include '../include/combottom_js_user.php'; 
    include '../include/combottom_user.php'; 
    ?>