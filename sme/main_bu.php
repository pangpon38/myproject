<?php
$path = "";
$menu_id="0";

include($path."include/config_header_top.php");
if($_REQUEST['sys_program']!=''){
$_SESSION['sys_program'] = $_REQUEST['sys_program'];
}else{
$_SESSION['sys_program'] = $_SESSION['sys_program'];	
}
if($_SESSION["sys_group_menu"]){
	foreach($_SESSION["sys_group_menu"] as $key0 => $arrVal0){
		$menu0_list .= $key0.",";
	}
}else{
	$menu0_list = 0;
}

//auto redirect url
if(count($_SESSION["sys_group_menu"][2]) == 1){
	$temp_menu_id = key($_SESSION["sys_group_menu"][2]);
	$temp_menu_url = $db->get_data_field("select MENU_URL from aut_menu_setting where menu_id = '".$temp_menu_id."' ","MENU_URL");
	$temp_menu_url = $temp_menu_url."?".url2code("r=home&menu_id=".$temp_menu_id);
	header('Location: '.$temp_menu_url);
}

$sqlMenu0 = "select * from aut_menu_setting where menu_level = '0' and menu_id in ('2') order by menu_order asc ";

$queryMenu0 = $db->query($sqlMenu0);
while($recMenu0 = $db->db_fetch_array($queryMenu0)){
	$recMenu0 = array_change_key_case($recMenu0,CASE_LOWER);
	$dataMenu0[] = array("menu_id"=>$recMenu0["menu_id"],"desc"=>text($recMenu0["menu_desc"]));

}
$fields = array(
		"AMOUNT_COUNT" => 1,
		"AUT_USER_ID" => $_SESSION['aut_user_id'],
		"ORG_ID" => $_SESSION['sys_dept_id'],
		"IP_USER" => $ip,
		"CREATE_TIMESTAMP" => $TIMESTAMP);
$db->db_insert("user_bu",$fields); unset($fields);


$sql_cju = "select sum(AMOUNT_COUNT)as scju from user_bu";
$query_cju = $db->query($sql_cju);
$rec_cju = $db->db_fetch_array($query_cju);

$sql = " select * from aut_menu_setting where menu_level = '1'  order by MENU_ORDER asc";
$query = $db->query($sql);
while($rec = $db->db_fetch_array($query)){
	$rec = array_change_key_case($rec);
	$dataMenu[$rec["menu_parent_id"]][$rec["menu_id"]] = array("menu_id"=>$rec["menu_id"],"desc"=>text($rec["menu_desc"]),"url"=>text($rec["menu_url"]),"img"=>text($rec["menu_img"]));
}//while

$link = "r=home";  /// for mobile
$paramlink = url2code($link);

?>
<!DOCTYPE html>
<html>
<head>
	<?php include($path."include/inc_main_top_sumall.php"); ?>
	<script src="<?php echo $path; ?>bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="<?php echo $path; ?>text/css" href="css/easytabs.css"/>
	<link rel="stylesheet" href="<?php echo $path; ?>ammap/ammap.css" type="text/css" media="all" />
	<script src="<?php echo $path; ?>js/jquery.circliful.min.js"></script>
	<script src="<?php echo $path; ?>js/jquery.easytabs.min.js"></script>
	<script src="<?php echo $path; ?>ammap/ammap.js" type="text/javascript"></script>
	<script src="<?php echo $path; ?>ammap/maps/js/thailandLow.js" type="text/javascript"></script>
	<style type="text/css">
		.aNavStyle{
					padding-left: 5px !important;
					padding-right: 10px !important;
					padding-top: 2px !important;
					padding-bottom: 2px !important;
					border-radius: 3px;
					margin-right:5px;
					margin-left:5px;
					border-right:1px solid #cccccc;
		}
		.headerNavStyle{
			color: #000000 !important;
			cursor: default;

		}
		.design{
			 background: url(images/icon3.png) left top;
			 background-size: 25px 25px;
			 width:25px; 
				 height:25px;
				 display: block;
				 float: left;
		}

		.image-nav > li:hover{
			background-color: #efefef;
			border-radius: 4px;
		}

		.shadow {
			-moz-box-shadow: 5px 5px 5px #ccc;
			-webkit-box-shadow: 5px 5px 5px #ccc;
			box-shadow: 5px 5px 5px #ccc;
		}
	</style>
</head>
<body>
<div class="container-full">
	<div><?php include($path."include/header.php"); ?></div>
    
	<div class="row" >
		<div class="col-lg-12">
			<?php //include($path."sumary_budget_out.php"); ?>
		</div>
	</div>
    <?php include($path."include/footer.php"); ?>
</div>
</body>
</html>