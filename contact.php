<?php
//แนบไฟล์//
$arr_attach_type = array("pdf","doc","docx");
$sql_attach = "SELECT
    *
FROM
	WF_FILE
WHERE
	WFR_ID IN (
		SELECT
			F_ID
		FROM
			FRM_CONTACT_FILES
		WHERE
			WFR_ID = '" . $WF['WFR_ID'] . "'
		AND WF_MAIN_ID = '17'
	)
AND WF_MAIN_ID = '18' AND WFS_FIELD_NAME = 'CONTACT_FILES'";
$query_attach = db::query($sql_attach);
$nums_attach = db::num_rows($query_attach);
if ($nums_attach > 0) {
    while ($result_attach = db::fetch_array($query_attach)) {
        $cond_attach = array(
            "WF_MAIN_ID" => '18',
            "WFS_FIELD_NAME" => 'CONTACT_FILES',
            "WFR_ID" => $result_attach['WFR_ID']
        );
        if(!in_array($result_attach['FILE_EXT'],$arr_attach_type)){
            db::db_delete("WF_FILE",$cond_attach);
        }
        else if($result['FILE_SIZE'] > 5*MB){
            db::db_delete("WF_FILE",$cond_attach);
        }
    }
}

$sql_del_attach = "SELECT
a.F_ID as f_id
FROM
FRM_CONTACT_FILES a
LEFT JOIN WF_FILE b ON b.WFR_ID = a.F_ID
WHERE
b.WFR_ID IS NULL";
$query_del_attach = db::query($sql_del_attach);
$nums_del_attach = db::num_rows($query_del_attach);
if ($nums_del_attach > 0) {
    while ($result_del = db::fetch_array($query_del_attach)) {
        $cond_del_attach = array(
            "F_ID" => $result_del_attach["f_id"]
        );
        db::db_delete("FRM_CONTACT_FILES", $cond_del_attach);
    }
}
?>