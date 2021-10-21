<?php
session_start();
$path = "../../";
include($path . "include/config_header_top.php");

$link = "r=home&menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id;  /// for mobile
$paramlink = url2code($link);
$sub_menu = "";
if ($proc == 'add') {
	$txt = "เพิ่มโครงการ";
} else {
	$txt = "แก้ไขโครงการ";
}

if ($_SESSION['year_round'] < 2564) {
	$HEAD_STRGIC_TEXT = 'แผนการส่งเสริมวิสาหกิจขนาดกลางและขนาดย่อม';
	$HEAD_STRGIC_TEXT3 = 'แผนยุทธศาสตร์ของ สสว.';
} else {
	$HEAD_STRGIC_TEXT = 'แผนส่งเสริมวิสาหกิจขนาดกลางและขนาดย่อม';
	$HEAD_STRGIC_TEXT3 = 'แผนปฏิบัติการประจำปี';
}

//echo floor((211)/100);
$yy = " AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
if ($_SESSION["sys_group_id"] == '5') {
	$sql_type_org = "select ORG_TYPE_ID,ORG_TYPE_NAME_TH FROM setup_org_type WHERE ACTIVE_STATUS = '1' ";
	$query_type_org = $db->query($sql_type_org);
	while ($rec_type_org  = $db->db_fetch_array($query_type_org)) {
		$arr_torg[$rec_type_org["ORG_TYPE_ID"]] = $rec_type_org["ORG_TYPE_NAME_TH"];
	}
}
$sql_type_bdg = "SELECT BDG_TYPE_ID,BDG_TYPE_NAME from bdg_type WHERE 1=1 AND ACTIVE_STATUS = '1'";
$query_type_bdg = $db->query($sql_type_bdg);
while ($rec_tbdg  = $db->db_fetch_array($query_type_bdg)) {
	$arr_tbdg[$rec_tbdg["BDG_TYPE_ID"]] = text($rec_tbdg["BDG_TYPE_NAME"]);
}


$sql_strgic = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic_lv1 where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic = $db->query($sql_strgic);
while ($rec_strgic  = $db->db_fetch_array($query_strgic)) {
	$arr_lvl1[$rec_strgic["STRGIC_ID"]] = $rec_strgic["STRGIC_NAME"];
}

