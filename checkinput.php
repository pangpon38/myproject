<link rel="stylesheet" href="../webapp/css/sweetalert2.min.css">
<script src="../webapp/js/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function(){
$("button[type=submit]").prop("type", "button").click(function(){
  if($("#GUILD_NAME").val() == null){
    Swal.fire({icon: 'error',
              text:  'กรุณาเลือก ชื่อสมาคม'
            });
    return false;
  }
  if($("#MEMBER_YEAR").val() == null){
    Swal.fire({icon: 'error',
              text:  'กรุณาเลือก ปี'
            });
    return false;
  }
  if($("#MEMBER_SUM").val() == ""){
    Swal.fire({icon: 'error',
              text:  'กรุณาระบุ จำนวนสมาชิกทั้งหมด ณ สิ้นปี'
              });
    $("#MEMBER_SUM").focus();
    return false;
  }
  if($("#MEMBER_DEAD").val() == ""){
    Swal.fire({icon: 'error',
              text:  'กรุณาระบุ จำนวนสมาชิกที่เสียชีวิตทั้งหมด ณ สิ้นปี'
            });
    $("#MEMBER_DEAD").focus();
    return false;
  }
  if($("#MEMBER_ADVANCE").val() == ""){
    Swal.fire({icon: 'error',
              text:  'กรุณาระบุ อัตราเงินสงเคราะห์ศพละ'
            });
    $("#MEMBER_ADVANCE").focus();
    return false;
  }
  if($("#MEMBER_PAY_MONEY").val() == ""){
    Swal.fire({icon: 'error',
              text:  'กรุณาระบุ จำนวนเงินสงเคราะห์ค้างชำระทั้งหมด ณ สิ้นปี'
            });
    $("#MEMBER_PAY_MONEY").focus();
    return false;
  }
  if($("#MEMBER_PAYMENT").val() == ""){
    Swal.fire({icon: 'error',
              text:  'กรุณาระบุ จำนวนสมาชิกที่ค้างชำระทั้งหมด ณ สิ้นปี'
            });
    $("#MEMBER_PAYMENT").focus();
    return false;
  }
  var guild_name = $("#GUILD_NAME").val();
  var mem_year = $("#MEMBER_YEAR").val();
  var chk_dup = chk_dup_guild(guild_name,mem_year);
  if(chk_dup == 1){
     //alert("สมาคมได้กรอกข้อมูลของปี "+mem_year+" ซํ้า ซึ่งมีข้อมูลอยู่แล้ว");
     Swal.fire({
       icon: "error",
       title: "ขออภัย !",
       confirmButtonText: "ตกลง",
       text: "สมาคมได้กรอกข้อมูลของปี "+mem_year+" ซํ้า ซึ่งมีข้อมูลอยู่แล้ว"
     });
    return false;
  }else{
  //   var result = confirm("ยืนยันการบันทึก");
  // if(result == false){
  //   return false;
  //   }
  Swal.fire({
  title: 'ยืนยันการบันทึก?',
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  cancelButtonText: 'ยกเลิก',
  confirmButtonText: 'ตกลง'
}).then((result) => {
  if (result.isConfirmed) {
    $("#form_wf").submit();
  }else{
    return false;
  }
});
  }
 });
});

function chk_dup_guild(guild_name,mem_year){
	var returned = 0;
	$.ajax({
		url: "../webapp/chk_guild_dup.php",
		type: "POST",
		async: false,
		data: {
      guild_name : guild_name,
      mem_year : mem_year
    },
		success: function(data){
				if(data.STATUS == 'Y'){
					 returned = 1;
				}
		},
		error: function(data,thrown,error){
			console.log(error);
		}
	});
	return returned;
}
</script>
