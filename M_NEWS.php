<?php $dep_id = $_SESSION['DEP_ID'];
 ?>
<script>
var chk_dep_id = <?php echo $dep_id;?>
if(chk_dep_id == 0){
    $("div[name='DEP_ID_BSF_AREA']").hide();
    //$('div[name="DEP_ID_BSF_AREA"]').prop('required',false);
}
</script>