<script>
    var arr_f_type = ["pdf", "doc", "docx"];

$('#CHAPA_FILE').on("change", function(e){
    var f_type = getfiletype(e.target.files[0].name);
    if ($.inArray(f_type.toLowerCase(), arr_f_type) == -1) {
      swal('','กรุณาแนบไฟล์ pdf,doc,docx เท่านั้น', "error")
       $('#CHAPA_FILE').val('');
    }
});

$('#WEBSITE_FILE').on("change", function(e){
    var f_type = getfiletype(e.target.files[0].name);
    if ($.inArray(f_type.toLowerCase(), arr_f_type) == -1) {
      swal('','กรุณาแนบไฟล์ pdf,doc,docx เท่านั้น', "error")
       $('#WEBSITE_FILE').val('');
    }
});


function getfiletype(filename){
    var parts = filename.split('.');
  return parts[parts.length - 1];
// }
</script>