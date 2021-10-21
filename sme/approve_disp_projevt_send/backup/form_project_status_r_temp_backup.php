<?php
/* @session_start();
$path = "../../";
include($path."include/config_header_top.php");
$path_a="../../fileupload/file_contract/";
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = ""; */

session_start();
$path = "../../";
$path_a = "../../fileupload/file_prjp/";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";

$ACT = '28';
if ($_POST['PRJP_ID'] != '') {
    $PRJP_ID = $_POST['PRJP_ID'];
} else {
    $PRJP_ID = $PRJP_ID;
}
$sql_head = "SELECT PRJP_ID,PRJP_CODE,PRJP_NAME,PRJP_CON_ID FROM prjp_project_temp WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$filter == "";
if ($_POST['s_round_year_bud'] != "") {
    $filter .= " AND PRJP_NAME LIKE '%" . $_POST['s_round_year_bud'] . "%' ";
}
$sql = "SELECT * from prjp_act_contract_temp  WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);

$sql_status = "SELECT * FROM dash_prjp_status WHERE ACTIVE_STATUS = '1' ";
$query_status = $db->query($sql_status);
while ($rec_status = $db->db_fetch_array($query_status)) {
    $arr_status[$rec_status['STATUS_ID']] = text($rec_status['STATUS_NAME']);
}

$sql_statuschk = "SELECT * FROM prjp_status_menu_temp WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_statuschk = $db->query($sql_statuschk);
while ($rec_chk = $db->db_fetch_array($query_statuschk)) {
    $chkStatus[$rec_chk['STATUS_ID']] = $rec_chk['STATUS_ID'];
    $chkDate[$rec_chk['STATUS_ID']] = $rec_chk['PRJP_STATUS_DATE'];
    $chkDesc[$rec_chk['STATUS_ID']] = $rec_chk['PRJP_STATUS_DESC'];
}




?>
<!DOCTYPE html>
<html>

