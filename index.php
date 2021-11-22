<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="js/jquery-3.6.0.min.js"></script>
    <script src="js/jquery.inputmask.js"></script>
</head>

<body>
    <p>If you click on me, I will disappear.</p>
    <p>Click me away!</p>
    <p>Click me too!</p>
    <input type="text" name="tel" id="tel" class="form-control tel">
</body>

</html>

<script type="text/javascript">
    $(document).ready(function() {
        $("p").click(function() {
            $(this).hide();
        });
        alert("asdasd");

        $(".tel").each(function () {
    //เลขประชาชน
    $("#" + $(this).attr("id")).inputmask("99-999-9-99999-9");
  });
    });
</script>