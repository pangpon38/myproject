<?php
## Database
include "connect_db.php";
include "../function/config_db.php";

$sql_conf = db::query("SELECT * FROM WF_CONFIG ORDER BY CONFIG_ID");

while($rec_conf = db::fetch_array($sql_conf))
{
	$system_conf[$rec_conf['CONFIG_NAME']] = $rec_conf['CONFIG_VALUE'];
	$system_label[$rec_conf['CONFIG_NAME']] = $rec_conf['CONFIG_LABEL'];
	$system_type[$rec_conf['CONFIG_NAME']] = $rec_conf['CONFIG_TYPE'];
	$system_option[$rec_conf['CONFIG_NAME']] = $rec_conf['CONFIG_OPTION'];
}

$WF_TEXT_MAIN_ADD = $system_conf["wf_text_main_add"];
$WF_TEXT_MAIN_PROCESS = $system_conf["wf_text_main_process"];
$WF_TEXT_MAIN_PROCESS_STEP = $system_conf["wf_text_main_process_step"];
$WF_TEXT_MAIN_DEL = $system_conf["wf_text_main_del"];
$WF_TEXT_MAIN_BACK = $system_conf["wf_text_main_back"];
$WF_TEXT_DETAIL_BACK = $system_conf["wf_text_detail_back"];
$WF_TEXT_DETAIL_PROCESS_BACK = $system_conf["wf_text_detail_process_back"];
$WF_TEXT_DETAIL_SAVE_TEMP = $system_conf["wf_text_detail_save_temp"];
$WF_TEXT_DETAIL_SAVE = $system_conf["wf_text_detail_save"];
$WF_TEXT_DETAIL_PROCESS = $system_conf["wf_text_detail_process"];
$WF_TEXT_MAIN_EDIT = $system_conf["wf_text_main_edit"];
$WF_TEXT_MAIN_VIEW = $system_conf["wf_text_main_view"];
$WF_TEXT_DETAIL_ATTACH = $system_conf["wf_text_detail_attach"];
$CONF_LOGIN_TEXT = $system_conf["conf_login_text"];
$CONF_LOGIN_LOGO = $system_conf["conf_login_logo"];
$CONF_LOGIN_IMAGE = $system_conf["conf_login_image"];
$CONF_HEADER_LOGO_STYLE = $system_conf["conf_header_logo_style"];
$CONF_HEADER_LOGO_WIDTH = $system_conf["conf_header_logo_width"];
$WF_TEXT_DET_STEP = $system_conf["wf_text_det_step"];
$WF_TEXT_DET_NEXT = $system_conf["wf_text_det_next"];
$WF_TEXT_MAIN_ORDER = ($system_conf["wf_text_main_order"] != '')?$system_conf["wf_text_main_order"]:'ลำดับ';
$CONF_USER_PREFIX = ($system_conf["conf_user_prefix"] != '')?$system_conf["conf_user_prefix"]:'นาย,นาง,นางสาว';
$ARR_U_PREFIX = explode(',',$CONF_USER_PREFIX);
$system_conf["wf_split_page"] = ($system_conf["wf_split_page"] != '')?$system_conf["wf_split_page"]:'หน้าที่,จากทั้งหมด,หน้า  ,จำนวนข้อมูล,รายการ';
$WF_SPLIT_PAGE = explode(',',$system_conf["wf_split_page"]);
$system_conf["wf_select_province"] = ($system_conf["wf_select_province"] != '')?$system_conf["wf_select_province"]:'เลือกจังหวัด';
$system_conf["wf_select_amphur"] = ($system_conf["wf_select_amphur"] != '')?$system_conf["wf_select_amphur"]:'เลือกตำบล';
$system_conf["wf_select_tambon"] = ($system_conf["wf_select_tambon"] != '')?$system_conf["wf_select_tambon"]:'';
$system_conf["wf_select_tambon2"] = ($system_conf["wf_select_tambon2"] != '')?$system_conf["wf_select_tambon2"]:'เลือกแขวง';
$system_conf["wf_delete_confirm"] = ($system_conf["wf_delete_confirm"] != '')?$system_conf["wf_delete_confirm"]:'ยืนยันการลบ';
$system_conf["wf_cancle"] = ($system_conf["wf_cancle"] != '')?$system_conf["wf_cancle"]:'ยกเลิก';
$system_conf["wf_save_complete"] = ($system_conf["wf_save_complete"] != '')?$system_conf["wf_save_complete"]:'บันทึกตำแหน่งเรียบร้อยแล้ว';
$system_conf["wf_delete_confirm_list"] = ($system_conf["wf_delete_confirm_list"] != '')?$system_conf["wf_delete_confirm_list"]:'คุณต้องการลบรายการนี้หรือไม่?';
$system_conf["wf_exist_data"] = ($system_conf["wf_exist_data"] != '')?$system_conf["wf_exist_data"]:'ข้อมูลนี้มีอยู่แล้วในระบบ';
$system_conf["conf_search"] = ($system_conf["conf_search"] != '')?$system_conf["conf_search"]:'ค้นหา';
$system_conf["wf_close"] = ($system_conf["wf_close"] != '')?$system_conf["wf_close"]:'ปิด';
$system_conf["wf_select"] = ($system_conf["wf_select"] != '')?$system_conf["wf_select"]:'เลือก';
$system_conf["wf_not_activated"] = ($system_conf["wf_not_activated"] != '')?$system_conf["wf_not_activated"]:'ยังไม่เปิดใช้งาน'; 
$system_conf["wf_attach"] = ($system_conf["wf_attach"] != '')?$system_conf["wf_attach"]:'เอกสารแนบ';$system_conf["wf_more_doc"] = ($system_conf["wf_more_doc"] != '')?$system_conf["wf_more_doc"]:'เอกสารเพิ่มเติม'; 
$system_conf["wf_agree"] = ($system_conf["wf_agree"] != '')?$system_conf["wf_agree"]:'ตกลง'; $system_conf["wf_process_back_comfirm"] = ($system_conf["wf_process_back_comfirm"] != '')?$system_conf["wf_process_back_comfirm"]:'คุณต้องการย้อนขั้นตอนหรือไม่?'; 
$system_conf["wf_export_pdf"] = ($system_conf["wf_export_pdf"] != '')?$system_conf["wf_export_pdf"]:'ส่งออก PDF'; 
$system_conf["wf_export_word"] = ($system_conf["wf_export_word"] != '')?$system_conf["wf_export_word"]:'ส่งออก word'; 
$system_conf["wf_export_excel"] = ($system_conf["wf_export_excel"] != '')?$system_conf["wf_export_excel"]:'ส่งออก excel'; 
$system_conf["wf_label_date"] = ($system_conf["wf_label_date"] != '')?$system_conf["wf_label_date"]:'วว/ดด/ปปปป';
$system_conf["wf_label_reset"] = ($system_conf["wf_label_reset"] != '')?$system_conf["wf_label_reset"]:'Reset';

