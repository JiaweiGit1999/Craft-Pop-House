<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<script src="loginpage.js"></script>
<body>

<header>
	<img src="pic/logo.png" alt="logo" id="logo">
	<div id="title"> | Craft Pop House</div>
	<form>
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar">
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
	<div class="loginbox">
		<a href="login.php" class="loginbutton">Login</a>
	</div>
</header>
<h1>Register Page</h1>';
$passnoti = " ";

Function connect(){
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "dp2";
	
	// Create connection
	$conn = new mysqli($servername, $username, $password,$dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	return $conn;
}
$conn = connect();
$sql = "SELECT name, price, quantity,img FROM products";
$result = $conn->query($sql);
$keyword1;

//Search Bar Function
if ( isset( $_REQUEST['searchbar'] ) ) {
	$searchArray = explode(" ",$_REQUEST['searchbar']);
	$sql = "SELECT name, price, description, quantity,img, ";

	for ($i = 0; $i < count($searchArray); $i++) {
	$sql .= "ROUND ((LENGTH(description) - LENGTH( REPLACE ( description, '$searchArray[$i]', '') ) ) / LENGTH('$searchArray[$i]'))
	+ROUND ((LENGTH(name)-LENGTH( REPLACE ( name, '$searchArray[$i]', '') ) ) / LENGTH('$searchArray[$i]')) ";
	if($i !=count($searchArray)-1)
		$sql .= "+ ";
}
	$sql .= "AS Count FROM products WHERE description REGEXP '";
	for ($i = 0; $i < count($searchArray); $i++) {
		$sql .= "$searchArray[$i]";
		if($i !=count($searchArray)-1)
			$sql .= "|";
	}	
	$sql .= "' or name REGEXP '";
	for ($i = 0; $i < count($searchArray); $i++) {
		$sql .= "$searchArray[$i]";
		if($i !=count($searchArray)-1)
			$sql .= "|";
	}	
	$sql .="' ORDER BY count desc ";
	$result = $conn->query($sql);
}

		
echo'<form id="loginform" onsubmit="return validateForm()" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" name="myForm">
	<img id="image" src="pic/no-image.jpg" height="100" width="100" id="registerphoto"/>
	<input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);" /*required="required"*//><br><br>
	<label for="username">Username: </label><br>
	<input type="text" name="username" class="logininput" ><br><br>
	<label for="password">Password: </label><br>
	<input type="text" name="password" class="logininput" ><br>
	<label for="re-enterpassword">Re-enter Password: </label><br>
	<input type="text" name="re-enterpassword" class="logininput" >
	<p id="errortxt"></p><br>
	<label for="email">Email: </label><br>
	<input type="text" name="email" class="logininput" ><br><br>
	<label for="address">Address: </label><br>
	<input type="text" name="address" class="logininput" ><br><br>
	<input type="submit" value="Register" id="register" name="register">
</form>
</body>
</html>';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	test_input($_POST["username"]);
	test_input($_POST["email"]);
}

function test_input($data) {
	$match = false;
	$data = trim($data);
	$conn = connect();
	$sql = "SELECT username, email FROM users";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if($row["username"]==$data || $row["email"]==$data){
				$match = true;
			}
		}
	} else {
		echo "0 results";
	}
	if($match == false){
		redirect();
	}
}

function redirect(){
	 header("Location:buyingpage.php");
	 exit();
}
?>
