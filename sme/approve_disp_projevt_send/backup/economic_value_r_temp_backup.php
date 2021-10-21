<?php
session_start();
$path = "../../";
$path_a="../../fileupload/file_prjp/";
include($path."include/config_header_top.php");
$link = "r=home&menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
$ACT = '27';
if(date("d")*1>='10'){
$ymchk = (date("Y")+543).date("m")+1;
}else{
$ymchk = (date("Y")+543).date("m");	
}
if($_POST['PRJP_ID']!=''){
$PRJP_ID = $_POST['PRJP_ID'];	
}else{
$PRJP_ID = $PRJP_ID;	
}
if(date("d")>10){
$select_ym = (date("Y")+543).sprintf("%'02d",date("m"));
}else{
$select_ym = (date("Y")+543).sprintf("%'02d",(date("m")-1));	
}

$month = array("10"=>"ต.ค.","11"=>"พ.ย.","12"=>"ธ.ค.","1"=>"ม.ค.","2"=>"ก.พ.","3"=>"มี.ค.","4"=>"เม.ย.","5"=>"พ.ค.","6"=>"มิ.ย.","7"=>"ก.ค.","8"=>"ส.ค.","9"=>"ก.ย.");
$month_full = array("1"=>"มกราคม","2"=>"กุมภาพันธ์","3"=>"มีนาคม","4"=>"เมษายน","5"=>"พฤษภาคม","6"=>"มิถุนายน","7"=>"กรกฎาคม","8"=>"สิงหาคม","9"=>"กันยายน","10"=>"ตุลาคม","11"=>"พฤศจิกายน","12"=>"ธันวาคม");
$sql_head="SELECT PRJP_CODE,PRJP_NAME,EDATE_PRJP,SDATE_PRJP,PRJP_CON_ID,BDG_TYPE_ID FROM prjp_project WHERE PRJP_ID = '".$PRJP_ID."'";
$query_head = $db->query($sql_head);
$rec_head = $db->db_fetch_array($query_head);

$ms = substr($rec_head['SDATE_PRJP'],5,2)*1;
$ys = substr($rec_head['SDATE_PRJP'],0,4)+543;
$me = substr($rec_head['EDATE_PRJP'],5,2)*1;
$ye = substr($rec_head['EDATE_PRJP'],0,4)+543;

$yse = ((($ye-$ys)*12))-(12-$me);
$row_col = (((12-$ms)+1)+((($ye-$ys)-1)*12)+(12-(12-$me)));
$fbs = $ys.sprintf("%'02d",$ms);
$fbe = $ye.sprintf("%'02d",$me);

if($select_ym>$fbe){
	$select_ym = $fbe;	
}else{
	$select_ym = $select_ym;	
}

$x = $fbs;
while($x<=$fbe){
			$m[] = $x;
			$sm = substr($x,4,2);
			$sy = substr($x,0,4);
			if($sm=='12'){
			$x = ($sy+1)."01";	
			}else{
			$x++;	
			}
}

//field
$arr_eco_field = array(
	'1'		=> 'income', 
	'1.1'	=> 'sale', //1
	'1.2'	=> 'export', //3
	'1.3'	=> 'income_other',
	'2'		=> 'investment', //4
	'3'		=> 'employment', 
	'4'		=> 'production', //6
	'5'		=> 'loss',	//8
	'6'		=> 'manufacture', //10
	'7'		=> 'labor',	//11
	'8'		=> 'eco_other',
	'9'		=> 'economic',
	
	'10'	=> 'be_employ',
	'11'	=> 'employ', //12 
	'12'	=> 'business_join', //14
	'13'	=> 'business_new', //14.1
	'14'	=> 'business_expand', //14.2
	'15'	=> 'operator',
	'15.1'	=> 'province',
	'15.2'	=> 'country',
	'16'	=> '', 
	'16.1'	=> 'business_up_1', //15.1
	'16.2'	=> 'business_up_2', //15.2
	'16.3'	=> 'business_up_3', //15.3
	'17'	=> '', 
	'17.1'	=> 'tax_corporation', //16.1
	'17.2'	=> 'tax_layman', //16.2
	'18'	=> 'sme_support', //17
	'19'	=> 'cluster_up', //18
	'20'	=> 'sme_connect', //20
	'21'	=> 'sme_award', //21
	'22'	=> 'sme_copyright', //22
	'23'	=> 'sme_brand', //23
	'24'	=> 'sme_standard', //24
	'25'	=> 'sme_regis',
	'26'	=> 'sme_account', 
	'27'	=> '',
	'27.1'	=> 'machine_new',
	'27.2'	=> 'machine_2h', 
	'27.3'	=> 'machine_repair', 
	'28'	=> 'business_digital', 
	);

