<?php
include "connect_db.php";
include "config_db.php";

$sql = "SELECT * FROM m_user";
$query = db::query($sql);
$nums = db::num_rows($query);
echo $nums;
?>