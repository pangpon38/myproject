<?php
$frontPath = "../CULTURE_MOVIE61/";
$HIDE_HEADER = "P";
include '../include/comtop_user.php';

$sql_chk_lock  = " SELECT   MEDIA_GCODE  FROM   M_LICENSE_WAREHOUSE   WHERE  LICENSE_ID = '" . $_REQUEST["RN"] . "' ";
$query_chk_lock = db::query($sql_chk_lock);
$rec_chk_lock = db::fetch_array($query_chk_lock);

if(!$rec_chk_lock['MEDIA_GCODE']){
    echo "<script>window.close();</script>";
    exit;
}

?>

<link rel="stylesheet" type="text/css" href="../assets/plugins/data-table/css/dataTables.bootstrap4.min.css">
<style>
    ul.pagination-s li {
        display: inline;
        padding: 5px;
    }

    .td_remove {
        display: none;
    }
</style>
<script src="<?php echo $frontPath; ?>js/paging.js"></script>
<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row" id="animationSandbox">
            <div class="col-sm-12">
                <div class="main-header">
                    <h4> <img src="../icon/icon8.png"> ไฟล์<?php echo $_REQUEST['SHORT_NAME']; ?></h4>
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
                    <form method="post" enctype="multipart/form-data" id="file_attach" name="file_attach" action="add_file_doc_process.php">
                        <input type="hidden" name="license_id" id="license_id" value="<?php echo $_REQUEST['RN']; ?>">
                        <input type="hidden" name="m_group_code" id="m_group_code" value="<?php echo $rec_chk_lock["MEDIA_GCODE"]; ?>">
                        <div class="form-group row">
                            <div id="DOC_FILE_BSF_AREA" class="col-md-2 offset-md-1 ">
                                <label for="DOC_FILE" class="form-control-label wf-right">เอกสารแนบ<span class="text-danger">*</span></label>
                            </div>
                            <div id="DOC_FILE_BSF_AREA" class="col-md-6 wf-left">
                                <div class="md-group-add-on">
                                    <span class="md-add-on-file">
                                        <button class="btn btn-primary waves-effect waves-light">
                                            <i class="zmdi zmdi-cloud-upload"></i> เลือกไฟล์
                                        </button>
                                    </span>
                                    <div class="md-input-file">
                                        <input type="file" name="DOC_FILE[]" id="DOC_FILE" class="" multiple="" required="" aria-required="true">
                                        <input type="text" class="md-form-control md-form-file"><label class="md-label-file"></label><span class="md-line">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div align="right" id="wf-div-btn-process">&nbsp;
                    <button type="submit" class="btn btn-success waves-effect waves-light" id="btn_save_attach"><i class="icofont icofont-tick-mark" title=""></i> บันทึก</button>
                    </form>
                </div>
            </div>
            <script type="text/javascript" src="../assets/plugins/data-table/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="../assets/plugins/data-table/js/dataTables.bootstrap4.min.js"></script>
            <?php
            include '../include/combottom_js_user.php';
            include '../include/combottom_user.php';
            ?>