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
$sql_head = "SELECT PRJP_ID,PRJP_CODE,PRJP_NAME,PRJP_CON_ID FROM prjp_project WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$filter == "";
if ($_POST['s_round_year_bud'] != "") {
    $filter .= " AND PRJP_NAME LIKE '%" . $_POST['s_round_year_bud'] . "%' ";
}
$sql = "SELECT * from prjp_act_contract  WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query = $db->query($sql);
$num_rows = $db->db_num_rows($query);

$sql_status_1 = "SELECT * FROM dash_prjp_status WHERE ACTIVE_STATUS = '1' AND STATUS_TYPE = 1";
$query_status_1 = $db->query($sql_status_1);
$rows_status_1 = $db->db_num_rows($query_status_1);
while ($rec_status = $db->db_fetch_array($query_status_1)) {
    $arr_status_1[$rec_status['STATUS_ID']] = text($rec_status['STATUS_NAME']);
}

$sql_status_2 = "SELECT * FROM dash_prjp_status WHERE ACTIVE_STATUS = '1' AND STATUS_TYPE = 2";
$query_status_2 = $db->query($sql_status_2);
$rows_status_2 = $db->db_num_rows($query_status_2);
while ($rec_status = $db->db_fetch_array($query_status_2)) {
    $arr_status_2[$rec_status['STATUS_ID']] = text($rec_status['STATUS_NAME']);
}

$sql_statuschk = "SELECT * FROM prjp_status_menu_temp WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_statuschk = $db->query($sql_statuschk);
while ($rec_chk = $db->db_fetch_array($query_statuschk)) {
    $chkStatus[$rec_chk['STATUS_ID']] = $rec_chk['STATUS_ID'];
    $chkDate[$rec_chk['STATUS_ID']] = $rec_chk['PRJP_STATUS_DATE'];
    $chkDesc[$rec_chk['STATUS_ID']] = $rec_chk['PRJP_STATUS_DESC'];
}

$sql_status_detail_1 = "SELECT * FROM prjp_status_detail
WHERE PRJP_ID = '" . $PRJP_ID . "' AND PRJP_STATUS_DETAIL_TYPE = 1";
$query_status_detail_1 = $db->query($sql_status_detail_1);
$row_sd_1 = $db->db_num_rows($query_status_detail_1);
// echo $sql_status_detail_1;