//field Detail
$arr_eco_field_det1 = array(
	'1'		=> 'มูลค่ารายได้', 
	'1.1'	=> 'มูลค่ายอดขายในประเทศ', 
	'1.2'	=> 'มูลค่ายอดขายจากการส่งออก', 
	'1.3'	=> 'มูลค่ารายได้อื่นๆ', 
	'2'		=> 'มูลค่าการลงทุน', 
	'3'		=> 'มูลค่าการจ้างงาน', 
	'4'		=> 'มูลค่าการลดต้นทุน',
	'5'		=> 'มูลค่าการลดของเสีย', 
	'6'		=> 'มูลค่าผลิตภาพการผลิต', 
	'7'		=> 'มูลค่าผลิตภาพแรงงาน',
	'8'		=> 'มูลค่าอื่นๆ (ถ้ามี) ระบุ',
	'9'		=> 'มูลค่าทางเศรษฐกิจ (คำนวณจากข้อ 1.1-8 รวมกัน)',
);
$arr_eco_field_det2 = array(
	'10'	=> 'การจ้างงานก่อนเข้าร่วมโครงการ', 
	'11'	=> 'ปัจจุบันมีการจ้างงาน <strong><u><i>เพิ่ม/ลด</strong></u></i> ทั้งหมด', 
	'12'	=> 'ธุรกิจเข้าร่วมโครงการ', 
	'13'	=> 'SME ประกอบธุรกิจเดิมและมีการจัดตั้งธุรกิจใหม่เพิ่มหลังเข้าร่วมโครงการ ', 
	'14'	=> 'มีการขยายสาขาธุรกิจ (หลังเข้าร่วมโครงการ)',
	'15'	=> 'เป็นผู้ประกอบการรายใหม่ ในสาขาธุรกิจ',
	'15.1'	=> 'ในพื้นที่จังหวัด',
	'15.2'	=> 'ในพื้นที่ประเทศ',
	'16'	=> 'ธุรกิจได้รับการพัฒนา',
	'16.1'	=> 'ยกระดับจากกลุ่มอาชีพ เป็นวิสาหกิจชุมชน',
	'16.2'	=> 'ยกระดับจากผู้ประกอบการรายย่อย (Micro) เป็น SMEs ขนาดย่อม',
	'16.3'	=> 'ยกระดับจาก SMEs ขนาดย่อมเป็น SMEs ขนาดกลาง',
	'17'	=> 'รูปแบบการเสียภาษีเงินได้ในปีที่ผ่านมา',
	'17.1'	=> 'นิติบุคคล',
	'17.2'	=> 'บุคคลธรรมดา',
	'18'	=> 'SME ได้รับสินเชื่อสนับสนุนการดำเนินธุรกิจ',
	'19'	=> 'SME เป็นสมาชิก Cluster / เครือข่ายวิสาหกิจชุมชน',
	'20'	=> 'SME เป็นสมาชิกสมาคม',
	'21'	=> 'ธุรกิจได้รับรางวัลจากการเข้าร่วมโครงการส่งเสริม SME',
	'22'	=> 'SME มีการจดทะเบียนทรัพย์สินทางปัญญา/ยื่นขอจดทะเบียนฯ',
	'23'	=> 'SME มีการจดตรายี่ห้อสินค้าและบริการ /ยื่นขอจดตราฯเพิ่มขึ้น',
	'24'	=> 'SME ได้รับการรับรองมาตรฐาน /ยื่นขอรับรองมาตรฐานฯเพิ่มขึ้น',
	'25'	=> 'SME จดทะเบียนธุรกิจ /ยื่นขอจดทะเบียนฯเพิ่มขึ้น',
	'26'	=> 'SME มีบัญชีเดียวเพิ่มขึ้น',
	'27'	=> 'ภายหลังเข้าร่วมโครงการ SME มีการ',
	'27.1'	=> 'ซื้อเครื่องจักรใหม่ ',
	'27.2'	=> 'ซื้อเครื่องจักรมือสอง',
	'27.3'	=> 'บำรุงรักษาฟื้นฟูเครื่องจักรที่มีอยู่เดิม',
	'28'	=> 'SME มีการนำเทคโนโลยี นวัตกรรม ดิจิตอล มาใช้ในการดำเนินธุรกิจ',
	
);

//column D
$arr_col_D = array(
	'10'	=> 'จำนวน', 
	'11'	=> '<strong><u>เพิ่มขึ้น</u></strong>(จำนวน)', 
	'12'	=> 'จำนวน', 
	'13'	=> 'จำนวน', 
	'14'	=> 'จำนวน', 
	'15'	=> 'จำนวน',
	'16.1'	=> 'จำนวน',
	'16.2'	=> 'จำนวน',
	'16.3'	=> 'จำนวน',
	'17.1'	=> 'จำนวน',
	'17.2'	=> 'จำนวน',
	'18'	=> 'จำนวน',
	'19'	=> '<strong><u>Cluster</strong></u> จำนวน',
	'20'	=> 'จำนวน',
	'21'	=> 'จำนวน',
	'22'	=> '<strong><u>จดทะเบียน</strong></u> จำนวน',
	'23'	=> '<strong><u>จดตราฯ</strong></u> จำนวน',
	'24'	=> '<strong><u>ได้รับการรับรอง</strong></u> จำนวน',
	'25'	=> '<strong><u>จดทะเบียน</strong></u> จำนวน',
	'26'	=> 'จำนวน',
	'27.1'	=> 'จำนวน',
	'27.2'	=> 'จำนวน',
	'27.3'	=> 'จำนวน',
	'28'	=> 'จำนวน',
	
);
//column F
$arr_col_F = array(
	'10'	=> 'คน', 
	'11'	=> 'คน', 
	'12'	=> 'กิจการ/วิสาหกิจ', 
	'13'	=> 'กิจการ', 
	'14'	=> 'แห่ง', 
	'15'	=> 'ราย',
	'16.1'	=> 'ราย',
	'16.2'	=> 'ราย',
	'16.3'	=> 'ราย',
	'17.1'	=> 'ราย',
	'17.2'	=> 'ราย',
	'18'	=> 'ราย',
	'19'	=> 'ราย',
	'20'	=> 'ราย',
	'21'	=> 'กิจการ',
	'22'	=> 'กิจการ',
	'23'	=> 'กิจการ',
	'24'	=> 'กิจการ',
	'25'	=> 'กิจการ',
	'26'	=> 'กิจการ',
	'27.1'	=> 'ราย',
	'27.2'	=> 'ราย',
	'27.3'	=> 'ราย',
	'28'	=> 'ราย',
	
);
//column G
$arr_col_G = array( 
	'11'	=> '<strong><u>ลดลง</u></strong>(จำนวน)', 
	'13'	=> 'เงินลงทุน', 
	'14'	=> 'เงินลงทุน',
	'17.1'	=> 'จำนวน',
	'17.2'	=> 'จำนวน',
	'18'	=> 'จำนวน',
	'19'	=> '<strong><u>เครือข่ายวิสาหกิจ</strong></u> จำนวน',
	'22'	=> '<strong><u>ยื่นขอจดฯ</strong></u> จำนวน',
	'23'	=> '<strong><u>ยื่นขอจดตราฯ</strong></u> จำนวน',
	'24'	=> '<strong><u>ขอรับรองฯ</strong></u> จำนวน',
	'25'	=> '<strong><u>ยื่นขอจดฯ</strong></u> จำนวน',
);
//column I
$arr_col_I = array( 
	'11'	=> 'คน', 
	'13'	=> 'บาท', 
	'14'	=> 'บาท', 
	'17.1'	=> 'บาท/ปี',
	'17.2'	=> 'บาท/ปี',
	'18'	=> 'บาท',
	'19'	=> 'กิจการ',
	'22'	=> 'กิจการ',
	'23'	=> 'กิจการ',
	'24'	=> 'กิจการ',
	'25'	=> 'กิจการ',
);

