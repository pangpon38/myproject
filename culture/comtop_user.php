<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE);

if($_SESSION["WF_USER_ID"] == ""){
	?>
<script type="text/javascript">
	self.location.href='../index';
</script>
<?php	
exit;
	}
include '../include/include.php';
if($HIDE_HEADER != "Y"){
 ?><!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../include/template_user.php'; ?>
</head>

<body id="bsf_body" class="horizontal-fixed fixed">
 <div class="wrapper">
  <!--<div class="loader-bg">
    <div class="loader-bar"> 
    </div>
</div>-->
<!-- Navbar-->
     <?php if($HIDE_HEADER != "P"){ include '../include/header_user.php'; }
}	 
	 ?>