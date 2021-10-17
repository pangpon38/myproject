<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validate</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/index.js"></script>
</head>

<body>
    <div class="container">
        <form action="" id="form" name="form">
            <h2>แบบฟอร์ม</h2>
            <div class="form-control">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" name="username" id="username" placeholder="ชื่อผู้ใช้">
                <small>error</small>
            </div>

            <div class="form-control">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" placeholder="อีเมลล์">
                <small>error</small>
            </div>

            <div class="form-control">
                <label for="password1">รหัสผ่าน</label>
                <input type="password" name="password1" id="password1" placeholder="password">
                <small>error</small>
            </div>

            <div class="form-control">
                <label for="password2">ยืนยันรหัสผ่าน</label>
                <input type="password" name="password2" id="password2" placeholder="re-password">
                <small>error</small>
            </div>
            <button type="submit">ลงทะเบียน</button>
        </form>
    </div>
</body>

</html>