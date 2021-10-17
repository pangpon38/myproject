<?php
ini_set('display_errors', 0);

require 'include/config.php';
require 'include/include.php';
include './include/phpqrcode/qrlib.php';

define('secret_key_qrcode', 'dfsdfwerwe4rreww');

$gencode = trim(str_replace(' ', '', $_GET['gencode']));
$size = trim(str_replace(' ', '', $_GET['size']));
$bcode = trim(str_replace(' ', '', $_GET['bcode']));
$mno = trim(str_replace(' ', '', $_GET['mno']));

if (in_array($gencode, ['file', 'binary'])) {

    if ($gencode == 'binary') {
        header('Content-Type: application/json');
    }

    $msg = "Success";

    if ($size) {
        if (!is_numeric($size)) {
            $msg = "Data invalid.";
            echo json_encode(['msg' => $msg]);
            exit;
        }

        if ($size > 40) {
            $msg = "Data invalid.";
            echo json_encode(['msg' => $msg]);
            exit;
        }
    }

    if (strlen($bcode) != 32) {
        $msg = "Data invalid.";
        echo json_encode(['msg' => $msg]);
        exit;
    }

    if (strlen($mno) != 32) {
        $msg = "Data invalid.";
        echo json_encode(['msg' => $msg]);
        exit;
    }

    $db_cloud = new PHPDB($EWT_DB_TYPE, $WF_ROOT_HOST, $WF_ROOT_USER, $WF_ROOT_PASSWORD, $WF_DB_NAME);
    $connectdb_cloud = $db_cloud->CONNECT_SERVER();
    if (!$connectdb_cloud) {echo "connection error";exit;}

    $sql = "SELECT CHAPA_CLOUD_ID,TAXNO FROM M_BASIC WHERE CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(CHAPA_CLOUD_ID,'" . secret_key_qrcode . "')), 2)='" . $bcode . "'";
    $exc = $db_cloud->query($sql);
    $row = $db_cloud->db_fetch_array($exc);
    if (empty($row['CHAPA_CLOUD_ID'])) {
        $msg = "Data Invalid.";
        echo json_encode(['msg' => $msg]);
        exit;
    }

    if (empty($row['TAXNO'])) {
        $msg = "Data Invalid.";
        echo json_encode(['msg' => $msg]);
        exit;
    }

    $taxno = $row['TAXNO'];

    $db_cloud->db_close();

    $EWT_DB_NAME = "baac_chapa_" . $row['CHAPA_CLOUD_ID'];

    $db = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
    $connectdb = $db->CONNECT_SERVER();
    if (!$connectdb) {echo "connection error";exit;}

    $sql = "SELECT
                member_no
            FROM
                M_MEMBER
            WHERE
                CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(member_no,'" . secret_key_qrcode . "')), 2)='" . $mno . "'";
    $aaa = $db->query($sql);
    $row = $db->db_fetch_array($aaa);
    if (empty($row['member_no'])) {
        $msg = "Data invalid.";
        echo json_encode(['msg' => $msg]);
        exit;
    }

    $codeContents = "|" . $suffix_code . "\n" . $taxno . "\n" . $row['member_no'] . "\n0";

    $tempDir = "./qrcode_file/";

    if (empty($size)) {
        $size = 5;
    }

    $fileName = 'qr_file_' . md5($codeContents) . '_' . $size . '.png';

    $pngAbsoluteFilePath = $tempDir . $fileName;
    $urlRelativeFilePath = $tempDir . $fileName;

    if (!file_exists($pngAbsoluteFilePath)) {
        QRcode::png($codeContents, $pngAbsoluteFilePath, QR_ECLEVEL_H, $size);
    }

    switch ($gencode) {
        case 'file':

            header('Content-type: image/png');
            $p = file_get_contents($urlRelativeFilePath);
            echo $p;

            break;

        case 'binary':

            $data = fopen($pngAbsoluteFilePath, 'rb');
            $size = filesize($pngAbsoluteFilePath);
            $contents = fread($data, $size);
            fclose($data);

            $encoded = base64_encode($contents);

            echo json_encode(['msg' => $msg, 'image' => $encoded]);

            break;
    }

    $db->db_close();

} else {
    $msg = "data invalid.";

    echo json_encode(['msg' => $msg]);
}