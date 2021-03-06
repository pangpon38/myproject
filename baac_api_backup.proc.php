<?php
//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
//error_reporting(E_ALL);
ini_set('display_errors', 1);

require('include/config.php');
require 'include/include.php';
require './baac_api.class.php';
require './baac_config_api.php';
//require('./exim_api_login.class.php');
header('Content-Type: application/json');

function chgDate($date){
	if($date != ''){
		list($y,$m,$d) = explode("-",$date);
		return $d."/".$m."/".($y+543);
	}
}



$request = json_decode(file_get_contents("php://input"), true);


if (!empty($request)) {
	if($_GET['proc'] != 'getLogin' && $_GET['proc'] != 'insAssociation' && $_GET['proc'] != 'forGetpass'  && $_GET['proc'] != 'getProvince' && $_GET['proc'] != 'getAmphur'  && $_GET['proc'] != 'getTambon' && $_GET['proc'] != 'getZipcode' && $_GET['proc'] != 'getDep' && $_GET['proc'] != 'changePass' && $_GET['proc'] != 'getAddressSendDoc'){

		$EWT_DB_NAME = "baac_chapa_" . $request['id_db'];
		$db = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
		$connectdb = $db->CONNECT_SERVER();
		if (!$connectdb) {echo "connection error";}
	}else{
		$db = new PHPDB($EWT_DB_TYPE, $WF_ROOT_HOST, $WF_ROOT_USER, $WF_ROOT_PASSWORD, $WF_DB_NAME);
		$connectdb_cloud = $db->CONNECT_SERVER();
		if (!$connectdb_cloud) {echo "connection error";exit;}
	}


	$baacChapa = new baacChapa($request);


}

