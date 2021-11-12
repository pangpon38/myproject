var arr_f_type = ["pdf", "doc", "docx"];

$('#LAWS_FILES').on("change", function(e){
  var test1 = e.target.files;
  console.log(test1);
  var files = $('#LAWS_FILES')[0].files;
  // console.log("Files uploaded:", files);
  $.each(files, function(){
    console.log($(this)[0].name);
  });
  // var f_type = getfiletype(e.target.files[0].name);
  // var f_size = conv_filesize(e.target.files[0].size);
  // if ($.inArray(f_type.toLowerCase(), arr_f_type) == -1) {
  //   swal('','กรุณาแนบไฟล์ pdf,doc,docx เท่านั้น', "error")
  //    $('#LAWS_FILES').val('');
  //    return false;
  // }
  // if(f_size > 5){
  //   swal('','ขนาดไฟล์เกิน 5MB กรุณาเลือกไฟล์ใหม่', "error")
  //   $('#LAWS_FILES').val('');
  //   return false;
  // }
});

function getfiletype(filename){
  var parts = filename.split('.');
return parts[parts.length - 1];
}

function conv_filesize(filesize){
  var conv_size = (filesize/ (1024*1024)).toFixed(2);
  return conv_size;
}