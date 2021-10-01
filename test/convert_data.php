<?php
if($_GET['W']){
	$_GET['W']=(int)$_GET['W'];
}
if($_GET['WFR']){
	$_GET['WFR']=(int)$_GET['WFR'];
}

//ประกาศระเบียบ ข้อบังคับ
if($_GET['W']==6){
	$WF['RULES_TOPIC']=context($WF['RULES_TOPIC']);
}
?>