//infomation
$arr_eco_i = array(
	'1'		=> '', 
	'1.1'	=> 'ยอดขายสินค้า และบริการหลักจากการดำเนินกิจการ (นับเฉพาะยอดขายของผลิตภัณฑ์หรือบริการ ที่เกิดจากการส่งเสริมจากโครงการ เช่น ผลิตภัณฑ์ที่เกิดจากการส่งเสริม, พื้นที่/ลูกค้า ที่ได้จากการส่งเสริม (ได้จากการจัดงาน) เป็นต้น และไม่รวมยอดขายจากการส่งออก)', 
	'1.2'	=> 'ยอดขายจากการส่งออกต่างประเทศ (เฉพาะยอดการส่งออก ที่เกิดจากการส่งเสริมจากโครงการเท่านั้น เช่น ลูกค้าที่ได้จากการจัดงานในต่างประเทศ, ลูกค้าจากการจับคู่ทางธุรกิจโดยเป็นลูกค้าต่างประเทศ เป็นต้น)', 
	'1.3'	=> 'รายได้อื่นๆ ที่เกิดขึ้น จากการดำเนินกิจการ เช่น ค่าเช่าหน้าร้าน เป็นต้น', 
	'2'		=> 'การลงทุนทั้งหมดที่เกิดขึ้นในกิจการ เช่น ก่อสร้างธุรกิจใหม่, ขยายธุรกิจ(ไม่รวมการจ้างงานเพิ่ม), การซื้อ/ปรับปรุงเครื่องจักร เครื่องมือ อุปกรณ์, ซื้อซอฟต์แวร์, ทำ R&D เป็นต้น', 
	'3'		=> 'จำนวนเงินเดือน ค่าจ้าง ค่าตอบแทนที่จ่ายให้ผู้ปฏิบัติงานในธุรกิจ (นับเฉพาะมูลค่าการจ้างงานที่เพิ่มขึ้นจากการส่งเสริมจากโครงการเท่านั้น)', 
	'4'		=> 'ต้นทุนการผลิต ต้นทุนวัตถุดิบหรือวัสดุ ต้นทุนค่าแรงงาน และต้นทุนค่าใช้จ่ายในการผลิตหรือประกอบธุรกิจ เช่น ต้นทุนพลังงาน ต้นทุนโลจิสติกส์ ต้นทุนซ่อมบำรุงเครื่องจักร เป็นต้น (นับเฉพาะต้นทุนในส่วนที่ลดได้ อันเนื่องจากโครงการส่งเสริมเท่านั้น) ตัวอย่าง ต้นทุนแรงงานที่ลดลง อันเนื่องจากโครงการได้ส่งเสริมให้นำเทคโนโลยีระบบมาใช้ทดแทนแรงงานคน จะนับเฉพาะค่าแรงในส่วนที่ถูกทดแทนด้วยระบบเท่านั้น การคำนวณ ต้นทุนการผลิตก่อนเข้าร่วมโครงการ = ต้นทุนค่าจ้างพนักงานผลิตสินค้า จำนวน 10 คน คนละ 10,000 บาทต่อเดือน ทั้งปีเท่ากับ 10x12x10,000 = 1,200,000 บาท ต้นทุนการผลิตหลังเข้าร่วมโครงการ = ต้นทุนค่าจ้างพนักงานควบคุมระบบ จำนวน 3 คน คนละ 15,000 บาทต่อเดือน ทั้งปีเท่ากับ 3x12x15,000 = 540,000 บาท',
	'5'		=> 'การลดของเสีย ลดสินค้ามีตำหนิ (เฉพาะของเสียที่ลดได้ จากการส่งเสริมจากโครงการเท่านั้น) ตัวอย่าง การลดของเสียในการผลิตสินค้า เนื่องจากปรับเปลี่ยนวิธีการทำงาน ทำให้ของเสียในการผลิตลดลง การคำนวณ ของเสียในการผลิตก่อนเข้าร่วมโครงการ = 400 ชิ้นต่อปี ต้นทุนของเสียชิ้นละ 10 บาท ทั้งปีเท่ากับ 400x10 = 4,000 บาท ของเสียในการผลิตหลังเข้าร่วมโครงการ = 50 ชิ้นต่อปี ต้นทุนของเสียชิ้นละ 10 บาท ทั้งปีเท่ากับ 50x10 = 500 บาท ', 
	'6'		=> 'คือ สัดส่วน output/input โดยนับจากคุณภาพของสินค้าและบริการเป็นงานดีเท่านั้น เช่น ในเวลา 1 ชั่วโมง ผลิตสินค้าได้ 100 ชิ้น (เป็นของดี 80 ชิ้น ของเสีย 20 ชิ้น) ผลิตภาพเท่ากับ 80 ชิ้น/ชม.  นำไปคิดต่อเป็นมูลค่าผลิตภาพการผลิตบาทต่อปี ', 
	'7'		=> 'ผลิตภาพแรงงาน คือ ขีดความสามารถในการผลิตของปัจจัยการผลิต(แรงงาน)หน่วยหนึ่งว่าจะก่อให้เกิดผลผลิตได้เท่าใดต่อหนึ่งหน่วยระยะเวลาหนึ่ง บรรลุวัตถุประสงค์หรือไม่ เช่น ตอบสนองความต้องการพึงพอใจของลูกค้า เป็นต้น ตัวอย่าง โครงการส่งเสริมการฝึกอบรมแรงงานให้มีทักษะที่เพิ่มขึ้นก่อนเข้าร่วมโครงการผลิตภาพแรงงาน 1 คน สามารถผลิตได้ 10 ชิ้น ต่อชม. หลังเข้าร่วมโครงการผลิตภาพแรงงาน 1 คนสามารถผลิตได้ 20 ชิ้นต่อชม. นำไปคิดต่อเป็นมูลค่าผลิตภาพแรงงานบาทต่อปี ',
	'8'		=> 'มูลค่าทางเศรษฐกิจ นอกเหนือจากข้อ 1-7 ที่เกิดจากการส่งเสริมจากโครงการ ',
	'9'		=> 'คำนวณจากข้อ 1.1-8 รวมกัน',
	'10'	=> 'จำนวนการจ้างงานทั้งหมด ก่อนได้รับการพัฒนาจากโครงการ', 
	'11'	=> 'จำนวนการจ้างงานเฉพาะส่วนที่เพิ่มขึ้น/ลดลง หลังจากกิจการได้รับการพัฒนาจากโครงการ', 
	'12'	=> 'จำนวนธุรกิจที่เข้าร่วมโครงการ นับเฉพาะที่เป็นธุรกิจหรือกิจการ', 
	'13'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME มีการเปิดกิจการเพิ่มใหม่ ที่ไม่ใช่การขยายสาขา', 
	'14'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME การขยายสาขาใหม่ ในธุรกิจเดิม กิจการเดิม',
	'15'	=> 'เป็นผู้ประกอบการรายใหม่ ในสาขาธุรกิจ (อ้างอิงสาขาธุรกิจ จากประกาศกระทรวงอุตสาหกรรม ปี2555) พื้นที่จังหวัด ประเทศ',
	'15.1'	=> '',
	'15.2'	=> '',
	'16'	=> '',
	'16.1'	=> '',
	'16.2'	=> '',
	'16.3'	=> '',
	'17'	=> 'ประเภทการเสียภาษีเงินได้ของผู้ประกอบการในปีที่ผ่านมา',
	'17.1'	=> '',
	'17.2'	=> '',
	'18'	=> 'SME ได้รับเงินสนับสนุนการดำเนินธุรกิจจากการยื่นกู้ หรือผลจากโครงการทำให้ SME ได้รับเงินกู้/สินเชื่อจากสถาบันการเงิน',
	'19'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME เป็นสมาชิก Cluster / เครือข่ายวิสาหกิจชุมชน ระบุชื่อ Cluster/ เครือข่ายฯ ที่เป็นสมาชิก และจำนวนสมาชิก',
	'20'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME ได้เข้าร่วมเป็นสมาชิกสมาคมที่เกี่ยวข้องกับการดำเนินธุรกิจ',
	'21'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME ได้รับรางวัลจากการพัฒนากิจการ หรือ พัฒนาผลิตภัณฑ์ ที่เข้าร่วมโครงการ',
	'22'	=> 'หลังจากเข้าร่วมโครงการ SME ได้มีการจดทะเบียนทรัพย์สินทางปัญญา/ ยื่นขอจดทะเบียนฯ',
	'23'	=> 'หลังจากเข้าร่วมโครงการ SME ได้มีการจดตรายี่ห้อสินค้าและบริการ/ ยื่นขอจดตราฯ กับกระทรวงพาณิชย์',
	'24'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME ได้นำกิจการหรือ ผลิตภัณฑ์ยื่นหรือได้รับการรับรองมาตราฐานหรือได้รับ อย. เป็นต้น',
	'25'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME มีการจดทะเบียนธุรกิจ/ ยื่นขอจดทะเบียนฯเพิ่มขึ้น',
	'26'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME จัดทำบัญชีและงบการเงินให้สอดคล้องกับสภาพที่แท้จริงของกิจการเพียงชุดเดียว',
	'27'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME ปรับเปลี่ยนเครื่องจักรหรือฟื้นฟูเครื่องจักร',
	'27.1'	=> '',
	'27.2'	=> '',
	'27.3'	=> '',
	'28'	=> 'ขณะหรือหลังจากเข้าร่วมโครงการ SME มีการนำเทคโนโลยี นวัตกรรม ดิจิตอล มาใช้ในการดำเนินธุรกิจ',
);