## Line Login Config ##
DEFINE('LINE_CLIENT_ID', $WF_LINE_CLIENT_ID);
DEFINE('LINE_CLIENT_SECRET', $WF_LINE_CLIENT_SECRET);
DEFINE('LINE_REDIRECT_URI', $WF_URL.'register/login_finish.php');
//DEFINE('LINE_STATE', 'BizSmartFlow');




## Environment
$template = array(
    'title'                     => $system_conf["conf_title"],
    'author'                    => 'BizPotential',
    'description'               => 'BizPotential',
    'keywords'                  => ', Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app',
    'active_page'               => basename($_SERVER['PHP_SELF'])
);

## Menu
$primary_nav = array(
	array(
		'name' => 'Workflow Management',
		'url' => 'workflow.php',
		'icon' => 'icofont icofont-chart-flow-alt-2',
		'sub' => 0
	),
	array(
		'name' => 'Form Management',
		'url' => 'form.php',
		'icon' => 'icon-grid',
		'sub' => 0
	),
	array(
		'name' => 'Master Management',
		'url' => 'master.php',
		'icon' => 'fa fa-table',
		'sub' => 0
	),
	array(
		'name' => 'Report Management',
		'url' => 'report.php',
		'icon' => 'ion-stats-bars',
		'sub' => 0
	),
	array(
            'name' => 'Setting',
            'sub-title1' => 'Users',
            'icon' => 'icon-wrench',
            'sub1' => array(
                array(
                    'name' => 'User Management',
                    'url' => 'user_list.php'
                ),
                array(
                    'name' => 'ตั้งค่าผู้ใช้งาน',
                    'url' => 'setting_user_option.php'
                ),
                array(
                    'name' => 'บริหารหน่วยงาน',
                    'url' => 'department_list.php'
                ),
                array(
                    'name' => 'บริหารตำแหน่ง',
                    'url' => 'position_list.php'
                ),
                array(
                    'name' => 'บริหารกลุ่มผู้ใช้งาน',
                    'url' => 'group_list.php'
                )
            ),

            'sub-title2' => 'Group Setting',
            'sub2' => array(
                array(
                    'name' => 'กลุ่มของ Workflow',
                    'url' => 'workflow_group_list.php'
                ),
                array(
                    'name' => 'กลุ่มของ Form',
                    'url' => 'form_group_list.php'
                ),
                array(
                    'name' => 'กลุ่มของ Master',
                    'url' => 'master_group_list.php'
                ),
                array(
                    'name' => 'กลุ่มของ Report',
                    'url' => 'report_group_list.php'
                ),
                array(
                    'name' => 'กลุ่มของ Prototype',
                    'url' => 'prototype_group_list.php'
                ),
                array(
                    'name' => 'กลุ่มของ Menu',
                    'url' => 'menu_group_list.php'
                )
            ),

            'sub-title3' => 'System Setting',
            'sub3' => array(
                array(
                    'name' => 'ตั้งค่าระบบ',
                    'url' => '../process/system_setting.php'
                ),
                array(
                    'name' => 'ตั้งค่าภาษา (Multi-Languages)',
                    'url' => '../process/system_language.php'
                )
            ),

            'sub-title4' => 'More',
            'sub4' => array(
				array(
                    'name' => 'Prototype Management',
                    'url' => '../process/prototype.php'
                ),
				array(
                    'name' => 'BPMN',
                    'url' => '../process/bpmn_list.php'
                ),
				array(
                    'name' => 'Diagram Tools',
                    'url' => '../process/mindmap_list.php'
                ),
                array(
                    'name' => 'Export Data Dictionary',
                    'url' => '../process/wf_datadict_list.php'
                ),
                array(
                    'name' => 'Export Entity Relationship Diagram',
                    'url' => '../process/wf_er_list.php'
                ),
                array(
                    'name' => 'Log Process',
                    'url' => '../process/log_process.php'
                ),
				array(
                    'name' => 'Log Error',
                    'url' => '../process/log_error.php'
                )
            )
        ),
		array(
		'name' => 'Help',
		'url' => 'help.php',
		'icon' => 'ion-help-circled',
		'sub' => 0
	)

);