$qry_nation_object = $db->query("select object_id, object_name, object_level, object_id as object_parent_id 
						from service_bdg_object a
						where object_year_bdg = '" . $_SESSION['year_round'] . "' and object_level = 3
						union
						select b.object_id, b.object_name, b.object_level, b.object_parent_id
						from service_bdg_object a
						join service_bdg_object b on b.object_parent_id = a.object_id 
						where a.object_year_bdg = '" . $_SESSION['year_round'] . "' and a.object_level = 3
						order by object_parent_id, object_level ");
while ($rec_obj = $db->db_fetch_array($qry_nation_object)) {
	$arr_na_obj[$rec_obj["object_id"]] = array($rec_obj["object_name"], $rec_obj["object_level"]);
}

$sql_strgic = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic = $db->query($sql_strgic);
while ($rec_strgic  = $db->db_fetch_array($query_strgic)) {
	$arr_strgic[$rec_strgic["STRGIC_ID"]] = $rec_strgic["STRGIC_NAME"];
}
$sql_strgic = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic = $db->query($sql_strgic);
while ($rec_strgic  = $db->db_fetch_array($query_strgic)) {
	$arr_strgic[$rec_strgic["STRGIC_ID"]] = $rec_strgic["STRGIC_NAME"];
}
$sql_strgic2 = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic2 where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic2 = $db->query($sql_strgic2);
while ($rec_strgic2  = $db->db_fetch_array($query_strgic2)) {
	$arr_strgic2[$rec_strgic2["STRGIC_ID"]] = $rec_strgic2["STRGIC_NAME"];
}
$sql_strgic3 = "SELECT STRGIC_ID,STRGIC_NAME FROM plan_strgic3 where STRGIC_LEVEL = '1' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_strgic3 = $db->query($sql_strgic3);
while ($rec_strgic3  = $db->db_fetch_array($query_strgic3)) {
	$arr_strgic3[$rec_strgic3["STRGIC_ID"]] = $rec_strgic3["STRGIC_NAME"];
}


$sql_mg = "SELECT MAIN_GOAL_ID,MAIN_GOAL_NAME FROM plan_main_goal where 1=1 AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
$query_mg = $db->query($sql_mg);
while ($rec_mg  = $db->db_fetch_array($query_mg)) {
	$arr_mg[$rec_mg["MAIN_GOAL_ID"]] = $rec_mg["MAIN_GOAL_NAME"];
}
$sql_goal = "SELECT GOAL_TYPE_ID,GOAL_TYPE_NAME FROM setup_goal_type WHERE 1=1";
$query_goal = $db->query($sql_goal);
$c_goal = $db->db_num_rows($query_goal);
while ($rec_goal  = $db->db_fetch_array($query_goal)) {
	$arr_goal[$rec_goal["GOAL_TYPE_ID"]] = $rec_goal["GOAL_TYPE_NAME"];
}
$sql_goal_major = "SELECT GOAL_TYPE_ID,GOAL_MAJOR_ID,GOAL_MAJOR_NAME FROM setup_goal_major WHERE 1=1 ";
$query_goal_major = $db->query($sql_goal_major);
while ($rec_goal_major  = $db->db_fetch_array($query_goal_major)) {
	$arr_goal_major[$rec_goal_major["GOAL_TYPE_ID"]][$rec_goal_major["GOAL_MAJOR_ID"]] = $rec_goal_major["GOAL_MAJOR_NAME"];
}





$sql_country = "SELECT COUNTRY_ID,COUNTRY_NAME_TH,COUNTRY_NAME_EN FROM setup_country WHERE 1=1 AND ACTIVE_STATUS = '1'";
$query_country = $db->query($sql_country);
while ($rec_country  = $db->db_fetch_array($query_country)) {
	$arr_country[$rec_country["COUNTRY_ID"]] = $rec_country["COUNTRY_NAME_TH"] . "(" . $rec_country["COUNTRY_NAME_EN"] . ")";
}
$sql_zone = "SELECT ZONE_ID,ZONE_CODE,ZONE_NAME_TH,ZONE_NAME_EN FROM setup_zone WHERE 1=1 AND ACTIVE_STATUS = '1'";
$query_zone = $db->query($sql_zone);
while ($rec_zone = $db->db_fetch_array($query_zone)) {
	$arr_zone[$rec_zone["ZONE_CODE"]] = $rec_zone["ZONE_NAME_TH"] . "(" . text($rec_zone["ZONE_NAME_EN"]) . ")";
}
$sql_data_used = "select DATA_USED_ID,DATA_USED_NAME from setup_data_used WHERE 1=1 AND DATA_USED_STATUS = '1' AND DATA_USED_PARENT_ID = '0'";
$query_data_used = $db->query($sql_data_used);
while ($rec_data_used = $db->db_fetch_array($query_data_used)) {
	$arr_data_used[$rec_data_used["DATA_USED_ID"]] = $rec_data_used["DATA_USED_NAME"];
}

$code_1 = substr($_SESSION['year_round'], 2, 2);

$sql_edit = "SELECT PRJP_ID,
					prjp_project.ORG_ID,
					ORG_TYPE_ID,
					PRJP_CODE,
					PRJP_NAME,
					PRJP_LEVEL,
					STRGIC_ID,
					STRGY_ID,
					MAIN_GOAL_ID,
					TASK_JOB_ID,
					YEAR_BDG,
					MONEY_BDG,
					STRGIC_GOAL_ID,
					OBJECTTIVE_DESC,
					(select ORG_TYPE_ID FROM setup_org_bu where setup_org_bu.ORG_ID = prjp_project.ORG_ID)as ORG_TYPE_ID ,
					(select BUS_SIZE_ID FROM prjp_bus_size where prjp_project.PRJP_ID = prjp_bus_size.PRJP_ID) as BUS_SIZE,
					(select LIFE_CYCLE_ID FROM prjp_life_cycle where prjp_project.PRJP_ID = prjp_life_cycle.PRJP_ID) as LIFE_CYCLE,
					(select MSME_GROUP_ID FROM prjp_msme_group where prjp_project.PRJP_ID = prjp_msme_group.PRJP_ID) as MSME_GROUP,
					(select PRO_DESC_ID FROM prjp_pro_desc where prjp_project.PRJP_ID = prjp_pro_desc.PRJP_ID) as PRO_DESC,
					(select GOVE_POLICY_ID FROM prjp_gove_policy where prjp_project.PRJP_ID = prjp_gove_policy.PRJP_ID) as GOVE_POLICY,
					(select VALUE_CHAIN_ID FROM prjp_value_chain where prjp_project.PRJP_ID = prjp_value_chain.PRJP_ID) as VALUE_CHAIN,
					(select HIGH_IMPACT_ID FROM prjp_high_impact where prjp_project.PRJP_ID = prjp_high_impact.PRJP_ID) as HIGH_IMPACT,
					SDATE_PRJP,
					EDATE_PRJP,
					COORDINATOR_NAME,
					REASONABLE_NAME,
					prjp_project.ORG_NAME,
					prjp_project.STRGIC_ID2,
					prjp_project.STRGY_ID2,
					prjp_project.STRGIC_ID3,
					prjp_project.STRGIC_GOAL_ID3,
					prjp_project.STRGY_ID3,
					prjp_project.STRGY_ID3,
					prjp_project.STRGIC_INDICATE_ID3,
					prjp_project.STRGIC_LVL1_ID,
					prjp_project.STRGIC_LVL1_INDICATE_ID,
					prjp_project.OBJECT_STRCIG_ID,
					COORDINATOR_TEL,
					STRGIC_INDICATE_ID,
					RULE_ID,
					PROLONG_STATUS,
					PRJP_STATUS,
					BDG_TYPE_ID,
					EMAIL_PRJP,
					PRJP_CON_ID,
					RULE_MAIN_ID,
					USER_ADD_PROJECT,
					USER_ADD_PROJECT_TEL,
					USER_ADD_PROJECT_EMAIL,
					STATUS_CHK_PRJP,
					MONEY_BDG_ALL,
					MONEY_BDG_SME,
					MONEY_BDG_OUT,
					COOR_CHK_EMAIL,
					RESP_CHK_EMAIL,
					PRJP_RUN_STATUS,
					ISNULL(SERVICE_PROJECT_ID, 0) as SERVICE_PROJECT_ID,
					COORDINATOR_NAME2,
					COORDINATOR_CODE2,
					COORDINATOR_TEL2,
					EMAIL_PRJP2,
					COOR_CHK_EMAIL2,
					COORDINATOR_CODE

				FROM prjp_project  
				left join setup_org_bu on setup_org_bu.ORG_ID = prjp_project.ORG_ID
				WHERE PRJP_ID = '" . $PRJP_ID . "'";
$query_edit = $db->query($sql_edit);
if ($db->db_num_rows($query_edit) > 0) {
	$rec = $db->db_fetch_array($query_edit);

	//$code_1 = substr($rec['PRJP_CODE'],0,2);
	$code_2 = substr($rec['PRJP_CODE'], 2, 2);
	$code_3 = substr($rec['PRJP_CODE'], 4, 2);
	$code_4 = substr($rec['PRJP_CODE'], 6, 2);
	$code_5 = substr($rec['PRJP_CODE'], 8, 3);

	#--------วันที่ได้รับอนุมัติเปิดโครงการ-----------------#
	$sql_set_step_1 = $db->query(" SELECT * FROM prjp_set_step WHERE prjp_id = '" . $PRJP_ID . "' AND prjp_step_id = 1 ");
	$rec_set_step_1  = $db->db_fetch_array($sql_set_step_1);
	#--------วันที่ได้รับอนุมัติขยาย-----------------#
	$sql_set_step_3 = $db->query(" SELECT * FROM prjp_set_step WHERE prjp_id = '" . $PRJP_ID . "' AND prjp_step_id = 3 ");
	$rec_set_step_3  = $db->db_fetch_array($sql_set_step_3);
	#-------วันที่อนุมัติปรับแผน------------------#
	$sql_set_step_4 = $db->query(" SELECT * FROM prjp_set_step WHERE prjp_id = '" . $PRJP_ID . "' AND prjp_step_id = 4 ");
	$rec_set_step_4  = $db->db_fetch_array($sql_set_step_4);
	#-------------------------#


	$sql_rule = "SELECT RULE_ID,RULE_NAME FROM setup_rule where ACTIVE_STATUS = '1' AND RULE_MAIN_ID = '" . $rec['RULE_MAIN_ID'] . "' order by RULE_ID";
	$query_rule = $db->query($sql_rule);
	while ($rec_rule  = $db->db_fetch_array($query_rule)) {
		$arr_rule[$rec_rule["RULE_ID"]] = $rec_rule["RULE_NAME"];
	}
	if ($rec['PRJP_CON_ID'] != '') {
		$sql_idp = "select ORG_ID from prjp_project where PRJP_ID = '" . $rec['PRJP_CON_ID'] . "'";
		$query_idp = $db->query($sql_idp);
		$rec_idp = $db->db_fetch_array($query_idp);
	}
	//if($rec['ORG_TYPE_ID']=='5'){
	//$at5 = " AND ORG_LEVEL = '3'";	
	//		$sql_org = "SELECT ORG_ID,ORG_NAME_TH,ORG_LEVEL
	//							from setup_org 
	//							where 1=1 and ACTIVE_STATUS = '1' AND ORG_TYPE_ID =  '".$rec['ORG_TYPE_ID']."' AND ORG_LEVEL = '1' ";
	//		$query_org = $db->query($sql_org);
	//		while($rec_org = $db->db_fetch_array($query_org)){
	//		$arr_org[$rec_org["ORG_ID"]]=$rec_org["ORG_NAME_TH"];
	//}


	//}else{
	//$at5 = "";
	$sql_org = "SELECT ORG_ID,ORG_NAME
					from setup_org_bu 
					where 1=1 and ACTIVE_STATUS = '1' AND ORG_TYPE_ID =  '" . $rec['ORG_TYPE_ID'] . "' AND ORG_LEVEL = '1' ";
	$query_org = $db->query($sql_org);
	while ($rec_org  = $db->db_fetch_array($query_org)) {
		$arr_org[$rec_org["ORG_ID"]] = $rec_org["ORG_NAME"];
	}

	//	}
	//$sql_org = "select ORG_ID,ORG_NAME_TH FROM setup_org WHERE ACTIVE_STATUS = '1' AND ORG_TYPE_ID = '".$rec['ORG_TYPE_ID']."' ".$at5."";
	//		$query_org = $db->query($sql_org);
	//		while($rec_org = $db->db_fetch_array($query_org)){
	//			$arr_org[$rec_org["ORG_ID"]]=$rec_org["ORG_NAME_TH"];
	//		}
	$sql_ppetc = "select PRJP_ID,DATA_USED_ID FROM prjp_project_etc where PRJP_ID = '" . $PRJP_ID . "' ";
	$query_ppetc = $db->query($sql_ppetc);
	while ($rec_ppetc = $db->db_fetch_array($query_ppetc)) {
		$arr_ppetc[$rec_ppetc["DATA_USED_ID"]] = $rec_ppetc["DATA_USED_ID"];
	}
	$sql_checked_rule = "SELECT * FROM prjp_rule WHERE PRJP_ID = '" . $PRJP_ID . "'";
	$query_chk_rule = $db->query($sql_checked_rule);
	while ($rec_chk_rule = $db->db_fetch_array($query_chk_rule)) {
		$arr_chk_rule[$rec_chk_rule["RULE_ID"]] = "checked";
	}
	$sql_checked_main_goal = "SELECT * FROM prjp_main_goal WHERE PRJP_ID = '" . $PRJP_ID . "'";
	$query_chk_main_goal = $db->query($sql_checked_main_goal);
	while ($rec_chk_main_goal = $db->db_fetch_array($query_chk_main_goal)) {
		$arr_chk_main_goal[$rec_chk_main_goal["MAIN_GOAL_ID"]] = "checked";
	}

	$arr_indicate_lvl1 = array();
	if ($rec['STRGIC_LVL1_ID'] > 0) {
		$sql_strgic = "SELECT STRGIC_ID,STRGIC_NAME 
						FROM plan_strgic_lv1 
						where STRGIC_PARENT_ID =  '" . $rec['STRGIC_LVL1_ID'] . "' AND
						STRGIC_LEVEL = '2' AND YEAR_BDG = '" . $_SESSION['year_round'] . "' ";
		$query_strgic = $db->query($sql_strgic);
		while ($rec_strgic  = $db->db_fetch_array($query_strgic)) {
			$arr_indicate_lvl1[$rec_strgic["STRGIC_ID"]] = $rec_strgic["STRGIC_NAME"];
		}
	}

	$sql_strgy = "SELECT STRGIC_ID,STRGIC_NAME 
								from plan_strgic 
								where 1=1 AND STRGIC_LEVEL = '2' AND STRGIC_PARENT_ID =  '" . $rec['STRGIC_ID'] . "' ";
	$query_strgy = $db->query($sql_strgy);
	while ($rec_strgy = $db->db_fetch_array($query_strgy)) {
		$arr_strgy[$rec_strgy["STRGIC_ID"]] = $rec_strgy["STRGIC_NAME"];
	}
	$sql_strgy2 = "SELECT STRGIC_ID,STRGIC_NAME 
								from plan_strgic2 
								where 1=1 AND STRGIC_LEVEL = '2' AND STRGIC_PARENT_ID =  '" . $rec['STRGIC_ID2'] . "' ";
	$query_strgy2 = $db->query($sql_strgy2);
	while ($rec_strgy2 = $db->db_fetch_array($query_strgy2)) {
		$arr_strgy2[$rec_strgy2["STRGIC_ID"]] = $rec_strgy2["STRGIC_NAME"];
	}
	$sql_strgy3 = "SELECT STRGIC_ID,STRGIC_NAME 
								from plan_strgic3
								where 1=1 AND STRGIC_LEVEL = '2' AND STRGIC_PARENT_ID =  '" . $rec['STRGIC_ID3'] . "' ";
	$query_strgy3 = $db->query($sql_strgy3);
	while ($rec_strgy3 = $db->db_fetch_array($query_strgy3)) {
		$arr_strgy3[$rec_strgy3["STRGIC_ID"]] = $rec_strgy3["STRGIC_NAME"];
	}
	$sql_strgy_indicate = "select STRGIC_INDICATE_ID,STRGIC_INDICATE_NAME 
								FROM plan_strgic_indicate
								where 1=1 AND STRGIC_ID = '" . $rec['STRGY_ID'] . "'
								";
	$query_strgy_indicate = $db->query($sql_strgy_indicate);
	while ($rec_strgy_indicate = $db->db_fetch_array($query_strgy_indicate)) {
		$arr_strgy_indicate[$rec_strgy_indicate["STRGIC_INDICATE_ID"]] = $rec_strgy_indicate["STRGIC_INDICATE_NAME"];
	}
	$sql_strgy_indicate3 = "select STRGIC_INDICATE_ID,STRGIC_INDICATE_NAME 
								FROM plan_strgic_indicate3
								where 1=1 AND STRGIC_ID = '" . $rec['STRGY_ID3'] . "'
								";
	$query_strgy_indicate3 = $db->query($sql_strgy_indicate3);
	while ($rec_strgy_indicate3 = $db->db_fetch_array($query_strgy_indicate3)) {
		$arr_strgy_indicate3[$rec_strgy_indicate3["STRGIC_INDICATE_ID"]] = $rec_strgy_indicate3["STRGIC_INDICATE_NAME"];
	}


	$sql_strgy_goal = "select STRGIC_GOAL_ID,STRGIC_GOAL_NAME,STRGIC_GOAL_CODE 
								from plan_strgic_goal 
								WHERE 1=1 AND STRGIC_ID = '" . $rec['STRGIC_ID'] . "'";
	$query_strgy_goal = $db->query($sql_strgy_goal);
	while ($rec_strgy_goal = $db->db_fetch_array($query_strgy_goal)) {
		$arr_strgy_goal[$rec_strgy_goal["STRGIC_GOAL_ID"]] = $rec_strgy_goal["STRGIC_GOAL_CODE"] . " " . $rec_strgy_goal["STRGIC_GOAL_NAME"];
	}
	$sql_strgy_goal3 = "select STRGIC_GOAL_ID,STRGIC_GOAL_NAME,STRGIC_GOAL_CODE 
								from plan_strgic_goal3 
								WHERE 1=1 AND STRGIC_ID = '" . $rec['STRGIC_ID3'] . "'";
	$query_strgy_goal3 = $db->query($sql_strgy_goal3);
	while ($rec_strgy_goal3 = $db->db_fetch_array($query_strgy_goal3)) {
		$arr_strgy_goal3[$rec_strgy_goal3["STRGIC_GOAL_ID"]] = $rec_strgy_goal3["STRGIC_GOAL_CODE"] . " " . $rec_strgy_goal3["STRGIC_GOAL_NAME"];
	}

	$sql_task = "SELECT TASK_JOB_ID,TASK_JOB_NAME 
								from plan_task_job 
								where 1=1 AND STRGIC_ID =  '" . $rec['STRGY_ID'] . "' AND STRGIC_INDICATE_ID = '{$rec['STRGIC_INDICATE_ID']}'  ";
	$query_task = $db->query($sql_task);
	while ($rec_task = $db->db_fetch_array($query_task)) {
		$arr_task[$rec_task["TASK_JOB_ID"]] = $rec_task["TASK_JOB_NAME"];
	}
	$sql_prjp_major = "SELECT * FROM prjp_major WHERE PRJP_ID = '" . $PRJP_ID . "'";
	$query_prjp_major = $db->query($sql_prjp_major);
	while ($rec_prjp_major = $db->db_fetch_array($query_prjp_major)) {
		$arr_prjp_major_m[$rec_prjp_major["GOAL_TYPE_ID"]] = "checked";
		$arr_prjp_major_ms[$rec_prjp_major["GOAL_TYPE_ID"]][$rec_prjp_major['GOAL_MAJOR_ID']] = "checked";
	}


	$sql_checked = "SELECT * FROM prjp_location WHERE PRJP_ID = '" . $PRJP_ID . "'";
	$query_chk = $db->query($sql_checked);
	while ($rec_chk = $db->db_fetch_array($query_chk)) {
		$arr_chk_zone[$rec_chk["ZONE_CODE"]] = "checked";
		$arr_chk_in[$rec_chk["ZONE_CODE"]][$rec_chk['PROVINCE_CODE']] = "checked";
		$arr_chk_all[$rec_chk['PROVINCE_CODE']] = "checked";
	}
	$sql_checked_out = "SELECT * FROM prjp_location_out WHERE PRJP_ID = '" . $PRJP_ID . "'";
	$query_chk_out = $db->query($sql_checked_out);
	while ($rec_chk_out = $db->db_fetch_array($query_chk_out)) {
		$arr_chk_area[$rec_chk_out["AREA_ID"]] = "checked";
		$arr_chk_out[$rec_chk_out["AREA_ID"]][$rec_chk_out['COUNTRY_ID']] = "checked";
	}
} //if edit
/////////////////////////// tree พื้นที่  /////////////////////////////////
$sql_area = "
			SELECT AREA_ID,AREA_NAME FROM setup_area WHERE ACTIVE_STATUS = '1'
		ORDER BY AREA_ID asc
			";
$query_area = $db->query($sql_area);
while ($rec_area = $db->db_fetch_array($query_area)) {
	$arr_m3[$rec_area['AREA_ID']] = text($rec_area['AREA_NAME']);
}


$sql_country = "SELECT AREA_ID,COUNTRY_ID,COUNTRY_NAME_TH FROM setup_country WHERE ACTIVE_STATUS = '1' ORDER BY COUNTRY_ID asc";
$query_country = $db->query($sql_country);
while ($rec_country = $db->db_fetch_array($query_country)) {
	$arr_m4[$rec_country['AREA_ID']][$rec_country['COUNTRY_ID']] = text($rec_country['COUNTRY_NAME_TH']);
}
$sql_zone = "
			SELECT ZONE_CODE,ZONE_NAME_TH FROM setup_zone WHERE ACTIVE_STATUS = '1'
		ORDER BY ZONE_CODE asc
			";
$query_zone = $db->query($sql_zone);
while ($rec_zone = $db->db_fetch_array($query_zone)) {
	$arr_m1[$rec_zone['ZONE_CODE']] = text($rec_zone['ZONE_NAME_TH']);
}
$sql_province = "
			SELECT ZONE_CODE,PROVINCE_CODE,PROVINCE_NAME_TH FROM setup_province WHERE ACTIVE_STATUS = '1'
		ORDER BY ZONE_CODE asc,PROVINCE_CODE asc
			";
$query_province = $db->query($sql_province);
while ($rec_province = $db->db_fetch_array($query_province)) {
	$arr_m2[$rec_province['ZONE_CODE']][$rec_province['PROVINCE_CODE']] = text($rec_province['PROVINCE_NAME_TH']);
}


if ($proc == 'add') {
	$sql_prj = "select PRJP_ID,PRJP_NAME 
					from prjp_project 
					where ORG_ID = '" . $_SESSION['sys_dept_id'] . "' 
					and YEAR_BDG = '" . $_SESSION['year_round'] . "' 
					order by PRJP_ID asc
					";
	$query_prj = $db->query($sql_prj);
	while ($rec_prj = $db->db_fetch_array($query_prj)) {
		$arr_prj[$rec_prj['PRJP_ID']] = text($rec_prj['PRJP_NAME']);
	}
} else {
	if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) {
		$sql_prj = "select PRJP_ID,PRJP_NAME 
					from prjp_project 
					where ORG_ID = '" . $rec['ORG_ID'] . "' 
					and YEAR_BDG = '" . $_SESSION['year_round'] . "' 
					and PRJP_ID <> '" . $PRJP_ID . "'
					order by PRJP_ID asc
					";
		$query_prj = $db->query($sql_prj);
		while ($rec_prj = $db->db_fetch_array($query_prj)) {
			$arr_prj[$rec_prj['PRJP_ID']] = text($rec_prj['PRJP_NAME']);
		}
	} else {
		$sql_prj = "select PRJP_ID,PRJP_NAME 
					from prjp_project 
					where ORG_ID = '" . $_SESSION['sys_dept_id'] . "' 
					and YEAR_BDG = '" . $_SESSION['year_round'] . "' 
					and PRJP_ID <> '" . $PRJP_ID . "'
					order by PRJP_ID asc
					";
		$query_prj = $db->query($sql_prj);
		while ($rec_prj = $db->db_fetch_array($query_prj)) {
			$arr_prj[$rec_prj['PRJP_ID']] = text($rec_prj['PRJP_NAME']);
		}
	}
}
// user
$sql_user = "select a.*
from aut_user a 
INNER join aut_appv_status d on a.AUT_USER_ID = d.APPV_USER_ID
where d.APPV_USER_VALUE = 1";
$query_user = $db->query($sql_user);
while ($rec_user = $db->db_fetch_array($query_user)) {
	$arr_user[$rec_user['AUT_USER_ID']] = $rec_user['AUT_F_NAME'] . " " . $rec_user['AUT_L_NAME'];
}
// ผู้บังคับบัญชา
$sql_sup = "SELECT a.AUT_USER_ID,
a.AUT_F_NAME,
a.AUT_L_NAME,
b.TYPE_USER_ID,
b.TYPE_USER_VALUE
FROM aut_user a 
INNER JOIN aut_type_user b ON a.AUT_USER_ID = b.TYPE_USER_ID
WHERE b.TYPE_USER_VALUE != 3";
$query_sup = $db->query($sql_sup);
while ($rec_sup = $db->db_fetch_array($query_sup)) {
	$arr_sup[$rec_sup['AUT_USER_ID']] = $rec_sup['AUT_F_NAME'] . " " . $rec_sup['AUT_L_NAME'];
}