switch ($_GET['proc']) {
	case 'getLogin':
	$baacChapa->checkAuth($request);
	if($request['username'] != "" & $request['password'] != ""){

       /* $txt_salt = md5("1Qazxsw234760");
       $password = hash_hmac('md5', $request['password'],$txt_salt);*/
       $password =  $request['password'];


       $sqlChk_c = "SELECT COUNT(USER_ID) AS NUM FROM M_USER U
       WHERE U.ID_CARD_NO = '".$request['username']."' AND U.PASSWORD = '".$password."' ";
       $q_c = $db->query($sqlChk_c);
       $numrow = $db->db_fetch_array($q_c);

       if($numrow['NUM'] > 0){
       	$sqlChk = "SELECT U.* , P.PREFIX_NAME FROM M_USER U
       	LEFT JOIN M_PREFIX P ON U.PREFIX_ID = P.PREFIX_ID
       	WHERE U.ID_CARD_NO = '".$request['username']."' AND U.PASSWORD = '".$password."'";
       	$queryChk = $db->query($sqlChk);
       	$row = $db->db_fetch_array($queryChk);
       	$data_login = array(
       		'user_id'       => $row['USER_ID'],
       		'id_card_no'    => $row['ID_CARD_NO'],
       		'name'          => $row["PREFIX_NAME"].$row["FNAME"]." ".$row["LNAME"],
       		'prefixname'    => $row["PREFIX_ID"],
       		'firstname'      => $row['FNAME'],
       		'lastname'      => $row['LNAME']
       	);
       	$i=1;
       	$sql_dep = "SELECT mb.CHAPA_CLOUD_ID , mb.DEP_NAME,ud.MEMBER_NO FROM FRM_USER_DEP ud LEFT JOIN  M_BASIC mb ON ud.DEP_ID = mb.BASIC_ID  WHERE ud.WFR_ID = '".$row['USER_ID']."' ";
       	$q_dep = $db->query($sql_dep);
       	$data_dep  = array();
       	while($row_dep = $db->db_fetch_array($q_dep)){
       		$EWT_DB_NAME = "baac_chapa_" .$row_dep['CHAPA_CLOUD_ID'];
       		$db_dep = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
       		$connectdb_dep = $db_dep->CONNECT_SERVER();
       		if (!$connectdb_dep) {echo "connection error";}
       		$sql_m = "select 	M_MEMBER.member_no
       		,M_TYPE.m_cate_name
       		,PREFIX.prefix_name+' '+M_MEMBER.fname+' '+M_MEMBER.lname as name
       		,M_STATUS.m_status_name
       		,M_MEMBER.EFF_DATE
       		,M_MEMBER.PAY_CODE
       		,M_MEMBER.member_status
       		from 	M_MEMBER
       		LEFT JOIN M_STATUS on M_STATUS.m_status_id = M_MEMBER.member_status
       		LEFT JOIN M_TYPE on M_TYPE.m_cate_id = M_MEMBER.m_cate_id
       		LEFT JOIN PREFIX on M_MEMBER.prefix_id = PREFIX.prefix_id
       		where M_MEMBER.member_no = '" . $row_dep['MEMBER_NO']  . "' ";
       		$q_m = $db_dep->query($sql_m);
       		$rec_m = $db_dep->db_fetch_array($q_m);
       		if($rec_m['member_status']!=4 && $rec_m['member_status']!=0 && $rec_m['member_status']!=5){
       			array_push($data_dep, array('member_no'=>$rec_m['member_no'],'m_status_id'=>$rec_m['member_status'],'m_status_name'=>$rec_m['m_status_name'],'m_cate_name'=> $rec_m['m_cate_name'],'chapa_cloud_id' => $row_dep['CHAPA_CLOUD_ID'] ,'dep_name' => $row_dep['DEP_NAME'])) ;
       		}
       		$i++;
       	}
       	$data_login = array(
       		'user_id'       => $row['USER_ID'],
       		'id_card_no'    => $row['ID_CARD_NO'],
       		'name'          => $row["PREFIX_NAME"].$row["FNAME"]." ".$row["LNAME"],
       		'prefixname'    => $row["PREFIX_NAME"],
       		'firstname'      => $row['FNAME'],
       		'lastname'      => $row['LNAME'],
       		'in_dep'		=> $data_dep
       	);
       	$data = array('status_login' => 'Y' , 'msg' =>'??????????????????' , 'detail_member' => $data_login);
       	echo json_encode($data);
       }else{
       	$data = array('status_login' => 'N' , 'msg' =>'????????????????????????????????????????????? USERNAME ????????? PASSWORD ??????????????????????????????');
       	echo json_encode($data);
       }
   }else{
   	$data = array('status_login' => 'N' , 'msg' =>'????????????????????????????????????????????? USERNAME ????????? PASSWORD');
   	echo json_encode($data);
   }
   break;
   case 'generateToken':
   $response = $baacChapa->generateToken(array('APP_CODE' => 'baacChapa', 'APP_NAME' => 'baacChapa'));
   echo json_encode($response);
   break;

   case 'getMember':
   $baacChapa->checkAuth($request);
        /*$response = $baacChapa->getDetail($request);
        echo json_encode($response);
         */
        $sql = "select 	M_MEMBER.member_no
        ,M_TYPE.m_cate_name
        ,PREFIX.prefix_name+' '+M_MEMBER.fname+' '+M_MEMBER.lname as name
        ,M_STATUS.m_status_name
        ,M_MEMBER.EFF_DATE
        ,M_MEMBER.PAY_CODE
        from 	M_MEMBER
        LEFT JOIN M_STATUS on M_STATUS.m_status_id = M_MEMBER.member_status
        LEFT JOIN M_TYPE on M_TYPE.m_cate_id = M_MEMBER.m_cate_id
        LEFT JOIN PREFIX on M_MEMBER.prefix_id = PREFIX.prefix_id
        where M_MEMBER.member_no = '" . $request['member_no']  . "'
        ";
        $aaa = $db->query($sql);
        $row = $db->db_fetch_array($aaa);
        if ($row['PAY_CODE'] == 1) {
        	$pay_name = "???????????????????????????????????????????????????????????????????????????";
        } else if ($row['PAY_CODE'] == 2) {
        	$pay_name = "???????????????????????????????????????????????????????????????????????????";
        } else if ($row['PAY_CODE'] == 3) {
        	$pay_name = "?????????????????????????????????????????????????????????????????????????????????";
        } else if ($row['PAY_CODE'] == 4) {
        	$pay_name = "?????????????????????????????????????????????????????????????????????????????????";
        }
        $member = array('member_no' => $row['member_no'], 'm_cate_name' => $row['m_cate_name'], 'name' => $row['name'], 'm_status_name' => $row['m_status_name'], 'pay_code' => $pay_name, 'eff_date' => chgDate(date_format($row['EFF_DATE'] ,'Y-m-d')) );
        $data = array('datachapa' => $member);
        echo json_encode($data);
        break;

        case 'getMemberDetail':
        $baacChapa->checkAuth($request);
        $sql_mem = " select
        M_MEMBER.member_no
        ,M_MEMBER.member_id
        ,PREFIX.prefix_name + ' ' + M_MEMBER.fname + ' ' + M_MEMBER.lname as name
        ,M_MEMBER.gender
        ,M_MEMBER.birthdate
        ,M_MEMBER.home_no
        ,M_MEMBER.moo_no
        ,M_MEMBER.soi_name
        ,M_MEMBER.road_name
        ,M_MEMBER.prov_id
        ,M_MEMBER.amp_id
        ,M_MEMBER.tam_id
        ,province.province_name
        ,amphur.amphur_name
        ,tambon.tambon_name
        ,M_MEMBER.postcode
        ,M_MEMBER.email
        ,M_MEMBER.marry_status
        ,M_MEMBER.PAY_CODE
        ,M_MEMBER.EFF_DATE
        ,M_STATUS.m_status_name
        ,M_MEMBER.deposit_money
        from 	M_MEMBER
        LEFT JOIN PREFIX on M_MEMBER.prefix_id = PREFIX.prefix_id
        LEFT JOIN M_STATUS on M_STATUS.m_status_id = M_MEMBER.member_status
        LEFT JOIN province on M_MEMBER.prov_id = province.province_code
        LEFT JOIN amphur on M_MEMBER.amp_id = amphur.amphur_code and province.province_code = amphur.province_code
        LEFT JOIN tambon on M_MEMBER.tam_id = tambon.tambon_code and amphur.amphur_code = tambon.amphur_code and province.province_code = tambon.province_code
        where 	M_MEMBER.member_no = '" . $request['member_no']  . "'";
        $mem_q = $db->query($sql_mem);
        $row_mem = $db->db_fetch_array($mem_q);

        if ($row_mem['gender'] == 1) {
        	$gender = "?????????";
        } else if ($row_mem['gender'] == 2) {
        	$gender = "????????????";
        }

        if ($row_mem['PAY_CODE'] == 1) {
        	$pay_name = "???????????????????????????????????????????????????????????????????????????";
        } else if ($row_mem['PAY_CODE'] == 2) {
        	$pay_name = "???????????????????????????????????????????????????????????????????????????";
        } else if ($row_mem['PAY_CODE'] == 3) {
        	$pay_name = "?????????????????????????????????????????????????????????????????????????????????";
        } else if ($row_mem['PAY_CODE'] == 4) {
        	$pay_name = "?????????????????????????????????????????????????????????????????????????????????";
        }
        switch ($row_mem['marry_status']) {
        	case '1':
        	$row_mem['marry_status'] = "?????????";
        	break;
        	case '2':
        	$row_mem['marry_status'] = "????????????";
        	break;
        	case '3':
        	$row_mem['marry_status'] = "????????????????????????";
        	break;
        	case '4':
        	$row_mem['marry_status'] = "???????????????";
        	break;

        	default:
        	$row_mem['marry_status'] = "-";
        	break;
        }

        $sql_have_money = "select M_BENEFIT.benefit_no,
        (select prefix_name from prefix where prefix.prefix_id = M_BENEFIT.prefix_id) as prefix_name
        ,M_BENEFIT.fname
        ,M_BENEFIT.lname
        from
        M_BENEFIT
        where
        M_BENEFIT.member_id = '" . $row_mem['member_id'] . "' ORDER BY M_BENEFIT.benefit_no ASC";
        $have_money_q = $db->query($sql_have_money);
        $i = 1;
        $people_income = array();
        while ($row_have_money = $db->db_fetch_array($have_money_q)) {
        	array_push($people_income,  array('benefit_no' => $row_have_money['benefit_no'] , 'fname' => $row_have_money['prefix_name']." ".$row_have_money['fname']." ".$row_have_money['lname']));
        	$i++;
        }
        $sql_count_mem = "select
        count(member_id) AS num_mem
        from
        M_MEMBER
        where
        member_status = 1";
        $count_mem_q = $db->query($sql_count_mem);
        $row_count_mem = $db->db_fetch_array($count_mem_q);

        $member_detail = array(
        	'member_id' => $row_mem['member_id'],
        	'member_no' => $row_mem['member_no'],
        	'name' => $row_mem['name'],
        	'gender' => $gender,
        	'm_status_name' => $row_mem['m_status_name'],
        	'birthdate' => chgDate(date_format($row_mem['birthdate'] ,'Y-m-d')),
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
        	'marry_status' => $row_mem['marry_status'],
        	'pay_code' => $pay_name,
        	'deposit_money' => number_format($row_mem['deposit_money'],2),
        	'eff_date' => chgDate(date_format($row_mem['EFF_DATE'] ,'Y-m-d')),
        	'people_income' => $people_income,
        	'count_member' => number_format($row_count_mem['num_mem'])
        );

        $data = array('datachapa' => $member_detail);
        echo json_encode($data);
        break;

        case 'getCalMoney':

        $baacChapa->checkAuth($request);
        $sql_count_mem = "select
        count(member_id) AS num_mem
        from
        M_MEMBER
        where
        member_status = 1";
        $count_mem_q = $db->query($sql_count_mem);
        $row_count_mem = $db->db_fetch_array($count_mem_q);

        $sql_advance_fee = "select
        advance_fee
        from
        SYS_ADVANCE_FEE
        where
        status_use = 1";
        $advance_fee_q = $db->query($sql_advance_fee);
        $row_advance_fee = $db->db_fetch_array($advance_fee_q);

        $sql_person_amount = "select
        person_amount
        from
        SYS_ADVANCE
        where
        status_use = 1";
        $person_amount_q = $db->query($sql_person_amount);
        $row_person_amount = $db->db_fetch_array($person_amount_q);

        $sum_money = $row_count_mem['num_mem']*$row_person_amount['person_amount'];
        $fee_money = ($sum_money*$row_advance_fee['advance_fee'])/100;

        $member_detail = array(
        	'count_member' => $row_count_mem['num_mem'],
        	'person_amount' => number_format($row_person_amount['person_amount'],2),
        	'sum_money' => number_format($sum_money,2),
        	'advance_fee' => $row_advance_fee['advance_fee']."%",
        	'cost_deduction' => number_format($fee_money,2),
        	'balance_money' => number_format($sum_money-$fee_money,2)
        );

        $data = array('datachapa' => $member_detail);
        echo json_encode($data);

        break;

        case 'getAddressSendDoc':
        $baacChapa->checkAuth($request);
        $sql_dep = "SELECT mb.CHAPA_CLOUD_ID , mb.DEP_NAME,ud.MEMBER_NO FROM FRM_USER_DEP ud LEFT JOIN  M_BASIC mb ON ud.DEP_ID = mb.BASIC_ID  WHERE ud.WFR_ID = '".$request['user_id']."' ";
        $q_dep = $db->query($sql_dep);
        $data_dep  = array();
        while($row_dep = $db->db_fetch_array($q_dep)){
        	$EWT_DB_NAME = "baac_chapa_" .$row_dep['CHAPA_CLOUD_ID'];
        	$db_dep = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
        	$connectdb_dep = $db_dep->CONNECT_SERVER();
        	if (!$connectdb_dep) {echo "connection error";}
        	$sql_address = "SELECT member_id
        	,member_no
        	,home_no
        	,moo_no
        	,soi_name
        	,road_name
        	,prov_id
        	,amp_id
        	,tam_id
        	,province.province_name
        	,amphur.amphur_name
        	,tambon.tambon_name
        	,postcode
        	FROM M_MEMBER
        	LEFT JOIN province on M_MEMBER.prov_id = province.province_code
        	LEFT JOIN amphur on M_MEMBER.amp_id = amphur.amphur_code  and  province.province_code = amphur.province_code
        	LEFT JOIN tambon on M_MEMBER.tam_id = tambon.tambon_code and amphur.amphur_code = tambon.amphur_code and province.province_code = tambon.province_code
        	where M_MEMBER.member_no = '" . $row_dep['MEMBER_NO'] . "'";
        	$q_address = $db_dep->query($sql_address);
        	$row_address = $db_dep->db_fetch_array($q_address);
        	array_push( $data_dep ,
        		array('chapa_cloud_id'=>$row_dep['CHAPA_CLOUD_ID'],
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
        			'address' => "?????????????????????????????? ".$row_address['home_no']." ???????????? ".$row_address['moo_no']." ????????? ".$row_address['soi_name']." ????????? ".$row_address['road_name']." ????????????/???????????? ".$row_address['tambon_name']." ???????????????/????????? ".$row_address['amphur_name']." ????????????????????? ".$row_address['province_name']." ".$row_address['postcode'])  );
        }


        $data = array('datachapa'=>$data_dep);
        echo json_encode($data);
        break;

        case 'changeAddressSendDoc':
        $baacChapa->checkAuth($request);
        $sql_address = "SELECT member_id
        ,member_no
        ,home_no
        ,moo_no
        ,soi_name
        ,road_name
        ,prov_id
        ,amp_id
        ,tam_id
        ,fname
        ,lname
        ,case when gender =  1 Then '?????????' when gender = 2 Then '????????????' end as gen
        ,(SELECT prefix_name FROM PREFIX WHERE prefix_id = M_MEMBER.prefix_id) As prefix
        ,province.province_name
        ,amphur.amphur_name
        ,tambon.tambon_name
        ,postcode
        FROM M_MEMBER
        LEFT JOIN province on M_MEMBER.prov_id = province.province_code
        LEFT JOIN amphur on M_MEMBER.amp_id = amphur.amphur_code  and  province.province_code = amphur.province_code
        LEFT JOIN tambon on M_MEMBER.tam_id = tambon.tambon_code and amphur.amphur_code = tambon.amphur_code and province.province_code = tambon.province_code
        where M_MEMBER.member_no = '" . $request['member_no'] . "' ";
        $q_address = $db->query($sql_address);
        $row_address = $db->db_fetch_array($q_address);

        $datetime = date('Y-m-d H:i:s');
        $date = date('Y-m-d');
        /////////////////////////////////////////////////////////////

        $memid = $row_address['member_id'];
        $member_name = $row_address['prefix']." ".$row_address['fname']." ".$row_address['lname'];
        /////////////////////////////////////////////////////////////
        $sql_Ins = "INSERT INTO M_CHG_PROFILE(member_id ,chg_date  , approve_status , create_by , create_datetime , chg_code ) VALUES
        ( '".$memid."' , '".$date."' ,'0' , '".$member_name."' , '".$datetime."' , 1) ";
        $q_Ins = $db->query($sql_Ins);

        $sqlMax = "SELECT  MAX(chg_id) AS MAX_ID FROM M_CHG_PROFILE ";
        $qMax = $db->query($sqlMax);
        $rowMax = $db->db_fetch_array($qMax);
        $maxId = $rowMax['MAX_ID'];
        $sql_add = "INSERT INTO M_CHG_ADDS
        (chg_id,type_chg_id ,home_no2_old,moo_no2_old,soi_name2_old,road_name2_old,prov_id2_old,amp_id2_old,tam_id2_old,postcode2_old,home_no2_new,moo_no2_new,soi_name2_new,road_name2_new,prov_id2_new,amp_id2_new,tam_id2_new,postcode2_new ,approve_status,create_by,create_datetime,home_no_old,moo_no_old,soi_name_old,road_name_old,prov_id_old,amp_id_old,tam_id_old,postcode_old,home_no_new,moo_no_new,soi_name_new,road_name_new,prov_id_new,amp_id_new,tam_id_new,postcode_new )
        VALUES ('".$maxId."' , 2 ,'".$row_address['home_no']."' ,'".$row_address['moo_no']."','".$row_address['soi_name']."', '".$row_address['road_name']."','".$row_address['prov_id']."','".$row_address['amp_id']."','".$row_address['tam_id']."' ,'".$row_address['postcode']."' ,'".$request['home_no']."','".$request['moo_no']."','".$request['soi_name']."','".$request['road_name']."','".$request['prov_code']."','".$request['amp_code']."','".$request['tam_code']."','".$request['postcode']."' , 0 ,'".$member_name."' , '".$datetime."' ,'".$row_address['home_no']."' ,'".$row_address['moo_no']."','".$row_address['soi_name']."', '".$row_address['road_name']."','".$row_address['prov_id']."','".$row_address['amp_id']."','".$row_address['tam_id']."' ,'".$row_address['postcode']."' ,'".$row_address['home_no']."' ,'".$row_address['moo_no']."','".$row_address['soi_name']."', '".$row_address['road_name']."','".$row_address['prov_id']."','".$row_address['amp_id']."','".$row_address['tam_id']."' ,'".$row_address['postcode']."' ) ";
        $q_add = $db->query($sql_add);
        $status = 'Y';
        $msg = '??????????????????';

        $data = array('datachapa' => array('status_insert' =>$status , 'msg' =>$msg) );
        echo json_encode($data);

        break;

        case 'getReportPayment':
        $baacChapa->checkAuth($request);
        $sql_chk_memid = "select member_id from M_MEMBER where member_no = '" . $request['member_no']  . "' ";
        $q_chk_memid = $db->query($sql_chk_memid);
        $row_chk_memid = $db->db_fetch_array($q_chk_memid);

        $sql_payment = "select
        M_MEMBER.member_no
        ,F_RECEIPT.rec_id
        ,F_RECEIPT.rec_code
        ,F_RECEIPT.date_receipt
        ,F_RECEIPT.sum_total
        ,F_RECEIPT.call_type
        from
        F_RECEIPT
        left join M_MEMBER on M_MEMBER.member_id = F_RECEIPT.member_id
        where
        M_MEMBER.member_id = '" . $row_chk_memid['member_id'] . "' ORDER BY F_RECEIPT.date_receipt DESC";
        $q_payment = $db->query($sql_payment);
        $i = 1;
        $report_payment = array();
        while ($row_payment = $db->db_fetch_array($q_payment)) {

        	if ($row_payment['call_type'] == 1) {
        		$type_payment = "??????????????????";
        	} else if ($row_payment['call_type'] == 2) {
        		$type_payment = "??????????????????????????????????????????????????????";
        	} else if ($row_payment['call_type'] == 3) {
        		$type_payment = "?????????";
        	}
        	array_push($report_payment,array('rec_code' => $row_payment['rec_code'], 'date_receipt' =>  chgDate(date_format($row_payment['date_receipt'],'Y-m-d')), 'sum_total' => number_format($row_payment['sum_total'],2), 'call_type' => $type_payment));
        	$i++;
        }
        $data = array('reportpayment' => $report_payment);
        echo json_encode($data);

        break;

        case 'getListPayment':
        $baacChapa->checkAuth($request);
        $year = $request['year']-543;
        $sql_chk_memid = "select member_id from M_MEMBER where member_no = '" . $request['member_no']  . "' ";
        $q_chk_memid = $db->query($sql_chk_memid);
        $row_chk_memid = $db->db_fetch_array($q_chk_memid);

        if(!empty($request['year'])){
        	$txt = " AND F_RECEIPT.date_receipt LIKE '".$year."%' " ;
        }
        $sql_de = "select deposit_money  from M_MEMBER where member_id = '".$row_chk_memid['member_id']."' ";
        $q_de = $db->query($sql_de);
        $row_de = $db->db_fetch_array($q_de);
        $sql_date_payment = "select
        F_RECEIPT.rec_id
        ,F_RECEIPT.code_receipt
        ,F_RECEIPT_DE.member_id
        ,F_RECEIPT.date_receipt
        ,sum(money) as sum_money
        from
        F_RECEIPT
        JOIN F_RECEIPT_DE ON F_RECEIPT.rec_id = F_RECEIPT_DE.rec_id
        where
        F_RECEIPT_DE.member_id = '" . $row_chk_memid['member_id'] . "' $txt
        group by
        F_RECEIPT.date_receipt, F_RECEIPT.rec_id, F_RECEIPT_DE.member_id,F_RECEIPT.code_receipt
        order by
        F_RECEIPT.date_receipt desc";
        $q_date_payment = $db->query($sql_date_payment);
        //$list_payment = array();
        $list = array();

        $k = 0;
        while ($row_date_payment = $db->db_fetch_array($q_date_payment)) {
        	//unset($list_payment);
        	$sql_list = "select
        	ACC_INCOME.income_name_th
        	,F_RECEIPT_DE.money,F_RECEIPT_DE.rec_de_id ,F_RECEIPT_DE.rec_id
        	from
        	F_RECEIPT_DE
        	left join ACC_INCOME ON F_RECEIPT_DE.income_id = ACC_INCOME.income_id
        	where
        	F_RECEIPT_DE.rec_id = '" . $row_date_payment['rec_id'] . "' and F_RECEIPT_DE.member_id = '" . $row_chk_memid['member_id'] . "'
        	order by
        	F_RECEIPT_DE.income_id ASC";

        	$q_list = $db->query($sql_list);

        	$list_payment[$row_date_payment['rec_id']] = array();

        	$i = 1;
        	while ($row_list = $db->db_fetch_array($q_list)) {
        		array_push($list_payment[$row_date_payment['rec_id']] , array('income_name_th' => $row_list['income_name_th'], 'money' =>number_format($row_list['money'],2 ) ) ); $i++;
        	}
        	array_push($list,  array('code_receipt'=>$row_date_payment['code_receipt'] ,'date' => chgDate(date_format($row_date_payment['date_receipt'],'Y-m-d')), 'listname' => $list_payment[$row_date_payment['rec_id']]));



        }
        $data = array( 'deposit_money'=>$row_de['deposit_money'],'datachapa' => $list);
        echo json_encode($data);
        break;

        case 'insAssociation' :
        $baacChapa->checkAuth($request);
        /*$sql_chk_memid = "select USER_ID from M_USER where MEMBER_NO = '" . $request['member_no']  . "' ";
        $q_chk_memid = $db->query($sql_chk_memid);
        $row_chk_memid = $db->db_fetch_array($q_chk_memid);*/
        $sql = "SELECT F_ID FROM FRM_USER_DEP WHERE WFR_ID = '".$request['user_id']."' AND MEMBER_NO = '".$request['member_no']."' AND DEP_ID = '".$request['dep_id']."' ";
        $q = $db->query($sql);
        $row = $db->db_fetch_array($q);

        $sql_d = $db->query("SELECT CHAPA_CLOUD_ID FROM M_BASIC WHERE BASIC_ID = '".$request['dep_id']."' ");
        $rec_d = $db->db_fetch_array($sql_d);

        $EWT_DB_NAME = "baac_chapa_" .$rec_d['CHAPA_CLOUD_ID'];
        $db_dep = new PHPDB($EWT_DB_TYPE, $EWT_ROOT_HOST, $EWT_ROOT_USER, $EWT_ROOT_PASSWORD, $EWT_DB_NAME);
        $connectdb_dep = $db_dep->CONNECT_SERVER();
        if (!$connectdb_dep) {echo "connection error";}

        $sql_m = $db_dep->query("SELECT member_id FROM M_MEMBER WHERE member_no ='".$request['member_no']."' AND id_card_no='".$request['id_card_no']."' AND member_status = 1");
        $rec_m = $db_dep->db_fetch_array($sql_m);
        if(!empty($rec_m['member_id'])){
        	$sqlm = "SELECT  MAX(F_ID) AS MAX_ID FROM FRM_USER_DEP ";
        	$qm = $db->query($sqlm);
        	$rowm = $db->db_fetch_array($qm);
        	if(empty($row['F_ID'])){
        		$dep      = $request['dep_id'];
        		$member_no    = $request['member_no'];
        		$maxid = $rowm['MAX_ID']+1;
        		$date = date('Y-m-d');

        		$sqll = "INSERT INTO FRM_USER_DEP(F_ID , DEP_ID , MEMBER_NO , F_CREATE_DATE , F_UPDATE_DATE  ,WF_MAIN_ID ,WFD_ID , WFR_ID , WFS_ID ,F_TEMP_ID ,F_CREATE_BY , F_UPDATE_BY) VALUES ('".$maxid."' , '".$dep."' , '".$member_no."' , '".$date."' , '".$date."' , 34 , 0 , '".$request['user_id']."' , 496 , '".$request['user_id']."' ,'".$request['user_id']."' ,'".$request['user_id']."')";
        		$qq = $db->query($sqll);
        		$data = array('status' => 'Y' , 'msg' =>'complete');
        		echo json_encode($data);
        	}else{
        		$data = array('status' => 'N' , 'msg' =>'????????????????????????????????????????????????????????????');
        		echo json_encode($data);
        	}
        }else{
        	$data = array('status' => 'N' , 'msg' =>'??????????????????????????????????????????????????????????????????????????????????????????');
        	echo json_encode($data);
        }

        break;

        case 'forGetpass' :
        $baacChapa->checkAuth($request);
        $data = array('status' => 'Y' , 'link' =>'https://www.smartchapa.com/webapp/forget_password.php');
        echo json_encode($data);
        break;

        case 'getBank' :
        $baacChapa->checkAuth($request);
        $sql_chk_memid = "select member_id from M_MEMBER where member_no = '" . $request['member_no']  . "' ";
        $q_chk_memid = $db->query($sql_chk_memid);
        $row_chk_memid = $db->db_fetch_array($q_chk_memid);
        $sql_de = "select deposit_money  from M_MEMBER where member_id = '".$row_chk_memid['member_id']."' ";
        $q_de = $db->query($sql_de);
        $row_de = $db->db_fetch_array($q_de);
        $data_bank = array('??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????','???????????????????????????????????????????????? ??????????????? (???????????????)','?????????????????????????????????????????? ??????????????? (???????????????)' ,'????????????????????????????????????????????????????????? ??????????????? (???????????????)' , '????????????????????????????????????' ,'??????????????????????????????????????? ??????????????? (???????????????)','??????????????????????????????????????? ??????????????? (???????????????)' , '??????????????????????????????????????? ??????????????? (???????????????)' ,'???????????????????????????????????? ??????????????? (???????????????)' , '????????????????????????????????? ??????????????? (???????????????)');
        $c_bank = count($data_bank);
        $data = array();
        for($i=0;$i<$c_bank;$i++){
        	array_push($data,array('bankname'=>$data_bank[$i]));
        }
        $data_c = array('deposit_money'=>$row_de['deposit_money'],'datachapa'=>$data);
        echo json_encode($data_c);
        break;

        case 'getProvince' :
        $baacChapa->checkAuth($request);
        $sql_province = "SELECT PROVINCE_CODE , PROVINCE_NAME FROM G_PROVINCE";
        $q_province = $db->query($sql_province);
        $data_province = array();
        while ($rec_province = $db->db_fetch_array($q_province)) {
        	array_push($data_province,array('province_code'=>$rec_province['PROVINCE_CODE'],'province_name'=>$rec_province['PROVINCE_NAME']));
        }
        $data = array('datachapa' => $data_province );
        echo json_encode($data);
        break;

        case 'getAmphur' :
        $baacChapa->checkAuth($request);
        $sql_amphur = "SELECT AMPHUR_CODE , AMPHUR_NAME FROM G_AMPHUR WHERE PROVINCE_CODE = '".$request['prov_code']."' ";
        $q_amphur = $db->query($sql_amphur);
        $data_amphur = array();
        while ($rec_amphur = $db->db_fetch_array($q_amphur)) {
        	array_push($data_amphur,array('amphur_code'=>$rec_amphur['AMPHUR_CODE'],'amphur_name'=>$rec_amphur['AMPHUR_NAME']));
        }
        $data = array('datachapa' => $data_amphur );
        echo json_encode($data);
        break;

        case 'getTambon' :
        $baacChapa->checkAuth($request);
        $sql_tambon = "SELECT TAMBON_CODE , TAMBON_NAME FROM G_TAMBON WHERE PROVINCE_CODE = '".$request['prov_code']."' AND AMPHUR_CODE = '".$request['amp_code']."' ";
        $q_tambon = $db->query($sql_tambon);
        $data_tambon = array();
        while ($rec_tambon = $db->db_fetch_array($q_tambon)) {
        	array_push($data_tambon,array('tambon_code'=>$rec_tambon['TAMBON_CODE'],'tambon_name'=>$rec_tambon['TAMBON_NAME']));
        }
        $data = array('datachapa' => $data_tambon );

        echo json_encode($data);
        break;

        case 'getZipcode' :
        $baacChapa->checkAuth($request);
        $sql_tambon = "SELECT TAMBON_CODE , TAMBON_NAME ,ZIP_CODE FROM G_TAMBON WHERE PROVINCE_CODE = '".$request['prov_code']."' AND AMPHUR_CODE = '".$request['amp_code']."' AND TAMBON_CODE = '".$request['tam_code']."' ";
        $q_tambon = $db->query($sql_tambon);
        $data_tambon = array();
        while ($rec_tambon = $db->db_fetch_array($q_tambon)) {
        	array_push($data_tambon,array('tambon_code'=>$rec_tambon['TAMBON_CODE'],'tambon_name'=>$rec_tambon['TAMBON_NAME'] , 'zipcode' => $rec_tambon['ZIP_CODE']));
        }
        $data = array('datachapa' => $data_tambon );
        echo json_encode($data);
        break;
        case 'getDep':
        $baacChapa->checkAuth($request);
        $sql_depname = "SELECT BASIC_ID,DEP_NAME,CHAPA_CLOUD_ID FROM M_BASIC WHERE SMART_CHAPA_STATUS='Y' ";
        $q = $db->query($sql_depname);
        $data_dep = array();
        while ($rec_dep = $db->db_fetch_array($q)) {
        	array_push($data_dep,array('dep_id'=>$rec_dep['BASIC_ID'],'dep_name'=>$rec_dep['DEP_NAME'],'id_db'=>$rec_dep['CHAPA_CLOUD_ID']));
        }
        $data = array('datachapa'=>$data_dep);
        echo json_encode($data);
        break;
        $baacChapa->checkAuth($request);
        case 'changePass' :
        $baacChapa->checkAuth($request);
        $sql = $db->query("SELECT * FROM M_USER WHERE ID_CARD_NO = '".$request['username']."' AND PASSWORD = '".$request['pass_old']."' ");
        $q = $db->db_fetch_array($sql);
        if(!empty($q['USER_ID'])){
           $update = $db->query("UPDATE M_USER SET PASSWORD = '".$request['pass_new']."' WHERE ID_CARD_NO = '".$request['username']."' ");
           $data_1 = array('status'=>'Y' ,'msg'=>'??????????????????');
         }else{
           $data_1 = array('status'=>'N' ,'msg'=>'username ???????????? password ??????????????????????????????');
         }

        $data = array('datachapa'=>$data_1);
        echo json_encode($data);
        break;
    }
