<?php
session_start();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=report_22_xls.xls");

$path = "../../";
include($path . "include/config_header_top.php");
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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
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
        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"><strong>รหัสโครงการ  </strong></div></td>';
        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:4cm;"><strong>กระทรวง/<br>หน่วยงาน/<br>โครงการ/<br>กิจกรรม</strong></div></td>';
        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"><strong> แนวทาง </strong></div></td>';
        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"><strong> ตัวชี้วัด </strong></div></td>';
        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> กลุ่มโครงการ </strong></div></td>';
        $html .= '<td class="" rowspan="2" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:4cm;"  ><strong> รวมทั้งสิ้น</strong></div></td>';
        $html .= '<td class="" colspan="' . (count($header_n) + 2) . '" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> ภาคเหนือ </strong></div></td>';
        $html .= '<td class="" colspan="' . (count($header_c) + 2) . '" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> ภาคกลาง </strong></div></td>';
        $html .= '<td class="" colspan="' . (count($header_n_east) + 2) . '" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> ภาคตะวันออกเฉียงเหนือ </strong></div></td>';
        $html .= '<td class="" colspan="' . (count($header_w) + 2) . '" style="vertical-align:middle;background-color:#ffffff  ;" ><div align="center" style="width:100%;"  ><strong> ภาคตะวันตก </strong></div></td>';
        $html .= '<td class="" colspan="' . (count($header_e) + 2) . '" style="vertical-align:middle;background-color:#ffffff ;" ><div align="center" style="width:100%;"  ><strong> ภาคตะวันออก </strong></div></td>';
        $html .= '<td class="" colspan="' . (count($header_s) + 2) . '" style="vertical-align:middle;background-color:#ffffff  ;" ><div align="center" style="width:100%;"  ><strong> ภาคใต้ </strong></div></td>';
        $html .= '</tr>';

        $html .= '<tr >';
        foreach ($header_n as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
        }
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
        foreach ($header_c as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
        }
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
        foreach ($header_n_east as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
        }
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
        foreach ($header_w as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff ;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
        }
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';

        foreach ($header_e as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff ;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
        }
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';

        foreach ($header_s as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff ;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
        }
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>รวม</strong></div></td>';
        $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>%</strong></div></td>';
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
        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong>กระทรวง</strong></div></th>';
        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
        for ($i = 0; $i < $total_row; $i++) {
            $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
        }
        $html .= '</tr>';

        $html .= '<tr >';
        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong>หน่วยงาน</strong></div></th>';
        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
        for ($i = 0; $i < $total_row; $i++) {
            $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
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
</body>

</html>