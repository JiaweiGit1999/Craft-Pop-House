function validateForm() {
  var password = document.forms["myForm"]["password"].value;
  var password2 = document.forms["myForm"]["re-enterpassword"].value;
  if (password != password2) {
    alert("Different password");
    return false;
  }
}