<?php
session_start();
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$path_a = '../fileupload/file_member_all/';

$sql = "SELECT
			member_id,
			prefix_id,
			fname,
			lname,
			m_cate_id,
			member_no,
			mobile,
			pay_code
		FROM
			M_MEMBER
		WHERE
			member_id = '" . $_POST['member_id'] . "'";
$query = $db->query($sql);
$rec = $db->db_fetch_array($query);



$sql_chg = "SELECT * FROM M_CHG_PROFILE WHERE chg_id= '" . $_POST['chg_id'] . "'";
$query_chg = $db->query($sql_chg);
$rec_chg = $db->db_fetch_array($query_chg);

$sql1 = "SELECT * FROM M_CHG_NAME WHERE chg_id= '" . $_POST['chg_id'] . "'";
$query1 = $db->query($sql1);
$nums1 = $db->db_num_rows($query1);
$rec1 = $db->db_fetch_array($query1);

$sql2 = "SELECT * FROM M_CHG_ADDS WHERE chg_id= '" . $_POST['chg_id'] . "'";
$query2 = $db->query($sql2);
$nums2 = $db->db_num_rows($query2);
$rec2 = $db->db_fetch_array($query2);

$sql3 = "SELECT * FROM M_CHG_MARRY WHERE chg_id= '" . $_POST['chg_id'] . "'";
$query3 = $db->query($sql3);
$nums3 = $db->db_num_rows($query3);
$rec3 = $db->db_fetch_array($query3);

$sql5 = "SELECT * FROM M_CHG_BANK WHERE chg_id= '" . $_POST['chg_id'] . "'";
$query5 = $db->query($sql5);
$nums5 = $db->db_num_rows($query5);
$rec5 = $db->db_fetch_array($query5);

$sql6 = "SELECT * FROM M_CHG_OTHER WHERE chg_id= '" . $_POST['chg_id'] . "'";
$query6 = $db->query($sql6);
$nums6 = $db->db_num_rows($query6);
$rec6 = $db->db_fetch_array($query6);

$disabled = 'disabled';
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
    <script src="js/member_change_approve_disp.js?<?php echo rand(); ?>"></script>

</head>

