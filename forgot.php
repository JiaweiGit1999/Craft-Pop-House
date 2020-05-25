<?php 

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

		


if ($_SERVER["REQUEST_METHOD"] == "POST") {
	test_input($_POST["email"]);
}

function test_input($username,$password, $email) {
	$match = false;
	$username = trim($username);
	$password = trim($password);
	$conn = connect();
	$sql = "SELECT username, password FROM users";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if($row["username"]==$username || $row["password"]==$password){
				$match = true;
			}
		}
	} else {
		echo "0 results";
	}
	if($match == true){
		session_start();
		$_SESSION["loggedin"] = true;
		$_SESSION["username"] = $_POST["username"];
		header("refresh: 2; url=homepage.php");
		
	}
    
    
    $email = trim($email);
	$conn = connect();
	$sql = "SELECT email FROM users";
	$result = $conn->query($sql1`);

    
    $msg ="";
    if ($result->num_rows > 0) {
    
        $msg = "Please check your email";
        
        
        //send email
        
    }else{
        header("refresh: 2; url=register.php");
        $msg = "Please Register";
    }
    
}

?>



<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<body>

<header>
	<div id="header-content">
	<div id="topheadnav">
		<a href="sellingpage.php" id="sellingcentre">Seller Centre</a>
		<div class="loginbox">
			<a href="login.php" name ="login" class="loginbutton">Log in</a>
		</div>
	</div>
		<div id="website-logo">
			<img src="pic/logo.png" alt="logo" id="logo" onclick="location.href=\'homepage.php\'">
		</div>
		<div id="shopping-cart-button">
			<img src="pic/shopping-cart-solid.svg" height="50" width="50" onclick="location.href=\'login.php\'"/>
		</div>
	<form action="buyingpage.php">
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar"/>
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
	</div>
	
</header>
<h1>Forgot Password Page</h1>

<form id="loginform" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
	<label for="email">Email: </label><br>
	<input type="email" name="email" class="logininput"><br><br>
	
	 <input type="submit" value="Forgot Password?" name="login" id="login">
  
</form>
   
	<a href="register.php" id="register">Register</a>
</body>
</html>
