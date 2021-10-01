<?php
header('Content-type: text/html; charset=utf-8');
$path = "../../../";
include $path . "include/config_header_top.php";
$url_back = "../payment2_disp.php";
$proc = $_POST['proc'];

$table1 = "F_RECEIPT";
$table2 = "F_RECEIPT_DE";
$table3 = "F_BOOK_DE";
$table4 = "M_MEMBER";
$Arr_income = getIncome();
$str_oder = array(",", " ");

switch ($proc) {
    case "add": {
            try {
                $db->db_begin();

                $sql_max = "select sys_id , year_work , rec_code_type" . $_POST['call_type'] . " new_code from sys_setup where active_status = 1";
                $query_max = $db->query($sql_max);
                $rec_max = $db->db_fetch_array($query_max);
                $new_code = $rec_max['new_code'] + 1;
                $sys_id = $rec_max['sys_id'];

                $year_work = substr($rec_max['year_work'], 2);
                if ($new_code == "") {
                    $new_code = 1;
                }

                $code_receipt = $new_code;
                $rec_code = $setup_call_type[$_POST['call_type']] . substr((date("Y") + 543), 2) . '-' . sprintf('%05d', $new_code);
                unset($fields);
                $sum_total = $_POST['pay_money'];

                if ($_POST['income_var'][1] > 0) {
                    $status_receipt = 1;
                } else {
                    $status_receipt = 2;
                }

                $fields = array(
                    "rec_code" => ctext($rec_code), //เลขที่ใบเสร็จ
                    "code_receipt" => $code_receipt, //รหัสรันใบเสร็จ
                    "date_receipt" => conv_date_db($_POST['date_receipt']),
                    "member_id" => $_POST['member_id'],
                    "pay_prefix_name" => ctext($_POST['pay_prefix_name']), //คำนาหน้าผู้ชำระ
                    "pay_fname" => ctext($_POST['pay_fname']),
                    "pay_lname" => ctext($_POST['pay_lname']),
                    "aut_id" => $_SESSION['sys_id'],
                    "rec_prefixname" => ctext($_POST['REC_PREFIX_NAME']),
                    "rec_fname" => ctext($_POST['REC_FNAME']),
                    "rec_lname" => ctext($_POST['REC_LNAME']),
                    "rec_posname" => ctext($_POST['REC_POSNAME']),
                    "money_sum" => $sum_total, //จำนวนเงินทั้งหมด
                    "remark" => ctext($_POST['REMARK']), //หมายเหตุ
                    "sum_total" => $sum_total,
                    "bath_text" => ctext(num2thai($sum_total)),
                    "status_use" => "1", //สถานะใยเสร็จ 1ใช้งาน 0เลิก
                    "remove_status" => "0", //สถานะลบใบเสร็จ 1 ลบ 0 ปกติ
                    "status_receipt" => $status_receipt, //สถานะใบเสร็จ 1 รับเงินค่าสมัคร 2 รับเงินสงเคราะห์
                    "rec_type" => "2",
                    "PAYIN_REFER" => ctext($_POST['payin_refer']), //ประเภทการรับเงิน 1 เงินค่าสมัคร 2 เงินอื่นๆ
                    "call_type" => $_POST['call_type'],
                    "create_by" => $_SESSION['sys_person_name'],
                    "create_datetime" => $DB_DATE_NOW,
                    "update_by" => $_SESSION['sys_person_name'],
                    "update_datetime" => $DB_DATE_NOW,
                    "confirm_status" => 1,
                    "rec_group" => 0,
                    "OLD_MONEY" => str_replace($str_oder, "", $_POST['have_money']),
                    "NEW_MONEY" => str_replace($str_oder, "", $_POST['txt_after_money']),
                    "bank_id" => $_POST['bank_id'],
                    "aut_user_id" => $_SESSION['aut_user_id']
                );

                $rec_id = $db->db_insert($table1, $fields, 'y');

                ///update เลขที่ใบเสร็จรับเงิน
                $fields_sys = array(
                    "rec_code_type" . $_POST['call_type'] => $new_code,
                );
                $db->db_update('sys_setup', $fields_sys, " sys_id = '" . $sys_id . "' ");

                //====table2======
                if (count($_POST['income_id']) > 0) {
                    foreach ($_POST['income_id'] as $key => $val) {

                        if ($_POST['income_var'][$val] > 0) {
                            $fields_2 = array(
                                "rec_id" => $rec_id,
                                "money" => trim(str_replace($str_oder, "", $_POST['income_var'][$val])),
                                "member_id" => $member_id,
                                "income_id" => $val,
                            );

                            $db->db_insert($table2, $fields_2);
                        }
                    }
                }

                if ($_POST['income_var'][1] > 0) {
                    $sql_update = "update m_member set PAY_MEMBER='" . $rec_id . "' where member_id = '" . $member_id . "'";
                    $db->query($sql_update);
                }

                $sql_update = "update m_member set deposit_money='" . str_replace($str_oder, "", $_POST['txt_after_money']) . "' where member_id = '" . $member_id . "'";
                $db->query($sql_update);

                $sql = "select deposit_money from m_member where member_id='" . $member_id . "' ";
                $deposit_money = $db->get_data_field($sql, "deposit_money");
                if ($deposit_money != str_replace($str_oder, "", $_POST['txt_after_money'])) {

                    $db->db_rollback();

                    $text = "พบข้อผิดพลาดที่ deposit_money ทำให้ไม่สามารถบันทึกได้ กรุณาลองใหม่อีกครั้ง";
                    break;
                }

                $sql_update_alert = "update M_LETTER_ALERT_DE set pay_status=1 where member_id = '" . $member_id . "'";
                $db->query($sql_update_alert);

                //ตรวจสอบการค้างจ่ายเงินสงเคราะห์ศพ
                if ($_POST['income_var'][$Arr_income['005']] > 0) {
                    $money_add = $_POST['income_var'][$Arr_income['005']];
                    $sql_arrear = "select
										*
									from
										M_ARREAR
									where
										MEMBER_ID ='" . $member_id . "' and
										FLAG_PAID = 0
									ORDER BY
										MEMBER_OUT_ID asc";
                    $query_arrear = $db->query($sql_arrear);
                    $nums_arrear = $db->db_num_rows($query_arrear);
                    if ($nums_arrear > 0) {
                        while ($rec_arrear = $db->db_fetch_array($query_arrear)) {
                            //มีเงินจ่ายทั้งหมด
                            if ($money_add >= $rec_arrear['ARREAR_MONEY'] && $money_add > 0) {

                                $money_add = $money_add - $rec_arrear['ARREAR_MONEY'];
                                $fields_arrear = array(
                                    "FLAG_PAID" => 1,
                                    "TRAN_DATE" => conv_date_db($_POST['date_receipt']),
                                    "TRAN_NO" => $rec_id,
                                    "rec_id" => $rec_id,
                                    "update_by" => $_SESSION['sys_person_name'],
                                    "update_date" => $DB_DATE_NOW,
                                );
                                $db->db_update('M_ARREAR', $fields_arrear, " ARREAR_ID = '" . $rec_arrear['ARREAR_ID'] . "' ");
                            } else if ($money_add < $rec_arrear['ARREAR_MONEY'] && $money_add > 0) {

                                $fields_arrear = array(
                                    "FLAG_PAID" => 1,
                                    "ARREAR_MONEY" => $money_add,
                                    "TRAN_DATE" => conv_date_db($_POST['date_receipt']),
                                    "TRAN_NO" => $rec_id,
                                    "rec_id" => $rec_id,
                                    "update_by" => $_SESSION['sys_person_name'],
                                    "update_date" => $DB_DATE_NOW,
                                );
                                $db->db_update('M_ARREAR', $fields_arrear, " ARREAR_ID = '" . $rec_arrear['ARREAR_ID'] . "' ");

                                /////เพิ่มการชำระไม่ได้เต็มจำนวน
                                $fields_arrear_add = array(
                                    "MEMBER_ID" => $rec_arrear['MEMBER_ID'],
                                    "MEMBER_OUT_ID" => $rec_arrear['MEMBER_OUT_ID'],
                                    "MEM_DIED_ID" => $rec_arrear['MEM_DIED_ID'],
                                    "FLAG_PAID" => 0,
                                    "ARREAR_MONEY" => ($rec_arrear['ARREAR_MONEY'] - $money_add),
                                    "TRAN_DATE" => conv_date_db($_POST['date_receipt']),
                                    "TRAN_NO" => $rec_id,
                                    "rec_id" => $rec_id,
                                    "create_by" => $_SESSION['sys_person_name'],
                                    "update_by" => $_SESSION['sys_person_name'],
                                    "create_date" => $DB_DATE_NOW,
                                    "update_date" => $DB_DATE_NOW,
                                );

                                $db->db_insert("M_ARREAR", $fields_arrear_add);
                                $money_add = 0;
                            } else {
                                break;
                            }
                        }
                    }
                }

                unset($fields_book);
                //ในสมุดมีข้อมูลแล้ว
                $sql_dead_start = "select max(dead_end) dead_start from F_BOOK_DE where member_id = '" . $member_id . "' AND cancel_check not in (1)";
                $dead_start = $db->get_data_field($sql_dead_start, "dead_start");
                if (trim($dead_start)) {
                    $dead_start += 1;
                }

                //ในสมุดมียังไม่
                if (trim($dead_start) == '') {
                    $sql_dead_start = "select
											min(dead_number_all) dead_start
										from
											M_MEMBER_OUT
											join  M_ARREAR on M_MEMBER_OUT.member_out_id  = M_ARREAR.MEMBER_OUT_ID
										where
											M_ARREAR.MEMBER_ID = '" . $member_id . "'
											AND M_ARREAR.FLAG_PAID =1";
                    $dead_start = $db->get_data_field($sql_dead_start, "dead_start");
                }

                $sql_dead_end = "select
									max(dead_number_all) dead_end
								from
									M_MEMBER_OUT
									join  M_ARREAR on M_MEMBER_OUT.member_out_id  = M_ARREAR.MEMBER_OUT_ID
								where
									M_ARREAR.MEMBER_ID = '" . $member_id . "'
									AND M_ARREAR.FLAG_PAID=1";
                $dead_end = $db->get_data_field($sql_dead_end, "dead_end");

                if (trim($dead_start - 1) == trim($dead_end) && trim($dead_start)) {
                    $dead_start = "";
                    $dead_end = "";
                }

                if (trim($dead_start)) {
                    $sql_dead_end = "select
										sum(M_ARREAR.ARREAR_MONEY) dead_money
									from
										M_MEMBER_OUT
										join M_ARREAR on M_MEMBER_OUT.member_out_id = M_ARREAR.MEMBER_OUT_ID
									where
										M_ARREAR.MEMBER_ID='" . $member_id . "'
										and dead_number_all>='" . $dead_start . "' and dead_number_all<='" . $dead_end . "'
										and M_ARREAR.FLAG_PAID=1";
                    $dead_money = $db->get_data_field($sql_dead_end, "dead_money");
                }

                $fields_book = array(
                    "member_id" => $_POST['member_id'],
                    "rec_id" => $rec_id,
                    "old_money" => str_replace($str_oder, "", $_POST['have_money']),
                    "new_money" => str_replace($str_oder, "", $_POST['txt_after_money']),
                    "advance_money" => str_replace($str_oder, "", $_POST['income_var'][$Arr_income['004']]),
                    "dead_money" => str_replace($str_oder, "", $_POST['income_var'][$Arr_income['005']]),
                    "fee_year" => str_replace($str_oder, "", $_POST['income_var'][$Arr_income['002']]),
                    "dead_start" => $dead_start,
                    "dead_end" => $dead_end,
                    "dead_money" => $dead_money,
                    "create_by" => $_SESSION['sys_person_name'],
                    "update_by" => $_SESSION['sys_person_name'],
                    "create_date" => $DB_DATE_NOW,
                    "update_date" => $DB_DATE_NOW,
                );
                $book_id = $db->db_insert($table3, $fields_book, "y");

                $fields_book = array(
                    "book_id" => $book_id,
                );
                $db->db_update($table1, $fields_book, " rec_id = '" . $rec_id . "' ");

                $text = $save_proc;

                $db->db_commit();
            } catch (Exception $e) {
                $db->db_rollback();
            }
        }
        break;
    case "get_tem_list": {
            unset($arr_project);
            $arr_project['1'] = "เงินสด";
            $arr_project['2'] = "ผ่านเคาเตอร์ธนาคาร";
?>

            <select name="call_type[]" class="selectbox form-control" id="call_type_<?php echo $_POST['id_tb']; ?>" placeholder="รายการชำระ " style="width:200px">
                <?php
                foreach ($arr_project as $key => $val) {
                ?>
                    <option value="<?php echo $key ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
        <?php

        }
        break;

    case "get_mem": {

            $sql_mem = "SELECT member_id, m_cate_id, m_cate_name, member_no, prefix_id,prefix_name, fname, lname ,deposit_money FROM V_MEMBER WHERE member_id = '" . $_POST['pop_member_id'] . "'";
            $query_mem = $db->query($sql_mem);
            $rec_mem = $db->db_fetch_array($query_mem);

        ?>
            <?php
            if ($_POST['col'] == '1') { ?>
                <div id="member_no_n_<?php echo $_POST['id_tb']; ?>" align="center">
                    <?php echo disp_member_code($rec_mem['member_no']); ?></div>
                <input type="hidden" id="member_id_<?php echo $_POST['id_tb']; ?>" name="member_id[]" value="<?php echo $rec_mem['member_id']; ?>">
                <input type="hidden" id="prefix_name_<?php echo $_POST['id_tb']; ?>" name="pay_prefix_name[]" value="<?php echo text($rec_mem['prefix_name']); ?>">
                <input type="hidden" id="pay_fname_<?php echo $_POST['id_tb']; ?>" name="pay_fname[]" value="<?php echo text($rec_mem['fname']); ?>">
                <input type="hidden" id="pay_lname_<?php echo $_POST['id_tb']; ?>" name="pay_lname[]" value="<?php echo text($rec_mem['lname']); ?>">
                <input type="hidden" id="deposit_money_<?php echo $_POST['id_tb']; ?>" name="deposit_money[]" value="<?php echo text($rec_mem['deposit_money']); ?>">
            <?php
            } else if ($_POST['col'] == '2') { ?>
                <div id="full_name_n_<?php echo $_POST['id_tb']; ?>" align="left">
                    <?php echo text($rec_mem['prefix_name'] . $rec_mem['fname'] . " " . $rec_mem['lname']); ?></div>
            <?php
            } else if ($_POST['col'] == '3') { ?>
                <div id="deposit_money_html_<?php echo $_POST['id_tb']; ?>" align="center">
                    <?php echo number_format($rec_mem['deposit_money'], 2); ?></div>
    <?php
            }
        }
        break;

    case "delete": {
            $sql_rec = "SELECT member_id,rec_type   FROM F_RECEIPT WHERE rec_id = '" . $_POST['rec_id'] . "'";
            $query_rec = $db->query($sql_rec);
            $rec = $db->db_fetch_array($query_rec);
            //ค่าสมัคร
            if ($rec['rec_type'] == 1) {
                $rec['member_id'];
                $fields_up['PAY_MEMBER'] = 0;
                $fields_up['deposit_money'] = 0;

                $db->db_update('M_MEMBER', $fields_up, " member_id = '" . $rec['member_id'] . "' ");
            }
            $db->db_delete($table2, " rec_id = '" . $_POST['rec_id'] . "' ");
            $db->db_delete($table1, " rec_id = '" . $_POST['rec_id'] . "' ");
            $text = $del_proc;
            break;
        }
    case "cancel": {
            $url_back = "../approve_cancel.php";

            $sql_memid = "select member_id,remark_cancel,date_receipt,book_id from F_RECEIPT where rec_id = '" . $_POST['rec_id'] . "'";
            $query_memid = $db->query($sql_memid);
            $rec_memid = $db->db_fetch_array($query_memid);
            $remark_cancel = $rec_memid['remark_cancel'];
            $date_rec = $rec_memid['date_receipt'];

            $sql_fee_reg = " select sum(money)  money from F_RECEIPT_DE where  rec_id = '" . $_POST['rec_id'] . "'
				and income_id in (
				'" . $Arr_income['001'] . "') ";
            $query_fee_reg = $db->query($sql_fee_reg);
            $rec_fee_reg = $db->db_fetch_array($query_fee_reg);
            $money_regis = $rec_fee_reg['money'];

            //////หาว่าจ่ายไปทั้งหมดเท่าไร
            $sql_rec = " select sum(money)  money from F_RECEIPT_DE where  rec_id = '" . $_POST['rec_id'] . "'
				and income_id in (
				'" . $Arr_income['003'] . "',
				'" . $Arr_income['004'] . "',
				'" . $Arr_income['005'] . "'
				) ";
            $query_rec = $db->query($sql_rec);
            $rec = $db->db_fetch_array($query_rec);
            $money_pay = $rec['money'];
            $member_id = $rec_memid['member_id'];

            $sql_member = "SELECT member_id,deposit_money   FROM M_MEMBER WHERE member_id = '" . $member_id . "'";
            $query_member = $db->query($sql_member);
            $rec_mem = $db->db_fetch_array($query_member);
            if ($money_pay == '') {
                $money_pay = 0;
            }
            if ($rec_mem['deposit_money'] == '') {
                $rec_mem['deposit_money'] = 0;
            }
            $deposit_money_remain = $rec_mem['deposit_money'] - $money_pay;

            if ($deposit_money_remain < 0) {
                //เอาเงินคืนจ่าย ศพ
                $sql_2 = " select sum(money)  money from F_RECEIPT_DE where  rec_id = '" . $_POST['rec_id'] . "'
				and income_id in ('" . $Arr_income['005'] . "') ";
                $query_2 = $db->query($sql_2);
                $rec2 = $db->db_fetch_array($query_2);
                $money_pay2 = $rec2['money'];

                if ($rec_mem['deposit_money'] < 0 && $deposit_money_remain < 0) {
                    $money_pay2 = $money_pay;
                } else {
                    $money_pay2 = abs($deposit_money_remain);
                }
                //////เริ่มวนเงินคืน
                $sql_arrear = "select sum(ARREAR_MONEY)  ARREAR_MONEY from  M_ARREAR where MEMBER_ID ='" . $member_id . "' and  FLAG_PAID = 1
							and  STATUS_PAID_DIED = 0 and   rec_id = '" . $_POST['rec_id'] . "' ";
                $query_arrear = $db->query($sql_arrear);
                $rec_arrear = $db->db_fetch_array($query_arrear);
                if ($rec_arrear['ARREAR_MONEY'] == $money_pay2) { //จำนวนเท่ากันพอดี    กับใบเสร็จ
                    $fields_arrear['FLAG_PAID'] = '0';
                    $wheer_arrear = "MEMBER_ID ='" . $member_id . "' and  FLAG_PAID = 1
								and  STATUS_PAID_DIED = 0 and   rec_id = '" . $_POST['rec_id'] . "'";
                    $db->db_update("M_ARREAR", $fields_arrear, $wheer_arrear);
                } else { ///นวนไม่พอ
                    //จำนวนเงิน
                    $sql_arrear2 = "select  *
					from  M_ARREAR where MEMBER_ID ='" . $member_id . "' and  FLAG_PAID = 1
							and  STATUS_PAID_DIED = 0
					ORDER BY    MEMBER_OUT_ID  desc ";
                    $query_arrear2 = $db->query($sql_arrear2);
                    $money_add = $money_pay2;
                    while ($rec_arrear2 = $db->db_fetch_array($query_arrear2)) {

                        if ($money_add >= $rec_arrear2['ARREAR_MONEY'] && $money_add > 0) {
                            $rec_arrear2['ARREAR_MONEY'];

                            unset($fields_arrear_add);
                            $fields_arrear = array(
                                "FLAG_PAID" => 0,
                                "TRAN_NO" => 0,
                                "rec_id" => 0,
                                "update_by" => $_SESSION['sys_person_name'],
                                "update_date" => $DB_DATE_NOW,
                            );
                            $db->db_update('M_ARREAR', $fields_arrear, " ARREAR_ID = '" . $rec_arrear2['ARREAR_ID'] . "' ");

                            $money_add = $money_add - $rec_arrear2['ARREAR_MONEY'];
                        } else if ($money_add < $rec_arrear2['ARREAR_MONEY'] && $money_add > 0) {
                            //จำนวนเงินน้อยกว่าที่จะเอาคืน

                            /////ดึงคืนได้ไม่เต็มจำนวน
                            //$money_add = $money_add-$rec_arrear2['ARREAR_MONEY'];
                            unset($fields_arrear_add);
                            $fields_arrear = array(
                                "FLAG_PAID" => 0,
                                "ARREAR_MONEY" => $money_add,
                                "TRAN_NO" => 0,
                                "rec_id" => 0,
                                "update_by" => $_SESSION['sys_person_name'],
                                "update_date" => $DB_DATE_NOW,
                            );
                            $db->db_update('M_ARREAR', $fields_arrear, " ARREAR_ID = '" . $rec_arrear2['ARREAR_ID'] . "' ");

                            unset($fields_arrear_add);
                            $fields_arrear_add = array(
                                "MEMBER_ID" => $member_id,
                                "FLAG_PAID" => 1,
                                "ARREAR_MONEY" => ($rec_arrear2['ARREAR_MONEY'] - $money_add),
                                "TRAN_DATE" => conv_date_db($date_rec),
                                "TRAN_NO" => 0,
                                "rec_id" => 0,
                                "MEMBER_OUT_ID" => $rec_arrear2['MEMBER_OUT_ID'],
                                "MEM_DIED_ID" => $rec_arrear2['MEM_DIED_ID'],
                                "create_by" => $_SESSION['sys_person_name'],
                                "update_by" => $_SESSION['sys_person_name'],
                                "create_date" => $DB_DATE_NOW,
                                "update_date" => $DB_DATE_NOW,
                            );
                            $db->db_insert("M_ARREAR", $fields_arrear_add);

                            //echo "dfgdfgdf";
                            $money_add = $money_add - $rec_arrear2['ARREAR_MONEY'];
                        } else {
                            break;
                        }
                    }
                    $money_add;

                    if ($money_add > 0) { //เงินค้างยังเหลืออยู่ให้ติดลบทางสมาคม
                        unset($fields_arrear_add);
                        $fields_arrear_add = array(
                            "MEMBER_ID" => $member_id,
                            "FLAG_PAID" => 0,
                            "ARREAR_MONEY" => $money_add,
                            "MEMBER_OUT_ID" => 0,
                            "MEM_DIED_ID" => 0,
                            "TRAN_NO" => $rec_id,
                            "rec_id" => $rec_id,
                            "create_by" => $_SESSION['sys_person_name'],
                            "update_by" => $_SESSION['sys_person_name'],
                            "create_date" => $DB_DATE_NOW,
                            "update_date" => $DB_DATE_NOW,
                        );
                        $db->db_insert("M_ARREAR", $fields_arrear_add);
                    }
                }
            }

            $fields = array(
                "status_use" => "0", //สถานะใยเสร็จ 1ใช้งาน 0เลิก
                "remark_cancel" => ctext($remark_cancel),
                "approve_date" => $DB_DATE_NOW,
                "date_cancel" => date("Y-m-d"),
                "update_by" => $_SESSION['sys_person_name'],
                "update_datetime" => $DB_DATE_NOW,
                "approve_status" => "2", //อนุมัติ
                "approve_by" => $_SESSION['sys_person_name'],
                "approve_print" => 0

            );
            $db->db_update($table1, $fields, " rec_id = '" . $_POST['rec_id'] . "' ");

            $fields_member = array(
                "deposit_money" => $deposit_money_remain, //สถานะใยเสร็จ 1ใช้งาน 0เลิก
                "update_by" => $_SESSION['sys_person_name'],
                "update_datetime" => $DB_DATE_NOW,

            );

            //ถ้าจ่ายค่าสมัครมาให้เอาออก
            if ($money_regis > 0) {
                $fields_member['PAY_MEMBER'] = 0;
            }
            $db->db_update($table4, $fields_member, " member_id = '" . $member_id . "' ");

            //cancel_print//
            $field_cancel_print = array(
                "cancel_check" => "1",
            );

            $db->db_update($table3, $field_cancel_print, " book_de_id = '" . $rec_memid['book_id'] . "' ");
            //    $db->db_delete($table3," rec_id = '".$_POST['rec_id']."' ");

            $sql_cancel = "select * from  F_BOOK_DE where book_de_id = '" . $rec_memid['book_id'] . "' ";
            $query_cancel = $db->query($sql_cancel);
            $rec_cancel = $db->db_fetch_array($query_cancel);

            $fields_cancel = array(
                "member_id" => $rec_cancel['member_id'],
                "rec_id" => $rec_cancel['rec_id'],
                "old_money" => $rec_cancel['old_money'],
                "new_money" => $rec_cancel['new_money'],
                "advance_money" => $rec_cancel['advance_money'],
                "dead_money" => $rec_cancel['dead_money'],
                "fee_year" => $rec_cancel['fee_year'],
                "dead_start" => $rec_cancel['dead_start'],
                "dead_end" => $rec_cancel['dead_end'],
                "dead_money" => $rec_cancel['dead_money'],
                "create_by" => $_SESSION['sys_person_name'],
                "update_by" => $_SESSION['sys_person_name'],
                "create_date" => $DB_DATE_NOW,
                "update_date" => $DB_DATE_NOW,
                "cancel_check" => '1',
                "book_de_id_cancel" => $rec_memid['book_id'],
            );
            $db->db_insert($table3, $fields_cancel);

            $text = "ยกเลิกข้อมูลเรียบร้อย";
            break;
        }
    case "edit": {
            try {
                unset($fields);
                if ($_POST['call_type'] == 1) {
                    $bank_id = $_POST['cash_bank_id'];
                } else {
                    $bank_id = $_POST['bank_id'];
                }
                $fields = array(
                    "member_id" => $_POST['pay_member_id'],
                    "rec_code" => ctext($rec_code),
                    "code_receipt" => $code_receipt,
                    "date_receipt" => conv_date_db($_POST['date_receipt']),
                    "call_type" => $_POST['call_type'],
                    "bank_id" => $_POST['bank_id'],
                    "bank_time" => $_POST['bank_time'],
                    "rec_type" => $_POST['rec_type'],
                    "pay_prefix_name" => ctext($_POST['pay_prefix_id']),
                    "pay_fname" => ctext($_POST['pay_fname']),
                    "pay_lname" => ctext($_POST['pay_lname']),
                    "remark" => ctext($_POST['remark']),
                    "rec_prefixname" => ctext($_POST['h_rec_prefixname']),
                    "rec_fname" => ctext($_POST['rec_fname']),
                    "rec_lname" => ctext($_POST['rec_lname']),
                    "rec_posname" => ctext($_POST['rec_posname']),
                    "money_sum" => $money_sum,
                    "status_use" => "1", //สถานะใยเสร็จ 1ใช้งาน 0เลิก
                    "remove_status" => "0", //สถานะลบใบเสร็จ 1 ลบ 0 ปกติ
                    "update_by" => $_SESSION['sys_person_name'],
                    "update_datetime" => $DB_DATE_NOW,
                    "tel_id" => ($_POST['TEL_ID']),
                    "bank_transfer" => $_POST['bank_transfer'],
                    "source_acc" => ctext($_POST['source_acc']),

                );
                $db->db_update($table1, $fields, " rec_temp_id = '" . $_POST['rec_temp_id'] . "' ");

                //====table2======
                //count($_POST['id_item'])
                if (count($_POST['member_id']) > '0') {
                    $db->db_delete($table2, " rec_temp_id = '" . $_POST['rec_temp_id'] . "' ");
                    foreach ($_POST['member_id'] as $key => $value) {
                        $fields_2 = array(
                            "rec_temp_id" => $_POST['rec_temp_id'],
                            "rec_temp_list" => $_POST['rec_temp_list'][$key],
                            "member_id" => $value,
                            "money" => str_replace(',', '', $_POST['money'][$key]),
                            "rec_temp_desc" => ctext($_POST['rec_temp_desc'][$key]),
                        );

                        $db->db_insert($table2, $fields_2);
                    }
                }

                $text = $edit_proc;
            } catch (Exception $e) {
                $text = $e->getMessage();
            }
        }
        break;
    case "re_print": {
            $fields = array(
                "approve_print" => "1",
                "update_by" => $_SESSION['sys_person_name'],
                "update_datetime" => $DB_DATE_NOW,

            );
            $db->db_update($table1, $fields, " rec_id = '" . $_POST['rec_id'] . "' ");
            $url_back = "../receipt_approve_print_disp.php";
            $text = "อนุมัติพิมพ์ซ่อมใบเสร็จเรียบร้อยแล้ว";
        }
        break;
    case "re_print_all": {

            if (count($_POST['chk_rec_id']) > 0) {
                foreach ($_POST['chk_rec_id'] as $v) {
                    $fields = array(
                        "approve_print" => "1",
                        "update_by" => $_SESSION['sys_person_name'],
                        "update_datetime" => $DB_DATE_NOW,

                    );
                    $db->db_update($table1, $fields, " rec_id = '" . $v . "' ");
                }
            }
            $url_back = "../receipt_approve_print_disp.php";
            $text = "อนุมัติพิมพ์ซ่อมใบเสร็จเรียบร้อยแล้ว";
        }
        break;
    case "approve": {
            $url_back = "../receipt_cancel_disp.php";

            $fields = array(
                "remark_cancel" => ctext($_POST['remark_cancel']),
                "date_cancel" => $DB_DATE_NOW,
                "aut_approve" => $_SESSION['sys_person_name'],
                "approve_status" => "1", //รออนุมัติ
            );
            $db->db_update($table1, $fields, " rec_id = '" . $_POST['rec_id'] . "' ");
            $text = "คำร้องยกเลิกข้อมูลสำเร็จ กรุณาอนุมัติเพื่อยกเลิกข้อมูล";
        }
        break;
    case "noapprove": {
            $url_back = "../approve_cancel.php";

            $fields = array(
                "approve_date" => $DB_DATE_NOW,
                "update_by" => $_SESSION['sys_person_name'],
                "approve_status" => "3", //ไม่อนุมัติ
                "approve_by" => $_SESSION['sys_person_name'],
            );
            $db->db_update($table1, $fields, " rec_id = '" . $_POST['rec_id'] . "' ");
            $text = "ไม่อนุมัติสำเร็จ";
        }
        break;
}
if ($proc == 'add' || $proc == 'edit' || $proc == 'delete' || $proc == 'cancel' || $proc == 're_print' || $proc == 're_print_all' || $proc == 'approve' || $proc == 'noapprove') {
    ?>
    <meta charset="utf-8" />
    <form name='form_back' method="post" action="<?php echo $url_back; ?>">
        <input type="hidden" id="proc" name="proc" value="<?php echo $proc; ?>" />
        <input type="hidden" id="menu_id" name="menu_id" value="<?php echo $menu_id; ?>" />
        <input type="hidden" id="menu_sub_id" name="menu_sub_id" value="<?php echo $menu_sub_id ?>" />
        <input type="hidden" id="rec_id" name="rec_id" value="<?php echo $rec_id; ?>" />
        <input type="hidden" id="print_form" name="print_form" value="<?php echo $_POST['print_form']; ?>" />
    </form>
    <script>
        alert('<?php echo $text; ?>');
        form_back.submit();
    </script>
<?php } ?>