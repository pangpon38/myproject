<?php
session_start();
$path = "../../";
include($path . "include/config_header_top.php");
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '1';
$path_cache = 'cache/'; ////////ที่เราเก็บไฟล์
$FILE_NAME = pathinfo(__FILE__, PATHINFO_FILENAME); /////////อ่านpath.php   ชื่อไฟล์คือ payment_print.php

$_POST['s_round_year_bud'] = $_SESSION['year_round'];
if (date("m") == 1) {
    $YEAR_NOW = (date("Y") + 543) - 1;
    $MONTH_NOW = 12;
} else {
    $YEAR_NOW = (date("Y") + 543);
    $MONTH_NOW = date("m") - 1;
}
$MONTH_NOW = sprintf('%02d', $MONTH_NOW);
$YEAR_MONTH_NOW = $YEAR_NOW . $MONTH_NOW;
$YEAR_MONTH_NEXT = (date("Y") + 543) . date("m");
$YEAR_NOW_SH = substr($YEAR_NOW, -2);

function number_format_chk($number = '', $digit = 0)
{
    if ($number == '') {
        return '';
    } else {
        return number_format($number, $digit);
    }
}

$header_n = array(
    "เชียงใหม่",
    "ลำพูน",
    "ลำปาง",
    "อุตรดิตถ์",
    "แพร่",
    "น่าน",
    "พะเยา",
    "เชียงราย",
    "แม่ฮ่องสอน",
    "นครสวรรค์",
    "กำแพงเพชร",
    "ตาก",
    "สุโขทัย",
    "พิษณุโลก",
    "พิจิตร",
    "เพชรบูรณ์"
);

$header_c = array(
    "กรุงเทพมหานคร",
    "สมุทรปราการ",
    "นนทบุรี",
    "ปทุมธานี",
    "พระนครศรีอยุธยา",
    "อ่างทอง",
    "ลพบุรี",
    "สิงห์บุรี",
    "ชัยนาท",
    "สระบุรี",
    "ฉะเชิงเทรา",
    "นครนายก",
    "อุทัยธานี",
    "สุพรรณบุรี",
    "นครปฐม",
    "สมุทรสาคร",
    "สมุทรสงคราม"
);

$header_n_east = array(
    "นครราชสีมา",
    "บุรีรัมย์",
    "สุรินทร์",
    "ศรีสะเกษ",
    "อุบลราชธานี",
    "ยโสธร",
    "ชัยภูมิ",
    "อำนาจเจริญ",
    "หนองบัวลำภู",
    "ขอนแก่น",
    "อุดรธานี",
    "เลย",
    "หนองคาย",
    "มหาสารคาม",
    "ร้อยเอ็ด",
    "กาฬสินธุ์",
    "สกลนคร",
    "นครพนม",
    "มุกดาหาร",
    "บึงกาฬ"
);

$header_w = array(
    "ราชบุรี",
    "กาญจนบุรี",
    "เพชรบุรี",
    "ประจวบคีรีขันธ์"
);

$header_e = array(
    "ชลบุรี",
    "ระยอง",
    "จันทบุรี",
    "ตราด",
    "ปราจีนบุรี",
    "สระแก้ว"
);

$header_s = array(
    "นครศรีธรรมราช",
    "กระบี่",
    "พังงา",
    "ภูเก็ต",
    "สุราษฎร์ธานี",
    "ระนอง",
    "ชุมพร",
    "สงขลา",
    "สตูล",
    "ตรัง",
    "พัทลุง",
    "ปัตตานี",
    "ยะลา",
    "นราธิวาส"

);
?>
<!DOCTYPE html>
<html>