//comment
$arr_eco_field_com = array(
	'15'	=> '(ระบุชื่อสาขาธุรกิจ)', 
	'19'	=> '(ระบุชื่อ Cluster/เครือข่ายฯ และจำแนกจำนวนตาม Cluster)',
	'20'	=> '(ระบุชื่อสมาคม และจำแนกจำนวนตามสมาคม)',
	'21'	=> '(ระบุชื่อรางวัล)',
	'22'	=> '(ระบุชื่อทะเบียนทรัพย์สิน)',
	'23'	=> '(ระบุชื่อตรายี่ห้อ)',
	'24'	=> '(ระบุชื่อมาตรฐานที่ได้รับรอง และจำแนกจำนวนตามประเภทมาตราฐานที่ได้รับ)',
	'25'	=> '(ระบุชื่อทะเบียนที่จด และจำแนกจำนวนตามประเภททะเบียนที่จด)',
);

///////////////////////////////////////////////////////////////////////////////////////

$select_data = "select * from prjp_eco_temp WHERE PRJP_ID = '{$PRJP_ID}' ";
$query = $db->query($select_data);
$rec_data = $db->db_fetch_array($query);
?>
<!DOCTYPE html>
<html>
<head>
	<?php include($path."include/inc_main_top.php"); ?>
<script src="js/disp_file_project.js?<?php echo rand(); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {

});

