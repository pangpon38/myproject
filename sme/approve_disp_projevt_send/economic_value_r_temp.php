<?php
session_start();
$path = "../../";
$path_a = "../../fileupload/file_prjp/";
include $path . "include/config_header_top.php";
$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id; /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '27';
$disables_txt = "disabled";
$readonly_txt = "readonly";
$ymchk = (date("Y") + 543) . date("m");
$ymchk_js = (date("Y") + 543) . sprintf("%'02d", date("m"));

if ($_POST['PRJP_ID'] != '') {
	$PRJP_ID = $_POST['PRJP_ID'];
} else {
	$PRJP_ID = $PRJP_ID;
}


$sql_head = "SELECT
                    PRJP_CODE,
				    PRJP_NAME,
				    EDATE_PRJP,
				    SDATE_PRJP,
				    MONEY_BDG,
					(SELECT SUM(WEIGHT) FROM prjp_project b WHERE b.PRJP_PARENT_ID = a.PRJP_ID AND PRJP_LEVEL = '2') AS SW,
					(SELECT SUM(TRAGET_VALUE) FROM prjp_project b WHERE b.PRJP_PARENT_ID = a.PRJP_ID AND PRJP_LEVEL = '2') AS ST
				FROM
                    prjp_project a
                WHERE
                    PRJP_ID = '" . $PRJP_ID . "'
                ORDER BY
                    ORDER_ROW_1,
                    ORDER_ROW_2,
                    ORDER_ROW_3,
                    ORDER_NO";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$sql_r_value_now = "SELECT
                        SUM(prjp_report_money.BDG_VALUE) AS sumnow
					FROM
                        prjp_report_money
					    JOIN prjp_project ON prjp_project.PRJP_ID = prjp_report_money.PRJP_ID
					WHERE
                        prjp_project.PRJP_PARENT_ID = '" . $PRJP_ID . "'
					    AND (YEAR + RIGHT ('0' + CAST(MONTH AS VARCHAR), 2) <= '" . date("Y") . sprintf("%'02d", date("m")) . "')";
$query_r_value_now = $db->query($sql_r_value_now);
$rec_r_value_now = $db->db_fetch_array($query_r_value_now);

$sql_binding = "SELECT * FROM prjp_binding WHERE PRJP_ID = '" . $PRJP_ID . "' AND YEAR = '" . (date("Y") + 543) . "' AND MONTH = '" . (date("m") * 1) . "' ";
$query_binding = $db->query($sql_binding);
$rec_binding = $db->db_fetch_array($query_binding);

if ($rec_head['PROLONG_STATUS'] == 1) {
	$shead = "(ขอขยายเวลา)";
} else {
	$shead = "";
}

////////// เช็คสถานะการบันทึกย้อนหลัง ///////////////////
if ($rec_head['PRJP_SET_TIME_CHK'] == 1) {
	$ds_set = substr($rec_head['PRJP_SET_STIME'], 8, 2) * 1;
	$ms_set = substr($rec_head['PRJP_SET_STIME'], 5, 2) * 1;
	$ys_set = substr($rec_head['PRJP_SET_STIME'], 0, 4) + 543;
	$de_set = substr($rec_head['PRJP_SET_ETIME'], 8, 2) * 1;
	$me_set = substr($rec_head['PRJP_SET_ETIME'], 5, 2) * 1;
	$ye_set = substr($rec_head['PRJP_SET_ETIME'], 0, 4) + 543;
	$chk_set = $ye_set . sprintf("%'02d", $me_set);
}

///////////////////////////////////////
$ms = substr($rec_head['SDATE_PRJP'], 5, 2) * 1;
$ys = substr($rec_head['SDATE_PRJP'], 0, 4) + 543;
$me = substr($rec_head['EDATE_PRJP'], 5, 2) * 1;
$ye = substr($rec_head['EDATE_PRJP'], 0, 4) + 543;

$yse = ((($ye - $ys) * 12)) - (12 - $me);
$row_col = (((12 - $ms) + 1) + ((($ye - $ys) - 1) * 12) + (12 - (12 - $me)));
$row_wh = ceil($row_col / 12);
$row_round = (($row_wh * 12) - $row_col);
$fbs = $ys . sprintf("%'02d", $ms);
$fbe = $ye . sprintf("%'02d", $me);

//field
$arr_eco_field = array(
	'1' => 'sale',
	'2' => 'export',
	'3' => 'investment',
	'4' => 'employment',
	'5' => 'production',
	'6' => 'loss',
	'7' => 'manufacture',
	'8' => 'labor',
	'9' => 'eco_other',
	'10' => 'economic',
	'11' => 'be_employ',
	'12' => 'employ',
	'13' => 'business_join',
	'14' => '',
	'14.1' => 'business_up_1',
	'14.2' => 'business_up_2',
	'14.3' => 'business_up_3',
	'15' => '',
	'15.1' => 'tax_corporation',
	'15.2' => 'tax_layman',
	'16' => '',
	'16.1' => 'sme_support_process',
	'16.2' => 'sme_support_success',
	'17' => 'cluster_up',
	'18' => 'sme_connect',
	'19' => 'sme_copyright',
	'20' => 'sme_brand',
	'21' => 'sme_standard',
	'22' => 'sme_regis',
	'23' => 'business_new',
	'24' => '',
	'24.1' => 'business_expand',
	'24.2' => 'business_freelance',
	'24.3' => 'business_franchise',
	'25' => 'business_digital',
);

//field Detail
$arr_eco_field_det1 = array(
	'1' => 'มูลค่าการขายในประเทศที่เพิ่มขึ้น',
	'2' => 'มูลค่าจากการส่งออกที่เพิ่มขึ้น',
	'3' => 'มูลค่าการลงทุนที่เพิ่มขึ้น',
	'4' => 'มูลค่าการจ้างงานที่เพิ่มขึ้น',
	'5' => 'มูลค่าการลดต้นทุนที่เพิ่มขึ้น',
	'6' => 'มูลค่าการลดของเสียที่เพิ่มขึ้น',
	'7' => 'มูลค่าผลิตภาพการผลิตที่เพิ่มขึ้น',
	'8' => 'มูลค่าผลิตภาพแรงงานที่เพิ่มขึ้น',
	'9' => 'มูลค่าอื่นๆที่เพิ่มขึ้น (ถ้ามี) ระบุ',
	'10' => 'มูลค่าทางเศรษฐกิจที่เพิ่มขึ้น (รวมข้อ 1-9 )',
);

$arr_eco_field_det2 = array(
	'11' => 'การจ้างงานก่อนเข้าร่วมโครงการ',
	'12' => 'ปัจจุบันมีการจ้างงาน <strong><u><i>เพิ่ม/ลด</strong></u></i> ทั้งหมด',
	'13' => 'ธุรกิจเข้าร่วมโครงการ',
	'14' => 'ธุรกิจได้รับการพัฒนา',
	'14.1' => 'ยกระดับจากกลุ่มอาชีพ เป็นวิสาหกิจชุมชน',
	'14.2' => 'ยกระดับจากผู้ประกอบการรายย่อย (Micro) เป็น SMEs ขนาดย่อม',
	'14.3' => 'ยกระดับจาก SMEs ขนาดย่อมเป็น SMEs ขนาดกลาง',
	'15' => 'รูปแบบการเสียภาษีเงินได้ในปีที่ผ่านมา',
	'15.1' => 'นิติบุคคล',
	'15.2' => 'บุคคลธรรมดา',
	'16' => 'ได้รับสินเชื่อหรือเงินกู้ จากการเข้าร่วม/เชื่อมโยงจากโครงการ',
	'16.1' => 'อยู่ระหว่างกระบวนการยื่นขอสินเชื่อจากสถาบันการเงิน',
	'16.2' => 'ได้รับสินเชื่อจากสถาบันการเงิน',
	'17' => 'SME เป็นสมาชิก Cluster / เครือข่ายวิสาหกิจชุมชน',
	'18' => 'SME เป็นสมาชิกสมาคม',
	'19' => 'SME มีการจดทะเบียนทรัพย์สินทางปัญญา/ยื่นขอจดทะเบียนฯ',
	'20' => 'SME มีการจดตรายี่ห้อสินค้าและบริการ /ยื่นขอจดตราฯเพิ่มขึ้น',
	'21' => 'SME ได้รับการรับรองมาตรฐาน /ยื่นขอรับรองมาตรฐานฯเพิ่มขึ้น',
	'22' => 'SME จดทะเบียนธุรกิจ /ยื่นขอจดทะเบียนฯเพิ่มขึ้น',
	'23' => 'SME ประกอบธุรกิจเดิมและมีการจัดตั้งธุรกิจใหม่เพิ่มหลังเข้าร่วมโครงการ ',
	'24' => 'มีการขยายสาขาธุรกิจ (หลังเข้าร่วมโครงการ)',
	'24.1' => 'การขยายสาขาธุรกิจ',
	'24.2' => 'ขาย Franchise',
	'24.3' => '',
	'25' => 'SME มีการนำเทคโนโลยี นวัตกรรม ดิจิตอล มาใช้ในการดำเนินธุรกิจ',
);

//column D
$arr_col_D = array(
	'11' => 'จำนวน',
	'12' => '<strong><u>เพิ่มขึ้น</u></strong>(จำนวน)',
	'13' => 'จำนวน',
	'14.1' => 'จำนวน',
	'14.2' => 'จำนวน',
	'14.3' => 'จำนวน',
	'15.1' => 'จำนวน',
	'15.2' => 'จำนวน',
	'16.1' => 'จำนวน',
	'16.2' => 'จำนวน',
	'17' => '<strong><u>Cluster</strong></u> จำนวน',
	'18' => 'จำนวน',
	'19' => '<strong><u>จดทะเบียน</strong></u> จำนวน',
	'20' => '<strong><u>จดตราฯ</strong></u> จำนวน',
	'21' => '<strong><u>ได้รับการรับรอง</strong></u> จำนวน',
	'22' => '<strong><u>จดทะเบียน</strong></u> จำนวน',
	'23' => 'จำนวน',
	'24.1' => 'จำนวน',
	'24.2' => 'จำนวน',
	'24.3' => 'จำนวน',
	'25' => 'จำนวน',
);

//column F
$arr_col_F = array(
	'11' => 'คน',
	'12' => 'คน',
	'13' => 'กิจการ/วิสาหกิจ',
	'14.1' => 'ราย',
	'14.2' => 'ราย',
	'14.3' => 'ราย',
	'15.1' => 'ราย',
	'15.2' => 'ราย',
	'16.1' => 'ราย',
	'16.2' => 'ราย',
	'17' => 'ราย',
	'18' => 'ราย',
	'19' => 'กิจการ',
	'20' => 'กิจการ',
	'21' => 'กิจการ',
	'22' => 'กิจการ',
	'23' => 'กิจการ',
	'24.1' => 'แห่ง',
	'24.2' => 'สัญญา',
	'24.3' => 'สาขา',
	'25' => 'ราย',
);

//column G
$arr_col_G = array(
	'12' => '<strong><u>ลดลง</u></strong>(จำนวน)',
	'16.1' => 'จำนวน',
	'16.2' => 'จำนวน',
	'15.1' => 'จำนวน',
	'15.2' => 'จำนวน',
	'17' => '<strong><u>เครือข่ายวิสาหกิจ</strong></u> จำนวน',
	'19' => '<strong><u>ยื่นขอจดตราฯ</strong></u> จำนวน',
	'20' => '<strong><u>ขอรับรองฯ</strong></u> จำนวน',
	'21' => '<strong><u>ยื่นขอจดฯ</strong></u> จำนวน',
	'23' => 'เงินลงทุน',
	'24.1' => 'เงินลงทุน',
	'24.2' => 'เงินลงทุน',
);

//column I
$arr_col_I = array(
	'11' => 'คน',
	'15.1' => 'บาท/ปี',
	'15.2' => 'บาท/ปี',
	'16.1' => 'ราย',
	'16.2' => 'ราย',
	'17' => 'กิจการ',
	'19' => 'กิจการ',
	'20' => 'กิจการ',
	'21' => 'กิจการ',
	'23' => 'บาท',
	'24.1' => 'บาท',
	'24.2' => 'บาท',
);

//infomation
$arr_eco_i = array(
	'1' => 'ยอดขายสินค้า/บริการภายในประเทศที่เพิ่มขึ้นในรอบระยะเวลา (นับเฉพาะยอดขายจากผลิตภัณฑ์ หรือบริการที่เกิดจากการส่งเสริม/พัฒนาจากโครงการเท่านั้น) ยอดขายที่เพิ่มขึ้นบ่งบอกความสามารถในสองด้านหลักคือ 1) การขายที่มีปริมาณ/จำนวนชิ้นมากขึ้น 2) ราคาที่สูงขึ้น โดยเฉพาะราคาต่อหน่วย ที่ผู้บริโภคยอมจ่ายแพงขึ้น เพื่อให้ได้คุณภาพตรงความต้องการ 
    ตัวอย่างสูตรคำนวณ มูลค่ายอดขายในประเทศ = ผลรวมของ (จำนวนที่ขาย x ราคา)',
	'2' => 'ยอดขายสินค้า/บริการจากการส่งออกที่เพิ่มขึ้นในรอบระยะเวลา (นับเฉพาะยอดขายจากผลิตภัณฑ์ หรือบริการที่เกิดจากการส่งเสริม/พัฒนาจากโครงการเท่านั้น) มูลค่าการส่งออกที่เพิ่มขึ้นบ่งบอกความสามารถสองด้านหลักคือ 1) การขายในตลาดต่างประเทศที่มากขึ้น 2) ราคาที่สูงขึ้น โดยประเมินเฉพาะยอดขายที่เกิดจากการส่งออก 
    ตัวอย่างสูตรคำนวณ มูลค่ายอดขายจากการส่งออก = ผลรวมของ (จำนวนที่ขาย x ราคา)',
	'3' => 'การลงทุนทั้งหมดที่เกิดขึ้นในกิจการ เช่น ก่อสร้างธุรกิจใหม่, ขยายธุรกิจ
    (ไม่รวมการจ้างงานเพิ่ม), การซื้อ/ปรับปรุงเครื่องจักร เครื่องมือ อุปกรณ์, ซื้อซอฟต์แวร์, ทำ R&D เป็นต้น',
	'4' => 'จำนวนเงินเดือน ค่าจ้าง ค่าตอบแทนที่จ่ายให้ผู้ปฏิบัติงานในธุรกิจ (นับเฉพาะมูลค่าการจ้างงานที่เพิ่มขึ้นจากการส่งเสริมจากโครงการเท่านั้น)
    ตัวอย่างสูตรคำนวณ มูลค่าการจ้างงาน = ผลรวมของ (จำนวนพนักงานที่จ้าง x เงินเดือน)',
	'5' => 'ต้นทุนการผลิต ประกอบด้วย
    1. ต้นทุนด้านวัสดุ  เป็นค่าใช้จ่ายที่เกี่ยวข้องกับวัสดุ, อุปกรณ์, เครื่องมือ ที่ใช้ในเพื่อการผลิตโดยตรง เป็นต้นทุนที่สามารถระบุได้ว่าใช้วัตถุดิบในการผลิตสินค้าตัวใดตัวหนึ่งในปริมาณและต้นทุนเท่าไหร่ (โดยส่วนมากมักจะเป็นส่วนประกอบหนึ่งของผลิตภัณฑ์)
    2. ต้นทุนด้านแรงงาน เป็นค่าใช้จ่ายด้านแรงงานในการทำงานและผลิตสินค้าเพื่อให้เกิดผลิตภัณฑ์สำเร็จรูป เช่น ค่าจ้าง เงินเดือนของพนักงาน เป็นต้น
    3. ค่าใช้จ่ายในการผลิต เป็นค่าใช้จ่ายที่นอกเหนือจากค่าใช้จ่ายของวัสดุและค่าใช้จ่ายด้านแรงงาน เช่น ค่าสาธารณูปโภค, ค่าเช่าโรงงาน, ค่าบำรุงรักษาเครื่องจักร, ค่าขนส่ง , สวัสดิการต่างๆ  เป็นต้น
    ตัวอย่างสูตรคำนวณ ต้นทุนการผลิต =  ต้นทุนวัสดุ + ต้นทุนแรงงาน + ค่าใช้จ่ายอื่นๆ/จำนวนหน่วยที่ผลิตได้',
	'6' => 'การลดของเสียหรือลดสินค้ามีตำหนิในการผลิตสินค้า เนื่องจากปรับเปลี่ยนวิธีการทำงานหรือนำเครื่องจักร เทคโนโลยีมาใช้ ทำให้ของเสียในการผลิตลดลง (เฉพาะของเสียที่ลดได้ จากการส่งเสริมจากโครงการเท่านั้น) 
    ตัวอย่างสูตรการคำนวณ มูลค่าของเสีย = (จำนวนของเสีย x ต้นทุนการผลิตต่อชิ้น) + (((จำนวนสินค้ามีตำหนิ x เวลาที่ใช้ในการซ่อมงานต่อชิ้น)/ เวลาการทำงานของพนักงานต่อคนต่อวัน) x ค่าจ้างเฉลี่ยต่อคนต่อวัน)
    ',
	'7' => 'มูลค่าผลิตภาพการผลิต หมายถึง ความสามารถหรือประสิทธิภาพในการเปลี่ยนปจจัยหรือทรัพยากรที่ใชใน การผลิตตาง ๆ ใหเปนปจจัยผลิตภัณฑหรือบริการที่มีมูลคาเพิ่มขึ้น การวัดผลิตภาพเพิ่ม Productivity Measurement เพื่อให้ทราบว่าการดําเนินงานขององค์การประสบความสําเร็จหรือไม่และ มีจุดอ่อน จุดแข็งอะไรบ้าง ซึ่งส่งผลกระทบต่อการผลิต ต้นทุน รายได้ และกําไร การวัดผลิตภาพเพิ่มสามารถวัดตั้งแต่ระดับบุคคล แผนก ฝ่าย ประเภทอุตสาหกรรม ระดับชาติ และผลที่ได้จากการวัดผู้เกี่ยวข้องนำไปปรับปรุงแก้ไข และวางแผนพัฒนาผลิตภาพต่อไป',
	'8' => 'ผลิตภาพแรงงาน คือ ความสามารถในการทำงานของแรงงานหรือผลผลิตต่อหน่วยของแรงงาน เมื่อแรงงานได้ผ่านกระบวนการพัฒนา เช่น การฝึกอบรมแรงงานทั้งก่อนหรือขณะปฏิบัติงานเพื่อให้มีความเข้าใจและมีทักษะที่ถูกต้อง การปรับเปลี่ยนรูปแบบการทำงานโดยให้แรงงานทำงานกับเครื่องจักรมากขึ้นการส่งเสริมให้แรงงานมีการศึกษาสูงขึ้นการปรับปรุงวิธีการบริหารจัดการ ธุรกิจและอุตสาหกรรมให้มีประสิทธิภาพ เป็นต้น จะทำให้แรงงาน 
    มีความสามารถในการผลิตสินค้าและบริการได้เพิ่มขึ้น ส่งผลให้ต้นทุนการผลิตลดลง
    ตัวอย่างสูตรการคำนวณ มูลค่าผลิตภาพแรงงาน = ผลผลิต/แรงงาน',
	'9' => 'มูลค่าทางเศรษฐกิจ นอกเหนือจากข้อ 1-8 ที่เกิดจากการส่งเสริมจากโครงการ  ',
	'10' => 'คำนวณจากข้อ 1-9 รวมกัน',
	'11' => 'จำนวนการจ้างงานทั้งหมด ก่อนได้รับการพัฒนาจากโครงการ',
	'12' => 'จำนวนการจ้างงานเฉพาะส่วนที่เพิ่มขึ้น/ลดลง หลังจากกิจการได้รับการพัฒนาจากโครงการ',
	'13' => 'จำนวนธุรกิจที่เข้าร่วมโครงการ นับเฉพาะที่เป็นธุรกิจหรือกิจการ',
	'14' => 'ขณะหรือหลังจากเข้าร่วมโครงการ SME การขยายสาขาใหม่ ในธุรกิจเดิม กิจการเดิม',
	'15' => 'อ้างอิงนิยาม SME ตามประกาศกฎกระทรวง
    ว่าด้วยการกำหนดลักษณะของ
    วิสาหกิจขนาดกลางและขนาดย่อม พ.ศ.2562 
    ประกาศราชกิจจานุเบกษา ณ วันที่ 20 ธันวาคม พ.ศ.2562
    ข้อ 5',
	'15.1' => '',
	'15.2' => '',
	'16' => '',
	'17' => 'SME ได้รับเงินสนับสนุนการดำเนินธุรกิจจากการยื่นกู้ หรือผลจากโครงการทำให้ SME ได้รับเงินกู้/สินเชื่อจากสถาบันการเงิน',
	'18' => 'ขณะหรือหลังจากเข้าร่วมโครงการ SME เป็นสมาชิก Cluster / เครือข่ายวิสาหกิจชุมชน ระบุชื่อ Cluster/ เครือข่ายฯ ที่เป็นสมาชิก และจำนวนสมาชิก',
	'19' => 'ขณะหรือหลังจากเข้าร่วมโครงการ SME ได้เข้าร่วมเป็นสมาชิกสมาคมที่เกี่ยวข้องกับการดำเนินธุรกิจ',
	'20' => 'หลังจากเข้าร่วมโครงการ SME ได้มีการจดทะเบียนทรัพย์สินทางปัญญา/ ยื่นขอจดทะเบียนฯ',
	'21' => 'หลังจากเข้าร่วมโครงการ SME ได้มีการจดตรายี่ห้อสินค้าและบริการ/ ยื่นขอจดตราฯ กับกระทรวงพาณิชย์',
	'22' => 'ขณะหรือหลังจากเข้าร่วมโครงการ SME ได้นำกิจการหรือ ผลิตภัณฑ์ยื่นหรือได้รับการรับรองมาตราฐานหรือได้รับ อย. เป็นต้น',
	'23' => 'ขณะหรือหลังจากเข้าร่วมโครงการ SME มีการจดทะเบียนธุรกิจ/ ยื่นขอจดทะเบียนฯเพิ่มขึ้น',
	'24' => 'ขณะหรือหลังจากเข้าร่วมโครงการ SME มีการเปิดกิจการเพิ่มใหม่ ที่ไม่ใช่การขยายสาขา',
	'25' => 'ขณะหรือหลังจากเข้าร่วมโครงการ SME มีการนำเทคโนโลยี นวัตกรรม ดิจิตอล มาใช้ในการดำเนินธุรกิจ',
);

//comment
$arr_eco_field_com = array(
	'12' => 'โปรดระบุสาเหตุ เช่น ใช้ เทคโนโลยีทดแทนแรงงานคน / ลดค่าใช้จ่าย เป็นต้น',
	'17' => '(ระบุชื่อ Cluster/เครือข่ายฯ และจำแนกจำนวนตาม Cluster)',
	'18' => '(ระบุชื่อสมาคม และจำแนกจำนวนตามสมาคม)',
	'19' => '(ระบุชื่อทะเบียนทรัพย์สิน)',
	'20' => '(ระบุชื่อตรายี่ห้อ)',
	'21' => '(ระบุชื่อมาตรฐานที่ได้รับรอง และจำแนกจำนวนตามประเภทมาตราฐานที่ได้รับ)',
	'22' => '(ระบุชื่อทะเบียนที่จด และจำแนกจำนวนตามประเภททะเบียนที่จด)',
);
///////////////////////////////////////////////////////////////////////////////////////

$select_data = "SELECT * FROM prjp_eco_temp WHERE PRJP_ID = '{$PRJP_ID}' ";
$query = $db->query($select_data);
$rec_data = $db->db_fetch_array($query);
?>
<!DOCTYPE html>
<html>

<head>
	<?php include $path . "include/inc_main_top.php"; ?>
	<script src="js/disp_file_project.js?<?php echo rand(); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {

		});

		function sum_eco_1(t) { // sum 1.
			eco_result('income'); // update value , per 1.
		}

		function eco_result(f) { // update value , per

		}

		function sum_eco_9(t) { // sum 9.
			if (t == 1) { //ก่อน
				var sale_inc = $('#sale_inc').val().replace(/,/g, "");
				var export_inc = $('#export_inc').val().replace(/,/g, "");
				var investment_inc = $('#investment_inc').val().replace(/,/g, "");
				var employment_inc = $('#employment_inc').val().replace(/,/g, "");
				var production_inc = $('#production_inc').val().replace(/,/g, "");
				var loss_inc = $('#loss_inc').val().replace(/,/g, "");
				var manufacture_inc = $('#manufacture_inc').val().replace(/,/g, "");
				var labor_inc = $('#labor_inc').val().replace(/,/g, "");
				var eco_other_inc = $('#eco_other_inc').val().replace(/,/g, "");

				if (sale_inc == '') {
					sale_inc = 0;
				}
				if (export_inc == '') {
					export_inc = 0;
				}
				if (investment_inc == '') {
					investment_inc = 0;
				}
				if (employment_inc == '') {
					employment_inc = 0;
				}
				if (production_inc == '') {
					production_inc = 0;
				}
				if (loss_inc == '') {
					loss_inc = 0;
				}
				if (manufacture_inc == '') {
					manufacture_inc = 0;
				}
				if (labor_inc == '') {
					labor_inc = 0;
				}
				if (eco_other_inc == '') {
					eco_other_inc = 0;
				}
				sum_inc = parseFloat(sale_inc) + parseFloat(export_inc) + parseFloat(investment_inc) +
					parseFloat(employment_inc) + parseFloat(production_inc) + parseFloat(loss_inc) + parseFloat(manufacture_inc) +
					parseFloat(labor_inc) + parseFloat(eco_other_inc);
				sum_inc = sum_inc.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
				$('#economic_inc').val(sum_inc);

			} else if (t == 2) { //หลัง
				var sale_same = $('#sale_same').val().replace(/,/g, "");
				var export_same = $('#export_same').val().replace(/,/g, "");
				var investment_same = $('#investment_same').val().replace(/,/g, "");
				var employment_same = $('#employment_same').val().replace(/,/g, "");
				var production_same = $('#production_same').val().replace(/,/g, "");
				var loss_same = $('#loss_same').val().replace(/,/g, "");;
				var manufacture_same = $('#manufacture_same').val().replace(/,/g, "");
				var labor_same = $('#labor_same').val().replace(/,/g, "");
				var eco_other_same = $('#eco_other_same').val().replace(/,/g, "");

				if (sale_same == '') {
					sale_same = 0;
				}
				if (export_same == '') {
					export_same = 0;
				}
				if (investment_same == '') {
					investment_same = 0;
				}
				if (employment_same == '') {
					employment_same = 0;
				}
				if (production_same == '') {
					production_same = 0;
				}
				if (loss_same == '') {
					loss_same = 0;
				}
				if (manufacture_same == '') {
					manufacture_same = 0;
				}
				if (labor_same == '') {
					labor_same = 0;
				}
				if (eco_other_same == '') {
					eco_other_same = 0;
				}
				sum_same = parseFloat(sale_same) + parseFloat(export_same) + parseFloat(investment_same) +
					parseFloat(employment_same) + parseFloat(production_same) + parseFloat(loss_same) + parseFloat(manufacture_same) +
					parseFloat(labor_same) + parseFloat(eco_other_same);
				sum_same = sum_same.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
				$('#economic_same').val(sum_same);
			}
		}

		function sum_eco_value() {

		}

		function chk_accept() {
			if ($("#chk_apt").prop('checked') == true) {
				$("#btn_accept").removeAttr('disabled');
			} else if ($("#chk_apt").prop('checked') == false) {
				$("#btn_accept").attr('disabled', 'disabled');
			}
		}

		function chk_accept2() {
			if ($("#chk_apt2").prop('checked') == true) {
				$("#btn_accept2").removeAttr('disabled');
			} else if ($("#chk_apt2").prop('checked') == false) {
				$("#btn_accept2").attr('disabled', 'disabled');
			}
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
		<div><?php include $path . "include/header.php"; ?></div>
		<div class="col-xs-12 col-sm-12">
			<ol class="breadcrumb">
				<li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
				<li><a href="disp_approve_project_temp.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>">อนุมัติการรายงานผล</a>
				</li>
				<li class="active">รายละเอียดผลตัวชี้วัดของผลผลิต</li>
			</ol>
		</div>

		<div class="col-xs-12 col-sm-12">
			<div class="groupdata">
				<form id="frm-search" method="post" action="economic_value_process.php" enctype="multipart/form-data">
					<input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
					<input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
					<input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
					<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
					<input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
					<input type="hidden" id="year_round" name="year_round" value="<?php echo $_SESSION['year_round']; ?>">
					<input type="hidden" id="code_user" name="code_user" value="<?php echo $_SESSION['sys_dept_id']; ?>">
					<input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
					<input type="hidden" id="PRJP_TYPE" name="PRJP_TYPE" value="1">
					<input type="hidden" id="fbs" name="fbs" value="<?php echo $select_ym; ?>">
					<input type="hidden" id="fbso" name="fbso" value="<?php echo $fbso; ?>">
					<input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />

					<div id="myModal" class="modal fade" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 class="modal-title"></h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-md-12">
											<font color="">import ข้อมูล โดยใช้ไฟล์ <a target="_blank" href="upload/template/economic_value_report.xlsx">"แบบประเมินมูลค่า ศก.
													สำหรับผู้รับผิดชอบโครงการ (excel)"</a></font>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<input type="file" name="imp_economic" id="imp_economic" placeholder="แบบประเมินมูลค่า ศก. สำหรับผู้รับผิดชอบโครงการ" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<font color="#FF0000">รองรับไฟล์ที่นำเข้าข้อมูลได้ เฉพาะ
												ไฟล์ที่มีนามสกุล xls และ xlsx เท่านั้น</font>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12" style="text-align:center;">
											<input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
											<label><input type="checkbox" id="chk_apt" onchange="chk_accept();">
												ยืนยันว่าการบันทึกข้อมูลครั้งนี้เป็นไปตาม </label> <a class="btn-link" href="<?php echo $path; ?>fileupload_admin/หนังสือให้ความยินยอมในการเปิดเผยข้อมูลส่วนบุคคล.pdf" target="_blank"> พ.ร.บ.คุ้มครองข้อมูลส่วนบุคคล</a>
											<br>
											<button type="button" class="btn btn-success " onClick="imp_file();" id="btn_accept" disabled><i class="fa fa-check" aria-hidden="true"></i>
												บันทึก</button>
											<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i> ยกเลิก</button>
										</div>
									</div>
								</div>
								<div class="modal-footer"></div>
							</div>
						</div>
					</div>

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

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12"> </div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 font-blue" align="center">
							<strong><?php echo $rec_head['PRJP_CODE'] . " " . text($rec_head['PRJP_NAME']) ?></strong>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading row" style="">
									<div class="pull-left" style="">ผลที่เกิดขึ้น
										หลังเข้ารับบริการจากโครงการ/มาตรการของรัฐ</div>
								</div>
								<div class="panel-body epm-gradient">
									<?php $print_form = "<a class='btn btn-info' data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"economic_report('" . $PRJP_ID . "',1);\">" . $img_print . "  พิมพ์ รายงาน</a> "; ?>
									<?php $import = "<a class=\"btn btn-success\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"import_modal('" . $PRJP_ID . "');\">นำเข้าข้อมูล</a> "; ?>

									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<?php echo $print_form; ?>&nbsp;</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-3 ">
											ประเภทผู้เข้าร่วมโครงการ
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-1 "> Micro </div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="micro" name="micro" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["micro"]); ?>">
										</div>
										<div class="col-xs-12 col-sm-1 "> ราย </div>
										<div class="col-xs-12 col-sm-1 "> Small </div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="small" name="small" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["small"]); ?>">
										</div>
										<div class="col-xs-12 col-sm-1 "> ราย </div>
										<div class="col-xs-12 col-sm-1 "> Medium </div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="medium" name="medium" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["medium"]); ?>">
										</div>
										<div class="col-xs-12 col-sm-1 "> ราย </div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-1 "> ธุรกิจวิสาหกิจชุมชน </div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="smce" name="smce" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["smce"]); ?>">
										</div>
										<div class="col-xs-12 col-sm-1 "> ราย </div>
										<div class="col-xs-12 col-sm-1 "> กลุ่มอาชีพ </div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="group_prof" name="group_prof" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["group_prof"]); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-3 "> ระบุจำนวนผู้ที่ตอบแบบรายงาน </div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="count_person" name="count_person" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["count_person"]); ?>">
										</div>
										<div class="col-xs-12 col-sm-1 "> ราย </div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-3 ">
											ชื่อผู้ให้ข้อมูล (นาย/นาง/นางสาว)
										</div>
										<div class="col-xs-12 col-sm-3 ">
											<input <?php echo $disables_txt; ?> type="text" id="Fname" name="Fname" class="form-control" value="<?php echo text($rec_data["Fname"]); ?>">
										</div>
										<div class="col-xs-12 col-sm-2 ">
											นามสกุล
										</div>
										<div class="col-xs-12 col-sm-3 ">
											<input <?php echo $disables_txt; ?> type="text" id="Lname" name="Lname" class="form-control" value="<?php echo text($rec_data["Lname"]); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-3 ">
											ตำแหน่ง
										</div>
										<div class="col-xs-12 col-sm-3 ">
											<input <?php echo $disables_txt; ?> type="text" id="position" name="position" class="form-control" value="<?php echo text($rec_data["position"]); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-3 ">
											ชื่อหน่วยงาน
										</div>
										<div class="col-xs-12 col-sm-3 ">
											<input <?php echo $disables_txt; ?> type="text" id="ORG_NAME" name="ORG_NAME" class="form-control" value="<?php echo text($rec_data["ORG_NAME"]); ?>">
										</div>
										<div class="col-xs-12 col-sm-2 ">
											กอง/สำนัก/ฝ่าย
										</div>
										<div class="col-xs-12 col-sm-3 ">
											<input <?php echo $disables_txt; ?> type="text" id="ORG_NAME2" name="ORG_NAME2" class="form-control" value="<?php echo text($rec_data["ORG_NAME2"]); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-2 ">
											เกี่ยวข้องกับโครงการในฐานะ
										</div>
										<div class="col-xs-12 col-sm-3 ">
											<?php if (empty($rec_data["associated_text"])) { ?>
												<select <?php echo $disables_txt; ?> id="associated" name="associated" class="selectbox form-control">
													<option value=""></option>
													<option value="1" <?php echo $rec_data["associated"] == 1 ? "selected" : ""; ?>>
														หัวหน้าโครงการ</option>
													<option value="2" <?php echo $rec_data["associated"] == 2 ? "selected" : ""; ?>>
														ผู้รับผิดชอบโครงการ(ผู้ดำเนินโครงการ)</option>
													<option value="3" <?php echo $rec_data["associated"] == 3 ? "selected" : ""; ?>>
														ผู้ประสานงานโครงการ</option>
												</select>
											<?php } else { ?>
												<input <?php echo $disables_txt; ?> type="text" id="associated_text" name="associated_text" class="form-control" value="<?php echo text($rec_data["associated_text"]); ?>" readonly>
											<?php } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-1 ">
											โทรศัพท์
										</div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="telephone" name="telephone" class="form-control" value="<?php echo $rec_data["telephone"]; ?>">
										</div>
										<div class="col-xs-12 col-sm-2 ">
											โทรศัพท์มือถือ
										</div>
										<div class="col-xs-12 col-sm-2 ">
											<input <?php echo $disables_txt; ?> type="text" id="mobile" name="mobile" class="form-control" value="<?php echo $rec_data["mobile"]; ?>">
										</div>
										<div class="col-xs-12 col-sm-1 ">
											E-Mail
										</div>
										<div class="col-xs-12 col-sm-3 ">
											<input <?php echo $disables_txt; ?> type="text" id="Email" name="Email" class="form-control" value="<?php echo $rec_data["Email"]; ?>">
										</div>
									</div>
									<div class="clearfix"></div>
									<div class="row">
										<div class="col-xs-12 col-sm-12 tb2_v" id="show_rb_99999">
											<div class="table-responsive">
												<table width="100%" class="table table-bordered table-striped table-hover table-condensed" id="tb_file_prjp">
													<thead>
														<tr class="bgHead">
															<th width="10%" colspan="2">
																<div align="center"><strong>รายการ</strong></div>
															</th>
															<th width="15%" colspan="1">
																<div align="center">
																	<strong>มูลค่าเพิ่มทางเศรษฐกิจที่เกิดขึ้นจริง เมื่อสิ้นสุดโครงการ<br />(มูลค่าเปรียบเทียบก่อนและหลังการพัฒนา)</strong>
																</div>
															</th>
															<th width="15%" colspan="1">
																<div align="center">
																	<strong>มูลค่าเพิ่มทางเศรษฐกิจประมาณการที่จะเกิดขึ้นใน 1 ปีข้างหน้า<br />(มูลค่าเปรียบเทียบก่อนและหลังการพัฒนา)</strong>
																</div>
															</th>
															<th width="20%" colspan="1">
																<div align="center">
																	<strong>วิธีการคำนวณมูลค่าเพิ่มทางเศรษฐกิจที่เกิดขึ้นจริง เมื่อสิ้นสุดโครงการ<br />(เช่น จำนวนยอดขาย x ราคาขาย x เดือน)</strong>
																</div>
															</th>
															<th width="20%" colspan="1">
																<div align="center">
																	<strong>วิธีการคำนวณมูลค่าเพิ่มทางเศรษฐกิจประมาณการที่จะเกิดขึ้นใน 1 ปีข้างหน้า<br />(เช่น เจำนวนยอดขาย x ราคาขาย x เดือน เป็นต้น)</strong>
																</div>
															</th>
															<th width="20%" colspan="1">
																<div align="center">
																	<strong>วิธีการเก็บข้อมูลเพื่อนำมาใช้ในการคำนวณมูลค่าทางเศรษฐกิจ<br />(เช่น เก็บข้อมูลจากการจัดกิจกรรมออกร้านของผู้ประกอบการ เป็นต้น)</strong>
																</div>
															</th>
														</tr>
													</thead>
													<tbody>
														<?php for ($no = 1; $no <= 9; $no++) { ?>
															<tr>
																<td colspan="1"> <?php echo $no . "."; ?></td>
																<td colspan="1">
																	<span><?php echo $arr_eco_field_det1[$no]; ?></span>
																	<?php if (trim($arr_eco_i[$no]) != '') {
																		echo '<a class="data-info" data-placement="right" data-title="" data-content="' . $arr_eco_i[$no] . '" > </a>';
																	} ?>
																</td>
																<td colspan="1"> <input style="" type="text" <?php echo $disables_txt; ?> id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(1);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no] . '_inc'], 2) ?>">
																</td>
																<td colspan="1"> <input style="" type="text" <?php echo $disables_txt; ?> id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no] . '_same'], 2) ?>">
																</td>
																<td colspan="1">
																	<textarea <?php echo $readonly_txt ?> style="width:100%" <?php echo $readonly_txt ?> row="3" id="<?php echo $arr_eco_field[$no]; ?>_cal_end" name="<?php echo $arr_eco_field[$no]; ?>_cal_end" class="form-control"><?php echo $rec_data[$arr_eco_field[$no] . '_cal_end'] ?></textarea>
																</td>
																<td colspan="1">
																	<textarea <?php echo $readonly_txt ?> style="width:100%" <?php echo $readonly_txt ?> row="3" id="<?php echo $arr_eco_field[$no]; ?>_cal_year" name="<?php echo $arr_eco_field[$no]; ?>_cal_year" class="form-control"><?php echo $rec_data[$arr_eco_field[$no] . '_cal_year'] ?></textarea>
																</td>
																<td colspan="1">
																	<textarea <?php echo $readonly_txt ?> style="width:100%" <?php echo $readonly_txt ?> row="3" id="<?php echo $arr_eco_field[$no]; ?>_cal_data" name="<?php echo $arr_eco_field[$no]; ?>_cal_data" class="form-control"><?php echo $rec_data[$arr_eco_field[$no] . '_cal_data'] ?></textarea>
																</td>
															</tr>
														<?php } ?>
														<tr><?php $no = "10"; ?>
															<td colspan="1"> <?php echo $no . "."; ?></td>
															<td colspan="1">
																<span><?php echo $arr_eco_field_det1[$no]; ?></span>
																<?php if (trim($arr_eco_i[$no]) != '') {
																	echo '<a class="data-info" data-placement="right" data-title="" data-content="' . $arr_eco_i[$no] . '" > </a>';
																} ?>
															</td>
															<td colspan="1"> <input style="" type="text" <?php echo $disables_txt; ?> id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no] . '_inc'], 2) ?>" readonly></td>
															<td colspan="1"> <input style="" type="text" <?php echo $disables_txt; ?> id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no] . '_same'], 2) ?>" readonly></td>
														</tr>
													</tbody>
												</table>
												<table width="100%" class="table table-bordered table-striped table-hover table-condensed" id="tb_file_prjp">
													<tbody>
														<tr>
															<td colspan="10">
																<strong><u>ข้อมูลประโยชน์อื่นๆที่เกิดขึ้นทั้งในเชิงปริมาณและ/หรือเชิงคุณภาพ</u></strong>
															</td>
														</tr>
														<?php
														foreach ($arr_eco_field_det2 as $no => $text) { ?>
															<tr>
																<?php $dot = explode(".", $no); ?>
																<td colspan="1" nowrap>
																	<?php if ($text != '') {
																		echo empty($dot[1]) ? $no . "." : "";
																	} ?></td>
																<td colspan="3">
																	<span><?php if ($text != '') {
																				echo empty($dot[1]) ? $text : $no . ". " . $text;
																			} ?></span>
																	<?php if (trim($arr_eco_i[$no]) != '') {
																		echo '<a class="data-info" data-placement="right" data-title="" data-content="' . $arr_eco_i[$no] . '" > </a>';
																	} ?>
																</td>
																<?php
																if (trim($arr_col_D[$no]) != '') { ?>
																	<td align="center">
																		<span><?php echo $arr_col_D[$no]; ?></span>
																	</td>
																	<td width="15%"> <input style="" type="text" <?php echo $disables_txt; ?> id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no] . '_same'], 2) ?>">
																	</td>
																	<td width="10%" align="center">
																		<span><?php echo $arr_col_F[$no]; ?></span>
																	</td>
																	<?php
																} else {
																	if ($no == '16.1' || $no == '16.2') { ?>
																		<td colspan="6">
																			<textarea <?php echo $readonly_txt ?> id="<?php echo $arr_eco_field[$no]; ?>_comment" name="<?php echo $arr_eco_field[$no]; ?>_comment" class="form-control"><?php echo text($rec_data[$arr_eco_field[$no] . '_comment']); ?></textarea>
																		</td>
																	<?php
																	} else { ?>
																		<td colspan="3"></td>
																	<?php
																	}
																}
																if (trim($arr_col_G[$no]) != '') { ?>
																	<td align="center">
																		<span><?php echo $arr_col_G[$no]; ?></span>
																	</td>
																	<td width="15%"> <input style="" type="text" <?php echo $disables_txt; ?> id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no] . '_value'], 2) ?>">
																	</td>
																	<td align="center">
																		<span><?php echo $arr_col_I[$no]; ?></span>
																	</td>
																	<?php
																} else {
																	if ($no == '16.1' || $no == '16.2') {
																	} else { ?>
																		<td colspan="3"></td>
																<?php
																	}
																} ?>
															</tr>
															<?php
															if (trim($arr_eco_field_com[$no]) != '') { ?>
																<tr>
																	<td colspan="1" nowrap>&nbsp; </td>
																	<td colspan="3">
																		<span><?php echo $arr_eco_field_com[$no]; ?></span>
																		<?php if (trim($arr_eco_i[$no]) != '') {
																			echo '<a class="data-info" data-placement="right" data-title="" data-content="' . $arr_eco_i[$no] . '" > </a>';
																		} ?>
																	</td>
																	<td colspan="6">
																		<textarea <?php echo $readonly_txt ?> id="<?php echo $arr_eco_field[$no]; ?>_comment" name="<?php echo $arr_eco_field[$no]; ?>_comment" class="form-control"><?php echo text($rec_data[$arr_eco_field[$no] . '_comment']); ?></textarea>
																	</td>
																</tr>
														<?php
															}
														} ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12"><?php echo $print_form; ?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clearfix" align="center"></div>
					<div class="clearfix"></div>
				</form>
			</div>
		</div>
		<?php include $path . "include/footer.php"; ?>
	</div>
</body>

</html>
<?php echo form_model('myModal1', 'เลือกวันที่ออกรายงาน', 'show_display', '', '', '1'); ?>
<div class="modal fade" id="myModal"></div>
<div class="modal fade" id="myModal1"></div>