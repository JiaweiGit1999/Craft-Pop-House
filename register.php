<?php

echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<script src="loginpage.js"></script>
<script src="preview.js"></script>
<body>

<header>
	<div id="header-content">
	<div id="topheadnav">';
	session_start();
	if(isset($_SESSION["loginstatus"]) && $_SESSION["loginstatus"] === "Seller"){
		echo '<a href="sellingpage.php" id="sellingcentre">Seller Centre</a>';
	}
	else if(isset($_SESSION["loginstatus"]) && $_SESSION["loginstatus"] === "Admin"){
		echo '<a href="sellingpage.php" id="sellingcentre"> Seller Centre </a><a href="register.php" id="sellingcentre"> Become a Seller </a>';
	}else{
		echo '<a href="register.php" id="sellingcentre">Become a Seller</a>';
	}
	echo'
		<div class="loginbox">';
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		echo'<a href="profile.php" class="loginbutton">'.$_SESSION["username"].'</a><a href="logout.php" class="loginbutton"> | logout</a>';
		$website="cart.php";
	}else{
		echo'<a href="login.php" class="loginbutton">Login</a>';
		$website="login.php";
	}
	echo'</div>
	</div>
		<div id="website-logo">
			<img src="pic/logo.png" alt="logo" id="logo" onclick="location.href=\'homepage.php\'">
		</div>
		<div id="shopping-cart-button">
			<img src="pic/shopping-cart-solid.svg" height="50" width="50" onclick="location.href=\''. $website .'\'"/>
		</div>
	<form action="buyingpage.php">
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar"/>
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
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
	<img id="image" src="pic/no-image.jpg" height="100" width="100" name="photoimage"/>
	<input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);" /*required="required"*//><br><br>
	<input type="radio" name="acctype" Value="Seller" class="radiologininput" checked>
	<label for="Seller">Seller</label>
	<input type="radio" name="acctype" value="Buyer" class="radiologininput">
	<label for="Buyer">Buyer</label><br>
	<label for="username">Username: </label><br>
	<input type="text" name="username" class="logininput" required><br><br>
	<label for="password">Password: </label><br>
	<input type="password" name="password" class="logininput" required><br>
	<label for="re-enterpassword">Re-enter Password: </label><br>
	<input type="password" name="re-enterpassword" class="logininput" required>
	<p id="errortxt"></p><br>
	<label for="email">Email: </label><br>
	<input type="text" name="email" class="logininput" required><br><br>
	<label for="address">Address: </label><br>
	<input type="text" name="address" class="logininput" required><br><br>
	<input type="submit" value="Register" id="register" name="register">
</form>
</body>
</html>';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	test_input($_POST["username"],$_POST["email"]);
}

function test_input($data,$data2) {
	$match = false;
	$data = trim($data);
	$data2 = trim($data2);
	$conn = connect();
	$sql = "SELECT username, email FROM users";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if($row["username"]==$data || $row["email"]==$data2){
				$match = true;
			}
		}
	} else {
		echo "0 results";
	}
	if($match == false){
		$address = str_replace(","," ",$_POST["address"]);
		$sql = "insert INTO users (username,password,email,address,usericon,status)
				VALUES('".$_POST["username"]."','".$_POST["password"]."','".$_POST["email"]."','".$_POST["address"]."','pic/".$_POST["fileToUpload"]."','".$_POST["acctype"]."')";
		$conn->query($sql);
		redirect();
	}else{
		echo "failed registration";
	}
}

function redirect(){
	 header("refresh: 3; url=login.php");
	 exit();
}
?>
