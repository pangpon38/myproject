<?php
session_start();
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";

$Arr_rec_type = array(1 => 'ค่าสมัคร', 2 => 'ค่าอื่นๆ', 3 => 'รับทั่วไป');



$filter == "";
if ($rec_code != "") {
    $filter .= " AND rec_code like '%" . ctext($rec_code) . "%' ";
}
if ($pay_fname != "") {
    $filter .= " AND pay_fname like '%" . ctext($pay_fname) . "%' ";
}
if ($pay_lname != "") {
    $filter .= " AND pay_lname like '%" . ctext($pay_lname) . "$' ";
}
if ($call_type != "") {
    $filter .= " AND call_type like '" . $call_type . "' ";
}

if ($MEMBER_NO != "") {
    $filter .= " AND M_MEMBER.member_no+0 = '" . (str_replace($arr_order, "", $_POST['MEMBER_NO']) + 0) . "' ";
}
if ($FNAME != "") {
    $filter .= " AND(
	  M_MEMBER.fname like '%" . ctext($_POST['FNAME']) . "%'
	 OR   F_RECEIPT.pay_fname like '%" . ctext($_POST['FNAME']) . "%'   )

	 "; //อาจจะต้องใส่ textหรือctextด้วย ดูที่ตอนบันทึกในprocess
}
if ($LNAME != "") {
    $filter .= " AND (  M_MEMBER.lname like '%" . ctext($_POST['LNAME']) . "%'
				or  F_RECEIPT.pay_lname like '%" . ctext($_POST['LNAME']) . "%' )
	";
}
if ($AUT_USER_ID != "") {
    $filter .= " AND   F_RECEIPT.AUT_USER_ID = '" . ($_POST['AUT_USER_ID']) . "' ";
}
if ($_POST['s_date'] != "" && $_POST['e_date'] != "") {
    $filter .= " and date_receipt BETWEEN '" . conv_date_db($_POST['s_date']) . "' AND '" . conv_date_db($_POST['e_date']) . "'";
} elseif ($_POST['s_date'] != "") {
    $filter .= " and ( date_receipt = '" . conv_date_db($_POST['s_date']) . "'  )";
} elseif ($_POST['e_date'] != "") {
    $filter .= " and ( date_receipt = '" . conv_date_db($_POST['e_date']) . "' )";
}
if ($S_PAY_MONEY != "") {
    $filter .= " AND   F_RECEIPT.money_sum >= '" . ($_POST['S_PAY_MONEY']) . "' ";
}

if ($E_PAY_MONEY != "") {
    $filter .= " AND   F_RECEIPT.money_sum <= '" . ($_POST['E_PAY_MONEY']) . "' ";
}
if ($_POST['BAAC_AUMPHUR'] != "") {
    $filter .= " AND M_MEMBER.BAAC_AUMPHUR = '" . ($_POST['BAAC_AUMPHUR']) . "' ";
}


if ($BAAC_GROUP_START) {
    $filter .= " AND M_MEMBER.BAAC_GROUP+0 = '" . $_POST['BAAC_GROUP_START'] . "' ";
}

$field = " F_RECEIPT.* ";
$table = "F_RECEIPT
left join M_MEMBER  ON  M_MEMBER.member_id  =   F_RECEIPT.member_id
";
$pk_id = "rec_id";
$wh = "1=1  $filter";
$orderby = "ORDER BY rec_id DESC ";

$sql = "SELECT  " . $field . " FROM " . $table . " WHERE " . $wh . " " . $orderby;

