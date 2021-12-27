<?php
session_start();
include '../include/include.php';

if ($_SESSION) {
    if (isset($_POST['m_group_code']) && isset($_POST['license_id'])) {
        switch ($_POST['m_group_code']) {
            case '11': {
                    $path = '../culture_file_scan/poryor/';
                }
                break;
            case '22': {
                    $path = '../culture_file_scan/vortor/';
                }
                break;
        }
        $file_name = uploadfile($_FILES['DOC_FILE'], $path);
        //echo $_FILES['DOC_FILE']['name'][0];
        if ($file_name == 'errors') {
            echo "<script>alert('ชื่อไฟล์ไม่ถูกต้อง');</script>";
            echo "<script>window.close();</script>";
        } else {
            unset($field);
            $cond = ["LICENSE_ID" => $_POST['license_id']];
            $field_update = [
                "WH_FILE_NAME" => $file_name,
                "WH_ATTACH_FILE" => $path . $file_name
            ];
            db::db_update("M_LICENSE_WAREHOUSE", $field_update, $cond);
        }
        echo "<script>window.opener.location.reload(false);</script>";
        echo "<script>window.close();</script>";
        exit;
    } else {
        http_response_code(404);
        exit;
    }
} else {
    http_response_code(404);
    exit;
}

function uploadfile($fileupload, $url)
{
    $destination = "";

    if ($fileupload['size'][0] > 0) {

        $arr = explode(".", $fileupload['name'][0]);
        $number = count($arr);
        $allow_file = array('pdf');
        if (in_array(strtolower($arr[$number - 1]), $allow_file)) {

            $destination = $fileupload['name'][0];

            if (!@copy($fileupload['tmp_name'][0], $url . iconv("UTF-8","TIS-620",$destination))) {
                //$errors = error_get_last();
                //$destination = "COPY ERROR: ".$errors['type']."<br />\n".$errors['message'];
                $destination = "errors";
            } else {
                //donothing
            }

            @unlink($url);
        } else {
            $destination = "errors";
        }
    }
    return $destination;
}

function ctext($txt, $sendtodb = "")
{
    $strOut = strip_tags($txt);
    $strOut = htmlspecialchars($strOut, ENT_QUOTES);
    //$strOut = stripslashes($strOut);
    $strOut = str_replace("'", " ", $strOut);
    $strOut = trim($strOut);

    return $strOut;
}
