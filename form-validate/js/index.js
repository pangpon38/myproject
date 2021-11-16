window.onload = function () {
  const form = document.getElementById("form");
  const username = document.getElementById("username");
  const email = document.getElementById("email");
  const password1 = document.getElementById("password1");
  const password2 = document.getElementById("password2");
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    checkinput_all([username,email, password1, password2]);
  
      if (ValidateEmail(email.value.trim()) == "valid") {
        showsuccess(email);
      } else {
        showerror(email, "อีเมลล์ไม่ถูกต้อง");
      }
  });
};

function showerror(input, messenge) {
  const formControl = input.parentElement;
  formControl.className = "form-control error";
  const messenge_txt = formControl.querySelector("small");
  messenge_txt.innerText = messenge;
  messenge_txt.style.visibility = "visible";
}

function showsuccess(input) {
  const formControl = input.parentElement;
  formControl.className = "form-control success";
  const messenge_txt = formControl.querySelector("small");
  messenge_txt.style.visibility = "hidden";
}

function ValidateEmail(val) {
  var email_format =
    "^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$";
  if (val.match(email_format)) {
    return "valid";
  } else {
    return "invalid";
  }
}

function checkinput_all(inputArray) {
  inputArray.forEach(function (input) {
    if (input.value.trim() === "") {
      showerror(input, "กรุณากรอกข้อมูล");
    } else {
      showsuccess(input);
    }
  });
}
