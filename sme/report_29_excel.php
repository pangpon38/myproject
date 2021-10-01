<?php
session_start();
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=report_29_xls.xls");
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

$header = array(
    "พัฒนา SMEs /ผู้ประกอบการ",
    "พัฒนาบุคลากร SMEs",
    "เกิดประสิทธิภาพการผลิต",
    "เกิดประสิทธิภาพแรงงาน",
    "เกิดการสร้างงานสร้างอาชีพในพื้นที่",
    "มีรายได้/ยอดขาย",
    "เปิดตลาดการค้า",
    "จัดแสดงและจำหน่ายสินค้า",
    "เจรจาการค้า/จับคู่ธุรกิจ",
    "มีศูนย์กระจายสินค้า",
    "E-Commerce",
    "มีพันธมิตรธุรกิจ",
    "สร้างและพัฒนา Cluster",
    "การพัฒนาด้วยผลงานวิจัย(R&D)",
    "การพัฒนาด้วยวิทยาศาสตร์และเทคโนโลยี(S&T)",
    "ถ่ายทอดเทคโนโลยี",
    "ได้รับการรับรองมาตรฐาน",
    "รับรองมาตรฐาน มผช.",
    "พัฒนา OTOP",
    "ยกระดับ OTOP เป็น SMEs",
    "พัฒนาผลิตภัณฑ์",
    "พัฒนาบรรจุภัณฑ์",
    "เกิดผลิตภัณฑ์ต้นแบบ",
    "สร้าง Brand",
    "สร้างนักออกแบบ",
    "สร้างนักการตลาด",
    "สร้างพี่เลี้ยง/ที่ปรึกษาธุรกิจ",
    "บ่มเพาะธุรกิจ",
    "วินิจฉัย/ให้คำปรึกษาเชิงลึก",
    "มีแผนธุรกิจ",
    "มีหลักสูตรการฝึกอบรม",
    "พัฒนาโลจิสติกส์",
    "ร่วมทุน",
    "ได้รับสินเชื่อ",
    "อนุมัติสินเชื่อ",
    "ข้อมูลสนับสนุน SMEs",
    "รายงานการศึกษา/วิจัย",
    "ปรับกฎระเบียบ",
    "เกิดเครือข่ายความร่วมมือ",
    "มีระบบจดทะเบียน",
    "โครงการใหม่",
    "จัดตั้งศูนย์บริการครบวงจร",
    "พัฒนาเทคโนโลยี",
    "แผนพัฒนา",
    "ฐานข้อมูล",
    "ข้อเสนอแนะเชิงนโยบาย",
    "สร้างผู้ตรวจประเมิน",
    "พัฒนาวิสาหกิจชุมชน",
    "แผนปฏิบัติการ",
    "แผนยุทธศาสตร์",
    "ผู้เข้าร่วมการประชุม/เผยแพร่ผลการศึกษา",
    "เอกสารลิขสิทธิ์",
    "โปรแกรม",
    "ดัชนีความเชื่อมั่นผู้ประกอบการ",
    "พัฒนาแบบจำลอง",
    "เครื่องชี้วัดเศรษฐกิจ",
    "จัดกิจกรรม/สื่อเผยแพร่ต่างๆ",
    "เกิดความร่วมมือด้านการส่งเสริม SMEs ในต่างประเทศ",
    "เกิดกิจกรรมภายใต้กรอบอาเซียน",
    "แผนบริหารความเสี่ยง",
    "พัฒนาบุคลากร สสว.",
    "ขยายตลาด",
    "เข้าถึงแหล่งเงินทุน",
    "อื่น ๆ"

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
									<strong>ด้านการพัฒนา SME (หน่วยงาน) ปี ' . $text_year . '</strong>
                                   
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
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"><strong>รหัสโครงการ  </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:4cm;"><strong>โครงการ</strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"><strong> แนวทาง</strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> ตัวชี้วัด </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong> กลุ่มโครงการ </strong></div></td>';
        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:4cm;"  ><strong> รวมทั้งสิ้น</strong></div></td>';
        $html .= '<td class="" colspan="' . count($header) . '" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong>การพัฒนา SME</strong></div></td>';
        $html .= '</tr>';
        $html .= '<tr >';
        foreach ($header as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>' . ($key + 1) . '</strong></div></td>';
        }
        $html .= '</tr>';

        $html .= '<tr >';
        foreach ($header as $key => $value) {
            $html .= '<td class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
        }
        $html .= '</tr>';

        $html .= '</thead>';
        $html .= '<tbody>';
        $total_row = count($header);
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
        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="left" style="width:100%;"  ><strong>กระทรวง</strong></div></th>';
        $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
        for ($i = 0; $i < $total_row; $i++) {
            $html .= '<th class="" style="vertical-align:middle;background-color:#ffffff;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
        }
        $html .= '</tr>';

        $html .= '<tr >';
        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#ffffff;" ><div align="left" style="width:100%;"  ><strong>หน่วยงาน</strong></div></th>';
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