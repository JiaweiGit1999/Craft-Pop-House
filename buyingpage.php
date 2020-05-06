<?php ob_start();
define('SCRIPT_DEBUG', true);
echo '
<!DOCTYPE html>
<html lang="en">
<title>Craft Pop House</title>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
</head>
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
	<a href="homepage.php"><div id="title"> | Craft Pop House</div></a>
	<form>
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar"/>
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
	<div class="loginbox">
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

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

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
echo'<form id="buyersidenav">
		<p id="filterhead">Filters</p>
		<label for="filter">Rating:</label>
		<a href="?rate1"><div class="stargroup">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
		</div></a>
		<a href="?rate2"><div class="stargroup">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
		</div></a>
		<a href="?rate3"><div class="stargroup">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
		</div></a>
		<a href="?rate4"><div class="stargroup">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/stars.png" alt="starslogo" class="starslogo">
		</div></a>
		<a href="?rate5"><div class="stargroup">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
			<img src="pic/starscolor.png" alt="starslogo" class="starslogo">
		</div></a>
		<label for="price1" id="pricelabel">Price Range:</label>
		<input type="number" name="price1" class="pricerange"></input>
		<div id="range"> - </div>
		<input type="number" name="price2" class="pricerange"></input>
		<input type="submit" value="submit" name="filtersubmit"></input>
	</form>
	<div class="topmenu">
		<form id="buyermenuform">
			<label for="relevance">Sort By:</label>
			<button type="submit" value="relevance" name="relevance">Relevance</button>
			<button type="submit" value="rating" name="rating">Rating</button>
			<button type="submit" value="price" name="price">Price</button>
		</form>
	</div>';
///filter codes
if(isset($_GET['rate1'])){
	echo '<p>rate1 pressed</p>';
}

if(isset($_GET['rate2'])){
	echo '<p>rate2 pressed</p>';
}

if(isset($_GET['rate3'])){
	echo '<p>rate3 pressed</p>';
}
if(isset($_GET['rate4'])){
	echo '<p>rate4 pressed</p>';
}
if(isset($_GET['rate5'])){
	echo '<p>rate5 pressed</p>';
}

echo'<div id="filter_container" class="product_filters" name="filter">
			<button class="filter_btn active" onclick="filterSelection(\'All\')"> Show All</button>
			<button class="filter_btn" onclick="filterSelection(\'Chair\')"> Chair</button>
			<button class="filter_btn" onclick="filterSelection(\'Table\')"> Table</button>
			<button class="filter_btn" onclick="filterSelection(\'Decoration\')"> Decoration</button>
			<button class="filter_btn" onclick="filterSelection(\'Accessories\')"> Accessories</button>
		</div>
		<div id="productdisplay">';
if(isset($_GET["pid"])){
	$_SESSION["sellerproductid"] == $_GET["pid"];
	header("Location: productdetails.php");
}
echo'<div id="productdisplay">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<a href=?pid='.$row["productid"].' name="productid"><div class="productcolumn"> 
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
	</div></a>';
    }
} else {
    echo "0 results";
}

$conn->close();
		
echo'</div>
</body>
<script src="filter.js" type="text/javascript" defer="true"></script>
</html>';  
ob_end_flush();
?>
