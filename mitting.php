
<?php
//แนบรูป//
define('MB', 1048576);
$arr_f_type = array("png", "jpg", "jpeg", "gif");
$sql = "SELECT
    *
FROM
	WF_FILE
WHERE
	WFR_ID IN (
		SELECT
			F_ID
		FROM
			FRM_ATTACK_FILE
		WHERE
			WFR_ID = '" . $WF['MITTING_ID'] . "'
		AND WF_MAIN_ID = '7'
	)
AND WF_MAIN_ID = '4' AND WFS_FIELD_NAME = 'NEWS_PIC'";
$query = db::query($sql);
$nums = db::num_rows($query);
if ($nums > 0) {
    while ($result = db::fetch_array($query)) {
        $cond = array(
            "WF_MAIN_ID" => '4',
            "WFS_FIELD_NAME" => 'NEWS_PIC',
            "WFR_ID" => $result['WFR_ID']
        );
        db::db_update("FRM_ATTACK_FILE",$field_attack = ["WFS_FIELD_NAME" => 'NEWS_PIC'],$where = ["F_ID" => $result['WFR_ID']]);
        if (!in_array($result['FILE_EXT'], $arr_f_type)) {
            db::db_delete("WF_FILE", $cond);
        } else if ($result['FILE_SIZE'] > 5 * MB) {
            db::db_delete("WF_FILE", $cond);
        }
    }
}

$sql_del = "SELECT
a.F_ID as f_id
FROM
FRM_ATTACK_FILE a
LEFT JOIN WF_FILE b ON b.WFR_ID = a.F_ID AND b.WFS_FIELD_NAME = a.WFS_FIELD_NAME
WHERE
b.WFR_ID IS NULL";
$query_del = db::query($sql_del);
$nums_del = db::num_rows($query_del);
if ($nums_del > 0) {
    while ($result_del = db::fetch_array($query_del)) {
        $cond_del = array(
            "F_ID" => $result_del["f_id"]
        );
        db::db_delete("FRM_ATTACK_FILE", $cond_del);
    }
}

//แนบไฟล์//
$arr_attach_type = array("pdf", "doc", "docx");
$sql_attach = "SELECT
    *
FROM
	WF_FILE
WHERE
	WFR_ID IN (
		SELECT
			F_ID
		FROM
			FRM_NEWS_FILES
		WHERE
			WFR_ID = '" . $WF['MITTING_ID'] . "'
		AND WF_MAIN_ID = '7'
	)
AND WF_MAIN_ID = '15' AND WFS_FIELD_NAME = 'NEWS_FILES'";
$query_attach = db::query($sql_attach);
$nums_attach = db::num_rows($query_attach);
if ($nums_attach > 0) {
    while ($result_attach = db::fetch_array($query_attach)) {
        $cond_attach = array(
            "WF_MAIN_ID" => '15',
            "WFS_FIELD_NAME" => 'NEWS_FILES',
            "WFR_ID" => $result_attach['WFR_ID']
        );
        db::db_update("FRM_NEWS_FILES",$field_file = ["WFS_FIELD_NAME" => 'NEWS_FILES'],$where_file = ["F_ID" => $result_attach['WFR_ID']]);
        if (!in_array($result_attach['FILE_EXT'], $arr_attach_type)) {
            db::db_delete("WF_FILE", $cond_attach);
        } else if ($result_attach['FILE_SIZE'] > 5 * MB) {
            db::db_delete("WF_FILE", $cond_attach);
        }
    }
}

$sql_del_attach = "SELECT
a.F_ID as f_id
FROM
FRM_NEWS_FILES a
LEFT JOIN WF_FILE b ON b.WFR_ID = a.F_ID AND b.WFS_FIELD_NAME = a.WFS_FIELD_NAME
WHERE
b.WFR_ID IS NULL";
$query_del_attach = db::query($sql_del_attach);
$nums_del_attach = db::num_rows($query_del_attach);
if ($nums_del_attach > 0) {
    while ($result_del_attach = db::fetch_array($query_del_attach)) {
        $cond_del_attach = array(
            "F_ID" => $result_del_attach["f_id"]
        );
        db::db_delete("FRM_NEWS_FILES", $cond_del_attach);
    }
}
?>