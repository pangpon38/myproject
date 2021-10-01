$(document).ready(function () {
    if (isMobile.any() == "null") {
      $(window).scroll(function () {
        $("#myModal").css({
          "margin-top": function () {
            return window.pageYOffset;
          },
        });
      });
    }
    tab_list($("#fbs").val());
  });
  function excel_report(idr) {
    if (idr == 1) {
      $("#frm-search").attr("action", "report_24_excel.php").submit();
    } else {
      $("#frm-search").attr("action", "report_24_excel.php").submit();
    }
  }
  function word_report(idr) {
    if (idr == 1) {
      $("#frm-search").attr("action", "report_24_word.php").submit();
    } else {
      $("#frm-search").attr("action", "report_24_word.php").submit();
    }
  }
  function pdf_report(idr) {
    if (idr == 1) {
      $("#frm-search").attr("action", "report_24_pdf.php").submit();
    } else {
      $("#frm-search").attr("action", "report_24_pdf.php").submit();
    }
  }