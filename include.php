<?php
## Database
include "connect_db.php";
include "../function/config_db.php";

$sql_conf = db::query("SELECT * FROM WF_CONFIG ORDER BY CONFIG_ID");

while ($rec_conf = db::fetch_array($sql_conf)) {
    $system_conf[$rec_conf['CONFIG_NAME']] = $rec_conf['CONFIG_VALUE'];
}

/* Custom Variables */
$attach_href = "../baac_cloud_63/attach/";
/* ================ */

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
$WF_SUB_MENU = $system_conf["wf_sub_menu"];
$WF_TEXT_MAIN_ORDER = ($system_conf["wf_text_main_order"] != '') ? $system_conf["wf_text_main_order"] : 'ลำดับ';
$CONF_USER_PREFIX = ($system_conf["conf_user_prefix"] != '') ? $system_conf["conf_user_prefix"] : 'นาย,นาง,นางสาว';
$ARR_U_PREFIX = explode(',', $CONF_USER_PREFIX);
$system_conf["wf_split_page"] = ($system_conf["wf_split_page"] != '') ? $system_conf["wf_split_page"] : 'หน้าที่,จากทั้งหมด,หน้า  ,จำนวนข้อมูล,รายการ';
$WF_SPLIT_PAGE = explode(',', $system_conf["wf_split_page"]);
$system_conf["wf_select_province"] = ($system_conf["wf_select_province"] != '') ? $system_conf["wf_select_province"] : 'เลือกจังหวัด';
$system_conf["wf_select_amphur"] = ($system_conf["wf_select_amphur"] != '') ? $system_conf["wf_select_amphur"] : 'เลือกอำเภอ';
$system_conf["wf_select_tambon"] = ($system_conf["wf_select_tambon"] != '') ? $system_conf["wf_select_tambon"] : 'เลือกตำบล';
$system_conf["wf_select_tambon2"] = ($system_conf["wf_select_tambon2"] != '') ? $system_conf["wf_select_tambon2"] : 'เลือกแขวง';
$system_conf["wf_delete_confirm"] = ($system_conf["wf_delete_confirm"] != '') ? $system_conf["wf_delete_confirm"] : 'ยืนยันการลบ';
$system_conf["wf_cancle"] = ($system_conf["wf_cancle"] != '') ? $system_conf["wf_cancle"] : 'ยกเลิก';
$system_conf["wf_save_complete"] = ($system_conf["wf_save_complete"] != '') ? $system_conf["wf_save_complete"] : 'บันทึกตำแหน่งเรียบร้อยแล้ว';
$system_conf["wf_delete_confirm_list"] = ($system_conf["wf_delete_confirm_list"] != '') ? $system_conf["wf_delete_confirm_list"] : 'คุณต้องการลบรายการนี้หรือไม่?';
$system_conf["wf_exist_data"] = ($system_conf["wf_exist_data"] != '') ? $system_conf["wf_exist_data"] : 'ข้อมูลนี้มีอยู่แล้วในระบบ';
$system_conf["conf_search"] = ($system_conf["conf_search"] != '') ? $system_conf["conf_search"] : 'ค้นหา';
$system_conf["wf_close"] = ($system_conf["wf_close"] != '') ? $system_conf["wf_close"] : 'ปิด';
$system_conf["wf_select"] = ($system_conf["wf_select"] != '') ? $system_conf["wf_select"] : 'เลือก';
$system_conf["wf_not_activated"] = ($system_conf["wf_not_activated"] != '') ? $system_conf["wf_not_activated"] : 'ยังไม่เปิดใช้งาน';
$system_conf["wf_attach"] = ($system_conf["wf_attach"] != '') ? $system_conf["wf_attach"] : 'เอกสารแนบ';
$system_conf["wf_more_doc"] = ($system_conf["wf_more_doc"] != '') ? $system_conf["wf_more_doc"] : 'เอกสารเพิ่มเติม';
$system_conf["wf_agree"] = ($system_conf["wf_agree"] != '') ? $system_conf["wf_agree"] : 'ตกลง';
$system_conf["wf_process_back_comfirm"] = ($system_conf["wf_process_back_comfirm"] != '') ? $system_conf["wf_process_back_comfirm"] : 'คุณต้องการย้อนขั้นตอนหรือไม่?';
$system_conf["wf_export_pdf"] = ($system_conf["wf_export_pdf"] != '') ? $system_conf["wf_export_pdf"] : 'ส่งออก PDF';
$system_conf["wf_export_word"] = ($system_conf["wf_export_word"] != '') ? $system_conf["wf_export_word"] : 'ส่งออก word';
$system_conf["wf_export_excel"] = ($system_conf["wf_export_excel"] != '') ? $system_conf["wf_export_excel"] : 'ส่งออก excel';
$system_conf["wf_label_date"] = ($system_conf["wf_label_date"] != '') ? $system_conf["wf_label_date"] : 'วว/ดด/ปปปป';
$system_conf["wf_label_reset"] = ($system_conf["wf_label_reset"] != '') ? $system_conf["wf_label_reset"] : 'Reset';
## Environment
$template = array(
    'title' => $system_conf["conf_title"],
    'author' => 'BizPotential',
    'description' => 'BizPotential',
    'keywords' => ', Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app',
    'active_page' => basename($_SERVER['PHP_SELF']),
);

