<?php
session_start();
$path = "../../";
$path_a = "../../fileupload/file_prjp/";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$disables_txt = "disabled";
$readonly_txt = "readonly";

$ACT = '31';
if ($_POST['PRJP_ID'] != '') {
    $PRJP_ID = $_POST['PRJP_ID'];
} else {
    $PRJP_ID = $PRJP_ID;
}
$sql_head = "SELECT PRJP_ID,PRJP_CODE,PRJP_NAME,PRJP_CON_ID FROM prjp_project WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$sql = "SELECT RISK_ID, RISK_NAME FROM setup_prjp_risk WHERE ACTIVE_STATUS = '1' ";
$query = $db->query($sql);
while ($rec = $db->db_fetch_array($query)) {
    $arr_risk[$rec['RISK_ID']] = text($rec['RISK_NAME']);
}

$sql_main = "SELECT * FROM prjp_risk_detail WHERE PRJP_ID = '" . $PRJP_ID . "' ";
$query_main = $db->query($sql_main);
$row_main = $db->db_num_rows($query_main);
?>
<!DOCTYPE html>
<html>

<head>
    <?php include($path . "include/inc_main_top.php"); ?>
    <script src="js/form_project_risk.js?<?php echo rand(); ?>"></script>
    <script type="text/javascript">
        var menu_id = "<?php echo $menu_id; ?>"
        var menu_sub_id = "<?php echo $menu_sub_id; ?>"

        function addData() {
            $('#proc2').val("add");
            $('#modal_contract .modal-body').load("form_project_risk_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id)
            $('#modal_contract').modal('show');
        }

        function editData(idc, idp, e) {
            val = document.getElementById("proc2").value = "edit";
            val;
            $('#modal_contract .modal-body').load("form_project_risk_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&idc=" + idc)
            $('#modal_contract').modal('show');
        }

        function delData(idc, idp) {
            if (confirm("ต้องการลบข้อมูลใช่หรือไม่ ?")) {
                $('#proc').val("del");
                $("#PRJP_ID").val(idp);
                $("#PRJP_RISK_ID").val(idc);
                $("#frm-search").submit();
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
			<li><a href="disp_send_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
			<li><a href="disp_project_act.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id . "&PRJP_ID=" . $PRJP_ID . "&MONEY_PMAIN=" . $MONEY_PMAIN); ?>">กิจกรรม</a></li>
			<li class="active">สัญญาจ้าง</li>
		</ol>
    </div>-->
        <div class="col-xs-12 col-sm-12">
            <ol class="breadcrumb">
                <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
                <li><a href="disp_send_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>">รายละเอียด</a></li>
                <li class="active">ปัญหาอุปสรรคจากการดำเนินงาน/โครงการ (อ้างอิง สสว.300)</li>
            </ol>
        </div>
        <div class="col-xs-12 col-sm-12">
            <div class="groupdata">
                <form id="frm-search" method="post" action="process/disp_project_risk_process.php" enctype="multipart/form-data">
                    <input name="proc" type="hidden" id="proc">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <input type="hidden" id="PRJP_RISK_ID" name="PRJP_RISK_ID">

                    <!-- Modal -->

                    <div class="row">
                        <div class="col-xs-12 col-sm-12"><?php include("tab_menu2_r.php"); ?></div>
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
                    <div class="col-xs-12 col-sm-12">
                        <div class="">
                            <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                <thead>
                                    <tr class="bgHead">
                                        <th width="10%" rowspan="2" nowrap>
                                            <div align="center"><strong>ลำดับ</strong></div>
                                        </th>
                                        <th width="45%" rowspan="1" colspan="3" nowrap>
                                            <div align="center"><strong>ปัญหาอุปสรรคจากปัจจัยภายใน</strong></div>
                                        </th>
                                        <th width="45%" rowspan="1" colspan="3" nowrap>
                                            <div align="center"><strong>ปัญหาอุปสรรคจากปัจจัยภายนอก</strong></div>
                                        </th>
                                        <th width="10%" rowspan="2" nowrap>
                                            <div align="center"><strong>จัดการ</strong></div>
                                        </th>
                                    </tr>
                                    <tr class="bgHead">
                                        <th width="15%" rowspan="1" nowrap>
                                            <div align="center"><strong>ประเภทของปัญหา</strong></div>
                                        </th>
                                        <th width="15%" rowspan="1" nowrap>
                                            <div align="center"><strong>รายละเอียด</strong></div>
                                        </th>
                                        <th width="15%" rowspan="1" nowrap>
                                            <div align="center"><strong>แนวทางแก้ไข</strong></div>
                                        </th>
                                        <th width="15%" rowspan="1" nowrap>
                                            <div align="center"><strong>ประเภทของปัญหา</strong></div>
                                        </th>
                                        <th width="15%" rowspan="1" nowrap>
                                            <div align="center"><strong>รายละเอียด</strong></div>
                                        </th>
                                        <th width="15%" rowspan="1" nowrap>
                                            <div align="center"><strong>แนวทางแก้ไข</strong></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody class="body">
                                    <?php
                                    if ($row_main > 0) {
                                        $i = 1;
                                        while ($rec_main = $db->db_fetch_array($query_main)) {
                                    ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $arr_risk[$rec_main['RISK_ID1']]; ?></td>
                                                <td><?php echo text($rec_main['RISK_DETAIL1']); ?></td>
                                                <td><?php echo text($rec_main['RISK_SOLUTION1']); ?></td>
                                                <td><?php echo $arr_risk[$rec_main['RISK_ID2']]; ?></td>
                                                <td><?php echo text($rec_main['RISK_DETAIL2']); ?></td>
                                                <td><?php echo text($rec_main['RISK_SOLUTION2']); ?></td>

                                                <td nowrap>
                                                 ---
                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="13" align="center">ไม่มีข้อมูล</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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
                    <h4 class="modal-title" id="modal_contract_label"><i class="fa fa-edit" aria-hidden="true"></i> ปัญหาอุปสรรคจากการดำเนินงาน/โครงการ (อ้างอิง สสว.300)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="frm-contract" method="post" action="process/disp_project_risk_process.php" enctype="multipart/form-data">
                    <input name="proc" type="hidden" id="proc2">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <input type="hidden" id="RISK_ID" name="RISK_ID">

                    <div class="modal-body"></div>
                </form>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="chkinput();"><i class="fa fa-check" aria-hidden="true"></i> บันทึก</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i> ยกเลิก</button>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<!-- /.modal -->