// ลักษณะโครงการ
$sql_pro = "SELECT * FROM dash_pro_desc";
$query_pro = $db->query($sql_pro);
// ขนาดธุรกิจ
$sql_bus = "SELECT * FROM dash_bus_size";
$query_bus = $db->query($sql_bus);
//Life Cycle
$sql_life_cycle = "SELECT * FROM dash_life_cycle";
$query_life_cycle = $db->query($sql_life_cycle);
// กลุ่มการส่งเสริม MSME
$sql_msme = "SELECT * FROM dash_msme_group";
$query_msme = $db->query($sql_msme);
//value chain
$sql_value = "SELECT * FROM dash_value_chain";
$query_value = $db->query($sql_value);
//มตินโยบายรัฐ
$sql_gove = "SELECT * FROM dash_gove_policy";
$query_gove = $db->query($sql_gove);
//High impact
$sql_high = "SELECT * FROM dash_high_impact";
$query_high = $db->query($sql_high);
?>
<!DOCTYPE html>
<html>

<head>
	<?php include($path . "include/inc_main_top.php"); ?>
	<script src="js/disp_project.js?<?php echo rand(); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			<?php if ($rec['PRJP_STATUS'] == 1) { ?>
				$('#show_sdate_3').hide();
				$('#show_sdate_4').hide();
			<?php } elseif ($rec['PRJP_STATUS'] == 2) { ?>
				$('#show_sdate_3').show();
				$('#show_sdate_4').hide();
			<?php } elseif ($rec['PRJP_STATUS'] == 3) { ?>
				$('#show_sdate_3').hide();
				$('#show_sdate_4').show();
			<?php } ?>

			<?php if ($rec_set_step_1['step_sdate'] == "") { ?>
				$('#show_sdate_1').hide();
			<?php } ?>

			chk_can_edit();
		});

		function chk_can_edit() {
			var permission_edit = '<?php echo $_SESSION['sys_status_edit']; ?>';
			var project_status = $('input[name="PRJP_STATUS"][type=radio]:checked').val() || '';
			var SERVICE_PROJECT_ID = $('#SERVICE_PROJECT_ID').val();
			if (permission_edit == '1' && (project_status == '2' || project_status == '3' || SERVICE_PROJECT_ID == '0')) {
				$('#MONEY_BDG_SME').removeAttr('readonly');
				$('#MONEY_BDG_OUT').removeAttr('readonly');
				$('#SDATE_PRJP').removeAttr('readonly');
				$('span.datepicker[for="SDATE_PRJP"]').show();
				$('#EDATE_PRJP').removeAttr('readonly');
				$('span.datepicker[for="EDATE_PRJP"]').show();
			} else {
				// $('#MONEY_BDG_SME').attr('readonly', 'readonly');
				$('#MONEY_BDG_SME').attr('readonly', 'readonly');
				$('#MONEY_BDG_OUT').attr('readonly', 'readonly');
				$('#SDATE_PRJP').attr('readonly', 'readonly');
				$('span.datepicker[for="SDATE_PRJP"]').hide();
				$('#EDATE_PRJP').attr('readonly', 'readonly');
				$('span.datepicker[for="EDATE_PRJP"]').hide();
			}
		}

		function Chkshow() {
			if ($('#chk_show_sdate').prop("checked") == true) {
				$('#show_sdate_1').show();
			} else {
				$('#show_sdate_1').hide();
			}
		}

		function show_prj(id) {
			if (id == 1) {
				$("#show_tprj").hide();

				$('#show_sdate_3').hide();
				$('#show_sdate_4').hide();
			} else {
				$("#show_tprj").show();
			}

			if (id == 2) {
				$('#step_sdate_3').prop("disabled", false);
				$('#show_sdate_3').show();
				$('#step_sdate_4').prop("disabled", true);
				$('#show_sdate_4').hide();

			}
			if (id == 3) {
				$('#step_sdate_3').prop("disabled", true);
				$('#show_sdate_3').hide();
				$('#step_sdate_4').prop("disabled", false);
				$('#show_sdate_4').show();
			}

			chk_can_edit();
		}
	</script>