$notin = $wh . " and " . $pk_id . " not in (select top $goto " . $pk_id . " from " . $table . " where " . $wh . " " . $orderby . ") " . $orderby;
$sql = "select top {$page_size} " . $field . " from " . $table . " where " . $notin;
$total_record = $db->db_num_rows($db->query("SELECT " . $pk_id . " FROM " . $table . " WHERE " . $wh . " " . $orderby));

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="language" content="en" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo  $sys_title_web; ?></title>
    <link href="<?php echo $path; ?>css/design.css" rel="stylesheet">
    <link href="<?php echo $path; ?>css/main.css" rel="stylesheet">
    <link href="<?php echo $path; ?>bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $path; ?>bootstrap/css/bootstrap-theme.css" rel="stylesheet">
    <link href="<?php echo $path; ?>bootstrap/css/bootstrap-modal.css" rel="stylesheet">
    <link href="<?php echo $path; ?>images/splashy/splashy.css" rel="stylesheet">
    <link href="<?php echo $path; ?>bootstrap/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="<?php echo $path; ?>bootstrap/css/chosen.css" rel="stylesheet">
    <script src="<?php echo $path; ?>bootstrap/js/jquery.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/transition.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/holder.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/collapse.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/dropdown.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/modal.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/carousel.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/respond.min.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/html5shiv.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/bootstrap-datepicker.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/chosen.jquery.js"></script>
    <script src="<?php echo $path; ?>bootstrap/js/inputmask.js"></script>
    <script src="<?php echo $path; ?>js/func.js"></script>
    <script src="js/payment2_disp.js?<?php echo rand(); ?>"></script>
</head>

