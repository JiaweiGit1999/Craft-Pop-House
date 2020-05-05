<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<body>
<header>
	<div id="topheadnav">
		<a href="sellingpage.php" id="sellingcentre">Seller Centre</a>
		<div class="loginbox">';
	session_start();
 
	// Check if the user is already logged in, if yes then redirect him to welcome page
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		echo'<a href="profile.php" class="loginbutton">'.$_SESSION["username"].'</a><a href="logout.php" class="loginbutton"> | logout</a>';
		$website="cart.php";
	}else{
		echo'<a href="login.php" class="loginbutton">Login</a>';
		$website="login.php";
	}
	echo'</div>
	<div id="shopping-cart">
		<img src="pic/shopping-cart-solid.svg" height="50" width="50" onclick="location.href=\''.$website.'\'"/>
	</div>
	</div>
	<img src="pic/logo.png" alt="logo" id="logo">
	<div id="title"> | Craft Pop House</div>
	<form>
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar">
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
</header>';
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
echo'
<div id="hometopbox">
	<h2>Best Sellers</h2>
	<img src="pic/hushtail.png" width="342" height="344" id="homepic1">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
	<img src="pic/hushtail.png" width="170" height="170" class="homepic">
</div>';
echo'<div class="homeboxes">
		<div id="hometopmenu">
			<h2>Popular Items</h2>
		</div>';

echo'<div id="homeproductdisplay">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '
	<div class="homeproductcolumn"> 
		<img src=' . $row["img"]. ' alt="testing" class="productimg" width="100" height="100">
		<p class="productname">' . $row["name"] . '</p>
		<p class="price"> RM' . $row["price"] . '</p>
		<div class="stargroup">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
		</div>
	</div>';
    }
} else {
    echo "0 results";
}

$conn->close();
		
echo'</div></div>
</body>
</html>';  ?>
