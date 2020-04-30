<?php
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
	<img src="pic/logo.png" alt="logo" id="logo">
	<div id="title"> | Craft Pop House</div>
	<form>
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar"/>
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


echo'<div id="filter_container" class="product_filters" name="filter">
			<button class="filter_btn active" onclick="filterSelection(\'All\')"> Show All</button>
			<button class="filter_btn" onclick="filterSelection(\'Chair\')"> Chair</button>
			<button class="filter_btn" onclick="filterSelection(\'Table\')"> Table</button>
			<button class="filter_btn" onclick="filterSelection(\'Decoration\')"> Decoration</button>
			<button class="filter_btn" onclick="filterSelection(\'Accessories\')"> Accessories</button>
		</div>
		<div id="productdisplay">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<div class="product_column '.$row["category"].'"> 
		<img src=' . $row["img"]. ' alt="testing" class="productimg" height="100" width="100"/>
		<p class="productname">' . $row["name"] . '</p>
		<p class="price"> RM' . $row["price"] . '</p>
		<div class="stargroup">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo"/>
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo"/>
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo"/>
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo"/>
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo"/>
		</div>
	</div>';
    }
} else {
    echo "0 results";
}

$conn->close();
		
echo'</div>
</body>
<script src="filter.js" type="text/javascript" defer="true"></script>
</html>';  ?>