</head>

<body>
	<div class="container-full">
		<div>
			<?php include($path . "include/header.php"); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12">
			<ol class="breadcrumb">
				<li><a href="index.php?<?php echo $paramlink; ?>">หน้าแรก</a></li>
				<li><a href="disp_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id . $paramSearch); ?>"><?php echo Showmenu($menu_sub_id); ?></a></li>
				<li class="active"><?php echo $txt; ?></li>
			</ol>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="groupdata">
				<form id="frm-search" name="frm-search" action="" method="post" enctype="multipart/form-data">
					<input name="proc" type="hidden" id="proc" value="<?php echo $proc; ?>">
					<input name="menu_id" type="hidden" id="menu_id" value="<?php echo $menu_id; ?>">
					<input name="menu_sub_id" type="hidden" id="menu_sub_id" value="<?php echo $menu_sub_id; ?>">
					<input name="page" type="hidden" id="page" value="<?php echo $page; ?>">
					<input name="page_size" type="hidden" id="page_size" value="<?php echo $page_size; ?>">
					<input type="hidden" id="PRJP_ID" name="PRJP_ID" value="<?php echo $PRJP_ID; ?>">
					<input name="S_TYPE" type="hidden" id="S_TYPE" value="<?php echo $S_TYPE; ?>">
					<input name="hide_show" type="hidden" id="hide_show" value="<?php echo $hide_show; ?>">
					<input name="s_bdg_type_id" type="hidden" id="s_bdg_type_id" value="<?php echo $s_bdg_type_id; ?>">
					<input name="s_name" type="hidden" id="s_name" value="<?php echo $s_name; ?>">
					<input name="s_org_type" type="hidden" id="s_org_type" value="<?php echo $s_org_type; ?>">
					<input name="s_org_name" type="hidden" id="s_org_name" value="<?php echo $s_org_name; ?>">
					<input name="s_zone_code" type="hidden" id="s_zone_code" value="<?php echo $s_zone_code; ?>">
					<input name="s_province_code" type="hidden" id="s_province_code" value="<?php echo $s_province_code; ?>">
					<input name="s_goal_type" type="hidden" id="s_goal_type" value="<?php echo $s_goal_type; ?>">
					<input name="s_goal_main" type="hidden" id="s_goal_main" value="<?php echo $s_goal_main; ?>">
					<input type="hidden" id="SERVICE_PROJECT_ID" name="SERVICE_PROJECT_ID" value="<?php echo $rec['SERVICE_PROJECT_ID']; ?>">
					<?php
					if ($proc == 'add') { ?>
						<input type="hidden" id="SPRJP" name="SPRJP" value="1">
					<?php } else { ?>
						<input type="hidden" id="SPRJP" name="SPRJP" value="<?php echo $rec['PRJP_STATUS']; ?>">
						<?php $ACT = 0; ?>
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12"><?php include("tab_menu.php"); ?></div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading row" style="">
									<div class="pull-left">บันทึกโครงการ</div>
									<div class="pull-right">สสว.100</div>
								</div>
								<div class="panel-body epm-gradient">
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;"> การเปิดโครงการ : <font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-4"> <input type="checkbox" id="chk_show_sdate" name="chk_show_sdate" onClick="Chkshow();" value="Y" <?php echo ($rec_set_step_1['step_sdate'] != "") ? "checked" : ""; ?>> อนุมัติเปิดโครงการ </div>

										<span id="show_sdate_1">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">วันที่อนุมัติเปิดโครงการ :</div>
											<div class="col-xs-12 col-sm-3 col-md-2">
												<div class="input-group">
													<input type="text" id="step_sdate_1" name="step_sdate_1" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : 'chk_empty'; ?>" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo conv_date($rec_set_step_1["step_sdate"]); ?>">
													<span class="input-group-addon datepicker" for="step_sdate_1">&nbsp;
														<span class="glyphicon glyphicon-calendar"></span>&nbsp;
													</span>
												</div>
											</div>
										</span>
									</div>

									<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
										<div class="row">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ประเภทหน่วยงาน :</div>
											<div class="col-xs-12 col-sm-8 col-md-4">
												<div class="input-group">
													<select id="ORG_TYPE_ID" name="ORG_TYPE_ID" class="selectbox form-control" placeholder="ประเภทหน่วยงาน" onChange="get_org(this);" style="width:100%">
														<option value="" selected></option>
														<?php
														if (count($arr_torg) > 0) {
															$k = 1;
															foreach ($arr_torg as $key => $val) {
														?>
																<option value="<?php echo $key; ?>" <?php echo ($rec['ORG_TYPE_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
														<?php
															}
															$k++;
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-xs-12 col-sm-4 col-md-2">หน่วยงาน :</div>
											<div class="col-xs-12 col-sm-8 col-md-4">
												<!--<div class="input-group ">-->
												<select id="ORG_ID" name="ORG_ID" class="selectbox form-control" placeholder="หน่วยงาน" onChange="get_con_prjid(this,'<?php echo $_SESSION['year_round']; ?>');" style="width:100% !important;">
													<option value=""></option>
													<?php
													if (count($arr_org) > 0) {
														foreach ($arr_org as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['ORG_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
															<?php
															$sql_lv2 = "SELECT ORG_ID,ORG_NAME,ORG_LEVEL
																	from setup_org_bu 
																	where 1=1 and ACTIVE_STATUS = '1' AND ORG_PARENT_ID =  '" . $key . "' AND ORG_LEVEL = '2'";
															$query_lv2 = $db->query($sql_lv2);
															while ($rec_lv2 = $db->db_fetch_array($query_lv2)) {
															?>
																<option value="<?php echo $rec_lv2['ORG_ID']; ?>" style="padding-left:<?php echo (($rec_lv2['ORG_LEVEL'] - 1) * 20); ?>px" <?php echo ($rec['ORG_ID'] == $rec_lv2['ORG_ID'] ? "selected" : ""); ?>><?php echo text($rec_lv2['ORG_NAME']); ?></option>
																<?php
																$sql_lv3 = "SELECT ORG_ID,ORG_NAME,ORG_LEVEL 
																		from setup_org_bu 
																		where 1=1 and ACTIVE_STATUS = '1' AND ORG_PARENT_ID =  '" . $rec_lv2['ORG_ID'] . "' AND ORG_LEVEL = '3'";
																$query_lv3 = $db->query($sql_lv3);
																while ($rec_lv3 = $db->db_fetch_array($query_lv3)) {
																?>
																	<option value="<?php echo $rec_lv3['ORG_ID']; ?>" style="padding-left:<?php echo (($rec_lv3['ORG_LEVEL'] - 1) * 20); ?>px" <?php echo ($rec['ORG_ID'] == $rec_lv3['ORG_ID'] ? "selected" : ""); ?>><?php echo text($rec_lv3['ORG_NAME']); ?></option>
													<?php
																}
															}
														}
													}
													?>
												</select>
												<!--</div>	-->
											</div>
										</div>
									<?php } else { ?>
										<div class="row">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">หน่วยงาน :</div>
											<div class="col-xs-12 col-sm-8 col-md-10">
												<input type="hidden" id='ORG_ID' name="ORG_ID" value="<?php echo $_SESSION['sys_dept_id']; ?>">
												<?php echo text($_SESSION['sys_dept_name']); ?>
											</div>
										</div>
									<?php } ?>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">สถานะโครงการ :</div>
										<div class="col-xs-12 col-sm-8 col-md-5">
											<div class="input-group">
												<input name="PRJP_STATUS" id="PRJP_STATUS1" type="radio" value="1" <?php echo ($rec['PRJP_STATUS'] == '1' || $rec['PRJP_STATUS'] == '' ? "checked" : ""); ?> onClick="show_prj('1');">&nbsp;โครงการใหม่
												<?php if ($proc == 'edit') { ?>
													&nbsp;&nbsp;<input name="PRJP_STATUS" id="PRJP_STATUS2" type="radio" value="2" <?php echo ($rec['PRJP_STATUS'] == '2' ? "checked" : ""); ?> onClick="show_prj('2');">&nbsp;โครงการขยายเวลา
													&nbsp;&nbsp;<input name="PRJP_STATUS" id="PRJP_STATUS3" type="radio" value="3" <?php echo ($rec['PRJP_STATUS'] == '3' ? "checked" : ""); ?> onClick="show_prj('3');">&nbsp;&nbsp;โครงการปรับแผนใหม่
												<?php } ?>
											</div>
										</div>

										<span id="show_sdate_3">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">วันที่ได้รับอนุมัติขยาย : <font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
											</div>
											<div class="col-xs-12 col-sm-3 col-md-2">
												<div class="input-group">
													<input type="text" id="step_sdate_3" name="step_sdate_3" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : 'chk_empty'; ?>" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo conv_date($rec_set_step_3["step_sdate"]); ?>">
													<span class="input-group-addon datepicker" for="step_sdate_3">&nbsp;
														<span class="glyphicon glyphicon-calendar"></span>&nbsp;
													</span>
												</div>
											</div>
										</span>
										<span id="show_sdate_4">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">วันที่อนุมัติปรับแผน :<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
											</div>
											<div class="col-xs-12 col-sm-3 col-md-2">
												<div class="input-group">
													<input type="text" id="step_sdate_4" name="step_sdate_4" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : 'chk_empty'; ?>" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo conv_date($rec_set_step_4["step_sdate"]); ?>">
													<span class="input-group-addon datepicker" for="step_sdate_4">&nbsp;
														<span class="glyphicon glyphicon-calendar"></span>&nbsp;
													</span>
												</div>
											</div>
										</span>

									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">สถานะการดำเนินงาน :</div>
										<div class="col-xs-12 col-sm-8 col-md-9">
											<div class="input-group">
												<!--<label class="padr20" ><input name="PRJP_RUN_STATUS" type="radio" value="0"  <? php // echo ($rec['PRJP_RUN_STATUS'] == '0' || $rec['PRJP_RUN_STATUS'] == '' ? "checked":""); 
																																	?>>&nbsp;ยังไม่เริ่ม (ยังไม่กรอกผล)</label>
											<label class="padr20" ><input name="PRJP_RUN_STATUS" type="radio" value="1" <?php //echo ($rec['PRJP_RUN_STATUS'] == '1' ? "checked":""); 
																														?>>&nbsp;กำลังดำเนินกิจกรรม (กรอกผลแล้ว)</label>
											<label class="padr20" ><input name="PRJP_RUN_STATUS" type="radio" value="2" <?php //echo ($rec['PRJP_RUN_STATUS'] == '2' ? "checked":""); 
																														?>>&nbsp;เสร็จสิ้น (แนบไฟล์ 300 แล้ว)</label>!-->
												<?php

												if ($rec['PRJP_RUN_STATUS'] == '0' || empty($rec['PRJP_RUN_STATUS'])) {
													$data = "ยังไม่เริ่ม (ยังไม่กรอกผล)";
												} else if ($rec['PRJP_RUN_STATUS'] == 1) {
													$data = "กำลังดำเนินกิจกรรม (กรอกผลแล้ว)";
												} else if ($rec['PRJP_RUN_STATUS'] == 2) {
													$data = "เสร็จสิ้น (แนบไฟล์ 300 แล้ว)";
												}
												echo $data;
												?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ประเภทโครงการ :&nbsp;<font color="#FF0000">*</font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select id="BDG_TYPE_ID" name="BDG_TYPE_ID" onchange="toggle_money(this.value);" class="selectbox form-control " placeholder="ประเภทโครงการ">
												<option value="" selected></option>
												<?php
												if (count($arr_tbdg) > 0) {
													$k = 1;
													foreach ($arr_tbdg as $key_tbdg => $val_tbdg) {
												?>
														<option value="<?php echo $key_tbdg; ?>" <?php echo ($rec['BDG_TYPE_ID'] == $key_tbdg ? "selected" : ""); ?>><?php echo ($val_tbdg); ?></option>
												<?php
													}
													$k++;
												}
												?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">รหัสโครงการ :</div>
										<div class="col-xs-1 col-sm-8 col-md-10" style="white-space:nowrap;">
											<div class="input-group group-prjp-code">
												<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
													<input maxlength='2' readonly onkeyup="goto_1();" size='2' type="text" id="CODE_1" name="CODE_1" class="form-control " placeholder="" value="<?php echo ($code_1); ?>">
													<span class="input-group-addon">-</span>
													<input maxlength='2' readonly onkeyup="goto_2();" size='2' type="text" id="CODE_2" name="CODE_2" class="form-control " placeholder="" value="<?php echo ($code_2); ?>">
													<span class="input-group-addon">-</span>
													<input maxlength='2' readonly onkeyup="goto_3();" size='2' type="text" id="CODE_3" name="CODE_3" class="form-control " placeholder="" value="<?php echo ($code_3); ?>">
													<span class="input-group-addon">-</span>
													<input maxlength='2' readonly onkeyup="goto_4();" size='2' type="text" id="CODE_4" name="CODE_4" class="form-control " placeholder="" value="<?php echo ($code_4); ?>">
													<span class="input-group-addon">-</span>
													<input maxlength='3' readonly size='3' type="text" id="CODE_5" name="CODE_5" class="form-control " placeholder="" value="<?php echo ($code_5); ?>">
												<?php } else { ?>
													<input name="CODE_1" type="hidden" value="<?php echo ($code_1); ?>">
													<input type="hidden" name="CODE_2" value="<?php echo ($code_2); ?>">
													<input type="hidden" name="CODE_3" value="<?php echo ($code_3); ?>">
													<input type="hidden" name="CODE_4" value="<?php echo ($code_4); ?>">
													<input type="hidden" name="CODE_5" value="<?php echo ($code_5); ?>">
												<?php echo $code_1 . "-" . $code_2 . "-" . $code_3 . "-" . $code_4 . "-" . $code_5;
												} ?>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ชื่องาน/โครงการ :&nbsp;<font color="#FF0000">*</font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<textarea readonly class="form-control" name="PRJP_NAME" id="PRJP_NAME" rows="3" placeholder="ชื่องาน/โครงการ"><?php echo text($rec['PRJP_NAME']); ?></textarea>
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">หลักการและเหตุผล :</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<textarea readonly class="form-control" name="REASONABLE_NAME" id="REASONABLE_NAME" rows="7" placeholder="   หลักการและเหตุผล"><?php echo text_editor($rec['REASONABLE_NAME']); ?></textarea>
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">วัตถุประสงค์ :</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<textarea readonly class="form-control" name="OBJECTTIVE_DESC" id="OBJECTTIVE_DESC" rows="7" placeholder="   วัตถุประสงค์"><?php echo text_editor($rec['OBJECTTIVE_DESC']); ?></textarea>
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เงินงบประมาณ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="MONEY_BDG_SME" name="MONEY_BDG_SME" class="form-control number_format text-right" placeholder="วงเงินงบประมาณ" maxlength="20" value="<?php echo number_format($rec["MONEY_BDG_SME"], 2); ?>" onBlur="NumberFormat(this,2);" onchange="SumMoney()">
										</div>
										<div class="col-xs-12 col-sm-1 col-md-1">
											<span style="">บาท</span>
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เงินนอกงบประมาณ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="MONEY_BDG_OUT" name="MONEY_BDG_OUT" class="form-control number_format text-right" placeholder="เงินนอกงบประมาณ" maxlength="20" value="<?php echo number_format($rec["MONEY_BDG_OUT"], 2); ?>" onBlur="NumberFormat(this,2);" onchange="SumMoney()">
										</div>
										<div class="col-xs-12 col-sm-1 col-md-1">
											<span style="">บาท</span>
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">รวมงบประมาณ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="MONEY_BDG" name="MONEY_BDG" class="form-control number_format text-right" placeholder="รวมงบประมาณ" maxlength="20" value="<?php echo number_format($rec["MONEY_BDG"], 2); ?>" onBlur="NumberFormat(this,2);" readonly>
										</div>
										<div class="col-xs-12 col-sm-1 col-md-1">
											<span style="">บาท</span>
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">หน่วยงานร่วมดำเนินงาน :</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<input type="text" id="ORG_NAME" name="ORG_NAME" class="form-control" placeholder="หน่วยงานร่วมดำเนินงาน" value="<?php echo text($rec["ORG_NAME"]); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ระยะเวลา :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
										</div>
										<div class="col-xs-12 col-sm-3 col-md-2">
											<div class="input-group">
												<input readonly type="text" id="SDATE_PRJP" name="SDATE_PRJP" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : 'chk_empty'; ?>" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo conv_date($rec["SDATE_PRJP"]); ?>">
												<span class="input-group-addon datepicker" for="SDATE_PRJP" style="display:none">&nbsp;
													<span class="glyphicon glyphicon-calendar"></span>&nbsp;
												</span>
											</div>
										</div>
										<div class="col-xs-12 col-sm-1 col-md-1">ถึง</div>
										<div class="col-xs-12 col-sm-3 col-md-2">
											<div class="input-group">
												<input readonly type="text" id="EDATE_PRJP" name="EDATE_PRJP" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : 'chk_empty'; ?>" placeholder="DD/MM/YYYY" maxlength="10" value="<?php echo conv_date($rec["EDATE_PRJP"]); ?>">
												<span class="input-group-addon datepicker" for="EDATE_PRJP" style="display:none">&nbsp;
													<span class="glyphicon glyphicon-calendar"></span>&nbsp;
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="panel panel-default">
								<div class="panel-heading row" style="">
									<div class="pull-left">ผู้รับผิดชอบโครงการ และผู้บันทึกข้อมูล</div>
									<div class="pull-right"></div>
								</div>
								<div class="panel-body epm-gradient">

									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ผู้รับผิดชอบคนที่ 1 :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="COORDINATOR_NAME2" name="COORDINATOR_NAME2" class="form-control" placeholder="ผู้ประสานงาน" value="<?php echo text($rec["COORDINATOR_NAME2"]); ?>">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">รหัสพนักงาน :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="COORDINATOR_CODE2" name="COORDINATOR_CODE2" class="form-control" placeholder="รหัสพนักงาน" value="<?php echo text($rec["COORDINATOR_CODE2"]); ?>">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เบอร์โทรศัพท์ :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="COORDINATOR_TEL2" name="COORDINATOR_TEL2" class="form-control" placeholder="เบอร์โทรศัพท์" value="<?php echo text($rec["COORDINATOR_TEL2"]); ?>">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">E-mail :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="EMAIL_PRJP2" name="EMAIL_PRJP2" class="form-control" placeholder="อีเมล์" value="<?php echo text($rec["EMAIL_PRJP2"]); ?>" onBlur="check_email(this)">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;"> </div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<label><input name="COOR_CHK_EMAIL2" id="COOR_CHK_EMAIL2" value="1" type="checkbox" <?php if ($rec["COOR_CHK_EMAIL2"] == 1) {
																																	echo "checked";
																																} ?> /> รับการแจ้งเตือน Email</label>
										</div>
									</div>

									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ผู้รับผิดชอบคนที่ 2 :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="COORDINATOR_NAME" name="COORDINATOR_NAME" class="form-control" placeholder="ผู้ประสานงาน" value="<?php echo text($rec["COORDINATOR_NAME"]); ?>">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">รหัสพนักงาน :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="COORDINATOR_CODE" name="COORDINATOR_CODE" class="form-control" placeholder="รหัสพนักงาน" value="<?php echo text($rec["COORDINATOR_CODE"]); ?>">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เบอร์โทรศัพท์ :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="COORDINATOR_TEL" name="COORDINATOR_TEL" class="form-control" placeholder="เบอร์โทรศัพท์" value="<?php echo text($rec["COORDINATOR_TEL"]); ?>">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">E-mail :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : '*'; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input readonly type="text" id="EMAIL_PRJP" name="EMAIL_PRJP" class="form-control" placeholder="อีเมล์" value="<?php echo text($rec["EMAIL_PRJP"]); ?>" onBlur="check_email(this)">
										</div>
									</div>
									<div class="row toggle-money">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;"> </div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<label><input name="COOR_CHK_EMAIL" id="COOR_CHK_EMAIL" value="1" type="checkbox" <?php if ($rec["COOR_CHK_EMAIL"] == 1) {
																																	echo "checked";
																																} ?> /> รับการแจ้งเตือน Email</label>
										</div>
									</div>


									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ผู้บันทึกข้อมูล :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input type="text" id="USER_ADD_PROJECT" name="USER_ADD_PROJECT" class="form-control" placeholder="ผู้บันทึกข้อมูล" value="<?php echo text($rec["USER_ADD_PROJECT"]); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เบอร์โทรศัพท์ :</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input type="text" id="USER_ADD_PROJECT_TEL" name="USER_ADD_PROJECT_TEL" class="form-control" placeholder="เบอร์โทรศัพท์" value="<?php echo text($rec["USER_ADD_PROJECT_TEL"]); ?>">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">E-mail :&nbsp;<font color="#FF0000"></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<input type="text" id="USER_ADD_PROJECT_EMAIL" name="USER_ADD_PROJECT_EMAIL" class="form-control" placeholder="อีเมล์" value="<?php echo text($rec["USER_ADD_PROJECT_EMAIL"]); ?>" onBlur="check_email(this)">
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;"> </div>
										<div class="col-xs-12 col-sm-8 col-md-4">
											<label><input name="RESP_CHK_EMAIL" id="RESP_CHK_EMAIL" value="1" type="checkbox" <?php if ($rec["RESP_CHK_EMAIL"] == 1) {
																																	echo "checked";
																																} ?> /> รับการแจ้งเตือน Email</label>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-2">
											<div class="input-group">
												<?php if ($_SESSION['sys_status_add'] == '1' || $_SESSION['sys_status_edit'] == '1') { ?>
													<a data-toggle="modal" class="btn btn-default" data-backdrop="static" href="javascript:void(0);" onClick="add_row_sup();"><?php echo $img_save; ?>เพิ่มผู้บังคับบัญชา</a>
												<?php } ?>
											</div>
										</div>
										<div class="col-xs-12 col-sm-1" style="white-space:nowrap;"></div>
										<div class="col-xs-12 col-sm-2">
											<div class="input-group">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12">
											<table width="98%" class="table table-bordered table-striped table-hover table-condensed" id="tb_data_sup">
												<thead>
													<tr class="bgHead">
														<th width="5%">
															<div align="center"><strong>ลำดับ</strong></div>
														</th>
														<th width="85%">
															<div align="center"><strong>รายการ</strong></div>
														</th>
														<th width="10%" nowrap>
															<div align="center"><strong>ลบ</strong></div>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
													if ($proc == "edit") {
														$i = 0;
														$sql_sup = "select * from prjp_sup where PRJP_ID = '" . $_REQUEST['PRJP_ID'] . "'";
														$query_sup = $db->query($sql_sup);
														while ($rec_sup = $db->db_fetch_array($query_sup)) {
															$i++;
															$id_tbr = $i;
															$delete = "<button type=\"button\" class=\"btn btn-default btn-xs\" onClick=\"remove_id('" . $id_tbr . "','tb_data_sup');\">" . $img_del . " ลบ</a> ";
															?>
															<tr id="<?php echo $id_tbr; ?>">
																<td align="center"><?php echo $id_tbr; ?></td>
																<td>
																	<div class="row">
																		<div class="col-xs-12 col-sm-2" style="white-space:nowrap">ผู้บังคับบัญชา :</div>
																		<div class="col-xs-12 col-sm-2" style="white-space:nowrap">
																			<select width="100%" id="SUP_NAME_<?php echo $id_tbr ?>" name="SUP_NAME[]" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="ผู้บังคับบัญชา" onChange="get_sup_pos(this, <?php echo $id_tbr ?>); get_sup_low(this, <?php echo $id_tbr ?>)">
																				<option value="" selected></option>
																				<?php
																				// query ผู้บังคับบัญชา
																				if (count($arr_sup) > 0) {
																					$k = 1;
																					foreach ($arr_sup as $key => $val) {
																				?>
																						<option value="<?php echo $key; ?>" <?php echo ($rec_sup['SUP_NAME'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
																				<?php
																					}
																					$k++;
																				}
																				?>
																			</select>
																			<!-- <span id="SP_RN<?php echo $id_tbr; ?>">
																				<input class="form-control" name="SUP_NAME[]" id="SUP_NAME_<?php echo $id_tbr ?>" type="text" placeholder="เพิ่มผู้บังคับบัญชา" value="<?php echo text($rec_sup['SUP_NAME']); ?>">
																			</span> -->
																		</div>
																		<div class="col-xs-12 col-sm-2" style="white-space:nowrap">ตำแหน่ง :</div>
																		<div class="col-xs-12 col-sm-2" style="white-space:nowrap">
																			<input type="text" id="SUP_POS_<?php echo $id_tbr ?>" name="SUP_POS[]" class="form-control" placeholder="ตำแหน่ง" value="" readonly>
																			<!-- <span id="SP_RP<?php echo $id_tbr; ?>">
																				<input type="text" name="SUP_POS[]" id="SUP_POS_<?php echo $id_tbr ?>" maxlength="100" class="form-control" placeholder="ตำแหน่ง" value="<?php echo text($rec_sup['SUP_POS']); ?>">
																			</span> -->
																		</div>
																		<div class="col-xs-12 col-sm-2" style="white-space:nowrap">สายงาน :</div>
																		<div class="col-xs-12 col-sm-2" style="white-space:nowrap">
																			<input type="text" id="SUP_LOW_<?php echo $id_tbr ?>" name="SUP_LOW[]" class="form-control" placeholder="สายงาน" value="" readonly>
																			<!-- <span id="SP_RP<?php echo $id_tbr; ?>">
																				<input type="text" name="SUP_POS[]" id="SUP_POS_<?php echo $id_tbr ?>" maxlength="100" class="form-control" placeholder="ตำแหน่ง" value="<?php echo text($rec_sup['SUP_POS']); ?>">
																			</span> -->
																		</div>
																	</div>
																</td>
																<td align="center"><?php echo $delete; ?></td>
															</tr>
													<?php
														}
													}
													?>
												</tbody>
											</table>
										</div>
										<div class="row toggle-money">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ผู้อนุมัติโครงการ :</div>
											<div class="col-xs-12 col-sm-8 col-md-4">
												<select width="100%" id="APPROVE_USER_ID" name="APPROVE_USER_ID" class="form-control selectbox <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="ผู้อนุมัติโครงการ" onChange="get_user_low(); get_user_pos(); get_user_code();">

													<?php
													// check if have user_approve
													$sql_approve = "SELECT a.APPROVE_USER_ID, a.PRJP_ID, b.AUT_F_NAME, b.AUT_L_NAME 
																	from prjp_approve a 
																	LEFT JOIN aut_user b on a.APPROVE_USER_ID = b.AUT_USER_ID
																	WHERE a.PRJP_ID = '" . $_POST['PRJP_ID'] . "' ";
													$query_approve = $db->query($sql_approve);
													while ($rec_approve = $db->db_fetch_array($query_approve)) {
														$rec_approve_id = $rec_approve['APPROVE_USER_ID'];
													}
													?>
													<option value="" selected></option>
													<?php
													// query Users
													if (count($arr_user) > 0) {
														$k = 1;
														foreach ($arr_user as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec_approve_id == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											</div>
										</div>
										<div class="row toggle-money">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">รหัสพนักงาน :</div>
											<div class="col-xs-12 col-sm-8 col-md-4">
												<input type="text" id="PER_CODE_CHANGE" name="PER_CODE" class="form-control" placeholder="สายงานที่รับผิดชอบ" value="" readonly>
											</div>
										</div>
										<div class="row toggle-money">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ตำแหน่ง :</div>
											<div class="col-xs-12 col-sm-8 col-md-4">
												<input type="text" id="USER_POS_CHANGE" name="USER_POS" class="form-control" placeholder="ตำแหน่ง" value="" readonly>
											</div>
										</div>
										<div class="row toggle-money">
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">สายงานที่รับผิดชอบ :</div>
											<div class="col-xs-12 col-sm-8 col-md-4">
												<input type="text" id="LOW_NAME_CHANGE" name="LOW_NAME" class="form-control" placeholder="สายงานที่รับผิดชอบ" value="" readonly>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="panel panel-default">
								<div class="panel-heading" style="">แผนระดับ 1</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">แผน :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="STRGIC_LVL1_ID" name="STRGIC_LVL1_ID" class="selectbox form-control" onChange="get_lvl1_indicat(this.value);" placeholder="แผน">
												<option value="" selected></option>
												<?php
												if (count($arr_lvl1) > 0) {
													$k = 1;
													foreach ($arr_lvl1 as $key => $val) { ?>
														<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_LVL1_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
												<?php }
													$k++;
												} ?>
											</select>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">แผนย่อย :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="STRGIC_LVL1_INDICATE_ID" name="STRGIC_LVL1_INDICATE_ID" class="selectbox form-control" placeholder="แผนย่อย">
												<option value="" selected></option>
												<?php
												if (count($arr_indicate_lvl1) > 0) {
													$k = 1;
													foreach ($arr_indicate_lvl1 as $key => $val) { ?>
														<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_LVL1_INDICATE_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
												<?php }
													$k++;
												} ?>
											</select>
										</div>
									</div>
								</div>

							</div>

							<div class="panel panel-default">
								<div class="panel-heading" style="">แผนระดับ 2</div>
								<div class="panel-body epm-gradient">
									<?php $sql_obj_na = $db->query("SELECT
																object_id,
																object_name,
																object_level,
																object_id AS object_parent_id
															FROM service_bdg_object a
															WHERE object_year_bdg = '" . $_SESSION['year_round'] . "' AND object_level = 3 ");
									while ($res_obj_na = $db->db_fetch_array($sql_obj_na)) { ?>
										<div class="row">
											<div class="col-xs-12 col-sm-4 col-md-4" style="white-space:nowrap;"><?php echo text($res_obj_na['object_name']); ?>
											</div>
											<div class="col-xs-12 col-sm-8 col-md-8">
												<select width="100%" id="OBJECT_STRCIG_ID_<?php echo $res_obj_na['object_id']; ?>" name="OBJECT_STRCIG_ID[<?php echo $res_obj_na['object_id']; ?>]" class="selectbox form-control" placeholder="<?php echo text($res_obj_na['object_name']); ?>" onChange="">
													<option value="" selected></option>
													<?php
													$sql_obj_na1 = $db->query("SELECT
																a.object_id,
																a.object_name,
																a.object_level,
																a.object_id AS object_parent_id,
																ISNULL(b.PK_ID, 0) as is_selected
															FROM service_bdg_object a
																LEFT JOIN prjp_project_object_strcig b ON b.PRJP_ID = '{$PRJP_ID}' AND b.object_id = a.object_id AND b.object_parent_id = a.object_parent_id
															WHERE a.object_year_bdg = '" . $_SESSION['year_round'] . "' AND a.object_level = 4  AND a.object_parent_id = '" . $res_obj_na['object_parent_id'] . "'");
													while ($res_obj_na1 = $db->db_fetch_array($sql_obj_na1)) { ?>
														<option style="padding-left:7px" value="<?php echo $res_obj_na1['object_id']; ?>" <?php echo ($res_obj_na1['is_selected'] != '0' ? "selected" : ""); ?>><?php echo text($res_obj_na1['object_name']); ?></option>
													<?php
													} ?>
												</select>
											</div>
										</div>
									<?php } ?>
								</div>
							</div>

							<div class="panel panel-default">
								<div class="panel-heading" style="">แผนระดับ 3</div>
								<div class="panel-body epm-gradient">
									<div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div>
									<div class="row">
										<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ยุทธศาสตร์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
											</div>
											<div class="col-xs-12 col-sm-8 col-md-10">

												<select width="100%" id="STRGIC_ID" name="STRGIC_ID" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="ยุทธศาสตร์" onChange="get_strgy(this);get_st_goal(this);get_task_job('', '');get_strgy_indicate(this);">
													<option value="" selected></option>
													<?php
													if (count($arr_strgic) > 0) {
														$k = 1;
														foreach ($arr_strgic as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											</div>
										<?php } else { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ยุทธศาสตร์ :&nbsp;</div>
											<div class="col-xs-12 col-sm-8 col-md-10">
												<input id="STRGIC_ID" name="STRGIC_ID" type="hidden" value="<?php echo $rec['STRGIC_ID']; ?>">
												<?php echo text($arr_strgic[$rec['STRGIC_ID']]); ?>
											</div>
										<?php } ?>
									</div>
									<div class="row">
										<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เป้าหมายยุทธ์ศาสตร์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
											</div>
											<div class="col-xs-12 col-sm-8 col-md-10">

												<select width="100%" id="STRGIC_GOAL_ID" name="STRGIC_GOAL_ID" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="เป้าหมายยุทธ์ศาสตร์">
													<option value="" selected></option>
													<?php
													if (count($arr_strgy_goal) > 0) {
														$k = 1;
														foreach ($arr_strgy_goal as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_GOAL_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											</div>
										<?php } else { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เป้าหมายยุทธ์ศาสตร์ :</div>
											<div class="col-xs-12 col-sm-8 col-md-10">
												<input id="STRGIC_GOAL_ID" name="STRGIC_GOAL_ID" type="hidden" value="<?php echo $rec['STRGIC_GOAL_ID']; ?>">
												<?php echo text($arr_strgy_goal[$rec['STRGIC_GOAL_ID']]); ?>
											</div>
										<?php } ?>
									</div>
									<div class="row">
										<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">กลยุทธ์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
											</div>
											<div class="col-xs-12 col-sm-8 col-md-10">

												<select width="100%" id="STRGY_ID" name="STRGY_ID" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="กลยุทธ์" onChange="get_task_job(this.value, '');get_strgy_indicate(this);">
													<option value="" selected></option>
													<?php
													if (count($arr_strgy) > 0) {
														$k = 1;
														foreach ($arr_strgy as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGY_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											</div>
										<?php } else { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">กลยุทธ์ :</div>
											<div class="col-xs-12 col-sm-8 col-md-10">
												<input id="STRGY_ID" name="STRGY_ID" type="hidden" value="<?php echo $rec['STRGY_ID']; ?>">
												<?php echo text($arr_strgy[$rec['STRGY_ID']]); ?>
											</div>
										<?php  } ?>
									</div>
									<div class="row">
										<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ตัวชี้วัดกลยุทธ์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
											</div>
											<div class="col-xs-12 col-sm-8 col-md-10">

												<select width="100%" id="STRGIC_INDICATE_ID" name="STRGIC_INDICATE_ID" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="ตัวชี้วัดกลยุทธ์" onChange="get_task_job($('#STRGY_ID').val(), this.value);">
													<option value="" selected></option>
													<?php
													if (count($arr_strgy_indicate) > 0) {
														$k = 1;
														foreach ($arr_strgy_indicate as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_INDICATE_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											</div>
										<?php } else { ?>
											<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ตัวชี้วัดกลยุทธ์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '*' : ''; ?></font>
											</div>
											<div class="col-xs-12 col-sm-8 col-md-10">
												<input id="STRGIC_INDICATE_ID" name="STRGIC_INDICATE_ID" type="hidden" value="<?php echo $rec['STRGIC_INDICATE_ID']; ?>">
												<?php echo text($arr_strgy_indicate[$rec['STRGIC_INDICATE_ID']]); ?>
											</div>
										<?php } ?>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">แผนงาน :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
												<select width="100%" id="TASK_JOB_ID" name="TASK_JOB_ID" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="แผนงาน">
													<option value="" selected></option>
													<?php
													if (count($arr_task) > 0) {
														$k = 1;
														foreach ($arr_task as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['TASK_JOB_ID'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											<?php } else { ?>
												<input id="TASK_JOB_ID" name="TASK_JOB_ID" type="hidden" value="<?php echo $rec['TASK_JOB_ID']; ?>">
												<?php echo text($arr_task[$rec['TASK_JOB_ID']]); ?>
											<?php } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ตอบเป้าหมายของแผน :</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<table width="98%" class="table table-bordered table-striped table-hover table-condensed">
												<tr class="bgHead">
													<td align="center"></td>
													<td align="center">ตอบเป้าหมายของแผน</td>
												</tr>
												<?php
												if (count($arr_mg) > 0) {
													$k = 1;
													foreach ($arr_mg as $key => $val) {
												?>
														<tr>
															<td align="center"><input name="MAIN_GOAL_ID[]" id="MAIN_GOAL_ID_<?php echo $key; ?>" value="<?php echo $key; ?>" type="checkbox" <?php echo $arr_chk_main_goal[$key]; ?>></td>
															<td align="left"><?php echo text($val); ?></td>
														</tr>
												<?php
													}
													$k++;
												}
												?>
											</table>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;">แผนบูรณาการส่งเสริมวิสาหกิจขนาดกลางและขนาดย่อม
											<a class="data-info" data-placement="top" data-title="แผนบูรณาการส่งเสริมวิสาหกิจขนาดกลางและขนาดย่อม" data-content=""> </a>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">แนวทางการดำเนินงาน :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
												<select width="100%" id="STRGIC_ID2" name="STRGIC_ID2" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="แนวทางการดำเนินงาน" onChange="get_strgy2(this);">
													<option value="" selected></option>
													<?php
													if (count($arr_strgic2) > 0) {
														$k = 1;
														foreach ($arr_strgic2 as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_ID2'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											<?php  } else { ?>
												<input id="STRGIC_ID2" name="STRGIC_ID2" type="hidden" value="<?php echo $rec['STRGIC_ID2']; ?>">
												<?php echo text($arr_strgic2[$rec['STRGIC_ID2']]); ?>
											<?php } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ตัวชี้วัด :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
												<select width="100%" id="STRGY_ID2" name="STRGY_ID2" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="กลยุทธ์">
													<option value="" selected></option>
													<?php
													if (count($arr_strgy2) > 0) {
														$k = 1;
														foreach ($arr_strgy2 as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGY_ID2'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											<?php } else { ?>
												<input id="STRGY_ID2" name="STRGY_ID2" type="hidden" value="<?php echo $rec['STRGY_ID2']; ?>">
												<?php echo text($arr_strgy2[$rec['STRGY_ID2']]); ?>
											<?php } ?>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-12 col-md-12"> </div>
									</div>
									<div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT3; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT3; ?>" data-content=""> </a>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ยุทธศาสตร์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
												<select width="100%" id="STRGIC_ID3" name="STRGIC_ID3" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="ยุทธศาสตร์ของ สสว." onChange="get_strgy3(this);get_st_goal3(this);get_strgy_indicate3(this);">
													<option value="" selected></option>
													<?php
													if (count($arr_strgic3) > 0) {
														$k = 1;
														foreach ($arr_strgic3 as $key => $val) {
															if ($rec['STRGIC_ID3'] != $key) {
																// continue;
															}
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_ID3'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											<?php  } else { ?>
												<input id="STRGIC_ID3" name="STRGIC_ID3" type="hidden" value="<?php echo $rec['STRGIC_ID3']; ?>">
												<?php echo text($arr_strgic3[$rec['STRGIC_ID3']]); ?>

											<?php  } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">เป้าหมายยุทธ์ศาสตร์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
												<select width="100%" id="STRGIC_GOAL_ID3" name="STRGIC_GOAL_ID3" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="เป้าหมายยุทธ์ศาสตร์">
													<option value="" selected></option>
													<?php
													if (count($arr_strgy_goal3) > 0) {
														$k = 1;
														foreach ($arr_strgy_goal3 as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_GOAL_ID3'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}

													?>
												</select>
											<?php } else { ?>
												<input id="STRGIC_GOAL_ID3" name="STRGIC_GOAL_ID3" type="hidden" value="<?php echo $rec['STRGIC_GOAL_ID3']; ?>">
												<?php echo text($arr_strgy_goal3[$rec['STRGIC_GOAL_ID3']]); ?>
											<?php }	 ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">กลยุทธ์/แผนงาน :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
												<select width="100%" id="STRGY_ID3" name="STRGY_ID3" class="form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="กลยุทธ์" onChange="get_strgy_indicate3(this);">
													<option value="" selected></option>
													<?php
													if (count($arr_strgy3) > 0) {
														$k = 1;
														foreach ($arr_strgy3 as $key => $val) {
															if ($rec['STRGY_ID3'] != $key) {
																continue;
															}
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGY_ID3'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											<?php  } else {  ?>
												<input id="STRGY_ID3" name="STRGY_ID3" type="hidden" value="<?php echo $rec['STRGY_ID3']; ?>">
												<?php echo text($arr_strgy3[$rec['STRGY_ID3']]); ?>
											<?php  } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ตัวชี้วัดกลยุทธ์/กลยุทธ์ :&nbsp;<font color="#FF0000"><?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?></font>
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<?php if ($_SESSION["sys_group_id"] == '5' || $_SESSION['sys_program_administrator'] == 1) { ?>
												<select width="100%" id="STRGIC_INDICATE_ID3" name="STRGIC_INDICATE_ID3" class="selectbox form-control <?php echo $_SESSION['sys_program_administrator'] == 1 ? '' : ''; ?>" placeholder="ตัวชี้วัดกลยุทธ์">
													<option value="" selected></option>
													<?php
													if (count($arr_strgy_indicate3) > 0) {
														$k = 1;
														foreach ($arr_strgy_indicate3 as $key => $val) {
													?>
															<option value="<?php echo $key; ?>" <?php echo ($rec['STRGIC_INDICATE_ID3'] == $key ? "selected" : ""); ?>><?php echo text($val); ?></option>
													<?php
														}
														$k++;
													}
													?>
												</select>
											<?php } else { ?>
												<input id="STRGIC_INDICATE_ID3" name="STRGIC_INDICATE_ID3" type="hidden" value="<?php echo $rec['STRGIC_INDICATE_ID3']; ?>">
												<?php echo text($arr_strgy_indicate3[$rec['STRGIC_INDICATE_ID3']]); ?>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
							
							<div class="panel panel-default">
								<div class="panel-heading" style="">ลักษณะโครงการ</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ลักษณะโครงการ :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="PRO_DESC" name="PRO_DESC" class="selectbox form-control" placeholder="ลักษณะโครงการ">
												<option value="" selected></option>
												<?php while ($rec_pro = $db->db_fetch_array($query_pro)) { ?>
													<option value="<?php echo $rec_pro['PRO_DESC_ID']; ?>" <?php echo $rec['PRO_DESC'] == $rec_pro['PRO_DESC_ID'] ? "selected":"" ?>><?php echo text($rec_pro['PRO_DESC_NAME']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" style="">ขนาดธุรกิจ</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">ขนาดธุรกิจ :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="BUS_SIZE" name="BUS_SIZE" class="selectbox form-control" placeholder="ขนาดธุรกิจ">
												<option value="" selected></option>
												<?php while ($rec_bus = $db->db_fetch_array($query_bus)) { ?>
													<option value="<?php echo $rec_bus['BUS_SIZE_ID']; ?>" <?php echo $rec['BUS_SIZE'] == $rec_bus['BUS_SIZE_ID'] ? "selected":"" ?>><?php echo text($rec_bus['BUS_SIZE_NAME']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" style="">Life Cycle</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">Life Cycle :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="LIFE_CYCLE" name="LIFE_CYCLE" class="selectbox form-control" placeholder="Life Cycle">
												<option value="" selected></option>
												<?php while ($rec_life_cycle = $db->db_fetch_array($query_life_cycle)) { ?>
													<option value="<?php echo $rec_life_cycle['LIFE_CYCLE_ID']; ?>" <?php echo $rec['LIFE_CYCLE'] == $rec_life_cycle['LIFE_CYCLE_ID'] ? "selected":"" ?>><?php echo text($rec_life_cycle['LIFE_CYCLE_NAME']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" style="">กลุ่มการส่งเสริม MSME</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">กลุ่มการส่งเสริม MSME :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="MSME_GROUP" name="MSME_GROUP" class="selectbox form-control" placeholder="กลุ่มการส่งเสริม MSME">
												<option value="" selected></option>
												<?php while ($rec_msme = $db->db_fetch_array($query_msme)) { ?>
													<option value="<?php echo $rec_msme['MSME_GROUP_ID']; ?>" <?php echo $rec['MSME_GROUP'] == $rec_msme['MSME_GROUP_ID'] ? "selected":"" ?>><?php echo text($rec_msme['MSME_GROUP_NAME']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" style="">Value chain</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">Value chain :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="VALUE_CHAIN" name="VALUE_CHAIN" class="selectbox form-control" placeholder="Value chain">
												<option value="" selected></option>
												<?php while ($rec_value = $db->db_fetch_array($query_value)) { ?>
													<option value="<?php echo $rec_value['VALUE_CHAIN_ID']; ?>" <?php echo $rec['VALUE_CHAIN'] == $rec_value['VALUE_CHAIN_ID'] ? "selected":"" ?>><?php echo text($rec_value['VALUE_CHAIN_NAME']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" style="">มตินโยบายรัฐ</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">มตินโยบายรัฐ :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="GOVE_POLICY" name="GOVE_POLICY" class="selectbox form-control" placeholder="มตินโยบายรัฐ">
												<option value="" selected></option>
												<?php while ($rec_gove = $db->db_fetch_array($query_gove)) { ?>
													<option value="<?php echo $rec_gove['GOVE_POLICY_ID']; ?>" <?php echo $rec['GOVE_POLICY'] == $rec_gove['GOVE_POLICY_ID'] ? "selected":"" ?>><?php echo text($rec_gove['GOVE_POLICY_NAME']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" style="">High Impact</div>
								<div class="panel-body epm-gradient">
									<!--div class="row">
										<div class="col-sm-12 col-md-12 divider" style="white-space:nowrap;"><?php echo $HEAD_STRGIC_TEXT; ?>
											<a class="data-info" data-placement="top" data-title="<?php echo $HEAD_STRGIC_TEXT; ?>" data-content=""> </a>
										</div>
									</div-->
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-2" style="white-space:nowrap;">High Impact :
										</div>
										<div class="col-xs-12 col-sm-8 col-md-10">
											<select width="100%" id="HIGH_IMPACT" name="HIGH_IMPACT" class="selectbox form-control" placeholder="High Impact">
												<option value="" selected></option>
												<?php while ($rec_high = $db->db_fetch_array($query_high)) { ?>
													<option value="<?php echo $rec_high['HIGH_IMPACT_ID']; ?>" <?php echo $rec['HIGH_IMPACT'] == $rec_high['HIGH_IMPACT_ID'] ? "selected":"" ?>><?php echo text($rec_high['HIGH_IMPACT_NAME']); ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="row toggle-money">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading" style="">กลุ่มเป้าหมายและสาขาเป้าหมาย</div>
										<div class="panel-body epm-gradient">
											<div class="row">
												<div class="col-xs-12 col-sm-12 col-md-12">
													<table width="98%" class="table table-bordered table-striped table-hover table-condensed">
														<tr>
															<?php
															if (count($arr_goal) > 0) {
																foreach ($arr_goal as $key => $val) {
															?>
																	<td width="15%">
																		<input name="GOAL_TYPE_ID[]" id="GOAL_TYPE_ID_<?php echo $key; ?>" value="<?php echo $key; ?>" type="checkbox" <?php echo $arr_prjp_major_m[$key] ?> onClick="CheckAll_MA(<?php echo $key; ?>);" class="css_data_main_m_<?php echo $key; ?> class_zone11">&nbsp;&nbsp;<?php echo text($val) . "<br>"; ?>
																		<?php
																		if (count($arr_goal_major[$key]) > 0) {
																			foreach ($arr_goal_major[$key] as $key1 => $val1) {
																		?>
																				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="GOAL_MAJOR_ID[<?php echo $key; ?>][<?php echo $key1 ?>]" id="GOAL_MAJOR_ID_<?php echo $key . "_" . $key1; ?>" class="css_data_item_m_<?php echo $key; ?>" type="checkbox" onClick="Checkmain_MA(<?php echo $key; ?>,<?php echo $key1; ?>);" value="<?php echo $key1; ?>" <?php echo $arr_prjp_major_ms[$key][$key1]; ?>>&nbsp;&nbsp;<?php echo text($val1) . "<br>"; ?>
																		<?php
																			}
																		} ?>
																	</td>
															<?php
																}
															} ?>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="row toggle-money">
								<div class="col-xs-12 col-sm-12 col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading" style="">พื้นที่ส่งเสริม</div>
										<div class="panel-body epm-gradient">

											<div class="col-xs-12 col-sm-12 col-md-12"><?php include("tab_menu_area_in.php"); ?></div><br>
											<table width="70%" class="table table-bordered table-striped table-hover table-condensed" id="tb_area_in">
												<tr class="bgHead">
													<td align="left"> <input name="CHECK_ALL" id="CHECK_ALL" value="1" type="checkbox" onClick="CheckAll();" <?php if (count($arr_chk_all) == 77) {
																																									echo "checked";
																																								} ?>>&nbsp;&nbsp;ทั่วประเทศ</td>
													<td colspan="5" align="center">พื้นที่ส่งเสริมในประเทศ</td>
												</tr>
												<tr valign="top">
													<?php
													foreach ($arr_m1 as $key => $val) { ?>
														<td>
															<input name="CHECK_ALL_IN[]" id="CHECK_ALL_IN_<?php echo $key; ?>" value="<?php echo $key; ?>" type="checkbox" <?php echo $arr_chk_zone[$key] ?> onClick="CheckAll_IN(<?php echo $key; ?>);" class="css_data_main_<?php echo $key; ?> class_zone">&nbsp;&nbsp;<?php echo $val . "<br>"; ?>

															<?php
															foreach ($arr_m2[$key] as $key1 => $val1) {
															?>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="PROVINCE_CODE[<?php echo $key; ?>][<?php echo $key1 ?>]" id="PROVINCE_CODE_<?php echo $key . "_" . $key1; ?>" class="css_data_item_<?php echo $key; ?> class_province" type="checkbox" onClick="Checkmain(<?php echo $key; ?>,<?php echo $key1; ?>);" value="<?php echo $key1; ?>" <?php echo $arr_chk_in[$key][$key1]; ?>>&nbsp;&nbsp;<?php echo $val1 . "<br>"; ?>
															<?php
															} ?>
														</td>
													<?php
													} ?>
												</tr>
											</table>
											<table width="70%" class="table table-bordered table-striped table-hover table-condensed" id="tb_area_out">
												<tr class="bgHead">
													<td colspan="5" align="center">พื้นที่ส่งเสริมนอกประเทศ</td>
												</tr>
												<tr valign="top">
													<?php
													foreach ($arr_m3 as $key => $val) { ?>
														<td>
															<input name="CHECK_ALL_OUT[]" id="CHECK_ALL_OUT_<?php echo $key; ?>" value="<?php echo $key; ?>" type="checkbox" <?php echo $arr_chk_area[$key] ?> onClick="CheckAll_OUT(<?php echo $key; ?>);" class="css_data_main_out_<?php echo $key; ?> class_area">&nbsp;&nbsp;<?php echo $val . "<br>"; ?>
															<?php
															foreach ($arr_m4[$key] as $key1 => $val1) {
															?>
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="COUNTRY_ID[<?php echo $key; ?>][<?php echo $key1 ?>]" id="COUNTRY_ID_<?php echo $key . "_" . $key1; ?>" class="css_data_item1_<?php echo $key; ?> class_country" type="checkbox" onClick="Checkmain_out(<?php echo $key; ?>,<?php echo $key1; ?>);" value="<?php echo $key1; ?>" <?php echo $arr_chk_out[$key][$key1]; ?>>&nbsp;&nbsp;<?php echo $val1 . "<br>"; ?>
															<?php
															} ?>
														</td>
													<?php
													} ?>
												</tr>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12" align="center">
								<?php if ($_SESSION['sys_status_edit'] == '1') { ?>
									<button type="button" class="btn btn-success" onClick="chkinput();"><i class="fa fa-check" aria-hidden="true"></i> บันทึก</button>
								<?php } ?>
								<button type="button" class="btn btn-danger" onClick="self.location.href='disp_project.php?<?php echo url2code("menu_id=" . $menu_id . "&menu_sub_id=" . $menu_sub_id); ?>';"><i class="fa fa-times" aria-hidden="true"></i> ยกเลิก</button>
							</div>

							<div class="clearfix"></div>
							<?php echo @(ceil($total_record / $page_size) > 1) ? endPaging("frm-search", $total_record) : ""; ?>
							<div class="clearfix"></div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<?php include($path . "include/footer.php"); ?>
	</div>
	<script>
		toggle_money('<?php echo $rec['BDG_TYPE_ID']; ?>');
	</script>
</body>

</html>
<!-- Modal -->
<div class="modal fade" id="myModal"></div>
<!-- /.modal -->