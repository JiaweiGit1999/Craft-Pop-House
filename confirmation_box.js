function validate()
{
	var string = document.getElementById("submit").value;
	var question;
	if(string == 'Delete')
		question = "Confirm delete this product?";
	else if(string == 'Edit')
		question = "Confirm edit this product?";
	else
		question = "Confirm add this product?";
	var confirmation = confirm(question);
	if(confirmation)
		return true;
	else
		return false;
}
