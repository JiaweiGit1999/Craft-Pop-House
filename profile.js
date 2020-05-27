function DisplayProfileForm() {
  document.getElementById("edit").style.display = "none";
  document.getElementById("save").style.display = "contents";
  document.getElementById("profileorderstable").style.display = "none";
}

function DisplayProfileInfo() {
  document.getElementById("edit").style.display = "contents";
  document.getElementById("save").style.display = "none";
  document.getElementById("profileorderstable").style.display = "none";
}

function DisplayOrders() {
  document.getElementById("edit").style.display = "none";
  document.getElementById("save").style.display = "none";
  document.getElementById("profileorderstable").style.display = "contents";
}

function CheckProfileInfo() {
  var inputpass = document.forms["editform"]["editoldpass"].value;
  var actualpass = document.getElementById("userpass").innerHTML ;
  if (inputpass != actualpass) {
    alert("Wrong Password");
    return false;
  }
}