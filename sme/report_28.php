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

function check_odd($number){
    if($number % 2 == 0){
        return "Even"; 
    }
    else{
        return "Odd";
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
<html>

<head>
    <?php include($path . "include/inc_main_top.php"); ?>
    <script src="js/report_28.js?<?php echo rand(); ?>"></script>
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
									<strong>ด้านการพัฒนา SME (แนวทาง) ปี ' . $text_year . '</strong>
                                   
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
                        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"><strong>รหัสโครงการ  </strong></div></td>';
                        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:4cm;"><strong>โครงการ</strong></div></td>';
                        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:100%;"><strong> แนวทาง</strong></div></td>';
                        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:100%;"  ><strong> ตัวชี้วัด </strong></div></td>';
                        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:100%;"  ><strong> กลุ่มโครงการ </strong></div></td>';
                        $html .= '<td class="" rowspan="3" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:4cm;"  ><strong> รวมทั้งสิ้น</strong></div></td>';
                        $html .= '<td class="" colspan="' . count($header) . '" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:100%;"  ><strong>การพัฒนา SME</strong></div></td>';
                        $html .= '</tr>';
                        $html .= '<tr >';
                        foreach ($header as $key => $value) {
                            $html .= '<td class="" style="vertical-align:middle;background-color:#BDBDBD;" ><div align="center" style="width:3cm;"  ><strong>' . ($key + 1) . '</strong></div></td>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        foreach ($header as $key => $value) {
                            if(check_odd(($key+1)) == "Odd"){
                                $color = '#AFE1AF';
                            }else{
                                $color = '#EFE789';
                            }
                            $html .= '<td class="" style="vertical-align:middle;background-color:'.$color.';" ><div align="center" style="width:3cm;"  ><strong>' . $value . '</strong></div></td>';
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
                        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#7bacf5;" ><div align="left" style="width:100%;"  ><strong>แนวทางการ 1</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#7bacf5;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#7bacf5;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#afcefb;" ><div align="left" style="width:100%;"  ><strong>ตัวชี้วัด 1.1</strong></div></th>';
                        $html .= '<th class="" style="vertical-align:middle;background-color:#afcefb;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        for ($i = 0; $i < $total_row; $i++) {
                            $html .= '<th class="" style="vertical-align:middle;background-color:#afcefb;" ><div align="center" style="width:100%;"  ><strong></strong></div></th>';
                        }
                        $html .= '</tr>';

                        $html .= '<tr >';
                        $html .= '<th class="" colspan="5" style="vertical-align:middle;background-color:#afcefb;" ><div align="left" style="width:100%;"  ><strong>กลุ่มโครงการ 1</strong></div></th>';
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