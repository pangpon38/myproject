<?php
$params = "?depname2 =" . $_GET["depname"];
?>
<form name="search_dep" id="search_dep" method="get" action="search_result.php<?php echo $params; ?>">
    <div class="form-row">
        <div class="col-lg-3 mb-3">
            <input type="text" name="depname" class="form-control" placeholder="ชื่อสมาคม" value="<?php echo $_GET["depname"] != "" ? $_GET["depname"] : ""; ?>">
        </div>
        <div class="col-lg-2 mb-3">
            <!-- <button type="button" class="btn btn-pink btn-block" onClick="searchdata();">ค้นหา</button> -->
            <input type="submit" class="btn btn-pink btn-block" value="ค้นหา">
        </div>
    </div>
</form>