## Menu
$primary_nav = array(
    array(
        'name' => 'Workflow Management',
        'url' => 'workflow.php',
        'icon' => 'icofont icofont-chart-flow-alt-2',
        'sub' => 0,
    ),
    array(
        'name' => 'Form Management',
        'url' => 'form.php',
        'icon' => 'icon-grid',
        'sub' => 0,
    ),
    array(
        'name' => 'Master Management',
        'url' => 'master.php',
        'icon' => 'fa fa-table',
        'sub' => 0,
    ),
    array(
        'name' => 'User Management',
        'url' => 'user_list.php',
        'icon' => 'icon-people',
        'sub' => 0,
    ),
    array(
        'name' => 'Setting',
        'url' => 'widget.php',
        'icon' => 'icon-wrench',
        'sub' => array(
            array(
                'name' => 'กลุ่มของ Workflow',
                'url' => 'workflow_group_list.php',
            ),
            array(
                'name' => 'กลุ่มของ Form',
                'url' => 'form_group_list.php',
            ),
            array(
                'name' => 'กลุ่มของ Master',
                'url' => 'master_group_list.php',
            ),
            array(
                'name' => 'บริหารกลุ่มขั้นตอน X',
                'url' => '#',
            ),
            array(
                'name' => 'ตั้งค่าผู้ใช้งาน ',
                'url' => 'setting_user_option.php',
            ),
            array(
                'name' => 'บริหารหน่วยงาน',
                'url' => 'department_list.php',
            ),
            array(
                'name' => 'บริหารกลุ่มผู้ใช้งาน',
                'url' => 'group_list.php',
            ),
            array(
                'name' => 'บริหารตำแหน่ง',
                'url' => 'position_list.php',
            ),
            array(
                'name' => 'บริหารรายงาน X',
                'url' => '#',
            ),
            array(
                'name' => 'บริหาร Warehouse X',
                'url' => '#',
            ),
            array(
                'name' => 'บริหารการตั้งค่าระบบ',
                'url' => '../process/system_setting.php',
            ),
        ),
    ), array(
        'name' => 'More Setting',
        'url' => 'widget.php',
        'icon' => 'icon-people',
        'sub' => 0,
    ),
);

require '../bizsmartdoc/bizsmartdoc.config.php';
require '../bizsmartdoc/bizsmartdoc.class.php';
include "../include/function_workflow.php";
include "../include/function_form.php";
include "../include/function_master.php";
include "../function/function_custom.php";