$conf_code = array("conf_title",
				   "conf_login_image",
				   "conf_login_logo",
				   "conf_header_logo",
				   "conf_header_bg",
				   "conf_footer_text",
				   "2fa",
				   "wf_text_main_add",
				   "wf_text_main_process",
				   "wf_text_main_process_step",
				   "wf_text_main_del",
				   "wf_text_main_back",
				   "wf_text_detail_back",
				   "wf_text_detail_process_back",
				   "wf_text_detail_save_temp",
				   "wf_text_detail_save",
				   "wf_text_detail_process",
				   "conf_profile",
				   "conf_logout",
				   "conf_search",
				   "wf_text_main_edit",
				   "wf_text_main_view",
				   "wf_line_token_access",
				   "wf_show_menu",
				   "wf_show_user",
				   "wf_text_detail_attach",
				   "conf_login_text",
				   "conf_login_image_backend",
				   "conf_login_logo_backend",
				   "conf_login_text_backend",
				   "conf_header_logo_style",
				   "conf_header_logo_width",
				   "wf_text_det_step",
				   "wf_text_det_next",
				   "wf_text_main_order",
				   "wf_list_per_page",
				   "conf_user_prefix",
				   "wf_sub_menu",
				   "wf_split_page",
				   "wf_select_province",
				   "wf_select_amphur",
				   "wf_select_tambon",
				   "wf_select_tambon2",
				   "wf_delete_confirm",
				   "wf_cancle",
				   "wf_save_complete",
				   "wf_delete_confirm_list",
				   "wf_exist_data",
				   "wf_close",
				   "wf_select",
				   "wf_not_activated",
				   "wf_attach",
				   "wf_more_doc",
				   "wf_agree",
				   "wf_process_back_comfirm",
				   "wf_export_pdf",
				   "wf_export_word",
				   "wf_export_excel",
				   "wf_label_date",
				   "wf_label_reset",
				   "wf_department_style"
				   
);
$conf_title = array("Title bar",
					"รูปหน้า Login",
					"Logo หน้า Login",
					"Logo ส่วน Header",
					"สีหลักของระบบ",
					"คำพูด ส่วน Footer",
					"2 Factor Authenticator",
					"ปุ่มเพิ่มข้อมูลของหน้า workflow",
					"ปุ่มดำเนินการของหน้า workflow",
					"ปุ่มขั้นตอนการทำงานของหน้า workflow",
					"ปุ่มลบของหน้า workflow",
					"ปุ่มกลับหน้าหลักของหน้า workflow",
					"ปุ่มกลับหน้าหลักของหน้าบันทึกข้อมูล",
					"ปุ่มย้อนขั้นตอนของหน้าบันทึกข้อมูล",
					"ปุ่มบันทึกชั่วคราวของหน้าบันทึกข้อมูล",
					"ปุ่มบันทึกของหน้าบันทึกข้อมูล",
					"ปุ่มดำเนินการของหน้าบันทึกข้อมูล",
					"Label Llink Profile",
					"Label Llink logout",
					"label search",
					"ปุ่มแก้ไขของหน้า formและmaster",
					"ปุ่มดูรายละเอียดของหน้า formและmaster",
					"Line Token Access",
					"แสดงเมนู",
					"รูปแบบการแสดงผลของ user",
					"ปุ่มเอกสารทั้งหมดของ workflow",
					"label หน้า login",
					"รูปหน้า Login ส่วน backend",
					"Logo หน้า Login ส่วน backend",
					"label หน้า login ส่วน backend",
					"ความกว้างส่วน Header",
					"ความกว้าง Logo",
					"Label แสดงหัวคอลัมน์ขั้นตอนปัจจุบันในหน้า list รายการ",
					"Label แสดงหัวคอลัมน์ขั้นตอนถัดไปในหน้า list รายการ",
					"label แสดงคอลัมน์ลำดับในหน้า list workflow และ  list master",
					"จำนวนที่เป็นตัวเลือกในการแสดงผลต่อหน้า (ใส่เป็นคอมม่าคั่น เพื่อให้เป็นหลายตัวเลือก)",
					"คำนำหน้าชื่อ",
					"Sub Menu",
					 "Text แบ่งหน้า",
					 "Text เลือกจังหวัด",
					 "Text เลือกอำเภอ",
					 "Text เลือกตำบล",
					 "Text เลือกแขวง",
					 "Text ยืนยันการลบ",
					 "Text ยกเลิก",
					 "Text บันทึกตำแหน่งเรียบร้อยแล้ว",
					 "Text คุณต้องการลบรายการนี้หรือไม่?",
					 "Text ข้อมูลนี้มีอยู่แล้วในระบบ",
					 "Text ปิด",
					 "Text เลือก",
					 "Text ยังไม่เปิดใช้งาน",
					 "Text เอกสารแนบ",
					 "Text เอกสารเพิ่มเติม",
					 "Text ตกลง",
					 "Text คุณต้องการย้อนขั้นตอนหรือไม่?",
					 "Text ส่งออก PDF",
					 "Text ส่งออก word",
					 "Text ส่งออก excel",
					 "Text วว/ดด/ปปปป",
					 "Text ปุ่ม Reset",
					 "รูปแบบการแสดงหน่วยงาน"
);
$conf_data_type = array('conf_footer_text' => '2',
						'wf_line_token_access' => '2'
);

include "../include/function_workflow.php";
include "../include/function_form.php";
include "../include/function_master.php";
include "../function/function_custom.php";
?>