<body>

    <div class="container-full">
        <div><?php include($path . "include/header.php"); ?></div>
        <div><?php include($path . "include/menu.php"); ?></div>
        <div>
            <div class="col-xs-12 col-sm-12">
                <ol class="breadcrumb">
                    <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
                    <li><a href="member_change_approve_disp.php?<?php echo $paramlink; ?>"><?php echo showMenu($menu_sub_id); ?></a></li>
                    <li class="active">บันทึกการอนุมัติ</li>
                </ol>
            </div>
            <div class="col-xs-12 col-sm-12">
                <div style="background-color:#FFF; border:thin solid #cbc9c8; padding:5px; border-radius:10px; width:auto">
                    <div class="clearfix"></div>
                    <div class="clearfix"></div>
                    <form id="frm-search" name="frm-search" action="" method="post" enctype="multipart/form-data">
                        <input name="proc" type="hidden" id="proc" value="<?php echo $proc ?>">
                        <input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
                        <input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
                        <input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
                        <input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
                        <input type="hidden" id="member_id" name="member_id" value="<?php echo $member_id; ?>">
                        <input type="hidden" id="chg_id" name="chg_id" value="<?php echo $_POST['chg_id']; ?>">
                        <br>
                        <div class="row">
                            <div class="col-xs-12 col-sm-2" style="white-space:nowrap;">วันที่เปลี่ยนแปลง</div>
                            <div class="col-xs-12 col-sm-2"><?php echo conv_date($rec_chg['chg_date']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-2" style="white-space:nowrap;">ชื่อ</div>
                            <div class="col-xs-12 col-sm-2"><?php echo text($arr_prefix_chapa[$rec['prefix_id']]) . text($rec['fname']) . " " . text($rec['lname']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-2" style="white-space:nowrap;">ประเภทสมาชิก</div>
                            <div class="col-xs-12 col-sm-2"><?php echo ($rec['m_cate_id']) ? $arr_type_member_chapa[$rec['m_cate_id']] : "-"; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-2" style="white-space:nowrap;">เลขที่สมาชิก</div>
                            <div class="col-xs-12 col-sm-2"><?php echo ($rec['member_no']) ? text($rec['member_no']) : "-"; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-2" style="white-space:nowrap;">เบอร์โทรศัพท์มือถือ</div>
                            <div class="col-xs-12 col-sm-2"><?php echo ($rec['mobile']) ? text($rec['mobile']) : "-"; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12" style="white-space:nowrap;">ข้าพเจ้าขอเปลี่ยนแปลงข้อความในใบสมัครสมาชิก ฌกส. ซึ่งได้ยื่นไว้แล้ว ในรายการดังต่อไปนี้</div>
                        </div>
                        <fieldset>
                            <?php if ($rec_chg['chg_code'] == 1) { ?>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3">1.เปลี่ยนแปลง ชื่อ - นามสกุล</div>
                                    <div class="col-xs-12 col-sm-8"></div>
                                    <?php
                                    if ($nums1 > '0') {
                                    ?>
                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-sm-2" style="white-space:nowrap;" align="right">เลขประจำตัวประชาชน</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" id="id_card_new" name="id_card_new" value="<?php echo $rec1['id_card_new']; ?>" class="form-control idcard" placeholder="เลขประจำตัวประชาชน" maxlength="13" onBlur="checkID(this.id);" <?php echo $disabled; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">คำนำหน้าชื่อ</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <?php
                                        $sql_prefix = "SELECT prefix_id,prefix_name FROM PREFIX ORDER BY prefix_id ";
                                        $query_prefix = $db->query($sql_prefix);
                                        $sel_prefix[$rec1["prefix_id_new"]]  = "selected";
                                        ?>

                                        <select id="prefix_id_new" name="prefix_id_new" class="form-control" placeholder="คำนำหน้าชื่อ" <?php echo $disabled; ?>>
                                            <option value=""></option>
                                            <?php while ($rec_prefix = $db->db_fetch_array($query_prefix)) { ?>
                                                <option value="<?php echo $rec_prefix['prefix_id']; ?>" <?php echo $sel_prefix[$rec_prefix["prefix_id"]]; ?>><?php echo text($rec_prefix['prefix_name']); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-xs-12 col-sm-7"></div>
                                    <!--<div class="col-xs-12 col-sm-1"><input name="approve1" type="radio" value="2" <?php if ($rec1['approve_status'] == '2') {
                                                                                                                            echo "checked";
                                                                                                                        } ?>> ไม่อนุมัติ</div>-->
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">ชื่อ</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" id="fname_new" name="fname_new" value="<?php echo text($rec1['fname_new']); ?>" class="form-control" placeholder="ชื่อ" disabled>
                                    </div>
                                    <div class="col-xs-12 col-sm-1" align="right">นามสกุล</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" id="lname_new" name="lname_new" value="<?php echo text($rec1['lname_new']); ?>" class="form-control" placeholder="นามสกุล" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">วันเดือนปีเกิด</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <div class="input-group">
                                            <input type="hidden" name="birth_date_old" id="birth_date_old" value="<?php echo conv_date($rec1["birth_date_old"]); ?>">
                                            <input type="text" id="birth_date_new" name="birth_date_new" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo conv_date($rec1["birth_date_new"]); ?>" <?php echo $disabled; ?>>
                                            <span class="input-group-addon datepicker" for="birth_date_new">&nbsp;
                                                <span class="glyphicon glyphicon-calendar"></span>&nbsp;
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php } else {
                                        echo  "<br><center>ไม่มีข้อมูล&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center>";
                                    } ?>
                        </fieldset>

                        <fieldset>
                            <div class="row">
                                <div class="col-xs-12 col-sm-3">2.เปลี่ยนแปลงที่อยู่อาศัย</div>
                                <div class="col-xs-12 col-sm-8"></div>
                                <?php
                                if ($nums2 > '0') {
                                ?>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">บ้านเลขที่</div>
                                <div class="col-xs-12 col-sm-1">
                                    <input type="text" id="home_no_new" name="home_no_new" value="<?php echo text($rec2['home_no_new']); ?>" class="form-control" placeholder="บ้านเลขที่" disabled>
                                </div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">หมู่บ้าน</div>
                                <div class="col-xs-12 col-sm-2">
                                    <input type="text" name="moo_no_new" id="moo_no_new" value="<?php echo text($rec2["moo_no_new"]); ?>" class="form-control" placeholder="หมู่" disabled>
                                </div>
                                <div class="col-xs-12 col-sm-5"></div>
                                <!--<div class="col-xs-12 col-sm-1"><input name="approve2" type="radio" value="2" <?php if ($rec2['approve_status'] == '2') {
                                                                                                                        echo "checked";
                                                                                                                    } ?>> ไม่อนุมัติ</div>-->
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">ซอย</div>
                                <div class="col-xs-12 col-sm-2">
                                    <input type="text" id="soi_name_new" name="soi_name_new" value="<?php echo text($rec2['soi_name_new']); ?>" class="form-control" placeholder="ซอย" disabled>
                                </div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">ถนน</div>
                                <div class="col-xs-12 col-sm-2">
                                    <input type="text" name="road_name_new" id="road_name_new" value="<?php echo text($rec2["road_name_new"]); ?>" class="form-control" placeholder="ถนน" disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">จังหวัด</div>
                                <div class="col-xs-12 col-sm-2">
                                    <?php
                                    $sql_province = "SELECT PROVINCE_CODE,PROVINCE_NAME FROM PROVINCE ORDER BY PROVINCE_NAME ASC";
                                    $query_province = $db->query($sql_province);
                                    $sel_province[$rec2["prov_id_new"]]  = "selected";
                                    ?>
                                    <select name="PROVINCE_CODE" id="PROVINCE_CODE" class=" form-control" placeholder="จังหวัด" onChange="dochange('amphur', this.value)" disabled>
                                        <option value=""></option>
                                        <?php while ($rec_province = $db->db_fetch_array($query_province)) { ?>
                                            <option value="<?php echo $rec_province['PROVINCE_CODE']; ?>" <?php echo $sel_province[$rec_province['PROVINCE_CODE']]; ?>><?php echo text($rec_province['PROVINCE_NAME']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">อำเภอ</div>
                                <div class="col-xs-12 col-sm-2">
                                    <span id="amphur">
                                        <select name="AMPHUR_CODE" id="AMPHUR_CODE" class=" form-control" placeholder="อำเภอ" disabled>
                                            <?php
                                            $sql_amphur = "SELECT AMPHUR_CODE,AMPHUR_NAME FROM AMPHUR
                                    WHERE  PROVINCE_CODE ='" . $rec2["prov_id_new"] . "'
                                    ORDER BY AMPHUR_CODE ASC";
                                            $query_amphur = $db->query($sql_amphur);
                                            $sel_amphur[$rec2["amp_id_new"]]  = "selected";
                                            ?>
                                            <option value=""></option>
                                            <?php while ($rec_amphur = $db->db_fetch_array($query_amphur)) { ?>
                                                <option value="<?php echo $rec_amphur['AMPHUR_CODE']; ?>" <?php echo $sel_amphur[$rec_amphur['AMPHUR_CODE']]; ?>><?php echo text($rec_amphur['AMPHUR_NAME']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </span>
                                </div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">แขวง/ตำบล</div>
                                <div class="col-xs-12 col-sm-2">
                                    <span id="district">
                                        <?php
                                        $sql_tambon = "SELECT TAMBON_CODE,TAMBON_NAME FROM TAMBON
                                                WHERE  PROVINCE_CODE ='" . $rec2["prov_id_new"] . "'
                                                AND  AMPHUR_CODE ='" . $rec2["amp_id_new"] . "'
                                                ORDER BY TAMBON_NAME ASC";
                                        $query_tambon = $db->query($sql_tambon);
                                        // $sel_tambon[$rec2["tam_id_new"]]  = "selected";

                                        ?>
                                        <select name="TAMBON_CODE" id="TAMBON_CODE" class="form-control" placeholder="ตำบล" disabled>
                                            <option value=""></option>
                                            <?php while ($rec_tambon = $db->db_fetch_array($query_tambon)) { ?>
                                                <option value="<?php echo $rec_tambon['TAMBON_CODE']; ?>" <?php if ($rec_tambon['TAMBON_CODE'] == $rec2["tam_id_new"]) {
                                                                                                                echo "selected";
                                                                                                            } ?>><?php echo text($rec_tambon['TAMBON_NAME']); ?></option>
                                            <?php  } ?>
                                        </select>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-2" style="white-space:nowrap;" align="right">รหัสไปรษณีย์</div>
                                <div class="col-xs-12 col-sm-2">
                                    <input type="text" id="postcode_new" name="postcode_new" value="<?php echo text($rec2['postcode_new']); ?>" class="form-control" placeholder="รหัสไปรษณีย์" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-1"></div>
                                <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">เบอร์โทรศัพท์ </div>
                                <div class="col-xs-12 col-sm-2">
                                    <input type="text" name="tel_home_new" id="tel_home_new" value="<?php echo text($rec2["tel_home_new"]); ?>" class="form-control" placeholder="เบอร์โทรศัพท์" <?php echo $disabled; ?>>
                                </div>
                                <div class="col-xs-12 col-sm-2 col-md-offset-1" style="white-space:nowrap;">เบอร์โทรศัพท์มือถือ </div>
                                <div class="col-xs-12 col-sm-2">
                                    <input type="text" name="mobile_new" id="mobile_new" value="<?php echo text($rec2["mobile_new"]); ?>" class="form-control" placeholder="เบอร์โทรศัพท์มือถือ" <?php echo $disabled; ?>>
                                </div>
                            </div>

                            <div class="row">
                                <!-- <div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div> -->
                                <div class="col-xs-12 col-sm-2" style="white-space:nowrap;" align="right">ที่อยู่ตามที่ติดต่อได้</div>
                                <div class="col-xs-12 col-sm-2" style="white-space:nowrap;"><input name="chk_address" id="chk_address" type="checkbox" value="1" onClick="chk_address_now()" <?php if ($rec2["chk_address"] == 1) {
                                                                                                                                                                                                    echo "checked";
                                                                                                                                                                                                } ?> <?php echo $disabled ?>> ตามทะเบียนบ้าน</div>
                            </div>
                            <span id="show_adderss2">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right"></div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">บ้านเลขที่</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" name="home_no2_new" id="home_no2_new" value="<?php echo text($rec2["home_no2_new"]); ?>" class="form-control" placeholder="เลขที่" <?php echo $disabled ?>>
                                    </div>
                                    <div class="col-xs-12 col-sm-1 " style="white-space:nowrap;" align="right">หมู่ที่</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" name="moo_no2_new" id="moo_no2_new" value="<?php echo text($rec2["moo_no2_new"]); ?>" class="form-control" placeholder="หมู่ที่" <?php echo $disabled ?>>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-xs-12 col-sm-1"></div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">ตรอก/ซอย</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" name="soi_name2_new" id="soi_name2_new" value="<?php echo text($rec2["soi_name2_new"]); ?>" class="form-control" placeholder="ตรอก/ซอย" <?php echo $disabled ?>>
                                    </div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">ถนน</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" name="road_name2_new" id="road_name2_new" value="<?php echo text($rec2["road_name2_new"]); ?>" class="form-control" placeholder="ถนน" <?php echo $disabled ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-1"></div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">จังหวัด</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <?php
                                        $sql_province = "SELECT PROVINCE_CODE,PROVINCE_NAME FROM PROVINCE ORDER BY PROVINCE_NAME ASC";
                                        $query_province = $db->query($sql_province);
                                        $sel_province2[$rec2["prov_id2_new"]]  = "selected";
                                        ?>
                                        <select name="PROVINCE_CODE2" id="PROVINCE_CODE2" class=" form-control" placeholder="จังหวัด" onChange="dochange2('amphur2', this.value)" <?php echo $disabled ?>>
                                            <option value=""></option>
                                            <?php while ($rec_province = $db->db_fetch_array($query_province)) { ?>
                                                <option value="<?php echo $rec_province['PROVINCE_CODE']; ?>" <?php echo $sel_province2[$rec_province['PROVINCE_CODE']]; ?>><?php echo text($rec_province['PROVINCE_NAME']); ?></option>
                                            <?php } ?>
                                        </select>

                                    </div>
                                    <div class="col-xs-12 col-sm-1 " style="white-space:nowrap;" align="right">เขต/อำเภอ</div>
                                    <div class="col-xs-12 col-sm-2"><span id="amphur2">
                                            <select name="AMPHUR_CODE2" id="AMPHUR_CODE2" class=" form-control" placeholder="อำเภอ" <?php echo $disabled ?>>
                                                <?php
                                                $sql_amphur = "SELECT AMPHUR_CODE,AMPHUR_NAME FROM AMPHUR
								WHERE  PROVINCE_CODE ='" . $rec2["prov_id2_new"] . "'
								ORDER BY AMPHUR_CODE ASC";
                                                $query_amphur = $db->query($sql_amphur);
                                                $sel_amphur2[$rec2["amp_id2_new"]]  = "selected";
                                                ?>
                                                <option value=""></option>
                                                <?php while ($rec_amphur = $db->db_fetch_array($query_amphur)) { ?>
                                                    <option value="<?php echo $rec_amphur['AMPHUR_CODE']; ?>" <?php echo $sel_amphur2[$rec_amphur['AMPHUR_CODE']]; ?>><?php echo text($rec_amphur['AMPHUR_NAME']); ?></option>
                                                <?php } ?>
                                            </select>
                                        </span>
                                    </div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">แขวง/ตำบล</div>
                                    <div class="col-xs-12 col-sm-2"><span id="district2">
                                            <?php
                                            $sql_tambon = "SELECT TAMBON_CODE,TAMBON_NAME FROM TAMBON
								WHERE  PROVINCE_CODE ='" . $rec2["prov_id2_new"] . "'
								AND  AMPHUR_CODE ='" . $rec2["amp_id2_new"] . "'
								ORDER BY TAMBON_NAME ASC";
                                            $query_tambon = $db->query($sql_tambon);
                                            $sel_tambon2[$rec2["tam_id2_new"]]  = "selected";
                                            ?>
                                            <select name="TAMBON_CODE2" id="TAMBON_CODE2" class=" form-control" placeholder="ตำบล" <?php echo $disabled ?>>
                                                <option value=""></option>
                                                <?php while ($rec_tambon = $db->db_fetch_array($query_tambon)) { ?>
                                                    <option value="<?php echo $rec_tambon['TAMBON_CODE']; ?>" <?php echo $sel_tambon2[$rec_tambon['TAMBON_CODE']]; ?>><?php echo text($rec_tambon['TAMBON_NAME']); ?></option>
                                                <?php  } ?>
                                            </select>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-1"></div>
                                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">รหัสไปรษณีย์</div>
                                    <div class="col-xs-12 col-sm-2">
                                        <input type="text" name="postcode2_new" id="postcode2_new" value="<?php echo text($rec2["postcode2_new"]); ?>" class="form-control" placeholder="รหัสไปรษณีย์" maxlength="5" <?php echo $disabled ?>>
                                    </div>
                                </div>
                            </span>
                </div>
            <?php } else {
                                    echo  "<br><center>ไม่มีข้อมูล&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center>";
                                }
            ?>
            </fieldset>
            <fieldset>
                <div class="row">
                    <div class="col-xs-12 col-sm-2">3.เปลี่ยนสถานภาพ</div>
                    <?php
                                if ($nums3 > '0') {
                    ?>
                        <div class="col-xs-12 col-sm-1">
                            <input name="marry_status_new" id="marry_status_new1" type="radio" value="1" <?php if ($rec3['marry_status_new'] == '1') {
                                                                                                                echo "checked";
                                                                                                            } ?> disabled> โสด
                        </div>
                        <div class="col-xs-12 col-sm-1">
                            <input name="marry_status_new" id="marry_status_new2" type="radio" value="2" <?php if ($rec3['marry_status_new'] == '2') {
                                                                                                                echo "checked";
                                                                                                            } ?> disabled> สมรส
                        </div>
                        <div class="col-xs-12 col-sm-1">
                            <input name="marry_status_new" id="marry_status_new3" type="radio" value="3" <?php if ($rec3['marry_status_new'] == '3') {
                                                                                                                echo "checked";
                                                                                                            } ?> disabled> หย่า
                        </div>
                        <div class="col-xs-12 col-sm-1">
                            <input name="marry_status_new" id="marry_status_new4" type="radio" value="4" <?php if ($rec3['marry_status_new'] == '4') {
                                                                                                                echo "checked";
                                                                                                            } ?> disabled>หม้าย
                        </div>

                        <div class="col-xs-12 col-sm-6"></div>
                        <!--	<div class="col-xs-12 col-sm-1"><input name="approve3" type="radio" value="1" <?php if ($rec3['approve_status'] == '1') {
                                                                                                                    echo "checked";
                                                                                                                } ?>> อนุมัติ
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-11"></div>
                            	<div class="col-xs-12 col-sm-1"><input name="approve3" type="radio" value="2" <?php if ($rec3['approve_status'] == '2') {
                                                                                                                    echo "checked";
                                                                                                                } ?>> ไม่อนุมัติ</div>
                            </div>-->
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-1"></div>
                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">ชื่อคู่สมรส </div>
                    <div class="col-xs-12 col-sm-3">
                        <input type="hidden" name="marry_name_old" id="marry_name_old" value="<?php echo text($rec3["marry_name_old"]); ?>">
                        <input type="text" name="marry_name_new" id="marry_name_new" value="<?php echo text($rec3["marry_name_new"]); ?>" class="form-control" placeholder="ชื่อคู่สมรส" <?php echo $disabled; ?>>
                    </div>
                    <div class="col-xs-12 col-sm-1" style="white-space:nowrap;">วันที่จดทะเบียน</div>
                    <div class="col-xs-12 col-sm-2">
                        <div class="input-group">
                            <input type="hidden" name="marry_date_old" id="marry_date_old" value="<?php echo conv_date($rec3["marry_date_old"]); ?>">
                            <input type="text" id="marry_date_new" name="marry_date_new" class="form-control" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo conv_date($rec3["marry_date_new"]); ?>" <?php echo $disabled; ?>>
                            <span class="input-group-addon datepicker" for="marry_date">&nbsp;
                                <span class="glyphicon glyphicon-calendar"></span>&nbsp;
                            </span>

                        </div>
                        <?php //echo age(conv_date($rec1["marry_date"]),"d",conv_date(date("Y-m-d"))); 
                        ?>
                    </div>
                    <div class="col-xs-12 col-sm-2"><input type="text" name="age_marry_old" id="age_marry_old" value="<?php if ($rec3["marry_date_new"]) {
                                                                                                                            echo age(conv_date($rec3["marry_date_new"]), "y", conv_date(date("Y-m-d"))) . '  ปี ' . age(conv_date($rec3["marry_date_new"]), "m", conv_date(date("Y-m-d"))) . ' เดือน ' . age(conv_date($rec3["marry_date_new"]), "d", conv_date(date("Y-m-d"))) . ' วัน';
                                                                                                                        } ?>" class="form-control" placeholder="ปี" onClick="age_marryd(marry_date.value,REGISTER_DATE.value);" <?php echo $disabled; ?>></div>
                </div>
            <?php } else {
                                    echo  "<br><center>ไม่มีข้อมูล&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center>";
                                }
            ?>

            </fieldset>

            <fieldset>

                <div class="row">
                    <div class="col-xs-12 col-sm-3">4.เปลี่ยนแปลงเลขที่บัญชีธนาคาร</div>
                    <div class="col-xs-12 col-sm-8"></div>
                    <?php
                                if ($nums5 > '0') {
                    ?>
                </div>
                <div class="row">

                    <div class="col-xs-12 col-sm-2" style="white-space:nowrap;" align="right"><span class="col-xs-12 col-sm-3">เลขที่บัญชีธนาคาร</span></div>

                    <div class="col-xs-12 col-sm-2">
                        <input type="text" id="bank_no_new" name="bank_no_new" value="<?php echo text($rec5['bank_no_new']); ?>" class="form-control" placeholder="เลขที่บัญชีธนาคาร" disabled>
                    </div>
                    <div class="col-xs-12 col-sm-8"><input name="auto_deduct" id="auto_deduct" type="checkbox" value="1" disabled <?php echo ($rec5['chg_auto_deduct'] == 1) ? "checked" : ""; ?>> เรียกหักบัญชีเงินฝากสมาชิก
                        <span style="color:red;"> * กรณีสมาชิกยินยอมให้ทางสมาคมเรียกเก็บเงินผ่านบัญชีเงินฝากสมาชิก ให้เลือกรายการเรียกหักบัญชีเงินฝากสมาชิก</span>
                    </div>
                </div>
            <?php } else {
                                    echo  "<br><center>ไม่มีข้อมูล&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center>";
                                } ?>
            </fieldset>
            <fieldset>
                <div class="row">
                    <div class="col-xs-12 col-sm-3">5.เปลี่ยนแปลงอื่นๆ</div>
                </div>

                <?php if ($nums6 > '0') {
                ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3" style="white-space:nowrap;" align="right">ประเภทสมาชิก</div>
                        <div class="col-xs-12 col-sm-3">

                            <?php

                                    $sql_cate_id = "SELECT m_cate_id,m_cate_name FROM M_TYPE WHERE 1=1 ORDER BY m_cate_name ASC";
                                    $query_cate_id = $db->query($sql_cate_id);
                                    $sel_cate_id[$rec6["m_cate_id_new"]]  = "selected";

                            ?>
                            <select name="m_cate_id_new" id="m_cate_id_new" class="form-control" placeholder="ประเภทสมาชิก" <?php echo $disabled ?>>
                                <option value=""></option>
                                <?php while ($rec_cate_id = $db->db_fetch_array($query_cate_id)) { ?>
                                    <option value="<?php echo $rec_cate_id['m_cate_id']; ?>" <?php echo $sel_cate_id[$rec_cate_id['m_cate_id']]; ?>><?php echo text($rec_cate_id['m_cate_name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3" style="white-space:nowrap;" align="right">รหัสอำเภอ</div>
                        <div class="col-xs-12 col-sm-3">
                            <input type="text" id="baac_aumphur_new" name="baac_aumphur_new" class="form-control" value="<?php echo $rec6['baac_aumphur_new'] ?>" <?php echo $disabled ?> placeholder="รหัสอำเภอ" maxlength="4">
                        </div>
                        <div class="col-xs-12 col-sm-1 " style="white-space:nowrap;" align="right">รหัสตำบล</div>
                        <div class="col-xs-12 col-sm-2">
                            <input type="text" id="baac_tambon_new" name="baac_tambon_new" class="form-control" value="<?php echo $rec6['baac_tambon_new'] ?>" <?php echo $disabled ?> placeholder="รหัสตำบล" maxlength="10">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3" style="white-space:nowrap;" align="right">กลุ่ม</div>
                        <div class="col-xs-12 col-sm-3">
                            <input type="text" id="baac_group_new" name="baac_group_new" class="form-control" value="<?php echo $rec6['baac_group_new'] ?>" <?php echo $disabled ?> placeholder="กลุ่ม" maxlength="3">
                        </div>
                        <div class="col-xs-12 col-sm-1" style="white-space:nowrap;" align="right">เลขทะเบียน</div>
                        <div class="col-xs-12 col-sm-2">
                            <input type="text" id="baac_no_new" name="baac_no_new" class="form-control" value="<?php echo $rec6['baac_no_new'] ?>" <?php echo $disabled ?> placeholder="เลขทะเบียน" maxlength="10">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-3" style="white-space:nowrap;" align="right">เลข CIF</div>
                        <div class="col-xs-12 col-sm-3">
                            <input type="text" id="baac_cif_new" name="baac_cif_new" class="form-control number2" value="<?php echo $rec6['baac_cif_new'] ?>" <?php echo $disabled ?> placeholder="เลข CIF" maxlength="50">
                        </div>
                    </div>

                <?php } else {
                                    echo  "<center>ไม่มีข้อมูล&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center>";
                                }
                ?>

            </div>
            </fieldset>
            <fieldset>
            <?php } else if ($rec_chg['chg_code'] == 2) { ?>
                <div class="row">
                    <div class="col-xs-12 col-sm-3"> เปลี่ยนแปลงผู้รับเงินสงเคราะห์ จาก</div>


                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-2">เงื่อนไขการรับเงินสงเคราะห์</div>
                    <div class="col-xs-12 col-sm-2" style="white-space:nowrap;">
                        <input type="radio" value="1" <?php if ($rec['pay_code'] == '1') {
                                                            echo "checked";
                                                        } ?> disabled> รับเต็มจำนวนเพียงผู้เดียว &nbsp;&nbsp;&nbsp;
                        <input type="radio" value="2" <?php if ($rec['pay_code'] == '2') {
                                                            echo "checked";
                                                        } ?> disabled> รับหลายคนส่วนแบ่งเท่าๆ กัน &nbsp;&nbsp;&nbsp;
                        <input type="radio" value="3" <?php if ($rec['pay_code'] == '3') {
                                                            echo "checked";
                                                        } ?> disabled> รับตามลำดับแต่เพียงผู้เดียว
                        <input type="radio" value="4" <?php if ($rec['pay_code'] == '4') {
                                                            echo "checked";
                                                        } ?> disabled>
                        รับหลายคนส่วนแบ่งไม่เท่ากัน
                    </div>

                </div>
                <div class="row">
                    <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                        <thead>
                            <tr class="bgHead">
                                <th width="5%" nowrap>
                                    <div align="center"><strong>ลำดับ</strong></div>
                                </th>
                                <th width="10%" nowrap>
                                    <div align="center">เลขประจำตัวประชาชน</div>
                                </th>
                                <th width="15%" nowrap>
                                    <div align="center"><strong>ชื่อ - สกุล</strong></div>
                                </th>
                                <th width="20%" nowrap>
                                    <div align="center"><strong>ที่อยู่</strong></div>
                                </th>
                                <th width="5%">
                                    <div align="center"><strong>ฐานะเกี่ยวข้อง</strong></div>
                                </th>
                                <?php if ($rec['pay_code'] == 4) { ?> <th width="10%" align="center">
                                        <div align="center"><strong>ส่วนแบ่ง(%)</strong></div>
                                    </th><?php } ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sql_old = "SELECT M_BENEFIT.* , PREFIX.PREFIX_NAME FROM M_BENEFIT
							left join PREFIX  on PREFIX.PREFIX_ID = M_BENEFIT.PREFIX_ID
							WHERE member_id = '" . $member_id . "'
							ORDER BY benefit_no";
                                $query_old = $db->query($sql_old);
                                $nums_old = $db->db_num_rows($query_old);
                                if ($nums_old > 0) {
                                    $i = 1;
                                    while ($rec_old = $db->db_fetch_array($query_old)) {




                            ?>
                                    <tr bgcolor="#FFFFFF">
                                        <td align="center"><?php echo $rec_old['benefit_no'] ?></td>
                                        <td align="left"><?php echo get_idCard($rec_old['id_card_no']); ?></td>
                                        <td align="left" nowrap><?php echo text($rec_old['PREFIX_NAME'] . $rec_old['fname'] . ' ' . $rec_old['lname']); ?></td>

                                        <td align="left"><?php echo text($rec_old['BENFADDR1']); ?></td>
                                        <td align="center"><?php if ($rec_old['relation_member'] == 5) {
                                                                echo text($rec_old['relation_member_other']);
                                                            } else {
                                                                echo $arr_relation_chapa[$rec_old['relation_member']];
                                                            } ?></td>
                                        <?php if ($rec['pay_code'] == 4) { ?> <td align="right"><?php echo number_format($rec_old['percent_pay'], 2) ?></td><?php } ?>

                                    </tr>
                            <?php
                                        $i++;
                                    }
                                } else {
                                    echo "<tr bgcolor=\"#FFFFFF\"><td align=\"center\" colspan=\"11\">ไม่พบข้อมูล</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-10">เป็น เปลี่ยนแปลงผู้รับเงินสงเคราะห์ ดังนี้</div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-2">เงื่อนไขการรับเงินสงเคราะห์</div>
                    <div class="col-xs-12 col-sm-2" style="white-space:nowrap;">
                        <input name="pay_code" id="pay_code1" type="radio" value="1" <?php if ($rec_chg['pay_code'] == '1') {
                                                                                            echo "checked";
                                                                                        } ?> disabled> รับเต็มจำนวนเพียงผู้เดียว &nbsp;&nbsp;&nbsp;
                        <input name="pay_code" id="pay_code2" type="radio" value="2" <?php if ($rec_chg['pay_code'] == '2') {
                                                                                            echo "checked";
                                                                                        } ?> disabled> รับหลายคนส่วนแบ่งเท่าๆ กัน  &nbsp;&nbsp;&nbsp;
                        <input name="pay_code" id="pay_code3" type="radio" value="3" <?php if ($rec_chg['pay_code'] == '3') {
                                                                                            echo "checked";
                                                                                        } ?> disabled> รับตามลำดับแต่เพียงผู้เดียว
                        <input name="pay_code" id="pay_code4" type="radio" value="4" <?php if ($rec_chg['pay_code'] == '4') {
                                                                                            echo "checked";
                                                                                        } ?> disabled>
                        รับหลายคนส่วนแบ่งไม่เท่ากัน
                    </div>

                </div>

                <?php if ($chg_id) { ?>
                    <div class="row">
                        <div class="col-xs-12 col-sm-1"></div>
                    </div>
                    <div class="col-xs-12 col-sm-12">
                        <div class="table-responsive">
                            <table width="22%" class="table table-bordered table-striped table-hover table-condensed">
                                <thead>
                                    <tr class="bgHead">
                                        <th width="5%" nowrap>
                                            <div align="center"><strong>ลำดับ</strong></div>
                                        </th>
                                        <th width="10%" nowrap>
                                            <div align="center">เลขประจำตัวประชาชน</div>
                                        </th>
                                        <th width="15%" nowrap>
                                            <div align="center"><strong>ชื่อ - สกุล</strong></div>
                                        </th>
                                        <th width="20%" nowrap>
                                            <div align="center"><strong>ที่อยู่</strong></div>
                                        </th>
                                        <th width="5%">
                                            <div align="center"><strong>ฐานะเกี่ยวข้อง</strong></div>
                                        </th>
                                        <?php if ($rec_chg['pay_code'] == 4) { ?> <td width="10%" align="center"><strong>ส่วนแบ่ง(%)</strong></td><?php } ?>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql_benefit_new = "SELECT M_CHG_BENEFIT.* , PREFIX.PREFIX_NAME FROM M_CHG_BENEFIT
									left join PREFIX  on PREFIX.PREFIX_ID = M_CHG_BENEFIT.PREFIX_ID
									WHERE chg_id = '" . $chg_id . "'
									ORDER BY benefit_no";
                                    $query_benefit_new = $db->query($sql_benefit_new);
                                    $nums_b_new = $db->db_num_rows($query_benefit_new);
                                    if ($nums_b_new > 0) {
                                        $i = 1;
                                        $query = $db->query($sql_benefit_new);
                                        while ($rec_bnew = $db->db_fetch_array($query_benefit_new)) {


                                            $edit = "<a data-toggle=\"modal\" class=\"btn btn-default btn-xs\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"editBenifitData('" . $rec_bnew['chg_benefit_id'] . "');\">" . $img_edit . " แก้ไข</a> ";
                                            $delete = "<button type=\"button\" class=\"btn btn-default btn-xs\" onClick=\"delBenifitData('" . $rec_bnew["chg_benefit_id"] . "');\">" . $img_del . " ลบ</a> ";

                                    ?>
                                            <tr bgcolor="#FFFFFF">
                                                <td align="center"><?php echo $rec_bnew['benefit_no'] ?></td>
                                                <td align="left"><?php echo get_idCard($rec_bnew['id_card_no']); ?></td>
                                                <td align="left" nowrap><?php echo text($rec_bnew['PREFIX_NAME'] . $rec_bnew['fname'] . ' ' . $rec_bnew['lname']); ?></td>

                                                <td align="left"><?php echo text($rec_bnew['BENFADDR1']); ?></td>
                                                <td align="center"><?php if ($rec_bnew['relation_member'] == 5) {
                                                                        echo text($rec_bnew['relation_member_other']);
                                                                    } else {
                                                                        echo $arr_relation_chapa[$rec_bnew['relation_member']];
                                                                    } ?></td>
                                                <?php if ($rec_chg['pay_code'] == 4) { ?><td align="right"><?php echo number_format($rec_bnew['percent_pay'], 2) ?></td><?php } ?>
                                            </tr>
                                    <?php
                                            $i++;
                                        }
                                    } else {
                                        echo "<tr bgcolor=\"#FFFFFF\"><td align=\"center\" colspan=\"11\">ไม่พบข้อมูล</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>

                        </div>

                    </div>
                <?php } ?>

            </fieldset>
        <?php } ?>

        <div class="col-xs-12 col-sm-12" align="center">
            <input name="approve_status" type="radio" value="1" checked> อนุมัติ &nbsp;&nbsp;
            <input name="approve_status" type="radio" value="2"> ไม่อนุมัติ &nbsp;&nbsp;
        </div>

        <br>
        </form>
        <div class="col-xs-12 col-sm-12" align="center">
            <button type="button" class="btn btn-primary" onClick="chkinput();">บันทึก</button>
            <button type="button" class="btn btn-default" onClick="self.location.href='member_change_approve_disp.php?proc=<?php echo $proc ?>&<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>';">ยกเลิก</button>
        </div>
        </div>
    </div>
    </div>
    </div>
    <div style="text-align:center; bottom:0px;"><?php include($path . "include/footer.php"); ?></div>
</body>

</html>