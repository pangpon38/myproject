let form = document.getElementById("frm-input");
let email = document.getElementById("exampleInputEmail1");
var btn_save = document.getElementById("btn_save");
form.addEventListener("submit",function (e) {
  e.preventDefault();
  let formData = new FormData(this);
  //console.log(formData);
  //for (const formElement of formData) {
    //console.log(formElement);
  //}

  fetch('process.php',{
    method: 'POST',
    body: formData,
  })
  .then(response => response.json())
  .then(data => console.log(data.exampleInputEmail1));
  });