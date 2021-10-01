<?php $dep_id = $_SESSION['DEP_ID'];
$baac_id = $_SESSION['baac_cloud_id'];

if (!empty($baac_id)) {
    $sql_baac = "SELECT BASIC_ID FROM M_BASIC WHERE CHAPA_CLOUD_ID = {$baac_id}";
    $query_baac = db::query($sql_baac);
    $nums_baac = db::num_rows($query_baac);
    if ($nums_baac > 0) {
        $result_baac = db::fetch_array($query_baac);
    }
}

$basic_id = ($result_baac['BASIC_ID']) ? $result_baac['BASIC_ID'] : 0;

?>
<script>
    var chk_dep_id = <?php echo $dep_id; ?>;
    var basic_id = <?php echo $basic_id; ?>;

    if(basic_id != 0){
        $("select[id='DEP_ID']").val(basic_id);
    }

    if (chk_dep_id == 0) {
        $("div[id='DEP_ID_BSF_AREA']").hide();
        $("select[id='DEP_ID']").prop('required', false);
    }
</script>