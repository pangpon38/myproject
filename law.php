
<?php
define('MB', 1048576);
$arr_f_type = array("pdf", "doc", "docx");
$sql = "SELECT
    *
FROM
	WF_FILE
WHERE
	WFR_ID IN (
		SELECT
			LAWS_ID
		FROM
			M_LAWS
		WHERE
           LAWS_ID = '" . $WF['LAWS_ID'] . "'
	)
AND WF_MAIN_ID = '5' AND WFS_FIELD_NAME = 'LAWS_FILES'";
$query = db::query($sql);
$nums = db::num_rows($query);
if ($nums > 0) {
    while ($result = db::fetch_array($query)) {
        $cond = array(
            "WF_MAIN_ID" => '5',
            "WFS_FIELD_NAME" => 'LAWS_FILES',
            "WFR_ID" => $result['WFR_ID']
        );
        if (!in_array($result['FILE_EXT'], $arr_f_type)) {
            db::db_delete("WF_FILE", $cond);
        } else if ($result['FILE_SIZE'] > 5 * MB) {
            db::db_delete("WF_FILE", $cond);
        }
    }
}
?>