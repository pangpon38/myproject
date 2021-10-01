<?php
require $path . 'vendor/autoload.php';
use \Firebase\JWT\JWT;

class baacChapa
{
    protected $jwt_key;

    public function __construct($request)
    {
        $this->jwt_key = "FGETTgffhy44545TTYRE3453sWESDFSDfdfdterrterte3434EDJKtfghjytrdhtyrrr"; //jwt_key เป็นข้อความ random อะไรก็ได้ แต่เปิดเผยให้เขาอื่นรู้ เปรียบเสมือนกุญแจลับ
        $this->data = isset($request['TOKEN']) ? $this->getDataToken($request['TOKEN']) : array();
    }
    public function validateRequiredField($rules, $request)
    {
        $errors = array();
        foreach ($rules as $value) {
            if (!$request[$value]) {
                $errors[] = $value;
            }
        }
        if (count($errors) == 1) {
            $message = $errors[0] . ' is required';
        } else if (count($errors) > 1) {
            $message = implode(', ', $errors) . ' are required';
        } else {
            $message = '';
        }
        return array('return' => count($errors) ? 0 : 1, 'message' => $message);
    }

    public function generateToken($request)
    {
        $payload = array(
            'APP_CODE' => $request['APP_CODE'],
            'APP_NAME' => $request['APP_NAME'],
            'CREATE_DATETIME' => date('Y-m-d H:i:s'),
        );

        return JWT::encode($payload, $this->jwt_key);
    }

    public function getDataToken($jwt_token)
    {
        return (array) JWT::decode($jwt_token, $this->jwt_key, array('HS256'));
    }

    public function db_clean_val($str)
    {
        return str_replace("'", "''", $str);
    }

    public function checkAuth($request)
    {
        try {
            $arr_tokens = array('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJBUFBfQ09ERSI6ImJhYWNDaGFwYSIsIkFQUF9OQU1FIjoiYmFhY0NoYXBhIiwiQ1JFQVRFX0RBVEVUSU1FIjoiMjAyMS0wMS0yNiAxMDoxMDoyMCJ9.b_iHPcC8OKRtqDz1zHXH-YxzP8Dqi4vSBjI3jrEOB88');

            /*if(!in_array($request['TOKEN'],$arr_tokens)){
            throw new Exception('Invalid token');
            }*/
            $this->data = $this->getDataToken($request['token']);

            return array('SUCCESS' => 'Y', 'ERROR_LOG' => null, 'data' => $this->data);

        } catch (Exception $ex) {
            echo json_encode((object) array('SUCCESS' => 'N', 'ERROR_LOG' => $ex->getMessage()));
            exit;
        }
    }

    public function saveApiLog($data_log)
    {
        db::db_insert('M_API_LOGS', $data_log, 'LOG_ID');
    }

    public function getUser($user_ad)
    {

        $sql = "select USR_ID  from USR_MAIN  where USR_USERNAME  = '" . $user_ad . "'";
        $result = db::query($sql);
        $row = db::fetch_array($result);
        return $row['USR_ID'];

    }

    public function getDetail($request)
    {

        $sql = "select * from m_member where id_card_no = '" . $request['TEST'] . "' ";
        $aaa = $db->query($sql);
        $row = $db->db_fetch_array($aaa);
        return $row;
        //if(!$connectdb) {echo "connection error";}else{return array('SUCCESS'=>'Y','ERROR_LOG'=>'โว้ยยยยยยยยยยยยยยยยยยยยยยยย');}
        /*return $row['fname'];*/
        //return array('SUCCESS'=>'Y','ERROR_LOG'=>'โว้ยยยยยยยยยยยยยยยยยยยยยยยย');
    }

//////////////////////////////////////////////////////
}