$sql_status_detail_2 = "SELECT * FROM prjp_status_detail
WHERE PRJP_ID = '" . $PRJP_ID . "' AND PRJP_STATUS_DETAIL_TYPE = 2";
$query_status_detail_2 = $db->query($sql_status_detail_2);
$row_sd_2 = $db->db_num_rows($query_status_detail_2);




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
            $('#modal_contract .modal-body').load("form_project_status_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id)
            $('#modal_contract').modal('show');
        }

        function editData(idc, idp, e) {
            // $("#proc2").val("edit_lesson");
            val = document.getElementById("proc2").value = "edit_lesson";
            val;
            $('#modal_contract .modal-body').load("form_project_status_detail.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&idc=" + idc)
            $('#modal_contract').modal('show');
        }

        function editResult(idc, idp, e) {
            // $("#proc2").val("edit_lesson");
            val = document.getElementById("proc2").value = "edit_result";
            val;
            $('#modal_contract .modal-body').load("form_project_status_result.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&idc=" + idc)
            $('#modal_contract').modal('show');
        }

        function delData(idc, idp) {
            if (confirm("ต้องการลบข้อมูลใช่หรือไม่ ?")) {
                $('#proc2').val("del_lesson");
                $("#PRJP_ID").val(idp);
                $("#PRJP_ACT_CONTRACT_ID").val(idc);
                $("#frm-contract").submit();
            }
        }

        function addData2() {
            $('#proc2').val("add_lesson2");
            $('#modal_contract .modal-body').load("form_project_status_detail2.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id)
            $('#modal_contract').modal('show');
        }

        function editData2(idc, idp) {
            $('#proc2').val("edit_lesson2");
            $('#modal_contract .modal-body').load("form_project_status_detail2.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&idc=" + idc)
            $('#modal_contract').modal('show');
        }


        function editResult2(idc, idp, e) {
            // $("#proc2").val("edit_lesson");
            val = document.getElementById("proc2").value = "edit_result2";
            val;
            $('#modal_contract .modal-body').load("form_project_status_result2.php?menu_id=" + menu_id + "&menu_sub_id=" + menu_sub_id + "&idc=" + idc)
            $('#modal_contract').modal('show');
        }

        function delData2(idc, idp) {
            if (confirm("ต้องการลบข้อมูลใช่หรือไม่ ?")) {
                $('#proc2').val("del_lesson2");
                $("#PRJP_ID").val(idp);
                $("#PRJP_ACT_CONTRACT_ID").val(idc);
                $("#frm-contract").submit();
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
                <li><a href="disp_send_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
                <li class="active">ผลตัวชี้วัดของผลผลิต</li>
            </ol>
        </div>
        <div class="col-xs-12 col-sm-12">
            <div class="groupdata">
                <form id="frm-search" method="post" action="process/disp_project_status_process.php" enctype="multipart/form-data">
                    <!-- <input name="proc" type="hidden" id="proc3"> -->
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
                    <!-- <input type="hidden" id="PRJP_ACT_CONTRACT_ID" name="PRJP_ACT_CONTRACT_ID"> -->

                    <!-- Modal -->

                    <div class="row">
                        <div class="col-xs-12 col-sm-12"><?php include("tab_menu2.php"); ?></div>
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
                                <a class="btn btn-success" data-toggle="modal" onclick="addData();"> <i class="fa fa-plus-circle" aria-hidden="true"></i> เพิ่มกระบวนการจัดซื้อจัดจ้าง</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <div class="">
                            <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                <thead>
                                    <tr class="bgHead">
                                        <th width="1%" rowspan="3" nowrap>
                                            <div align="center"><strong>ลำดับ</strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>กิจกรรม สสว.100</strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>ชื่อกิจกรรม/สัญญา ที่จะจัดซื้อจัดจ้างปีงบประมาณ <?php echo $_SESSION['year_round']; ?></strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>แผน/ผล</strong></div>
                                        </th>
                                        <th width="2%" rowspan="1" colspan="<?php echo $rows_status_1 + 2; ?>" nowrap>
                                            <div align="center"><strong>สถานะกระบวนการจัดซื้อจัดจ้าง (โปรดระบุ สถานะ และวันที่จนถึงปัจจุบัน)</strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>งบประมาณ (ล้านบาท)</strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>ชื่อผู้รับจ้าง-ที่ปรึกษา</strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>ลงนามในสัญญาจ้าง</strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>หมายเหตุ</strong></div>
                                        </th>
                                        <th width="5%" rowspan="3" nowrap>
                                            <div align="center"><strong>จัดการ</strong></div>
                                        </th>
                                    </tr>
                                    <tr>
                                        <?php foreach ($arr_status_1 as $key => $val) { ?>
                                            <th colspan="<?php echo $key == '1' || $key == '2' ? "2" : "" ?>" rowspan="<?php echo $key == '1' || $key == '2' ? "" : "2" ?>" align="center">
                                                <div><?php echo $val; ?></div>
                                            </th>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <td width="5%" align="center">เสนอ</td>
                                        <td width="5%" align="center">อนุมัติ</td>
                                        <td width="5%" align="center">เสนอ</td>
                                        <td width="5%" align="center">อนุมัติ</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" align="center">พ.ร.บ.สสว. มาตราการใช้จ่ายเงินกองทุน</td>
                                        <?php for ($i = 1; $i <= 12; $i++) {
                                            echo "<td align='center'>34(4)</td>";
                                        }
                                        ?>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                <tbody class="body">
                                    <?php
                                    if ($row_sd_1 > 0) {
                                        $i = 1;
                                        while ($rec_d1 = $db->db_fetch_array($query_status_detail_1)) {
                                    ?>
                                            <tr>
                                                <td rowspan="2"><?php echo $i; ?></td>
                                                <td rowspan="2"></td>
                                                <td rowspan="2"><?php echo text($rec_d1['PRJP_STATUS_DETAIL_NAME']); ?></td>
                                                <th>แผน</th>
                                                <?php
                                                $sql_status_sub_detail_1 = "SELECT a.*,
                                                b.PRJP_STATUS_SUB_DESC as 'DESC'
                                                FROM prjp_status_sub_detail a
                                                LEFT JOIN prjp_status_sub_desc b on a.PRJP_STATUS_SUB_ID = b.PRJP_STATUS_SUB_ID AND a.PRJP_STATUS_DETAIL_TYPE = b.PRJP_STATUS_DETAIL_TYPE
                                                AND a.PRJP_STATUS_DETAIL_ID = b.PRJP_STATUS_DETAIL_ID WHERE a.PRJP_STATUS_DETAIL_ID = '" . $rec_d1['PRJP_STATUS_DETAIL_ID'] . "' AND a.PRJP_STATUS_DETAIL_TYPE = 1";
                                                $query_status_sub_detail_1 = $db->query($sql_status_sub_detail_1);
                                                // echo $sql_status_sub_detail_1;
                                                while ($rec_sub_1 = $db->db_fetch_array($query_status_sub_detail_1)) {
                                                    if ($rec_sub_1['PRJP_STATUS_SUB_ID'] == 1 || $rec_sub_1['PRJP_STATUS_SUB_ID'] == 2) {
                                                ?>
                                                        <td style="border:none;">
                                                            <?php echo $rec_sub_1['PRJP_STATUS_SUB_DATE']; ?>
                                                            <table style="border:none;">
                                                                <tbody style="border:none;">
                                                                    <tr style="border:none;">
                                                                        <td nowrap style="border:none;"><?php echo $rec_sub_1['PRJP_STATUS_SUB_ID'] == 1 ? "ผู้รับผิดชอบ" : "วิธีการจัดจ้าง" ?></td>
                                                                    </tr>
                                                                    <tr style="border:none;">
                                                                        <td nowrap style="border:none;"><?php echo $i . "." . text($rec_sub_1['DESC']); ?></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td style="background-color: #eeeeee;"></td>
                                                    <?php } elseif ($rec_sub_1['PRJP_STATUS_SUB_ID'] == 19) { ?>
                                                        <td style="background-color: #eeeeee;"></td>
                                                    <?php
                                                    } else {   ?>
                                                        <td><?php echo $rec_sub_1['PRJP_STATUS_SUB_DATE']; ?></td>
                                                <?php }
                                                } ?>
                                                <td>
                                                    <table style="border:none;">
                                                        <tbody style="border:none;">
                                                            <tr style="border:none;">
                                                                <td nowrap style="border:none;">วงเงินตามแผนจัดจ้าง</td>
                                                            </tr>
                                                            <tr style="border:none;">
                                                                <td style="border:none;">
                                                                    <?php echo $rec_d1['PRJP_STATUS_DETAIL_M_BDG']; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td><?php echo text($rec_d1['PRJP_SUP_NAME']); ?></td>
                                                <td><?php echo $rec_d1['PRJP_STATUS_DETAIL_DATE_CONTRA']; ?></td>
                                                <td><?php echo text($rec_d1['PRJP_NOTE']); ?></td>
                                                </td>
                                                <td nowrap>
                                                    <a class="btn btn-warning" data-toggle="modal" onclick="editData(<?php echo $rec_d1['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>,'edit_lesson');"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไข</a>
                                                    <a class="btn btn-danger" onclick="delData(<?php echo $rec_d1['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>);"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>ผล</th>
                                                <?php
                                                $sql_status_res_detail_1 = "SELECT a.*,
                                                  b.PRJP_STATUS_SUB_DESC as 'DESC'
                                                  FROM prjp_status_sub_detail a
                                                  LEFT JOIN prjp_status_sub_desc b on a.PRJP_STATUS_SUB_ID = b.PRJP_STATUS_SUB_ID AND a.PRJP_STATUS_DETAIL_TYPE = b.PRJP_STATUS_DETAIL_TYPE
                                                  AND a.PRJP_STATUS_DETAIL_ID = b.PRJP_STATUS_DETAIL_ID WHERE a.PRJP_STATUS_DETAIL_ID = '" . $rec_d1['PRJP_STATUS_DETAIL_ID'] . "' AND a.PRJP_STATUS_DETAIL_TYPE = 2";
                                                $query_status_res_detail_1 = $db->query($sql_status_res_detail_1);
                                                while ($rec_sub_1 = $db->db_fetch_array($query_status_res_detail_1)) {
                                                    if ($rec_sub_1['PRJP_STATUS_SUB_ID'] == 1) {
                                                ?>
                                                        <td><?php echo $rec_sub_1['PRJP_STATUS_SUB_DATE']; ?></td>
                                                        <td><?php echo $rec_sub_1['PRJP_STATUS_APPV_DATE']; ?></td>
                                                    <?php } elseif ($rec_sub_1['PRJP_STATUS_SUB_ID'] == 2) { ?>
                                                        <td><?php echo $rec_sub_1['PRJP_STATUS_SUB_DATE']; ?></td>
                                                        <td><?php echo $rec_sub_1['PRJP_STATUS_APPV_DATE']; ?>
                                                            <table style="border:none;">
                                                                <tr style="border:none;">
                                                                    <td nowrap style="border:none;">รหัสแผนจัดจ้าง</td>
                                                                </tr>
                                                                <tr style="border:none;">
                                                                    <td style="border:none;"><?php echo $rec_sub_1['DESC']; ?></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    <?php } else { ?>
                                                        <td><?php echo $rec_sub_1['PRJP_STATUS_SUB_DATE']; ?></td>
                                                <?php }
                                                }  ?>
                                                <td>
                                                    <table style="border:none;">
                                                        <tbody style="border:none;">
                                                            <tr style="border:none;">
                                                                <td nowrap style="border:none;">วงเงินตามสัญญา</td>
                                                            </tr>
                                                            <tr style="border:none;">
                                                                <td style="border:none;">
                                                                    <?php echo $rec_d1['PRJP_STATUS_DETAIL_M_CONTRACT_']; ?>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td><?php echo text($rec_d1['PRJP_SUP_RESULT']); ?></td>
                                                <td><?php echo $rec_d1['PRJP_STATUS_DETAIL_DATE_RESULT']; ?></td>
                                                <td><?php echo text($rec_d1['PRJP_NOTE_RESULT']); ?></td>
                                                <td nowrap>
                                                    <a class="btn btn-success" data-toggle="modal" onclick="editResult(<?php echo $rec_d1['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>);"><i class="fa fa-edit" aria-hidden="true"></i> นำเข้าผล</a>
                                                    <!-- <a class="btn btn-danger" onclick="delData(<?php echo $rec_d1['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>);"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a> -->
                                                </td>
                                            </tr>
                                        <?php
                                            $i++;
                                        }
                                    } else { ?>
                                        <tr>
                                            <td colspan="<?php echo $rows_status_1 + 13; ?>" align="center">ไม่มีข้อมูล</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>


                            <br />
                            <div class="row">
                                <div class="col-xs-12 col-sm-1">
                                    <?php if ($_SESSION['sys_status_add'] == '1') { ?>
                                        <a class="btn btn-success" data-toggle="modal" onclick="addData2();"> <i class="fa fa-plus-circle" aria-hidden="true"></i> เพิ่มกระบวนการคัดเลือกหน่วยร่วม</a>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <div class="">
                                    <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                        <thead>
                                            <tr class="bgHead">
                                                <th width="1%" rowspan="2" nowrap>
                                                    <div align="center"><strong>ลำดับ</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>กิจกรรม สสว.100</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>ชื่อกิจกรรม/สัญญา ที่จะจัดซื้อจัดจ้างปีงบประมาณ <?php echo $_SESSION['year_round']; ?></strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>แผน/ผล</strong></div>
                                                </th>
                                                <th width="2%" rowspan="1" colspan="<?php echo $rows_status_2; ?>" nowrap>
                                                    <div align="center"><strong>สถานะกระบวนการคัดเลือกหน่วยร่วมดำเนินงาน/ข้อตกลงความร่วมมือ (โปรดระบุ สถานะ และวันที่จนถึงปัจจุบัน)</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>งบประมาณ (ล้านบาท) (วงเงินตามสัญญาจำแนกตามหน่วยร่วม)</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>ชื่อหน่วยร่วม</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>ลงนามในสัญญาร่วม/MOU</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>หมายเหตุ</strong></div>
                                                </th>
                                                <th width="5%" rowspan="2" nowrap>
                                                    <div align="center"><strong>จัดการ</strong></div>
                                                </th>
                                            </tr>
                                            <tr>
                                                <?php foreach ($arr_status_2 as $key => $val) { ?>
                                                    <th rowspan="" align="center">
                                                        <div><?php echo $val; ?></div>
                                                    </th>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td colspan="4" align="center">พ.ร.บ.สสว. มาตราการใช้จ่ายเงินกองทุน</td>
                                                <?php for ($i = 1; $i <= 8; $i++) {
                                                    echo "<td align='center'>34(2)</td>";
                                                }
                                                ?>
                                                <td></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tbody class="body">
                                            <?php
                                            if ($row_sd_2 > 0) {
                                                $i = 1;
                                                while ($rec_d2 = $db->db_fetch_array($query_status_detail_2)) {
                                                    // echo text($rec_d2['PRJP_STATUS_DETAIL_M_CONTRACT_BDG']);
                                            ?>
                                                    <tr>
                                                        <td rowspan="2"><?php echo $i; ?></td>
                                                        <td rowspan="2"></td>
                                                        <td rowspan="2"><?php echo text($rec_d2['PRJP_STATUS_DETAIL_NAME']); ?></td>
                                                        <th>แผน</th>
                                                        <?php
                                                        $sql_status_sub_detail_2 = "SELECT a.*,
                                                        b.PRJP_STATUS_SUB_DESC as 'DESC'
                                                        FROM prjp_status_sub_detail a
                                                        LEFT JOIN prjp_status_sub_desc b on a.PRJP_STATUS_SUB_ID = b.PRJP_STATUS_SUB_ID AND a.PRJP_STATUS_DETAIL_TYPE = b.PRJP_STATUS_DETAIL_TYPE
                                                        AND a.PRJP_STATUS_DETAIL_ID = b.PRJP_STATUS_DETAIL_ID WHERE a.PRJP_STATUS_DETAIL_ID = '" . $rec_d2['PRJP_STATUS_DETAIL_ID'] . "' AND a.PRJP_STATUS_DETAIL_TYPE = 1";
                                                        $query_status_sub_detail_2 = $db->query($sql_status_sub_detail_2);
                                                        while ($rec_sub_2 = $db->db_fetch_array($query_status_sub_detail_2)) {
                                                            if ($rec_sub_2['PRJP_STATUS_SUB_ID'] == 11) {
                                                        ?>
                                                                <td style="background-color: #eeeeee;"></td>
                                                            <?php } else { ?>
                                                                <td><?php echo $rec_sub_2['PRJP_STATUS_SUB_DATE']; ?></td>
                                                        <?php }
                                                        } ?>
                                                        <td>
                                                            <table style="border:none;">
                                                                <tbody style="border:none;">
                                                                    <tr style="border:none;">
                                                                        <td nowrap style="border:none;">วงเงินตามแผนจัดจ้าง</td>
                                                                    </tr>
                                                                    <tr style="border:none;">
                                                                        <td style="border:none;">
                                                                            <?php echo $rec_d2['PRJP_STATUS_DETAIL_M_BDG']; ?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td><?php echo text($rec_d2['PRJP_SUP_NAME']); ?></td>
                                                        <td><?php echo $rec_d2['PRJP_STATUS_DETAIL_DATE_CONTRA']; ?></td>
                                                        <td><?php echo text($rec_d2['PRJP_NOTE']); ?></td>
                                                        <td nowrap>
                                                            <a class="btn btn-warning" data-toggle="modal" onclick="editData2(<?php echo $rec_d2['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>);"><i class="fa fa-edit" aria-hidden="true"></i> แก้ไข</a>
                                                            <a class="btn btn-danger" onclick="delData2(<?php echo $rec_d2['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>);"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>ผล</th>
                                                        <?php
                                                        $sql_status_res_detail_2 = "SELECT a.*,
                                                         b.PRJP_STATUS_SUB_DESC as 'DESC'
                                                         FROM prjp_status_sub_detail a
                                                         LEFT JOIN prjp_status_sub_desc b on a.PRJP_STATUS_SUB_ID = b.PRJP_STATUS_SUB_ID AND a.PRJP_STATUS_DETAIL_TYPE = b.PRJP_STATUS_DETAIL_TYPE
                                                         AND a.PRJP_STATUS_DETAIL_ID = b.PRJP_STATUS_DETAIL_ID WHERE a.PRJP_STATUS_DETAIL_ID = '" . $rec_d2['PRJP_STATUS_DETAIL_ID'] . "' AND a.PRJP_STATUS_DETAIL_TYPE = 2";
                                                        $query_status_res_detail_2 = $db->query($sql_status_res_detail_2);
                                                        while ($rec_sub_2 = $db->db_fetch_array($query_status_res_detail_2)) {
                                                            if ($rec_sub_2['PRJP_STATUS_SUB_ID'] == 11) {
                                                        ?>
                                                                <td>
                                                                    <table style="border:none;">
                                                                        <tbody style="border:none;">
                                                                            <tr style="border:none;">
                                                                                <td nowrap style="border:none;">ว/ด/ป คทง. คัดเลือกประชุมครั้งแรก</td>
                                                                            </tr>
                                                                            <tr style="border:none;">
                                                                                <td style="border:none;">
                                                                                <?php echo $rec_sub_2['PRJP_STATUS_SUB_DATE']; ?>
                                                                                </td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            <?php
                                                            } else {
                                                            ?>
                                                                <td><?php echo $rec_sub_2['PRJP_STATUS_SUB_DATE']; ?></td>
                                                        <?php
                                                            }
                                                        } ?>
                                                        <td>
                                                            <table style="border:none;">
                                                                <tbody style="border:none;">
                                                                    <tr style="border:none;">
                                                                        <td nowrap style="border:none;">วงเงินตามแผนจัดจ้าง</td>
                                                                    </tr>
                                                                    <tr style="border:none;">
                                                                        <td style="border:none;">
                                                                            <?php echo $rec_d2['PRJP_STATUS_DETAIL_M_CONTRACT_']; ?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                        <td><?php echo text($rec_d2['PRJP_SUP_RESULT']); ?></td>
                                                        <td><?php echo $rec_d2['PRJP_STATUS_DETAIL_DATE_RESULT']; ?></td>
                                                        <td><?php echo text($rec_d2['PRJP_NOTE_RESULT']); ?></td>
                                                        <td nowrap>
                                                            <a class="btn btn-success" data-toggle="modal" onclick="editResult2(<?php echo $rec_d2['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>);"><i class="fa fa-edit" aria-hidden="true"></i> นำเข้าผล</a>
                                                            <!-- <a class="btn btn-danger" onclick="delData2(<?php echo $rec_d2['PRJP_STATUS_DETAIL_ID']; ?>,<?php echo $PRJP_ID; ?>);"><i class="fa fa-trash" aria-hidden="true"></i> ลบ</a> -->
                                                        </td>
                                                    </tr>
                                                <?php
                                                    $i++;
                                                }
                                            } else { ?>
                                                <tr>
                                                    <td colspan="15" align="center">ไม่มีข้อมูล</td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                    <h4 class="modal-title" id="modal_contract_label"><i class="fa fa-edit" aria-hidden="true"></i> เพิ่มสถานะกระบวนการจัดซื้อจัดจ้าง</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <form id="frm-contract" method="post" action="process/disp_project_status_process.php" enctype="multipart/form-data">
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