function sum_eco_1(t){ // sum 1.
	if(t==1){ //ก่อน
		var sale_inc = $('#sale_inc').val().replace(/,/g, "");
		var export_inc = $('#export_inc').val().replace(/,/g, "");
		var income_other_inc = $('#income_other_inc').val().replace(/,/g, "");
		if(sale_inc == ''){sale_inc = 0;}
		if(export_inc == ''){export_inc = 0;}
		if(income_other_inc == ''){income_other_inc = 0;}
		sum_inc = parseFloat(sale_inc) + parseFloat(export_inc) + parseFloat(income_other_inc);
		sum_inc = sum_inc.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
		$('#income_inc').val(sum_inc);
	}else if(t==2){ //หลัง
		var sale_same = $('#sale_same').val().replace(/,/g, "");
		var export_same = $('#export_same').val().replace(/,/g, "");
		var income_other_same = $('#income_other_same').val().replace(/,/g, "");
		if(sale_same == ''){sale_same = 0;}
		if(export_same == ''){export_same = 0;}
		if(income_other_same == ''){income_other_same = 0;}
		sum_same  = parseFloat(sale_same) + parseFloat(export_same) + parseFloat(income_other_same);
		sum_same = sum_same.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
		$('#income_same').val(sum_same);
	}
	eco_result('income');// update value , per 1.
}
function eco_result(f){ // update value , per
	var inc = $('#'+f+'_inc').val().replace(/,/g, "");
	var same = $('#'+f+'_same').val().replace(/,/g, "");
	if(inc == ''){inc = 0;}
	if(same == ''){same = 0;}
	if(inc == 0 && same > 0){
		val = parseFloat(same) - parseFloat(inc);
		per = 100;
		val = val.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
		per = per.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	}else{
		val = parseFloat(same) - parseFloat(inc);
		per = (val/inc)*100;
		if(isNaN(per)) {
			per = 0;
		}
		val = val.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
		per = per.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	}
	
	
	$('#'+f+'_value').val(val);
	$('#'+f+'_per').val(per);
	
}
function sum_eco_9(t){ // sum 9.
	if(t==1){ //ก่อน
		var sale_inc = $('#sale_inc').val().replace(/,/g, "");
		var export_inc = $('#export_inc').val().replace(/,/g, "");
		var income_other_inc = $('#income_other_inc').val().replace(/,/g, "");
		var investment_inc = $('#investment_inc').val().replace(/,/g, "");
		var employment_inc = $('#employment_inc').val().replace(/,/g, "");
		var production_inc = $('#production_inc').val().replace(/,/g, "");
		var loss_inc = $('#loss_inc').val().replace(/,/g, "");
		var manufacture_inc = $('#manufacture_inc').val().replace(/,/g, "");
		var labor_inc = $('#labor_inc').val().replace(/,/g, "");
		var eco_other_inc = $('#eco_other_inc').val().replace(/,/g, "");
		if(sale_inc == ''){sale_inc = 0;}
		if(export_inc == ''){export_inc = 0;}
		if(income_other_inc == ''){income_other_inc = 0;}
		if(investment_inc == ''){investment_inc = 0;}
		if(employment_inc == ''){employment_inc = 0;}
		if(production_inc == ''){production_inc = 0;}
		if(loss_inc == ''){loss_inc = 0;}
		if(manufacture_inc == ''){manufacture_inc = 0;}
		if(labor_inc == ''){labor_inc = 0;}
		if(eco_other_inc == ''){eco_other_inc = 0;}
		sum_inc = parseFloat(sale_inc) + parseFloat(export_inc) + parseFloat(income_other_inc) + parseFloat(investment_inc) 
			+ parseFloat(employment_inc) + parseFloat(production_inc) + parseFloat(loss_inc) + parseFloat(manufacture_inc)
			+ parseFloat(labor_inc)+ parseFloat(eco_other_inc);
		sum_inc = sum_inc.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
		$('#economic_inc').val(sum_inc);
	}else if(t==2){ //หลัง
		var sale_same = $('#sale_same').val().replace(/,/g, "");
		var export_same = $('#export_same').val().replace(/,/g, "");
		var income_other_same = $('#income_other_same').val().replace(/,/g, "");
		var investment_same = $('#investment_same').val().replace(/,/g, "");
		var employment_same = $('#employment_same').val().replace(/,/g, "");
		var production_same = $('#production_same').val().replace(/,/g, "");
		var loss_same = $('#loss_same').val().replace(/,/g, "");;
		var manufacture_same = $('#manufacture_same').val().replace(/,/g, "");
		var labor_same = $('#labor_same').val().replace(/,/g, "");
		var eco_other_same = $('#eco_other_same').val().replace(/,/g, "");
		if(sale_same == ''){sale_same = 0;}
		if(export_same == ''){export_same = 0;}
		if(income_other_same == ''){income_other_same = 0;}
		if(investment_same == ''){investment_same = 0;}
		if(employment_same == ''){employment_same = 0;}
		if(production_same == ''){production_same = 0;}
		if(loss_same == ''){loss_same = 0;}
		if(manufacture_same == ''){manufacture_same = 0;}
		if(labor_same == ''){labor_same = 0;}
		if(eco_other_same == ''){eco_other_same = 0;}
		sum_same = parseFloat(sale_same) + parseFloat(export_same) + parseFloat(income_other_same) + parseFloat(investment_same) 
			+ parseFloat(employment_same) + parseFloat(production_same) + parseFloat(loss_same) + parseFloat(manufacture_same)
			+ parseFloat(labor_same)+ parseFloat(eco_other_same);
		sum_same = sum_same.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
		$('#economic_same').val(sum_same);
	}

}
function sum_eco_value(){
	var sale_value = $('#sale_value').val().replace(/,/g, "");
	var export_value = $('#export_value').val().replace(/,/g, "");
	var income_other_value = $('#income_other_value').val().replace(/,/g, "");
	var investment_value = $('#investment_value').val().replace(/,/g, "");
	var employment_value = $('#employment_value').val().replace(/,/g, "");
	var production_value = $('#production_value').val().replace(/,/g, "");
	var loss_value = $('#loss_value').val().replace(/,/g, "");
	var manufacture_value = $('#manufacture_value').val().replace(/,/g, "");
	var labor_value = $('#labor_value').val().replace(/,/g, "");
	var eco_other_value = $('#eco_other_value').val().replace(/,/g, "");
	loss = parseFloat(production_value) + parseFloat(loss_value);
	cost = parseFloat(sale_value) + parseFloat(export_value) + parseFloat(income_other_value) + parseFloat(investment_value) 
			+ parseFloat(employment_value) + parseFloat(manufacture_value) + parseFloat(labor_value)+ parseFloat(eco_other_value);
	sum_value = (cost) - (loss);
	var economic_inc = $('#economic_inc').val().replace(/,/g, "");
	if(economic_inc == 0 && sum_value > 0){
		per = 100;
	}else{
		per = (sum_value/economic_inc)*100;
		if(isNaN(per)) {
			per = 0;
		}
	}
	sum_value = sum_value.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	per = per.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
	$('#economic_value').val(sum_value);
	$('#economic_per').val(per);
}
function chk_accept(){
	if($("#chk_apt").prop('checked')==true){
		$("#btn_accept").removeAttr('disabled');
	}
	else if($("#chk_apt").prop('checked')==false){
		$("#btn_accept").attr('disabled','disabled');
	}
}
function chk_accept2(){
	if($("#chk_apt2").prop('checked')==true){
		$("#btn_accept2").removeAttr('disabled');
	}
	else if($("#chk_apt2").prop('checked')==false){
		$("#btn_accept2").attr('disabled','disabled');
	}
}
</script>
<style>
	table#tb_file_prjp2 tr td{
		white-space:nowrap;
	}
