<?php
ini_set('display_errors', 0);
require 'include/config.php';
require 'include/include.php';
require './baac_api.class.php';
require './baac_config_api.php';
header('Content-Type: application/json');

define('secret_key', 'acccvvvvbbbnnnmmmz');

$baacChapa = new baacChapa($request);

function get_message_error($text = "ข้อมูลผิดพลาด")
{
    return [
        'success' => 'Y',
        'error_log' => 'Success',
        'status_insert' => 'N',
        'msg' => $text,
    ];
}

function chgDate($date)
{
    if ($date != '') {
        list($y, $m, $d) = explode("-", $date);
        return $d . "/" . $m . "/" . ($y + 543);
    }
}

$request = json_decode(file_get_contents("php://input"), true);

$workflow_proc_arr = [
    'getLogin',
    'insAssociation',
    'forGetpass',
    'getProvince',
    'getAmphur',
    'getTambon',
    'getZipcode',
    'getDep',
    'changePass',
    'getAddressSendDoc',
];

if (empty($_GET['proc'])) {
    http_response_code(404);
    exit;
}

if ($_GET['proc'] != 'getLogin') {

    if (empty($request)) {
        http_response_code(404);
        exit;
    }

    if (empty($request['token'])) {
        http_response_code(404);
        exit;
    }
}

if (in_array($_GET['proc'], $workflow_proc_arr)) {
    $db = new PHPDB($EWT_DB_TYPE, $WF_ROOT_HOST, $WF_ROOT_USER, $WF_ROOT_PASSWORD, $WF_DB_NAME);
    $connectdb_cloud = $db->CONNECT_SERVER();
    if (!$connectdb_cloud) {
        http_response_code(404);
        exit;
    }
} else {

    if (empty(trim($request['id_db']))) {
        http_response_code(404);
        exit;
    }

    if (strlen($request['id_db']) != 32) {
        http_response_code(404);
        exit;
    }

    //==============
    $db = new PHPDB($EWT_DB_TYPE, $WF_ROOT_HOST, $WF_ROOT_USER, $WF_ROOT_PASSWORD, $WF_DB_NAME);
    $connectdb_cloud = $db->CONNECT_SERVER();
    if (!$connectdb_cloud) {
        http_response_code(404);
        exit;
    }

    $sql = "SELECT
                CHAPA_CLOUD_ID
            FROM
                M_BASIC
            WHERE
                CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(CHAPA_CLOUD_ID,'" . secret_key . "')), 2) = '" . $request['id_db'] . "' ";
    $exc = $db->query($sql);
    $rec = $db->db_fetch_array($exc);
    if (empty($rec['CHAPA_CLOUD_ID'])) {
        http_response_code(404);
        exit;
    }

    if ($request['token'] && $request['member_no'] && $request['id_db']) {

        $token_data = $baacChapa->checkAuth($request);

        $sql = "SELECT
                FRM_USER_DEP.F_ID
            FROM
                FRM_USER_DEP FRM_USER_DEP INNER JOIN
                M_BASIC M_BASIC ON M_BASIC.BASIC_ID=FRM_USER_DEP.DEP_ID
            WHERE
                FRM_USER_DEP.WFR_ID = '" . $token_data['data']['uid'] . "' AND
                CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(FRM_USER_DEP.MEMBER_NO,'" . secret_key . "')), 2) = '" . $request['member_no'] . "' AND
                CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_BASIC.CHAPA_CLOUD_ID ,'" . secret_key . "')), 2) = '" . $request['id_db'] . "' ";
        $exc = $db->query($sql);
        $row = $db->db_fetch_array($exc);

        if (empty($row['F_ID'])) {
            $data = get_message_error('ไม่มีสิทธิ์เรียกดูข้อมูล');
            echo json_encode($data);
            exit;
        }
    }

    $EWT_DB_NAME = "baac_chapa_" . $rec['CHAPA_CLOUD_ID'];

    $db->db_close();

    //===========
    $db = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
    $connectdb = $db->CONNECT_SERVER();
    if (!$connectdb) {
        http_response_code(404);
        exit;
    }
}