<head>
    <?php include($path . "include/inc_main_top.php"); ?>
    <script src="js/disp_project_status.js?<?php echo rand(); ?>"></script>
    <script type="text/javascript">
        var menu_id = "<?php echo $menu_id; ?>"
        var menu_sub_id = "<?php echo $menu_sub_id; ?>"

        function addData() {
            $('#proc2').val("add_lesson");
            $('#modal_contract .modal-body').load("form_project_act_contract_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id)
            $('#modal_contract').modal('show');
        }

        function editData(idc, idp) {
            $('#proc2').val("edit_lesson");
            $('#modal_contract .modal-body').load("form_project_act_contract_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&idc=" + idc)
            $('#modal_contract').modal('show');
        }

        function remove_id(id, tb_id) {
            $('#' + id).remove();
            sum_bdg();
            run_number_tb(tb_id);
        }

        function add_row_lesson() {
            var table = document.getElementById('tb_data_lesson');
            var rowCount = (table.rows.length);
            var row = table.insertRow(rowCount);

            var id_tbc = rowCount + "" + parseInt((Math.random() * 10) + 1);
            table.rows[rowCount].id = id_tbc;
            table.rows[rowCount].style.background = "#FFFFFF";
            var j = 0;

            for (var i = 0; i < 3; i++) {
                table.rows[rowCount].insertCell(i);
            }

            table.rows[rowCount].cells[0].align = "center";
            table.rows[rowCount].cells[1].align = "left";
            table.rows[rowCount].cells[2].align = "center";

            //data 
            var url = "process/disp_project_act_process.php";

            table.rows[rowCount].cells[0].innerHTML = "" + (rowCount);

            $.post(url, {
                        proc: "get_lesson",
                        id_tbc: id_tbc
                    }, function(msg) {
                        table.rows[rowCount].cells[1].innerHTML = "" + msg + "";

                        // 	// $(".PRJP_ACT_CONTRACT_LESSON_DETAIL_DATE" + id_tbc).each(function() { //ปฏิทิน
                        // 	// 	var date_for = $(this).attr("for");
                        // 	// 	$('span[for=' + date_for + ']').attr('data-date', $('#' + date_for).val());

                        // 	// 	$("span[for=" + date_for + "]").datepicker({
                        // 	// 		language: "th-th"
                        // 	// 	});
                        // 	// 	$("span[for=" + date_for + "]").on("changeDate", function(e) { //onchangeDate
                        // 	// 		$('#' + date_for).val(e.format('dd/mm/yyyy'));
                        // 	// 		$('span[for=' + date_for + ']').datepicker('hide');
                        // 	// 	});
                        // 	// 	$("#" + date_for).on("keyup", function(e) { //onkeyup
                        // 	// 		beginchk(this, e, this.id);
                        // 	// 	});
                        // 	});
                        // });

                        table.rows[rowCount].cells[2].innerHTML = "<a class=\"btn btn-danger btn-xs\" onClick=\"remove_id(" + id_tbc + ",'tb_data_coor');sum_bdg();\">" + img_del + " ลบ</a> ";

                    }

                    function delData(idc, idp) {
                        if (confirm("ต้องการลบข้อมูลใช่หรือไม่ ?")) {
                            $("#proc").val("del_lesson");
                            $("#PRJP_ID").val(idp);
                            $("#PRJP_ACT_CONTRACT_ID").val(idc);
                            $("#frm-search").attr("action", "process/disp_project_act_process.php").submit();
                        }
                    }

                    function chkinput() {
                        if (confirm("กรุณายืนยันการบันทึกอีกครั้ง ?")) {
                            $("#frm-contract").submit();
                        }
                    }

                    function sum_bdg() {
                        var sum = 0;
                        $.each($('input[type="text"][name="PRJP_ACT_CONTRACT_LESSON_DETAIL[]"][class*="bdg-detail"]'), function() {
                            var this_val = parseFloat($(this).val().split(",").join("")) || 0.00;
                            sum += this_val;
                        });
                        $("#PRJP_ACT_CONTRACT_BDG").val(number_format_txt(sum, 2));
                    }

                    function chk_old(id) {
                        if (id == 0) {
                            $("#hide_old").val(1);
                        } else if (id == 1) {
                            $("#hide_old").val(2);
                        } else if (id == 2) {
                            $("#hide_old").val(1);
                        }
                        id = $("#hide_old").val();
                        show_old(id);
                    }

                    function show_old(id) {
                        if (id == 1) {
                            $("#data_old").show();
                            $("#data_old2").show();
                            $("#TB_BR").html("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>");
                        } else {
                            $("#data_old").hide();
                            $("#data_old2").hide();
                            $("#TB_BR").html("");
                        }
                    }

                    function textfrm_sel(e) {
                        $(".txtfrm").hide();
                        $(".txtfrm" + e).show();
                    }

                    function tab_list(id) {
                        $(".tb_v").hide();
                        $(".re_act").removeClass("active");

                        $("#show_rb_" + id).show();
                        $("#tablist_" + id).addClass("active");

                    }
    </script>
    <style>
        table#tb_file_prjp2 tr td {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="container-full">
        <div><?php include($path . "include/header.php"); ?></div>
        <!--<div class="col-xs-12 col-sm-12">
		<ol class="breadcrumb">
			<li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
			<li><a href="disp_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
			<li><a href="disp_project_act.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id . "&PRJP_ID=" . $PRJP_ID . "&MONEY_PMAIN=" . $MONEY_PMAIN); ?>">กิจกรรม</a></li>
			<li class="active">สัญญาจ้าง</li>
		</ol>
    </div>-->
        <div class="col-xs-12 col-sm-12">
            <ol class="breadcrumb">
                <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
                <li><a href="disp_approve_project_temp.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
                <li class="active">ผลตัวชี้วัดของผลผลิต</li>
            </ol>
        </div>
        <div class="col-xs-12 col-sm-12">
            <div class="groupdata">
                <form id="frm-search" method="post" action="process/disp_project_status_process.php">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <input type="hidden" id="PRJP_ACT_CONTRACT_ID" name="PRJP_ACT_CONTRACT_ID">

                    <!-- Modal -->

                    <div class="row">
                        <div class="col-xs-12 col-sm-12"><?php include("tab_menu2_r_temp.php"); ?></div>
                        <?php
                        if ($_SESSION["sys_group_id"] == '5' || $_SESSION["sys_group_id"] == '9') {
                        ?>
                            <div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php"); ?></div>
                        <?php
                        }
                        ?>
                    </div>

                    <table width="100%">
                        <tr>
                            <td align="center"><strong class="font-blue"><?php echo $rec_head['PRJP_CODE'] . " " . text($rec_head['PRJP_NAME']) ?></strong></td>
                        </tr>
                    </table>
                    <br />
                    <div class="row">
                        <div class="col-xs-12 col-sm-1">
                            <?php if ($_SESSION['sys_status_add'] == '1') { ?>
                                <!-- <a class="btn btn-success" data-toggle="modal" onclick="addData();" ;><i class="fa fa-plus-circle" aria-hidden="true"></i> เพิ่มสัญญาจ้าง</a> -->
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <div class="">
                            <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                <thead>
                                    <tr class="bgHead">
                                        <th width="1%" rowspan="1" nowrap>
                                            <div align="center"><strong>ลำดับ</strong></div>
                                        </th>
                                        <!-- <th width="5%" rowspan="1" nowrap>
											<div align="center"><strong>ชื่อ</strong></div>
										</th> -->
                                        <th width="5%" rowspan="1" nowrap>
                                            <div align="center"><strong>สถานะการดำเนินการ</strong></div>
                                        </th>
                                        <th width="2%" rowspan="1" nowrap>
                                            <div align="center"><strong>วันที่</strong></div>
                                        </th>
                                        <th width="5%" rowspan="1" nowrap>
                                            <div align="center"><strong>รายละเอียดการดำเนินการ</strong></div>
                                        </th>

                                </thead>
                                <tbody>
                                <tbody class="body">
                                    <?php
                                    $i = 1;
                                    foreach ($arr_status as $key => $val) {
                                    ?>
                                        <tr bgcolor="#FFFFFF">
                                            <td align="center"><?php echo $i; ?></td>
                                            <!-- <td>
											<input type="text" class="form-control" name="status_name" id="status_name" value="">
										</td> -->
                                            <td>
                                                <input type="checkbox" name="status[]" id="status[]" value="<?php echo $key; ?>" <?php echo $key == $chkStatus[$key] ? "checked" : "" ?>> <?php echo $val; ?>
                                                <br>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="text" id="<?php echo "DATE" . "$i" ?>" name="SDATE_PRJP[]" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : 'chk_empty'; ?>" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo text($chkDate[$key]); ?>">
                                                    <span class="input-group-addon datepicker" for="<?php echo "DATE" . "$i" ?>">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <textarea name="status_desc[]" id="status_desc" style="width: 100%;" cols="50" rows="5"><?php echo text($chkDesc[$key]); ?></textarea>
                                            </td>
                                        </tr>
                                    <?php
                                        $i++;
                                    }

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="clearfix" align="center">
                        <?php if ($_SESSION['sys_status_edit'] == '1') { ?>
                            <!-- <button type="submit" name="submit" class="btn btn-primary">บันทึก</button> -->
                        <?php } ?>
                    </div>
                </form>
            </div>

        </div>
        <?php include($path . "include/footer.php"); ?>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modal_contract" tabindex="-1" role="dialog" aria-labelledby="modal_contract_label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document" style="width: 100%;max-width:800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_contract_label"><i class="fa fa-edit" aria-hidden="true"></i> เพิ่มสัญญาจ้าง</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="frm-contract" method="post" action="process/disp_project_act_process.php" enctype="multipart/form-data">
                    <input name="proc" type="hidden" id="proc2">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <input type="hidden" id="PRJP_ACT_CONTRACT_ID" name="PRJP_ACT_CONTRACT_ID">

                    <div class="modal-body"></div>
                </form>

                <div class="modal-footer">
                    <button type="summit" class="btn btn-success" onclick="chkinput();"><i class="fa fa-check" aria-hidden="true"></i> บันทึก</button> 
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i> ยกเลิก</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        var fields = document.getElementById("frm-search").getElementsByTagName('*');
        for (var i = 0; i < fields.length; i++) {
            fields[i].disabled = true;
        }
    </script>
</body>

</html>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<!-- /.modal -->