<head>
    <?php include($path . "include/inc_main_top.php"); ?>
    <script src="js/report_22.js?<?php echo rand(); ?>"></script>
    <style>
        .table-freeze>thead>tr:nth-child(1)>td,
        .table-freeze>thead>tr:nth-child(1)>td {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table-freeze>thead>tr:nth-child(2)>td,
        .table-freeze>thead>tr:nth-child(2)>td {
            position: sticky;
            top: 39px;
            z-index: 1;
        }

        .table-freeze>thead>tr:nth-child(1)>.freez-1,
        .table-freeze>thead>tr:nth-child(1)>.freez-2 {
            z-index: 2 !important;
        }

        .freez-1 {
            position: sticky;
            left: 0;
            min-width: 130px;
        }

        .freez-2 {
            position: sticky;
            left: 130px;
        }
    </style>
</head>

<body>
    <div class="container-full">
        <div><?php include($path . "include/header.php"); ?></div>
        <div class="col-xs-12 col-sm-12">
            <ol class="breadcrumb">
                <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
                <li class="active"><?php echo showMenu($menu_sub_id); ?></li>
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
                    <input name="YEAR" type="hidden" id="YEAR" value="<?php echo $_POST['s_round_year_bud']; ?>">
                    <input name="FILE_NAME" type="hidden" id="FILE_NAME" value="<?php echo $FILE_NAME; ?>">

                    <?php
                    $html = '';
                    if ($_POST['s_round_year_bud'] != "") {
                        if ($_POST['YEAR_ROUND_S'] != '' && $_POST['YEAR_ROUND_E'] != '' && $_POST['YEAR_ROUND_S'] != $_POST['YEAR_ROUND_E']) {
                            $text_year = "ระหว่างปี " . $_POST['YEAR_ROUND_S'] . " ถึง " . $_POST['YEAR_ROUND_E'] . " ";
                        } else if ($_POST['YEAR_ROUND_S'] != '' && $_POST['YEAR_ROUND_E'] != '' && $_POST['YEAR_ROUND_S'] == $_POST['YEAR_ROUND_E']) {
                            $text_year = $_POST['YEAR_ROUND_S'];
                        } else {
                            $text_year = $_SESSION['year_round'];
                        }
                        $html = '';
                        $html .= '<div class="row">
								<div align="center" >
									<strong>7. พื้นที่หน่วยงาน (โครงการ) ปี ' . $text_year . '</strong>
                                   
								</div>
							</div>
							<div class="row">
								<div align="center" >
									<strong>&nbsp;</strong>
								</div>
							</div>';
                        //class="table table-bordered table-striped table-hover table-condensed" width="100%"
                        $html .= '<table  border="1" width="100%" class=" table-hover table-condensed table-freeze" style="border-collapse: collapse;background-color:#FFFFFF;">';
                        $html .= '<thead>';
                        $html .= '<tr >';
                        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"><strong>รหัสโครงการ  </strong></div></td>';
                        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:4cm;"><strong>กระทรวง/<br>หน่วยงาน/<br>โครงการ/<br>กิจกรรม</strong></div></td>';
                        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:100%;"><strong> แนวทาง </strong></div></td>';
                        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:100%;"><strong> ตัวชี้วัด </strong></div></td>';
                        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:100%;"  ><strong> กลุ่มโครงการ </strong></div></td>';
                        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:4cm;"  ><strong> รวมทั้งสิ้น</strong></div></td>';
                        $html .= '<td class="" colspan="' . (count($header_n) + 2) . '" style="vertical-align:middle;background-color:#FF5733;" ><div align="center" style="width:100%;"  ><strong> ภาคเหนือ </strong></div></td>';
                        $html .= '<td class="" colspan="' . (count($header_c) + 2) . '" style="vertical-align:middle;background-color:#F4FAB7;" ><div align="center" style="width:100%;"  ><strong> ภาคกลาง </strong></div></td>';
                        $html .= '<td class="" colspan="' . (count($header_n_east) + 2) . '" style="vertical-align:middle;background-color:#AFE1AF;" ><div align="center" style="width:100%;"  ><strong> ภาคตะวันออกเฉียงเหนือ </strong></div></td>';
                        $html .= '<td class="" colspan="' . (count($header_w) + 2) . '" style="vertical-align:middle;background-color:#E083E3  ;" ><div align="center" style="width:100%;"  ><strong> ภาคตะวันตก </strong></div></td>';
                        $html .= '<td class="" colspan="' . (count($header_e) + 2) . '" style="vertical-align:middle;background-color:#97DEF4 ;" ><div align="center" style="width:100%;"  ><strong> ภาคตะวันออก </strong></div></td>';
                        $html .= '<td class="" colspan="' . (count($header_s) + 2) . '" style="vertical-align:middle;background-color:#EFE789  ;" ><div align="center" style="width:100%;"  ><strong> ภาคใต้ </strong></div></td>';
                        $html .= '</tr>';

                        $html .= '<tr >';
                        foreach ($header_n as $key => $value) {
                            $html .= '<td class="" style="vertical-align:middle;background-color:#FF5733;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
                        }
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
                        foreach ($header_c as $key => $value) {
                            $html .= '<td class="" style="vertical-align:middle;background-color:#F4FAB7;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
                        }
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
                        foreach ($header_n_east as $key => $value) {
                            $html .= '<td class="" style="vertical-align:middle;background-color:#AFE1AF;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
                        }
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
                        foreach ($header_w as $key => $value) {
                            $html .= '<td class="" style="vertical-align:middle;background-color:#E083E3 ;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
                        }
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';

                        foreach ($header_e as $key => $value) {
                            $html .= '<td class="" style="vertical-align:middle;background-color:#97DEF4 ;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
                        }
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';

                        foreach ($header_s as $key => $value) {
                            $html .= '<td class="" style="vertical-align:middle;background-color:#EFE789 ;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
                        }
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
                        $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
                        $html .= '</tr>';


                        $html .= '</thead>';
                        $html .= '<tbody>';

                        $total_row = (count($header_n) + 2) + (count($header_c) + 2) + (count($header_n_east) + 2) + (count($header_w) + 2) + (count($header_e) + 2) + (count($header_s) + 2);

                        $html .= '<tr >';
                        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="right" style="width:100%;"  ><strong>%</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="right" style="width:100%;"  ><strong>รวม</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#7bacf5;" ><div align="center" style="width:100%;"  ><strong>กระทรวง</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#7bacf5;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#7bacf5;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#afcefb;" ><div align="center" style="width:100%;"  ><strong>หน่วยงาน</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#afcefb;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#afcefb;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="right" style="width:100%;"  ><strong>65020011</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="left" style="width:100%;"  ><strong>โครงการ xxx</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="right" style="width:100%;"  ><strong>65020021</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="left" style="width:100%;"  ><strong>โครงการ xxx</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '</tbody>';
                        $html .= '</table>';
                    }
                    ?>

                    <?php

                    echo $html;

                    ?>

                    <?php if ($_POST['s_round_year_bud'] != '') { ?>
                        <div class="col-xs-12 col-sm-12">
                            <div class="dropdown">
                                <button class="btn btn-default" type="button" id="dropdownMenu1" data-toggle="dropdown">
                                    <?php echo $img_print; ?> ออกรายงาน
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a onClick="excel_report('<?php echo $_POST['s_round_year_bud']; ?>');"><?php echo $img_print; ?> ออกรายงาน excel</a></li>
                                    <!-- <li><a onClick="word_report('<?php echo $_POST['s_round_year_bud']; ?>');"><?php echo $img_print; ?> ออกรายงาน word</a></li>
                                    <li><a onClick="pdf_report('<?php echo $_POST['s_round_year_bud']; ?>');"><?php echo $img_print; ?> ออกรายงาน pdf</a></li> -->
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                    <br>
                    <div class="col-xs-12 col-sm-12">
                        <div class="table-responsive">

                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <?php //echo endPaging("frm-search",$total_record); 
                    ?>
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
<!-- /.modal -->