switch ($_GET['proc']) {
    case 'getLogin':

        if (empty($request['username'])) {
            $data = [
                'success' => 'Y',
                'error_log' => 'Success',
                'status_login' => 'N',
                'msg' => 'กรุณากรอกข้อมูล USERNAME และ PASSWORD',
            ];
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['username'])) {
            $data = [
                'success' => 'Y',
                'error_log' => 'Success',
                'status_login' => 'N',
                'msg' => 'กรุณากรอกข้อมูล USERNAME และ PASSWORD ให้ถูกต้อง',
            ];
            echo json_encode($data);
            exit;
        }

        if (empty($request['password'])) {
            $data = [
                'success' => 'Y',
                'error_log' => 'Success',
                'status_login' => 'N',
                'msg' => 'กรุณากรอกข้อมูล USERNAME และ PASSWORD',
            ];
            echo json_encode($data);
            exit;
        }

        $password = str_replace(' ', '', $request['password']);

        $sqlChk_c = "SELECT
                            COUNT(USER_ID) AS NUM
                        FROM
                            M_USER U
                        WHERE
                            U.ID_CARD_NO = '" . htmlspecialchars($request['username']) . "' AND
                            U.PASSWORD = '" . htmlspecialchars($password) . "'";
        $q_c = $db->query($sqlChk_c);
        $numrow = $db->db_fetch_array($q_c);

        if ($numrow['NUM'] > 0) {
            $sqlChk = "SELECT
                            U.*,
                            P.PREFIX_NAME
                        FROM
                            M_USER U LEFT JOIN
                            M_PREFIX P ON U.PREFIX_ID = P.PREFIX_ID
                        WHERE
                            U.ID_CARD_NO = '" . htmlspecialchars($request['username']) . "' AND
                            U.PASSWORD = '" . htmlspecialchars($password) . "'";
            $queryChk = $db->query($sqlChk);
            $row = $db->db_fetch_array($queryChk);

            $data_login = [
                'user_id' => $row['USER_ID'],
                'id_card_no' => $row['ID_CARD_NO'],
                'name' => $row["PREFIX_NAME"] . $row["FNAME"] . " " . $row["LNAME"],
                'prefixname' => $row["PREFIX_ID"],
                'firstname' => $row['FNAME'],
                'lastname' => $row['LNAME'],
            ];

            $i = 1;
            $sql_dep = "SELECT
                            mb.CHAPA_CLOUD_ID,
                            mb.DEP_NAME,
                            ud.MEMBER_NO
                        FROM
                            FRM_USER_DEP ud LEFT JOIN
                            M_BASIC mb ON ud.DEP_ID = mb.BASIC_ID
                        WHERE
                            ud.WFR_ID = '" . $row['USER_ID'] . "' ";
            $q_dep = $db->query($sql_dep);
            $data_dep = array();
            while ($row_dep = $db->db_fetch_array($q_dep)) {

                $EWT_DB_NAME = "baac_chapa_" . $row_dep['CHAPA_CLOUD_ID'];
                $db_dep = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
                $connectdb_dep = $db_dep->CONNECT_SERVER();
                if (!$connectdb_dep) {
                    http_response_code(404);
                    exit;
                }

                $sql_m = "SELECT
                                M_MEMBER.member_no,
                                M_TYPE.m_cate_name,
                                PREFIX.prefix_name+' '+M_MEMBER.fname+' '+M_MEMBER.lname AS name,
                                M_STATUS.m_status_name,
                                M_MEMBER.EFF_DATE,
                                M_MEMBER.PAY_CODE,
                                M_MEMBER.member_status
                            FROM
                                M_MEMBER
                                LEFT JOIN M_STATUS ON M_STATUS.m_status_id = M_MEMBER.member_status
                                LEFT JOIN M_TYPE ON M_TYPE.m_cate_id = M_MEMBER.m_cate_id
                                LEFT JOIN PREFIX ON M_MEMBER.prefix_id = PREFIX.prefix_id
                            WHERE
                                M_MEMBER.member_no = '" . $row_dep['MEMBER_NO'] . "' ";
                $q_m = $db_dep->query($sql_m);
                $rec_m = $db_dep->db_fetch_array($q_m);

                if ($rec_m['member_status'] != 4 && $rec_m['member_status'] != 0 && $rec_m['member_status'] != 5) {
                    $data = [
                        'member_no' => $rec_m['member_no'],
                        'm_status_id' => $rec_m['member_status'],
                        'm_status_name' => $rec_m['m_status_name'],
                        'm_cate_name' => $rec_m['m_cate_name'],
                        'chapa_cloud_id' => $row_dep['CHAPA_CLOUD_ID'],
                        'dep_name' => $row_dep['DEP_NAME'],
                    ];
                    array_push($data_dep, $data);
                }

                $i++;

            }

            $data_login = [
                'user_id' => $row['USER_ID'],
                'id_card_no' => $row['ID_CARD_NO'],
                'name' => $row["PREFIX_NAME"] . $row["FNAME"] . " " . $row["LNAME"],
                'prefixname' => $row["PREFIX_NAME"],
                'firstname' => $row['FNAME'],
                'lastname' => $row['LNAME'],
                'in_dep' => $data_dep,
            ];

            $payload = [
                'iss' => 'BAAC_CHAPA',
                'aud' => 'everyone',
                'name' => 'Bizpotential',
                'uid' => $row['USER_ID'],
            ];

            $token = $baacChapa->generateToken($payload);

            if (empty($token)) {
                http_response_code(404);
                exit;
            }

            $data = [
                'success' => 'Y',
                'error_log' => 'Success',
                'status_login' => 'Y',
                'msg' => 'สำเร็จ',
                'token' => $token,
                'detail_member' => $data_login,
            ];
            echo json_encode($data);

        } else {

            $data = [
                'success' => 'Y',
                'error_log' => 'Success',
                'status_login' => 'N',
                'msg' => 'กรุณากรอกข้อมูล USERNAME และ PASSWORD ให้ถูกต้อง',
            ];
            echo json_encode($data);
        }

        break;

    case 'getMember':

        $token_data = $baacChapa->checkAuth($request);

        if (empty($token_data['data']['uid'])) {
            http_response_code(404);
            exit;
        }

        if (empty($request['member_no'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['member_no']) != 32) {
            http_response_code(404);
            exit;
        }

        // check_owner_data($token_data['data']['uid'], $request['member_no'], $request['id_db']);

        $sql = "SELECT
                    TOP 1
                    M_MEMBER.member_no,
                    M_TYPE.m_cate_name,
                    PREFIX.prefix_name+' '+M_MEMBER.fname+' '+M_MEMBER.lname AS name,
                    M_STATUS.m_status_name,
                    M_MEMBER.EFF_DATE,
                    M_MEMBER.PAY_CODE
                FROM
                    M_MEMBER
                    LEFT JOIN M_STATUS ON M_STATUS.m_status_id = M_MEMBER.member_status
                    LEFT JOIN M_TYPE ON M_TYPE.m_cate_id = M_MEMBER.m_cate_id
                    LEFT JOIN PREFIX ON M_MEMBER.prefix_id = PREFIX.prefix_id
                WHERE
                    CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_MEMBER.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "'";

        $exc = $db->query($sql);
        $row = $db->db_fetch_array($exc);
        if ($row['PAY_CODE'] == 1) {
            $pay_name = "รับเต็มจำนวนเพียงผู้เดียว";
        } else if ($row['PAY_CODE'] == 2) {
            $pay_name = "รับหลายคนส่วนแบ่งเท่าๆกัน";
        } else if ($row['PAY_CODE'] == 3) {
            $pay_name = "รับตามลำดับแต่เพียงผู้เดียว";
        } else if ($row['PAY_CODE'] == 4) {
            $pay_name = "รับหลายคนส่วนแบ่งไม่เท่ากัน";
        }

        $member = [
            'member_no' => $row['member_no'],
            'm_cate_name' => $row['m_cate_name'],
            'name' => $row['name'],
            'm_status_name' => $row['m_status_name'],
            'pay_code' => $pay_name,
            'eff_date' => chgDate(date_format($row['EFF_DATE'], 'Y-m-d')),
        ];

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'datachapa' => $member,
        ];
        echo json_encode($data);

        break;
    case 'getMemberDetail':

        $baacChapa->checkAuth($request);

        if (empty($request['member_no'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['member_no']) != 32) {
            http_response_code(404);
            exit;
        }

        $sql_mem = "SELECT
                        M_MEMBER.member_no,
                        M_MEMBER.member_id,
                        PREFIX.prefix_name + ' ' + M_MEMBER.fname + ' ' + M_MEMBER.lname AS name,
                        M_MEMBER.gender,
                        M_MEMBER.birthdate,
                        M_MEMBER.home_no,
                        M_MEMBER.moo_no,
                        M_MEMBER.soi_name,
                        M_MEMBER.road_name,
                        M_MEMBER.prov_id,
                        M_MEMBER.amp_id,
                        M_MEMBER.tam_id,
                        province.province_name,
                        amphur.amphur_name,
                        tambon.tambon_name,
                        M_MEMBER.postcode,
                        M_MEMBER.email,
                        M_MEMBER.marry_status,
                        M_MEMBER.PAY_CODE,
                        M_MEMBER.EFF_DATE,
                        M_STATUS.m_status_name,
                        M_MEMBER.deposit_money,
                        M_MEMBER.BANK_NO
                    FROM
                        M_MEMBER
                        LEFT JOIN PREFIX ON M_MEMBER.prefix_id = PREFIX.prefix_id
                        LEFT JOIN M_STATUS ON M_STATUS.m_status_id = M_MEMBER.member_status
                        LEFT JOIN province ON M_MEMBER.prov_id = province.province_code
                        LEFT JOIN amphur ON M_MEMBER.amp_id = amphur.amphur_code AND province.province_code = amphur.province_code
                        LEFT JOIN tambon ON M_MEMBER.tam_id = tambon.tambon_code AND amphur.amphur_code = tambon.amphur_code AND province.province_code = tambon.province_code
                    WHERE
                        CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_MEMBER.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "'";
        $mem_q = $db->query($sql_mem);
        $row_mem = $db->db_fetch_array($mem_q);

        if ($row_mem['gender'] == 1) {
            $gender = "ชาย";
        } else if ($row_mem['gender'] == 2) {
            $gender = "หญิง";
        }

        if ($row_mem['PAY_CODE'] == 1) {
            $pay_name = "รับเต็มจำนวนเพียงผู้เดียว";
        } else if ($row_mem['PAY_CODE'] == 2) {
            $pay_name = "รับหลายคนส่วนแบ่งเท่าๆกัน";
        } else if ($row_mem['PAY_CODE'] == 3) {
            $pay_name = "รับตามลำดับแต่เพียงผู้เดียว";
        } else if ($row_mem['PAY_CODE'] == 4) {
            $pay_name = "รับหลายคนส่วนแบ่งไม่เท่ากัน";
        }

        switch ($row_mem['marry_status']) {
            case '1':
                $row_mem['marry_status'] = "โสด";
                break;
            case '2':
                $row_mem['marry_status'] = "สมรส";
                break;
            case '3':
                $row_mem['marry_status'] = "หย่าร้าง";
                break;
            case '4':
                $row_mem['marry_status'] = "หม้าย";
                break;

            default:
                $row_mem['marry_status'] = "-";
                break;
        }

        $sql_have_money = "SELECT
                                M_BENEFIT.benefit_no,
                                (SELECT prefix_name FROM prefix WHERE prefix.prefix_id = M_BENEFIT.prefix_id) AS prefix_name,
                                M_BENEFIT.fname,
                                M_BENEFIT.lname
                            FROM
                                M_BENEFIT
                            WHERE
                                M_BENEFIT.member_id = '" . $row_mem['member_id'] . "'
                            ORDER BY
                                M_BENEFIT.benefit_no ASC";
        $have_money_q = $db->query($sql_have_money);
        $i = 1;
        $people_income = array();
        while ($row_have_money = $db->db_fetch_array($have_money_q)) {
            $data = [
                'benefit_no' => $row_have_money['benefit_no'],
                'fname' => $row_have_money['prefix_name'] . " " . $row_have_money['fname'] . " " . $row_have_money['lname'],
            ];
            array_push($people_income, $data);

            $i++;
        }

        $sql_count_mem = "SELECT
                                COUNT(member_id) AS num_mem
                            FROM
                                M_MEMBER
                            WHERE
                                member_status = 1";
        $count_mem_q = $db->query($sql_count_mem);
        $row_count_mem = $db->db_fetch_array($count_mem_q);

        $sql_arrear = "SELECT sys_advance_arrear_person FROM SYS_ADVANCE_ARREAR WHERE status_use = 1";
        $query_arrear = $db->query($sql_arrear);
        $recFee_arrear = $db->db_fetch_array($query_arrear);
        $pay_amount = $recFee_arrear['sys_advance_arrear_person'];

        $sql_pay = "SELECT person_amount FROM SYS_ADVANCE WHERE status_use = 1";
        $query_pay = $db->query($sql_pay);
        $recFee_pay = $db->db_fetch_array($query_pay);
        $pay = $recFee_pay['person_amount'];

        $pay_all = $pay_amount * $pay;

        if ($row_mem['deposit_money'] >= $pay_all) {
            $pay_total = 0;
        } else {
            $pay_total = $pay_all - $row_mem['deposit_money'];
        }

        $member_detail = [
            'member_id' => $row_mem['member_id'],
            'member_no' => $row_mem['member_no'],
            'name' => $row_mem['name'],
            'gender' => $gender,
            'm_status_name' => $row_mem['m_status_name'],
            'birthdate' => chgDate(date_format($row_mem['birthdate'], 'Y-m-d')),
            'home_no' => $row_mem['home_no'],
            'moo_no' => $row_mem['moo_no'],
            'soi_name' => $row_mem['soi_name'],
            'road_name' => $row_mem['road_name'],
            'province_id' => $row_mem['prov_id'],
            'amphur_id' => $row_mem['amp_id'],
            'tambon_id' => $row_mem['tam_id'],
            'province_name' => $row_mem['province_name'],
            'amphur_name' => $row_mem['amphur_name'],
            'tambon_name' => $row_mem['tambon_name'],
            'postcode' => $row_mem['postcode'],
            'email' => $row_mem['email'],
            'bank_no' => $row_mem['BANK_NO'],
            'marry_status' => $row_mem['marry_status'],
            'paytotal' => number_format($pay_total, 2),
            'pay_code' => $pay_name,
            'deposit_money' => number_format($row_mem['deposit_money'], 2),
            'eff_date' => chgDate(date_format($row_mem['EFF_DATE'], 'Y-m-d')),
            'people_income' => $people_income,
            'count_member' => number_format($row_count_mem['num_mem']),
        ];

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'datachapa' => $member_detail,
        ];
        echo json_encode($data);

        break;
    case 'getCalMoney':

        $baacChapa->checkAuth($request);

        $sql_count_mem = "SELECT
                              COUNT(member_id) AS num_mem
                          FROM
                              M_MEMBER
                          WHERE
                              member_status = 1";
        $count_mem_q = $db->query($sql_count_mem);
        $row_count_mem = $db->db_fetch_array($count_mem_q);

        $sql_advance_fee = "SELECT
                                advance_fee
                            FROM
                                SYS_ADVANCE_FEE
                            WHERE
                                status_use = 1";
        $advance_fee_q = $db->query($sql_advance_fee);
        $row_advance_fee = $db->db_fetch_array($advance_fee_q);

        $sql_person_amount = "SELECT
                                  person_amount
                              FROM
                                  SYS_ADVANCE
                              WHERE
                                  status_use = 1";
        $person_amount_q = $db->query($sql_person_amount);
        $row_person_amount = $db->db_fetch_array($person_amount_q);

        $sum_money = $row_count_mem['num_mem'] * $row_person_amount['person_amount'];
        $fee_money = ($sum_money * $row_advance_fee['advance_fee']) / 100;

        $member_detail = [
            'count_member' => $row_count_mem['num_mem'],
            'person_amount' => number_format($row_person_amount['person_amount'], 2),
            'sum_money' => number_format($sum_money, 2),
            'advance_fee' => $row_advance_fee['advance_fee'] . "%",
            'cost_deduction' => number_format($fee_money, 2),
            'balance_money' => number_format($sum_money - $fee_money, 2),
        ];

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'datachapa' => $member_detail,
        ];

        echo json_encode($data);

        break;
    case 'getAddressSendDoc':

        $token_data = $baacChapa->checkAuth($request);

        if (empty($request['user_id'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['user_id']) != 32) {
            http_response_code(404);
            exit;
        }

        $sql_dep = "SELECT
                        mb.CHAPA_CLOUD_ID,
                        mb.DEP_NAME,
                        ud.MEMBER_NO
                    FROM
                        FRM_USER_DEP ud LEFT JOIN
                        M_BASIC mb ON ud.DEP_ID = mb.BASIC_ID
                    WHERE
                        ud.WFR_ID='" . $token_data['data']['uid'] . "' AND
                        CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(ud.WFR_ID,'" . secret_key . "')), 2)='" . $request['user_id'] . "'";
        $q_dep = $db->query($sql_dep);
        $data_dep = array();
        while ($row_dep = $db->db_fetch_array($q_dep)) {

            $EWT_DB_NAME = "baac_chapa_" . $row_dep['CHAPA_CLOUD_ID'];
            $db_dep = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
            $connectdb_dep = $db_dep->CONNECT_SERVER();
            if (!$connectdb_dep) {
                http_response_code(404);
                exit;
            }

            $sql_address = "SELECT
                                member_id,
                                member_no,
                                home_no2 as home_no,
                                moo_no2 as moo_no,
                                soi_name2 as soi_name,
                                road_name2 as road_name,
                                prov_id2 as prov_id,
                                amp_id2 as amp_id,
                                tam_id2 as tam_id,
                                postcode2 as postcode,
                                province.province_name,
                                amphur.amphur_name,
                                tambon.tambon_name
                            FROM
                                M_MEMBER
                                LEFT JOIN province ON M_MEMBER.prov_id2 = province.province_code
                                LEFT JOIN amphur ON M_MEMBER.amp_id2 = amphur.amphur_code AND province.province_code = amphur.province_code
                                LEFT JOIN tambon ON M_MEMBER.tam_id2 = tambon.tambon_code AND amphur.amphur_code = tambon.amphur_code AND province.province_code = tambon.province_code
                            WHERE
                                M_MEMBER.member_no = '" . $row_dep['MEMBER_NO'] . "'";
            $q_address = $db_dep->query($sql_address);
            $row_address = $db_dep->db_fetch_array($q_address);

            $data = [
                'chapa_cloud_id' => $row_dep['CHAPA_CLOUD_ID'],
                'member_no' => $row_address['member_no'],
                'home_no' => $row_address['home_no'],
                'moo_no' => $row_address['moo_no'],
                'soi_name' => $row_address['soi_name'],
                'road_name' => $row_address['road_name'],
                'province_name' => $row_address['province_name'],
                'prov_code' => $row_address['prov_id'],
                'amphur_name' => $row_address['amphur_name'],
                'amp_code' => $row_address['amp_id'],
                'tambon_name' => $row_address['tambon_name'],
                'tam_code' => $row_address['tam_id'],
                'postcode' => $row_address['postcode'],
                'address' => "บ้านเลขที่ " . $row_address['home_no'] . " หมู่ " . $row_address['moo_no'] . " ซอย " . $row_address['soi_name'] . " ถนน " . $row_address['road_name'] . " ตำบล/แขวง " . $row_address['tambon_name'] . " อำเภอ/เขต " . $row_address['amphur_name'] . " จังหวัด " . $row_address['province_name'] . " " . $row_address['postcode'],
            ];

            array_push($data_dep, $data);
        }

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'datachapa' => $data_dep,
        ];
        echo json_encode($data);

        break;
    case 'changeAddressSendDoc':

        $baacChapa->checkAuth($request);

        if (empty($request['member_no'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['member_no']) != 32) {
            http_response_code(404);
            exit;
        }

        if (empty($request['home_no'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (empty($request['prov_code'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['prov_code'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (strlen($request['prov_code']) != 2) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (empty($request['amp_code'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['amp_code'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (strlen($request['amp_code']) != 2) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (empty($request['tam_code'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['tam_code'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (strlen($request['tam_code']) != 2) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (empty($request['postcode'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['postcode'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (strlen($request['postcode']) != 5) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        $sql_address = "SELECT
                            member_id,
                            member_no,
                            home_no,
                            moo_no,
                            soi_name,
                            road_name,
                            prov_id,
                            amp_id,
                            tam_id,
                            fname,
                            lname,
                            (CASE WHEN gender =  1 THEN 'ชาย' WHEN gender = 2 THEN 'หญิง' END) AS gen,
                            (SELECT prefix_name FROM PREFIX WHERE prefix_id = M_MEMBER.prefix_id) AS prefix,
                            province.province_name,
                            amphur.amphur_name,
                            tambon.tambon_name,
                            M_MEMBER.postcode
                        FROM
                            M_MEMBER
                            LEFT JOIN province ON M_MEMBER.prov_id = province.province_code
                            LEFT JOIN amphur ON M_MEMBER.amp_id = amphur.amphur_code AND province.province_code = amphur.province_code
                            LEFT JOIN tambon ON M_MEMBER.tam_id = tambon.tambon_code AND amphur.amphur_code = tambon.amphur_code AND province.province_code = tambon.province_code
                        WHERE
                            CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_MEMBER.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "'";
        $q_address = $db->query($sql_address);
        $row_address = $db->db_fetch_array($q_address);

        if (empty($row_address['member_id'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        $datetime = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        /////////////////////////////////////////////////////////////

        $memid = $row_address['member_id'];
        $member_name = $row_address['prefix'] . " " . $row_address['fname'] . " " . $row_address['lname'];
        /////////////////////////////////////////////////////////////

        $sql_Ins = "INSERT INTO M_CHG_PROFILE (
                                    member_id,
                                    chg_date,
                                    approve_status,
                                    create_by,
                                    create_datetime,
                                    chg_code
                                ) VALUES (
                                    '" . $memid . "',
                                    '" . $date . "',
                                    '0',
                                    '" . $member_name . "',
                                    '" . $datetime . "' ,
                                    1)";
        $q_Ins = $db->query($sql_Ins);

        $sqlMax = "SELECT MAX(chg_id) AS MAX_ID FROM M_CHG_PROFILE ";
        $qMax = $db->query($sqlMax);
        $rowMax = $db->db_fetch_array($qMax);
        $maxId = $rowMax['MAX_ID'];

        $sql_add = "INSERT INTO M_CHG_ADDS (
                                    chg_id,type_chg_id,home_no2_old,moo_no2_old,soi_name2_old,road_name2_old,prov_id2_old,amp_id2_old,tam_id2_old,postcode2_old,
                                    home_no2_new,moo_no2_new,soi_name2_new,road_name2_new,prov_id2_new,amp_id2_new,tam_id2_new,postcode2_new,approve_status,create_by,
                                    create_datetime,home_no_old,moo_no_old,soi_name_old,road_name_old,prov_id_old,amp_id_old,tam_id_old,postcode_old,home_no_new,
                                    moo_no_new,soi_name_new,road_name_new,prov_id_new,amp_id_new,tam_id_new,postcode_new
                                ) VALUES (
                                    '" . $maxId . "',2,'" . $row_address['home_no'] . "','" . $row_address['moo_no'] . "','" . $row_address['soi_name'] . "','" . $row_address['road_name'] . "','" . $row_address['prov_id'] . "','" . $row_address['amp_id'] . "','" . $row_address['tam_id'] . "','" . $row_address['postcode'] . "',
                                    '" . htmlspecialchars($request['home_no']) . "','" . htmlspecialchars($request['moo_no']) . "','" . htmlspecialchars($request['soi_name']) . "','" . htmlspecialchars($request['road_name']) . "','" . $request['prov_code'] . "','" . $request['amp_code'] . "','" . $request['tam_code'] . "','" . $request['postcode'] . "',0,'" . $member_name . "',
                                    '" . $datetime . "','" . $row_address['home_no'] . "','" . $row_address['moo_no'] . "','" . $row_address['soi_name'] . "','" . $row_address['road_name'] . "','" . $row_address['prov_id'] . "','" . $row_address['amp_id'] . "','" . $row_address['tam_id'] . "','" . $row_address['postcode'] . "','" . $row_address['home_no'] . "',
                                    '" . $row_address['moo_no'] . "','" . $row_address['soi_name'] . "','" . $row_address['road_name'] . "','" . $row_address['prov_id'] . "','" . $row_address['amp_id'] . "','" . $row_address['tam_id'] . "','" . $row_address['postcode'] . "'
                                  )";
        $q_add = $db->query($sql_add);

        $status = 'Y';
        $msg = 'สำเร็จ';

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'datachapa' => [
                'status_insert' => $status,
                'msg' => $msg],
        ];

        echo json_encode($data);

        break;
    case 'getReportPayment':

        $baacChapa->checkAuth($request);

        if (empty($request['member_no'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['member_no']) != 32) {
            http_response_code(404);
            exit;
        }

        if (empty($request['year'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['year'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (strlen($request['year']) != 4) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        $year = $request['year'] - 543;
        $sql_chk_memid = "SELECT
                                member_id
                            FROM
                                M_MEMBER
                            WHERE
                                CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_MEMBER.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "'";
        $q_chk_memid = $db->query($sql_chk_memid);
        $row_chk_memid = $db->db_fetch_array($q_chk_memid);

        if (empty($row_chk_memid['member_id'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        $cond = " AND F_RECEIPT.date_receipt LIKE '" . $year . "%' ";

        $sql_payment = "SELECT
                            M_MEMBER.member_no,
                            F_RECEIPT.rec_id,
                            F_RECEIPT.rec_code,
                            F_RECEIPT.date_receipt,
                            F_RECEIPT.sum_total,
                            F_RECEIPT.call_type,
                            F_RECEIPT.status_use
                        FROM
                            F_RECEIPT
                            LEFT JOIN M_MEMBER on M_MEMBER.member_id = F_RECEIPT.member_id
                        WHERE
                            M_MEMBER.member_id = '" . $row_chk_memid['member_id'] . "' " . $cond . "
                        ORDER BY
                            F_RECEIPT.date_receipt DESC";
        $q_payment = $db->query($sql_payment);
        $i = 1;
        $report_payment = array();
        while ($row_payment = $db->db_fetch_array($q_payment)) {

            if ($row_payment['call_type'] == 1) {
                $type_payment = "เงินสด";
            } else if ($row_payment['call_type'] == 2) {
                $type_payment = "ผ่านเคาเตอร์ธนาคาร";
            } else if ($row_payment['call_type'] == 3) {
                $type_payment = "โอน";
            }

            $data = [
                'rec_code' => $row_payment['rec_code'],
                'date_receipt' => chgDate(date_format($row_payment['date_receipt'], 'Y-m-d')),
                'sum_total' => number_format($row_payment['sum_total'], 2),
                'call_type' => $type_payment,
                'status_use' => $row_payment['status_use'],
            ];
            array_push($report_payment, $data);
            $i++;
        }

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'reportpayment' => $report_payment,
        ];
        echo json_encode($data);

        break;
    case 'getListPayment':

        $baacChapa->checkAuth($request);

        if (empty($request['member_no'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['member_no']) != 32) {
            http_response_code(404);
            exit;
        }

        if (empty($request['year'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['year'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (strlen($request['year']) != 4) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        $year = $request['year'] - 543;
        $sql_chk_memid = "SELECT
                                member_id
                            FROM
                                M_MEMBER
                            WHERE
                                CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_MEMBER.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "'";
        $q_chk_memid = $db->query($sql_chk_memid);
        $row_chk_memid = $db->db_fetch_array($q_chk_memid);

        if (empty($row_chk_memid['member_id'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        $cond = " AND F_RECEIPT.date_receipt LIKE '" . $year . "%' ";

        $sql_de = "SELECT deposit_money FROM M_MEMBER WHERE member_id = '" . $row_chk_memid['member_id'] . "' ";
        $q_de = $db->query($sql_de);
        $row_de = $db->db_fetch_array($q_de);

        $sql_date_payment = "SELECT
                                    F_RECEIPT.rec_id,
                                    F_RECEIPT.code_receipt,
                                    F_RECEIPT_DE.member_id,
                                    F_RECEIPT.date_receipt,
                                    F_RECEIPT.status_use,
                                    SUM(money) AS sum_money
                                FROM
                                    F_RECEIPT
                                    INNER JOIN F_RECEIPT_DE ON F_RECEIPT.rec_id = F_RECEIPT_DE.rec_id
                                WHERE
                                    F_RECEIPT_DE.member_id = '" . $row_chk_memid['member_id'] . "' " . $cond . "
                                GROUP BY
                                    F_RECEIPT.date_receipt,
                                    F_RECEIPT.rec_id,
                                    F_RECEIPT_DE.member_id,
                                    F_RECEIPT.code_receipt,
                                    F_RECEIPT.status_use
                                ORDER BY
                                    F_RECEIPT.date_receipt DESC";
        $q_date_payment = $db->query($sql_date_payment);

        $list = array();
        $k = 0;
        while ($row_date_payment = $db->db_fetch_array($q_date_payment)) {

            $sql_list = "SELECT
                                ACC_INCOME.income_name_th,
                                F_RECEIPT_DE.money,
                                F_RECEIPT_DE.rec_de_id,
                                F_RECEIPT_DE.rec_id
        	                FROM
                            	F_RECEIPT_DE
        	                    LEFT JOIN ACC_INCOME ON F_RECEIPT_DE.income_id = ACC_INCOME.income_id
        	                WHERE
        	                    F_RECEIPT_DE.rec_id = '" . $row_date_payment['rec_id'] . "' AND F_RECEIPT_DE.member_id = '" . $row_chk_memid['member_id'] . "'
        	                ORDER BY
        	                    F_RECEIPT_DE.income_id ASC";
            $q_list = $db->query($sql_list);

            $list_payment[$row_date_payment['rec_id']] = array();

            $i = 1;
            while ($row_list = $db->db_fetch_array($q_list)) {
                $data = [
                    'income_name_th' => $row_list['income_name_th'],
                    'money' => number_format($row_list['money'], 2),
                ];
                array_push($list_payment[$row_date_payment['rec_id']], $data);
                $i++;
            }

            $data = [
                'code_receipt' => $row_date_payment['code_receipt'],
                'date' => chgDate(date_format($row_date_payment['date_receipt'], 'Y-m-d')),
                'listname' => $list_payment[$row_date_payment['rec_id']],
                'status_use' => $row_date_payment['status_use'],
            ];

            array_push($list, $data);
        }

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'deposit_money' => $row_de['deposit_money'],
            'datachapa' => $list,
        ];
        echo json_encode($data);

        break;
    case 'insAssociation':

        $token_data = $baacChapa->checkAuth($request);

        if (empty($request['user_id'])) {
            http_response_code(404);
            exit;
        }

        if (!is_numeric($request['user_id'])) {
            http_response_code(404);
            exit;
        }

        if ($request['user_id'] != $token_data['data']['uid']) {
            http_response_code(404);
            exit;
        }

        if (empty($request['member_no'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['member_no']) != 32) {
            http_response_code(404);
            exit;
        }

        if (empty($request['dep_id'])) {
            http_response_code(404);
            exit;
        }

        if (!is_numeric($request['dep_id'])) {
            http_response_code(404);
            exit;
        }

        if (empty($request['id_card_no'])) {
            http_response_code(404);
            exit;
        }

        if (!is_numeric($request['id_card_no'])) {
            http_response_code(404);
            exit;
        }

        $sql = "SELECT
                    F_ID
                FROM
                    FRM_USER_DEP
                WHERE
                    WFR_ID = '" . $request['user_id'] . "' AND
                    CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(FRM_USER_DEP.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "' AND
                    DEP_ID = '" . $request['dep_id'] . "' ";
        $q = $db->query($sql);
        $row = $db->db_fetch_array($q);
        if ($row['F_ID']) {
            $data = get_message_error('มีข้อมูลสมาคมนี้แล้ว');
            echo json_encode($data);
            exit;
        }

        $sql_d = $db->query("SELECT CHAPA_CLOUD_ID FROM M_BASIC WHERE BASIC_ID = '" . $request['dep_id'] . "' ");
        $rec_d = $db->db_fetch_array($sql_d);
        if (empty($rec_d['CHAPA_CLOUD_ID'])) {
            http_response_code(404);
            exit;
        }

        $EWT_DB_NAME = "baac_chapa_" . $rec_d['CHAPA_CLOUD_ID'];
        $db_dep = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
        $connectdb_dep = $db_dep->CONNECT_SERVER();
        if (!$connectdb_dep) {
            http_response_code(404);
            exit;
        }

        $sql = "SELECT
                    member_id,
                    member_no
                FROM
                    M_MEMBER
                WHERE
                    CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_MEMBER.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "' AND
                    id_card_no='" . $request['id_card_no'] . "' AND
                    member_status = 1";
        $sql_m = $db_dep->query($sql);
        $rec_m = $db_dep->db_fetch_array($sql_m);
        if (empty($rec_m['member_id'])) {
            $data = get_message_error('ยังไม่มีข้อมูลสมาชิกในสมาคมนี้');
            echo json_encode($data);
            exit;
        }

        $sqlm = "SELECT MAX(F_ID) AS MAX_ID FROM FRM_USER_DEP ";
        $qm = $db->query($sqlm);
        $rowm = $db->db_fetch_array($qm);

        $dep = $request['dep_id'];
        $member_no = $rec_m['member_no'];
        $maxid = $rowm['MAX_ID'] + 1;
        $date = date('Y-m-d');

        $sqll = "INSERT INTO FRM_USER_DEP (
                                        F_ID,
                                        DEP_ID,
                                        MEMBER_NO,
                                        F_CREATE_DATE,
                                        F_UPDATE_DATE,
                                        WF_MAIN_ID,
                                        WFD_ID,
                                        WFR_ID,
                                        WFS_ID,
                                        F_TEMP_ID,
                                        F_CREATE_BY,
                                        F_UPDATE_BY
                                    ) VALUES (
                                        '" . $maxid . "',
                                        '" . $dep . "',
                                        '" . $member_no . "',
                                        '" . $date . "',
                                        '" . $date . "',
                                        34,
                                        0,
                                        '" . $request['user_id'] . "',
                                        496,
                                        '" . $request['user_id'] . "',
                                        '" . $request['user_id'] . "',
                                        '" . $request['user_id'] . "'
                                    )";
        $qq = $db->query($sqll);

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'status' => 'Y',
            'msg' => 'complete',
        ];
        echo json_encode($data);

        break;
    case 'forGetpass':

        $baacChapa->checkAuth($request);

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'status' => 'Y',
            'link' => 'https://www.smartchapa.com/webapp/forget_password.php',
        ];
        echo json_encode();

        break;
    case 'getBank':

        $baacChapa->checkAuth($request);

        if (empty($request['member_no'])) {
            http_response_code(404);
            exit;
        }

        if (strlen($request['member_no']) != 32) {
            http_response_code(404);
            exit;
        }

        $sql_chk_memid = "SELECT
                                member_id
                            FROM
                                M_MEMBER
                            WHERE
                                CONVERT(VARCHAR(32), HashBytes('MD5', CONCAT(M_MEMBER.member_no,'" . secret_key . "')), 2) = '" . $request['member_no'] . "'";
        $q_chk_memid = $db->query($sql_chk_memid);
        $row_chk_memid = $db->db_fetch_array($q_chk_memid);
        if (empty($row_chk_memid['member_id'])) {
            $data = get_message_error('มีข้อมูลสมาคมนี้แล้ว');
            echo json_encode($data);
            exit;
        }

        $sql_de = "SELECT deposit_money FROM M_MEMBER WHERE member_id = '" . $row_chk_memid['member_id'] . "' ";
        $q_de = $db->query($sql_de);
        $row_de = $db->db_fetch_array($q_de);

        //ดึงเงินสงเคราะห์ล่วงหน้า
        $sql_arrear = "SELECT sys_advance_arrear_person FROM SYS_ADVANCE_ARREAR WHERE status_use = 1 ";
        $query_arrear = $db->query($sql_arrear);
        $recFee_arrear = $db->db_fetch_array($query_arrear);
        $pay_amount = $recFee_arrear['sys_advance_arrear_person'];

        //ดึงอัตราเงินสงเคราะห์ศพละ
        $sql_pay = "SELECT person_amount FROM SYS_ADVANCE WHERE status_use = 1 ";
        $query_pay = $db->query($sql_pay);
        $recFee_pay = $db->db_fetch_array($query_pay);
        $pay = $recFee_pay['person_amount'];
        $pay_all = $pay_amount * $pay;

        if ($row_de['deposit_money'] >= $pay_all) {
            $pay_total = 0;
        } else {
            $pay_total = $pay_all - $row_de['deposit_money'];
        }

        $data_bank = [
            'ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร',
            'ธนาคารไทยพาณิชย์ จำกัด (มหาชน)',
            'ธนาคารกสิกรไทย จำกัด (มหาชน)',
            'ธนาคารกรุงศรีอยุธยา จำกัด (มหาชน)',
            'ธนาคารออมสิน',
            'ธนาคารกรุงเทพ จำกัด (มหาชน)',
            'ธนาคารกรุงไทย จำกัด (มหาชน)',
            'ธนาคารทหารไทย จำกัด (มหาชน)',
            'ธนาคารยูโอบี จำกัด (มหาชน)',
            'ธนาคารธนชาต จำกัด (มหาชน)',
        ];

        $data = [];
        foreach ($data_bank as $value) {
            array_push($data, ['bankname' => $value]);
        }

        $data_c = [
            'success' => 'Y',
            'error_log' => 'Success',
            'deposit_money' => number_format($pay_total, 2),
            'datachapa' => $data,
        ];
        echo json_encode($data_c);

        break;
    case 'getProvince':

        $baacChapa->checkAuth($request);

        $sql_province = "SELECT PROVINCE_CODE,PROVINCE_NAME FROM G_PROVINCE ORDER BY PROVINCE_NAME ASC";
        $q_province = $db->query($sql_province);
        $data_province = array();
        while ($rec_province = $db->db_fetch_array($q_province)) {
            array_push($data_province, ['province_code' => $rec_province['PROVINCE_CODE'], 'province_name' => $rec_province['PROVINCE_NAME']]);
        }

        $data = [
            'success' => 'Y',
            'error_log' => 'Success',
            'datachapa' => $data_province,
        ];
        echo json_encode($data);

        break;
    case 'getAmphur':

        $baacChapa->checkAuth($request);

        if (empty($request['prov_code'])) {
            echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => []]);
            exit;
        }

        if (!is_numeric($request['prov_code'])) {
            http_response_code(404);
            exit;
        }

        $sql_amphur = "SELECT AMPHUR_CODE,AMPHUR_NAME FROM G_AMPHUR WHERE PROVINCE_CODE = '" . $request['prov_code'] . "' ORDER BY AMPHUR_NAME ASC";
        $q_amphur = $db->query($sql_amphur);
        $data_amphur = array();
        while ($rec_amphur = $db->db_fetch_array($q_amphur)) {
            array_push($data_amphur, ['amphur_code' => $rec_amphur['AMPHUR_CODE'], 'amphur_name' => $rec_amphur['AMPHUR_NAME']]);
        }

        echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => $data_amphur]);

        break;
    case 'getTambon':

        $baacChapa->checkAuth($request);

        if (empty($request['prov_code'])) {
            echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => []]);
            exit;
        }

        if (!is_numeric($request['prov_code'])) {
            http_response_code(404);
            exit;
        }

        if (empty($request['amp_code'])) {
            echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => []]);
            exit;
        }

        if (!is_numeric($request['amp_code'])) {
            http_response_code(404);
            exit;
        }

        $sql_tambon = "SELECT TAMBON_CODE,TAMBON_NAME FROM G_TAMBON WHERE PROVINCE_CODE = '" . $request['prov_code'] . "' AND AMPHUR_CODE = '" . $request['amp_code'] . "' ORDER BY TAMBON_NAME ASC";
        $q_tambon = $db->query($sql_tambon);
        $data_tambon = array();
        while ($rec_tambon = $db->db_fetch_array($q_tambon)) {
            array_push($data_tambon, ['tambon_code' => $rec_tambon['TAMBON_CODE'], 'tambon_name' => $rec_tambon['TAMBON_NAME']]);
        }

        echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => $data_tambon]);

        break;
    case 'getZipcode':

        $baacChapa->checkAuth($request);

        if (empty($request['prov_code'])) {
            echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => []]);
            exit;
        }

        if (!is_numeric($request['prov_code'])) {
            http_response_code(404);
            exit;
        }

        if (empty($request['amp_code'])) {
            echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => []]);
            exit;
        }

        if (!is_numeric($request['amp_code'])) {
            http_response_code(404);
            exit;
        }

        if (empty($request['tam_code'])) {
            echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => []]);
            exit;
        }

        if (!is_numeric($request['tam_code'])) {
            http_response_code(404);
            exit;
        }

        $sql_tambon = "SELECT TAMBON_CODE,TAMBON_NAME,ZIP_CODE FROM G_TAMBON WHERE PROVINCE_CODE = '" . $request['prov_code'] . "' AND AMPHUR_CODE = '" . $request['amp_code'] . "' AND TAMBON_CODE = '" . $request['tam_code'] . "' ";
        $q_tambon = $db->query($sql_tambon);
        $data_tambon = array();
        while ($rec_tambon = $db->db_fetch_array($q_tambon)) {
            array_push($data_tambon, ['tambon_code' => $rec_tambon['TAMBON_CODE'], 'tambon_name' => $rec_tambon['TAMBON_NAME'], 'zipcode' => $rec_tambon['ZIP_CODE']]);
        }

        echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => $data_tambon]);

        break;
    case 'getDep':

        $baacChapa->checkAuth($request);

        $sql_depname = "SELECT BASIC_ID,DEP_NAME,CHAPA_CLOUD_ID FROM M_BASIC WHERE SMART_CHAPA_STATUS='Y' ";
        $q = $db->query($sql_depname);
        $data_dep = array();
        while ($rec_dep = $db->db_fetch_array($q)) {
            array_push($data_dep, ['dep_id' => $rec_dep['BASIC_ID'], 'dep_name' => $rec_dep['DEP_NAME'], 'id_db' => $rec_dep['CHAPA_CLOUD_ID']]);
        }

        echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => $data_dep]);

        break;
    case 'changePass':

        $baacChapa->checkAuth($request);

        if (empty($request['username'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (!is_numeric($request['username'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (empty($request['pass_old'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        if (empty($request['pass_new'])) {
            $data = get_message_error();
            echo json_encode($data);
            exit;
        }

        $sql = $db->query("SELECT USER_ID FROM M_USER WHERE ID_CARD_NO = '" . htmlspecialchars($request['username']) . "' AND PASSWORD = '" . htmlspecialchars($request['pass_old']) . "' ");
        $q = $db->db_fetch_array($sql);
        if ($q['USER_ID']) {

            $update = $db->query("UPDATE M_USER SET PASSWORD = '" . htmlspecialchars($request['pass_new']) . "' WHERE ID_CARD_NO = '" . htmlspecialchars($request['username']) . "' ");
            $data = ['status' => 'Y', 'msg' => 'สำเร็จ'];

        } else {
            $data = ['status' => 'N', 'msg' => 'username หรือ password ไม่ถูกต้อง'];
        }

        echo json_encode(['success' => 'Y', 'error_log' => 'Success', 'datachapa' => $data]);

        break;
}
