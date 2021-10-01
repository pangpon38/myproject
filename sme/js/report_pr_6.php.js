var url_process = "process/report_pr_6_process.php";
var url_form = "form_round.php";
var url = "product_out_report.php";
$(document).ready(function(){
						   
	if(isMobile.any() == "null"){				   
		$(window).scroll(function(){
			$('#myModal').css(
			{
				'margin-top': function () {
					return window.pageYOffset
				}
			}
			);
		});
	}
	tab_list($('#fbs').val());
});
function searchData(){
	$("#page").val(1);
	if($("#s_round_year_bud").val() == ""){
	alert("ระบุ "+$("#s_round_year_bud").attr('placeholder'));
	$("#s_round_year_bud").focus();
	return false;
	}
	if($("#s_month").val() == ""){
	alert("ระบุ "+$("#s_month").attr('placeholder'));
	$("#s_month").focus();
	return false;
	}
	if($("#e_month").val() == ""){
	alert("ระบุ "+$("#e_month").attr('placeholder'));
	$("#e_month").focus();
	return false;
	}
	
	$('#frm-search').attr('action','report_pr_6.php').submit();
}
function addData(){
	$("#proc").val("add");
	$("#frm-search").attr("action",url_form).submit();
}
function editData(id){
	$("#proc").val("edit");
	$("#ROUND_ID").val(id);
	$("#frm-search").attr("action",url_form).submit();
}
function delData(id,idt){
	if(confirm("ต้องการลบข้อมูลใช่หรือไม่ ?")){
		$("#proc").val("delete");
		$("#TOPIC_ID").val(id);
		$("#TEMPLATE_ID").val(idt);
		$("#frm-search").attr("action",url_process).submit();
	}
}
function chkinput(){
	if($("#YEAR").val() == ""){
		alert("ระบุ "+$("#YEAR").attr('placeholder'));
		$("#YEAR").focus();
		return false;
	}
	if($("#ROUND_NAME").val() == ""){
	alert("ระบุ "+$("#ROUND_NAME").attr('placeholder'));
	$("#ROUND_NAME").focus();
	return false;
	}
	if($("#ROUND_SDATE").val() == ""){
	alert("ระบุ "+$("#ROUND_SDATE").attr('placeholder'));
	$("#ROUND_SDATE").focus();
	return false;
	}
	if($("#ROUND_EDATE").val() == ""){
	alert("ระบุ "+$("#ROUND_EDATE").attr('placeholder'));
	$("#ROUND_EDATE").focus();
	return false;
	}
	if(confirm("กรุณายืนยันการบันทึกอีกครั้ง ?")){
		$("#frm-search").attr("action",url_process).submit();
	}
		
	
}
function get_org(e){
	if(e.value > 0 && $.trim(e.value) != ""){
		$.ajax({
			url: 'process/project_out_report_process.php',
			type: "POST",
			data:{proc:"get_org",ORG_TYPE_ID:e.value},
			success : function(data){  				
				$("#s_org_id").html(data);
				$('select').trigger('liszt:updated');
			}
		});
	}else{
		$("#s_org_id").html('<option value="">เลือก</option>');
	}
}
function get_strgy(e){
	if(e.value > 0 && $.trim(e.value) != ""){
		$.ajax({
			url: url_process,
			type: "POST",
			data:{proc:"get_strgy",STRGIC_ID:e.value},
			success : function(data){  				
				$("#s_strgy").html(data);
				$('select').trigger('liszt:updated');
			}
		});
	}else{
		$("#s_strgy").html('<option value="">เลือก</option>');
	}
}
function get_task_job(e){
	if(e.value > 0 && $.trim(e.value) != ""){
		$.ajax({
			url: url_process,
			type: "POST",
			data:{proc:"get_task_job",STRGIC_ID:e.value},
			success : function(data){  				
				$("#s_task_job").html(data);
				$('select').trigger('liszt:updated');
			}
		});
	}else{
		$("#s_task_job").html('<option value="">เลือก</option>');
	}
}
function excel_report(idr){
	if(idr==1){
	$("#frm-search").attr("action","report_pr_6_excel.php").submit();
	}else{
	$("#frm-search").attr("action","report_prd_6_excel.php").submit();	
	}
}
function word_report(idr){
	if(idr==1){
	$("#frm-search").attr("action","report_pr_6_word.php").submit();
	}else{
	$("#frm-search").attr("action","report_prd_6_word.php").submit();	
	}
}
function pdf_report(idr){
	if(idr==1){
	$("#frm-search").attr("action","report_pr_6_pdf.php").submit();
	}else{
	$("#frm-search").attr("action","report_prd_6_pdf.php").submit();	
	}
}
//เลือกจังหวัด อำเภอ ตำบล ต้องมีฟังชั่นก์Inint_AJAX,dochange
function Inint_AJAX() {
   try { return new ActiveXObject("Msxml2.XMLHTTP");  } catch(e) {} //IE
   try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} //IE
   try { return new XMLHttpRequest();          } catch(e) {} //Native Javascript
   alert("XMLHttpRequest not supported");
   return null;
};