<body>
    <div class="container-full">
        <div><?php include($path . "include/header.php"); ?></div>
        <div><?php include($path . "include/menu.php"); ?></div>
        <div class="col-xs-12 col-sm-12">
            <ol class="breadcrumb">
                <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
                <li class="active">รายการใบเสร็จรับเงิน</li>
            </ol>
        </div>

        <div class="col-xs-12 col-sm-12">
            <div class="groupdata">
                <form id="frm-search" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
                    <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                    <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                    <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                    <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                    <input name="rec_id" type="hidden" id="rec_id" value="<?php echo $rec_id; ?>">
                    <input name="member_id" type="hidden" id="member_id" value="<?php echo $member_id; ?>">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12" style="white-space:nowrap;text-align:right;">
                            <input type="hidden" id="hide_show" name="hide_show" value="<?php echo $_POST['hide_show']; ?>">
                            <a href="#" class="hide_search" style="color:#000;"><?php echo $img_down; ?></a>
                        </div>
                    </div>
                    <fieldset id="fie_search">
                        <legend>ค้นหา</legend>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap; padding-left:4px;">ประเภทการชำระ :</div>
                            <div class="col-xs-12 col-sm-2">
                                <select name="call_type" id="call_type" class="selectbox form-control" placeholder="ช่องทางการชำระเงิน">
                                    <option value=""></option>
                                    <option value="1" <?php if ($_POST['call_type'] == '1') {
                                                            echo "selected";
                                                        } ?>>เงินสด</option>
                                    <option value="2" <?php if ($_POST['call_type'] == '2') {
                                                            echo "selected";
                                                        } ?>> ผ่านเคาเตอร์ธนาคาร</option>
                                    <option value="3" <?php if ($_POST['call_type'] == '3') {
                                                            echo "selected";
                                                        } ?>> โอน</option>
                                </select>
                            </div>

                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">เลขที่ใบเสร็จ :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='rec_code' name="rec_code" value="<?php echo $_POST["rec_code"]; ?>" class="form-control" placeholder="เลขที่ใบเสร็จ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">วันที่</div>
                            <div class="col-xs-12 col-sm-2">
                                <div class="input-group">
                                    <input type="text" id="s_date" name="s_date" class="form-control" onBlur="chk_date_greater('s_date','e_date',1);" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo $_POST["s_date"]; ?>">
                                    <span class="input-group-addon datepicker" for="s_date">&nbsp;
                                        <span class="glyphicon glyphicon-calendar"></span>&nbsp;
                                    </span>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-1">
                                <div align="center">-</div>
                            </div>
                            <div class="col-xs-12 col-sm-2">
                                <div class="input-group">
                                    <input type="text" id="e_date" name="e_date" class="form-control" onBlur="chk_date_greater('s_date','e_date',1);" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo $_POST["e_date"]; ?>">
                                    <span class="input-group-addon datepicker" for="e_date">&nbsp;
                                        <span class="glyphicon glyphicon-calendar"></span>&nbsp;
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">เลขที่สมาชิก :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='MEMBER_NO' name="MEMBER_NO" value="<?php echo $_POST["MEMBER_NO"]; ?>" class="form-control" placeholder="เลขที่สมาชิก">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">ชื่อ :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='FNAME' name="FNAME" value="<?php echo $_POST["FNAME"]; ?>" class="form-control" placeholder="ชื่อ">
                            </div>

                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">นามสกุล :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='LNAME' name="LNAME" value="<?php echo $_POST["LNAME"]; ?>" class="form-control" placeholder="นามสกุล">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">รหัสอำเภอ :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='BAAC_AUMPHUR' placeholder="รหัสอำเภอ" name="BAAC_AUMPHUR" value="<?php echo $_POST["BAAC_AUMPHUR"]; ?>" class="form-control">
                            </div>

                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">กลุ่ม :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='BAAC_GROUP_START' placeholder="กลุ่ม" name="BAAC_GROUP_START" value="<?php echo $_POST["BAAC_GROUP_START"]; ?>" class="form-control">
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">จำนวนที่ชำระ :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='S_PAY_MONEY' name="S_PAY_MONEY" value="<?php echo $_POST["S_PAY_MONEY"]; ?>" class="form-control" placeholder="จำนวนที่ชำระ">
                            </div>

                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">ถึง :</div>
                            <div class="col-xs-12 col-sm-2">
                                <input type="text" id='E_PAY_MONEY' name="E_PAY_MONEY" value="<?php echo $_POST["E_PAY_MONEY"]; ?>" class="form-control" placeholder="จำนวนที่ชำระ">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-3"></div>
                            <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">ผู้ปฏิบัติงาน :</div>
                            <div class="col-xs-12 col-sm-2">
                                <select name="AUT_USER_ID" id="AUT_USER_ID" class="selectbox form-control" placeholder="ผู้ปฏิบัติงาน">
                                    <option value=""></option>
                                    <?php
                                    $sql_aut = "select AUT_USER_ID,FNAME , LNAME  from  AUT_USER where  DELETE_FLAG = 0  ";
                                    $query_aut = $db->query($sql_aut);
                                    while ($rec_aut  = $db->db_fetch_array($query_aut)) {
                                    ?>
                                        <option value="<?php echo $rec_aut['AUT_USER_ID'] ?>" <?php if ($_POST['AUT_USER_ID'] == $rec_aut['AUT_USER_ID']) {
                                                                                                    echo "selected";
                                                                                                } ?>><?php echo text($rec_aut['FNAME'] . ' ' . $rec_aut['LNAME']); ?></option>
                                    <?php  } ?>

                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="row" align="center">
                                <div class="col-xs-12 col-sm-12 col-md-12"><button type="button" class="btn btn-primary" onClick="searchData();">ค้นหา</button></div>
                            </div>
                        </div>
                    </fieldset>
                    <div class="col-xs-12 col-sm-12">
                        <div class="table-responsive">
                            <table width="47%" class="table table-bordered table-striped table-hover table-condensed">
                                <thead>
                                    <tr class="bgHead">
                                        <th width="6%">
                                            <div align="center"><strong>ลำดับที่</strong></div>
                                        </th>
                                        <th width="6%">
                                            <div align="center"><input type="checkbox" name="CheckAll" id="CheckAll" onClick="ClickCheckAll();"></div>
                                        </th>
                                        <th width="18%">
                                            <div align="center">เลขที่ใบเสร็จรับเงิน</div>
                                        </th>
                                        <th width="17%">
                                            <div align="center"><strong>วันที่</strong></div>
                                        </th>
                                        <th width="12%">
                                            <div align="center"><strong>ประเภท</strong></div>
                                        </th>
                                        <th width="15%">
                                            <div align="center"><strong>ชื่อ-สกุล สมาชิก</strong></div>
                                        </th>
                                        <th width="9%">
                                            <div align="center"><strong>จำนวนเงิน</strong></div>
                                        </th>
                                        <th width="17%">
                                            <div align="center"><strong>การจัดการ</strong></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $query = $db->query($sql);
                                    $nums = $db->db_num_rows($query);
                                    if ($nums > 0) {
                                        $i = 1;
                                        $query = $db->query($sql);
                                        while ($rec = $db->db_fetch_array($query)) {


                                            $edit = $del = null;
                                            if ($rec['money_send_id'] == 0 || $rec['money_send_id'] == "") {
                                                $edit = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"editData('" . $rec['rec_id'] . "');\">" . $img_edit . " แก้ไข</a> ";
                                                $del = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"delData('" . $rec['rec_id'] . "');\">" . $img_del . " ลบ</a> ";
                                            }
                                            $print = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"printData('" . $rec['rec_id'] . "');\" >" . $img_print . " พิมพ์</a> ";
                                            if ($rec['status_use']  != 0  && $rec['count_rec'] == 0) {
                                                $cancel = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"CancelData('" . $rec['rec_id'] . "');\">" . $img_del . " ยกเลิก</a> ";
                                            } else {
                                                $cancel = " ";
                                            }

                                            if ($rec['status_use']  == 0) {
                                                $cancel_text = " ( ยกเลิก )";
                                            } else {
                                                $cancel_text = "";
                                            }
                                            if ($rec['rec_group'] == 1) {
                                                $view = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"viewDataGroup('" . $rec['rec_id'] . "');\">" . $img_edit . " รายละเอียด</a> ";
                                            } else {
                                                if ($rec['rec_type'] == 3) {
                                                    $view = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"viewDataOther('" . $rec['rec_id'] . "');\">" . $img_edit . " รายละเอียด</a> ";
                                                } else {
                                                    $view = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"viewData('" . $rec['rec_id'] . "');\">" . $img_edit . " รายละเอียด</a> ";
                                                }
                                            }
                                            if ($rec['member_id']) {
                                                $book_detail = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"BookDetail('" . $rec['member_id'] . "');\">" . $img_view . " พิมพ์สมุด</a> ";
                                            } else {
                                                $book_detail = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\"  disabled >" . $img_view . " พิมพ์สมุด</a> ";
                                            }
                                    ?>
                                            <tr bgcolor="#FFFFFF">
                                                <td align="center"><?php echo $i + $goto ?></td>
                                                <td align="center">
                                                    <?php if ($rec['approve_print'] == 1) { ?>
                                                        <input type="checkbox" name="chk_rec_id[]" id="chk_rec_id__<?php echo $i ?>" value="<?php echo $rec['rec_id']; ?>">
                                                    <?php } ?>
                                                    <label for="checkbox"></label>
                                                </td>
                                                <td align="center"><?php echo text($rec['rec_code']) . "<span style='color:#FF0000';>" . $cancel_text . "</span>"; ?></td>
                                                <td align="center"><?php echo conv_date($rec['date_receipt']); ?></td>
                                                <td align="center"><?php echo $Arr_rec_type[$rec['rec_type']]; ?></td>
                                                <td align="left"><?php echo text($rec['pay_prefix_name']) . text($rec['pay_fname']) . ' ' . text($rec['pay_lname']); ?></td>
                                                <td align="right"><?php echo number_format($rec['money_sum'], 2); ?></td>
                                                <td align="center"><?php //echo $edit.$print.$del.$cancel;
                                                                    echo $view . "&nbsp;&nbsp;" . $book_detail;
                                                                    ?></td>
                                            </tr>
                                    <?php
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr bgcolor=\"#FFFFFF\"><td align=\"center\" colspan=\"8\">ไม่พบข้อมูล</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-10">
                            <?php

                            echo $print = "<a data-toggle=\"modal\" class=\"btn btn-default btn\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"printData('" . $rec['rec_temp_id'] . "');\" >" . $img_print . " พิมพ์</a> ";
                            echo $print_form = "<a data-toggle=\"modal\" class=\"btn btn-default btn\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"printDataForm('" . $rec['rec_temp_id'] . "');\" >" . $img_print . " พิมพ์ฟอร์ม</a> ";

                            ?>
                        </div>
                    </div>
                    <br>
                    <div class="col-xs-12 col-sm-12">
                        <div class="table-responsive">

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php echo endPaging("frm-search", $total_record); ?>
                    <div class="clearfix"></div>
                </form>
            </div>
        </div>
        <div style="text-align:center; bottom:0px;">
            <?php include($path . "include/footer.php"); ?>
        </div>
    </div>
</body>

</html>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>

<script language="javascript">
    $(document).ready(function() {
        <?php

        if ($_POST['print_form'] == 1) { ?>
            printData();
        <?php $_POST['print_form'] = '';
        } ?>
    });
</script>
<!-- /.modal -->