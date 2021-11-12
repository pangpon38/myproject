window.onload = function () {
  const form = document.getElementById("form");
  const username = document.getElementById("username");
  const email = document.getElementById("email");
  const password1 = document.getElementById("password1");
  const password2 = document.getElementById("password2");
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    if (username.value === "") {
      showerror(username, "กรุณาใส่ชื่อ");
    } else {
      showsuccess(username);
    }
  });
};

function showerror(input, messenge) {
  const formControl = input.parentElement;
  formControl.className = 'form-control error';
  const messenge_txt = formControl.querySelector("small");
  messenge_txt.innerText = messenge;
  messenge_txt.style.visibility = "visible";
}

function showsuccess(input) {
  const formControl = input.parentElement;
  formControl.className = 'form-control success';
  const messenge_txt = formControl.querySelector("small");
  messenge_txt.style.visibility = "hidden";
}
