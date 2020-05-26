<?php echo '
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
		<a href="register.php" id="sellingcentre">Become a Seller</a>
		<div class="loginbox">
			<a href="login.php" class="loginbutton">Login</a>
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
<h1>Login Page</h1>';

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

		
echo'<form id="loginform" method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">
	<label for="username">Username: </label><br>
	<input type="text" name="username" class="logininput"><br><br>
	<label for="password">Password: </label><br>
	<input type="password" name="password" class="logininput"><br>
	<a href=" " class="forgot">forgot password?</a>
	<input type="submit" value="Login" id="login">
	<a href="register.php" id="register">Register</a>
</form>
</body>
</html>';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	test_input($_POST["username"],$_POST["password"]);
}

function test_input($username,$password) {
	$match = false;
	$id = 0;
	$username = trim($username);
	$password = trim($password);
	$conn = connect();
	$sql = "SELECT * FROM users";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			if($row["username"]==$username || $row["password"]==$password){
				$match = true;
				$id = $row["id"];
				$status = $row["status"];
			}
		}
	} else {
		echo "0 results";
	}
	if($match == true){
		session_start();
		$_SESSION["loggedin"] = true;
		$_SESSION["username"] = $_POST["username"];
		$_SESSION["loginid"] = $id;
		$_SESSION["loginstatus"] = $status;
		if($status == "Seller"){
			header("refresh: 2; url=sellingpage.php");
		}else{
			header("refresh: 2; url=homepage.php");
		}
		
		
	}
}?>