function dochange(src, val) {
	 var req = Inint_AJAX();
	 var con = "";
	 
	if(src == "district"){ //ส่งค่า PROVINCE_CODE 
		 province_code = $('#PROVINCE_CODE').val();
		 //alert(province_code)
		 con = "&province_code="+province_code;
		 //alert(con);
	 }
	 
	 req.onreadystatechange = function () { 
		  if (req.readyState==4) {
			   if (req.status==200) {
					document.getElementById(src).innerHTML=req.responseText; //รับค่ากลับมา
			   } 
		  }
	 };
	 req.open("GET", "../all/province.php?data="+src+"&val="+val+con); //สร้าง connection
	 if(src == "amphur"){ //ถ้าเปลี่ยนจังหวัดใหม่ ให้ตำบล default เป็น 0
		 dochange("district", 0);
	 }
	 req.send(null); //ส่งค่า
}
function Inint_AJAX() {
   try { return new ActiveXObject("Msxml2.XMLHTTP");  } catch(e) {} //IE
   try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch(e) {} //IE
   try { return new XMLHttpRequest();          } catch(e) {} //Native Javascript
   alert("XMLHttpRequest not supported");
   return null;
};

function dochange_division(src, val) {
	 var req = Inint_AJAX();
	 var con = "";

	if(src == "division3"){ //ส่งค่า PROVINCE_CODE 
		 DIVISION1_ID = $('#DIVISION1_ID').val();
		 //alert(province_code)
		 con = "&division1_id="+DIVISION1_ID;
		 //alert(con);
	 }
	 
	 req.onreadystatechange = function () { 
		  if (req.readyState==4) {
			   if (req.status==200) {
					document.getElementById(src).innerHTML=req.responseText; //รับค่ากลับมา
			   } 
		  }
	 };
	 req.open("GET", "../all/division.php?data="+src+"&val="+val+con); //สร้าง connection
	 if(src == "division2"){ //ถ้าเปลี่ยนจังหวัดใหม่ ให้ตำบล default เป็น 0
		 dochange_division("division3", 0);
	 }
	 req.send(null); //ส่งค่า
}
//=======================================



function   select_item(i){

	if(document.getElementById('ACT_NO_'+i).checked ){
		document.getElementById('PREFIX_BENEFIT_'+i).disabled	= '';
		document.getElementById('F_NAME_'+i).disabled	= '';
		document.getElementById('L_NAME_'+i).disabled	= '';
		document.getElementById('RELATION_NAME_'+i).disabled	= '';
	}else{
		document.getElementById('PREFIX_BENEFIT_'+i).disabled	= 'disabled';
		document.getElementById('F_NAME'+i).disabled	= 'disabled';
		document.getElementById('L_NAME'+i).disabled	= 'disabled';
		document.getElementById('RELATION_NAME_'+i).disabled	= 'disabled';
	}
}

function dochange_division2(src, val) {
	 var req = Inint_AJAX();
	 var con = "";

	if(src == "division3_2"){ //ส่งค่า PROVINCE_CODE 
		 DIVISION1_2_ID = $('#DIVISION1_2_ID').val();
		 //alert(province_code)
		 con = "&division1_2_id="+DIVISION1_2_ID;
		 //alert(con);
	 }
	 
	 req.onreadystatechange = function () { 
		  if (req.readyState==4) {
			   if (req.status==200) {
					document.getElementById(src).innerHTML=req.responseText; //รับค่ากลับมา
			   } 
		  }
	 };
	 req.open("GET", "../all/division.php?data="+src+"&val="+val+con); //สร้าง connection
	 if(src == "division2_2"){ //ถ้าเปลี่ยนจังหวัดใหม่ ให้ตำบล default เป็น 0
		 dochange_division2("division3_2", 0);
	 }
	 req.send(null); //ส่งค่า
}

function edit_text(id){
	$("#proc").val("add");
	$("#member_id").val(id);
	$("#frm-search").attr("action","member_change_form.php").submit();
}

var isMobile = {
	Android: function() {
		return navigator.userAgent.match(/Android/i);
	},
	BlackBerry: function() {
		return navigator.userAgent.match(/BlackBerry/i);
	},
	iOS: function() {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	},
	Opera: function() {
		return navigator.userAgent.match(/Opera Mini/i);
	},
	Windows: function() {
		return navigator.userAgent.match(/IEMobile/i);
	},
	any: function() {
		return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	}
};