</style>
</head>
<body>
<div class="container-full">
	<div><?php include($path."include/header.php"); ?></div>
	<div class="col-xs-12 col-sm-12">
        <ol class="breadcrumb">
          <li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
         <li><a href="disp_approve_project_temp.php?<?php echo url2code("menu_id=".$menu_id."&menu_sub_id=".$menu_sub_id);?>"><?php echo Showmenu($menu_sub_id);?></a></li>
          <li class="active">ผลตัวชี้วัดของผลผลิต</li>
        </ol>
    </div>
   
	<div class="col-xs-12 col-sm-12">
		<div class="groupdata" >
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
				<?php /*?><input type="" id="fbs" name="fbs" value="<?php echo $fbs; ?>"><?php */?>
				<input type="hidden" id="fbso" name="fbso" value="<?php echo $fbso; ?>">
				<input type="hidden" id="OPEN_FORM" name="OPEN_FORM" value="" />
				<!-- Modal -->
				<div id="myModal" class="modal fade" role="dialog">
				<div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
				  </div>
				  <div class="modal-body">
					 <div class="row">
						<div class="col-md-12">
							<input type="file" name="imp_economic" id="imp_economic" placeholder="แบบประเมินมูลค่า ศก. สำหรับผู้รับผิดชอบโครงการ" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<font color="#FF0000">import ข้อมูล โดยใช้ไฟล์ "แบบประเมินมูลค่า ศก. สำหรับผู้รับผิดชอบโครงการ (excel) รองรับไฟล์ที่นำเข้าข้อมูลได้ เฉพาะ excel 2003(.xls) เท่านั้น"</font>
						</div>
					
					</div>
					<div class="row">
						
						<div class="col-md-12" style="text-align:center;">
							<!-- <input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
							<label><input type="checkbox" id="chk_apt" onchange="chk_accept();"> ยืนยันว่าการบันทึกข้อมูลครั้งนี้เป็นไปตาม  </label> <a class="btn-link" href="<?php echo $path;?>fileupload_admin/หนังสือให้ความยินยอมในการเปิดเผยข้อมูลส่วนบุคคล.pdf" target="_blank"> พ.ร.บ.คุ้มครองข้อมูลส่วนบุคคล</a>
							<br>
							<button type="button" class="btn btn-success " onClick="imp_file();" id="btn_accept" disabled><i class="fa fa-check" aria-hidden="true"></i> บันทึก</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-close" aria-hidden="true"></i> ยกเลิก</button> -->
						</div>
					</div>
					
				  </div>
				  <div class="modal-footer"></div>
				</div>

				</div>
				</div>
				
				<div class="row">
					<div class="col-xs-12 col-sm-12"><?php include("tab_menu2_r_temp.php");?></div>
					<?php 
					if($_SESSION["sys_group_id"]=='5' || $_SESSION["sys_group_id"]=='9'){
					?>
					<div class="col-xs-12 col-sm-12"><?php include("tab_menu_300.php");?></div>
					<?php 
					}
					?>
				</div>
				<div class="row"><div class="col-xs-12 col-sm-12 col-md-12"> </div></div>
				<div class="row">  
					<div class="col-xs-12 col-sm-12 font-blue" align="center">
						<strong><?php echo $rec_head['PRJP_CODE']." ".text($rec_head['PRJP_NAME']) ?></strong>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading row" style="">
								<div class="pull-left" style="">ผลที่เกิดขึ้น หลังเข้ารับบริการจากโครงการ/มาตรการของรัฐ</div>
							</div>
							<div class="panel-body epm-gradient" >
								<?php $print_form = "<a class='btn btn-info' data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"economic_report('".$PRJP_BID."',1);\">".$img_print."  พิมพ์ รายงาน</a> "; ?>
								<?php // $import = "<a class=\"btn btn-success\" data-toggle=\"modal\" data-backdrop=\"static\" href=\"javascript:void(0);\" onClick=\"import_modal('".$PRJP_BID."');\">นำเข้าข้อมูล</a> ";?>
								
								<div class="row">
									<!-- <div class="col-xs-12 col-sm-12"><?php echo $print_form; ?>&nbsp;<?php echo $import; ?></div> -->
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-3 " >
										ประเภทผู้เข้าร่วมโครงการ
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-1 " > Micro </div>
									<div class="col-xs-12 col-sm-2 " >
										<input type="text" id="micro" name="micro" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["micro"]);?>" >
									</div>
									<div class="col-xs-12 col-sm-1 " > ราย </div>
									<div class="col-xs-12 col-sm-1 " > Small </div>
									<div class="col-xs-12 col-sm-2 " >
										<input type="text" id="small" name="small" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["small"]);?>" >
									</div>
									<div class="col-xs-12 col-sm-1 " > ราย </div>
									<div class="col-xs-12 col-sm-1 " > Medium </div>
									<div class="col-xs-12 col-sm-2 " >
										<input type="text" id="medium" name="medium" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["medium"]);?>" >
									</div>
									<div class="col-xs-12 col-sm-1 " > ราย </div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-3 " > ระบุจำนวนผู้ที่ตอบแบบรายงาน </div>
									<div class="col-xs-12 col-sm-2 " >
										<input type="text" id="count_person" name="count_person" class="form-control text-right" onblur="NumberFormat(this,0);" value="<?php echo number_format($rec_data["count_person"]);?>" >
									</div>
									<div class="col-xs-12 col-sm-1 " > ราย </div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-3 " >
										ชื่อผู้ให้ข้อมูล (นาย/นาง/นางสาว)
									</div>
									<div class="col-xs-12 col-sm-3 " >
										<input type="text" id="Fname" name="Fname" class="form-control" value="<?php echo text($rec_data["Fname"]);?>" >
									</div>
									<div class="col-xs-12 col-sm-2 " >
										นามสกุล
									</div>
									<div class="col-xs-12 col-sm-3 " >
										<input type="text" id="Lname" name="Lname" class="form-control" value="<?php echo text($rec_data["Lname"]);?>" >
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-3 " >
										ตำแหน่ง
									</div>
									<div class="col-xs-12 col-sm-3 " >
										<input type="text" id="position" name="position" class="form-control" value="<?php echo text($rec_data["position"]);?>" >
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-3 " >
										ชื่อหน่วยงาน
									</div>
									<div class="col-xs-12 col-sm-3 " >
										<input type="text" id="ORG_NAME" name="ORG_NAME" class="form-control" value="<?php echo text($rec_data["ORG_NAME"]);?>" >
									</div>
									<div class="col-xs-12 col-sm-2 " >
										กอง/สำนัก/ฝ่าย
									</div>
									<div class="col-xs-12 col-sm-3 " >
										<input type="text" id="ORG_NAME2" name="ORG_NAME2" class="form-control" value="<?php echo text($rec_data["ORG_NAME2"]);?>" >
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-2 " >
										เกี่ยวข้องกับโครงการในฐานะ
									</div>
									<div class="col-xs-12 col-sm-3 " >
										<?php if (empty($rec_data["associated_text"])){?>
											<select id="associated" name="associated" class="selectbox form-control"> 
											<option value=""></option>
											<option value="1" <?php echo $rec_data["associated"]==1?"selected":"";?>>หัวหน้าโครงการ</option>
											<option value="2" <?php echo $rec_data["associated"]==2?"selected":"";?>>ผู้รับผิดชอบโครงการ(ผู้ดำเนินโครงการ)</option>
											<option value="3" <?php echo $rec_data["associated"]==3?"selected":"";?>>ผู้ประสานงานโครงการ</option>
										</select>
										<?php }else {?>
											<input type="text" id="associated_text" name="associated_text" class="form-control" value="<?php echo text($rec_data["associated_text"]);?>" readonly>
										<?php } ?>
									</div>
								</div>
								
								<div class="row">
									<div class="col-xs-12 col-sm-1 " >
										โทรศัพท์
									</div>
									<div class="col-xs-12 col-sm-2 " >
										<input type="text" id="telephone" name="telephone" class="form-control" value="<?php echo $rec_data["telephone"];?>" >
									</div>
									<div class="col-xs-12 col-sm-2 " >
										โทรศัพท์มือถือ
									</div>
									<div class="col-xs-12 col-sm-2 " >
										<input type="text" id="mobile" name="mobile" class="form-control" value="<?php echo $rec_data["mobile"];?>" >
									</div>
									<div class="col-xs-12 col-sm-1 " >
										E-Mail
									</div>
									<div class="col-xs-12 col-sm-3 " >
										<input type="text" id="Email" name="Email" class="form-control" value="<?php echo $rec_data["Email"];?>" >
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 tb2_v" id="show_rb_99999">
									  <div class="table-responsive">
								
								
										<table width="100%" class="table table-bordered table-striped table-hover table-condensed" id="tb_file_prjp">
										  <thead>
												<tr class="bgHead">
												  <th width="30%" rowspan="2" colspan="4" nowrap><div align="center"><strong>รายการ</strong></div></th>
												  <th width="30%" rowspan="1" colspan="2" nowrap><div align="center"><strong>มูลค่า (บาท/ปี)</strong></div></th>
												  <th width="20%" rowspan="2" colspan="2" nowrap><div align="center"><strong>คิดเป็นมูลค่าเพิ่ม<br>(บาท/ปี) <a class="data-info" data-placement="right" data-title="" data-content="คำนวณจาก มูลค่าหลังเข้าร่วมโครงการ -มูลค่าก่อนเข้าร่วมโครงการ"> </a></strong></div></th>
												  <th width="20%" rowspan="2" colspan="2" nowrap><div align="center"><strong>คิดเป็นอัตรา<br>เพิ่ม/ลด (%)</strong></div></th>
												</tr>
												<tr class="bgHead">
												  <th width="15%" nowrap><div align="center"><strong>ก่อนเข้าร่วมโครงการ</strong></div></th>
												  <th width="15%" nowrap><div align="center"><strong>หลังเข้าร่วมโครงการ</strong></div></th>
												</tr>
											</thead>
											<tbody>
												<tr><?php $no = "1";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" readonly></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "1.1";?>
													<td colspan="1" nowrap> &nbsp; </td>
													<td colspan="3" >
														<span><?php echo $no.". ".$arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(1);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>"></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>"></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "1.2";?>
													<td colspan="1" nowrap> &nbsp; </td>
													<td colspan="3" >
														<span><?php echo $no.". ".$arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(1);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>"></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>"></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "1.3";?>
													<td colspan="1" nowrap> &nbsp; </td>
													<td colspan="3" >
														<span><?php echo $no.". ".$arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(1);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>"></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_1(2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>"></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "2";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" ></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "3";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" ></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "4";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" ></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "5";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" ></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "6";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" ></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "7";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" ></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "8";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(1);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" ></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);sum_eco_9(2);eco_result('<?php echo $arr_eco_field[$no]; ?>');sum_eco_value();" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
												<tr><?php $no = "9";?>
													<td colspan="1" nowrap> <?php echo $no."."; ?></td>
													<td colspan="3" >
														<span><?php echo $arr_eco_field_det1[$no]; ?></span>
														<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
													</td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_inc" name="<?php echo $arr_eco_field[$no]; ?>_inc" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_inc'],2) ?>" readonly></td>
													<td><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" readonly></td>
													<td colspan="2"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_per" name="<?php echo $arr_eco_field[$no]; ?>_per" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_per'],2) ?>" readonly></td>
												</tr>
											
												<tr >
													<td  colspan="10">
														&nbsp;
													</td>
												</tr>
												<tr >
													<td  colspan="10">
														<strong><u>ข้อมูลอื่นๆ</u></strong>
													</td>
												</tr>
												<?php foreach($arr_eco_field_det2 AS $no => $text){ ?>
													<tr> 
														<?php $dot = explode(".",$no); ?>
														<td colspan="1" nowrap> <?php echo empty($dot[1])?$no.".":""; ?></td>
														<td colspan="3" >
															<span><?php echo empty($dot[1])?$text:$no.". ".$text; ?></span>
															<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
														</td>
														<?php if(trim($arr_col_D[$no]) != ''){?>
															<td align="center" ><span><?php echo $arr_col_D[$no]; ?></span></td>
															<td width="15%"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_same" name="<?php echo $arr_eco_field[$no]; ?>_same" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_same'],2) ?>" ></td>
															<td width="10%" align="center" ><span><?php echo $arr_col_F[$no]; ?></span></td>
														<?php }else{ ?>
															<?php if($no == '15.1' || $no == '15.2'){?>
																<td colspan="6">
																	<textarea id="<?php echo $arr_eco_field[$no]; ?>_comment" name="<?php echo $arr_eco_field[$no]; ?>_comment" class="form-control" ><?php echo text($rec_data[$arr_eco_field[$no].'_comment']); ?></textarea>
																</td>
															<?php }else{ ?>
																<td colspan="3"></td>
															<?php } ?>
															
														<?php } ?>
														<?php if(trim($arr_col_G[$no]) != ''){?>
															<td align="center" ><span><?php echo $arr_col_G[$no]; ?></span></td>
															<td width="15%"><input style="" type="text" id="<?php echo $arr_eco_field[$no]; ?>_value" name="<?php echo $arr_eco_field[$no]; ?>_value" class="form-control text-right" onblur="NumberFormat(this,2);" value="<?php echo number_format($rec_data[$arr_eco_field[$no].'_value'],2) ?>" ></td>
															<td align="center" ><span><?php echo $arr_col_I[$no]; ?></span></td>
														<?php }else{ ?>
															<?php if($no == '15.1' || $no == '15.2'){?>
																
															<?php }else{ ?>
																<td colspan="3"></td>
															<?php } ?>
														<?php } ?>
													</tr>
													<?php if(trim($arr_eco_field_com[$no]) != ''){?>
														<tr>
															<td colspan="1" nowrap> &nbsp; </td>
															<td colspan="3" >
																<span><?php echo $arr_eco_field_com[$no]; ?></span>
																<?php if(trim($arr_eco_i[$no]) != ''){echo '<a class="data-info" data-placement="right" data-title="" data-content="'.$arr_eco_i[$no].'" > </a>';} ?>
															</td>
															<td colspan="6">
																<textarea id="<?php echo $arr_eco_field[$no]; ?>_comment" name="<?php echo $arr_eco_field[$no]; ?>_comment" class="form-control" ><?php echo text($rec_data[$arr_eco_field[$no].'_comment']); ?></textarea>
															</td>
														</tr>
													<?php } ?>
												<?php } ?>
											</tbody>
										</table>
									  </div>
									</div>  
								</div>
								<div class="row">
									<!-- <div class="col-xs-12 col-sm-12"><?php echo $print_form; ?></div> -->
								</div>
							</div>
						</div>
					</div>
				</div>			  
				<div class="clearfix" align="center"></div>
				<?php if($_SESSION['sys_status_edit']=='1'){ ?>
				<div class="row">
					<div class="col-md-12" style="white-space:nowrap;">
						<center>
							<!-- <label><input type="checkbox" id="chk_apt2" onchange="chk_accept2();"> ยืนยันว่าการบันทึกข้อมูลครั้งนี้เป็นไปตาม  </label> <a class="btn-link" href="<?php echo $path;?>fileupload_admin/หนังสือให้ความยินยอมในการเปิดเผยข้อมูลส่วนบุคคล.pdf" target="_blank"> พ.ร.บ.คุ้มครองข้อมูลส่วนบุคคล</a>
							<br>
							<button type="button" class="btn btn-success " onClick="chkinput2();" id="btn_accept2" disabled><i class="fa fa-check" aria-hidden="true"></i> บันทึก</button> -->
						</center>
					</div>
				</div>
				<?php } ?>

				<?php //echo endPaging("frm-search",$total_record); ?>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
	<?php include($path."include/footer.php"); ?>
</div>
<script>
 var fields = document.getElementById("frm-search").getElementsByTagName('*');
        for (var i = 0; i < fields.length; i++) {
            fields[i].disabled = true;
        }
</script>
</body>
</html>
<?php //echo form_model('myModal','ปัญหา-อุปสรรค','show_display','','','1');?>
<?php //echo form_model1('myModal1','เลือกวันที่ออกรายงาน','show_display1','','','1');?>
<?php echo form_model('myModal1','เลือกวันที่ออกรายงาน','show_display','','','1');?>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<div class="modal fade" id="myModal1"></div>

<!-- /.modal -->