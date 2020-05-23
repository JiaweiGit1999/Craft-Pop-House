function DisplayProfileForm() {
  document.getElementById("edit").style.display = "none";
  document.getElementById("save").style.display = "contents";
}

function DisplayProfileInfo() {
  document.getElementById("edit").style.display = "contents";
  document.getElementById("save").style.display = "none";
}

function CheckProfileInfo() {
  var inputpass = document.forms["editform"]["editoldpass"].value;
  var actualpass = document.getElementById("userpass").innerHTML ;
  if (inputpass != actualpass) {
    alert("Wrong Password");
    return false;
  }
}