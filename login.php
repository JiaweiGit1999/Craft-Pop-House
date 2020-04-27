<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
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
<h1>Login Page</h1>';
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

		
echo'<form id="loginform">
	<label for="username">Username: </label><br>
	<input type="text" name="username" class="logininput"><br><br>
	<label for="username">Password: </label><br>
	<input type="text" name="password" class="logininput"><br>
	<a href=" ">forgot password?</a>
	<input type="submit" value="Login" id="login">
	<input type="submit" value="Register" id="register">
</form>
</body>
</html>';  ?>
