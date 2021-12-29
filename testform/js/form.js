let form = document.getElementById("frm-input");
let email = document.getElementById("exampleInputEmail1");
var btn_save = document.getElementById("btn_save");
let span = document.getElementById("test_html");
form.addEventListener("submit",function (e) {
  e.preventDefault();
  let formData = new FormData(this);

  fetch('process.php',{
    method: 'POST',
    body: formData,
  })
  .then(response => response.text())
  .then(data => span.innerHTML = data);
  });