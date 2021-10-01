
<?php
define('MB', 1048576);
$path_file = "../attach/w59/";
$arr_f_type = array("pdf", "doc", "docx");
$sql = "SELECT
    *
FROM
	WF_FILE
WHERE
	WFR_ID IN (
		SELECT
			M_ID
		FROM
            M_MANUAL
		WHERE
           M_ID = '" . $WF['M_ID'] . "'
	)
AND WF_MAIN_ID = '59' AND WFS_FIELD_NAME IN ('CHAPA_FILE','WEBSITE_FILE')";
$query = db::query($sql);
$nums = db::num_rows($query);
if ($nums > 0) {
    while ($result = db::fetch_array($query)) {
        $cond = array(
            "WF_MAIN_ID" => '59',
            "WFS_FIELD_NAME" => $result['WFS_FIELD_NAME'],
            "WFR_ID" => $result['WFR_ID']
        );
        if (!in_array($result['FILE_EXT'], $arr_f_type)) {
            //unlink($path_file.$result["FILE_SAVE_NAME"]);
            @unlink($path_file.$result["FILE_SAVE_NAME"]);
            db::db_delete("WF_FILE", $cond);
        } 
        // else if ($result['FILE_SIZE'] > 5 * MB) {
        //     db::db_delete("WF_FILE", $cond);
        // }
    }
}
?>