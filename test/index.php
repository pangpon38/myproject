<?php
include 'include/config.php';
include 'include/include.php';

$sql = "SELECT * FROM SYS_BASIC";
$exc = $db->query($sql);
$row = $db->db_fetch_array($exc);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>SMART CHAPA : ระบบงานฌาปนกิจสงเคราะห์</title>
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style1.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo $path; ?>bootstrap/js/jquery.js"></script>
    <script src="<?php echo $path; ?>js/linkage.js"></script>
</head>
<script>
function chkinput() {
    if ($('#user').val() == "") {
        alert("กรุณากรอกชื่อผู้ใช้งาน");
        $('#user').focus();
        return false;
    }
    if ($('#pass').val() == "") {
        alert("กรุณากรอกรหัสผ่าน");
        $('#pass').focus();
        return false;
    }
    if ($('#EWT_DB_NAME').val() == "") {
        alert("");
        $('#EWT_DB_NAME').focus();
        return false;
    }
}
</script>
<script>
$(document).ready(function() {

    $('.btn_idcard').click(function() {

        var result = readCardOffline()

        $('#response_code').html(result.status);
        if (result.status == '0000') {
            $('#user').val(result.data.idcard);
        } else {
            alert(result.message);
        }

    });

});
</script>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <div class="mt-5 mb-5  text-center">
                    <img src="images/logo_login.png" width="150" alt="Logo" /><br>
                    <span class="text">SMART CHAPA</span><br />
                    <span class="text2">ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร</span>
                    <br />
                    <span class="text2">
                        <h5><?php echo $row['DEP_NAME']; ?></h5>
                    </span>
                </div>
                <div class="login">
                    <form id="frm-input" method="post" action="login_portal.php" onsubmit="return chkinput();">
                        <div class="form-group">
                            <label for="user">ชื่อผู้ใช้งาน</label>
                            <input type="Username" class="form-control" name="user" id="user"
                                placeholder="ชื่อผู้ใช้" />
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">รหัสผ่าน</label>
                            <input type="password" class="form-control" name="pass" id="pass" placeholder="รหัสผ่าน" />
                            <input name="EWT_DB_NAME" value="baac_chapa" type="hidden">
                        </div>
                        <button type="submit" class="btn btn-submit btn-block">เข้าสู่ระบบ</button>
                        <button type="button" class="btn btn-submit btn-block btn_idcard">อ่านบัตรประชาชน</button>
                    </form>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>

    <div class="container">
        <div class="footer">สงวนลิขสิทธิ์ 2558 ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร</div>
    </div>

</body>

</html>