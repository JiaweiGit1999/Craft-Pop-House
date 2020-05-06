<?php
session_start();
 
if(isset($_SESSION["sellerproductid"])){
    $pid = $_SESSION["sellerproductid"];
}

 echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<body>

<header >
<div id="topheadnav">
		<a href="sellingpage.php" id="sellingcentre">Seller Centre</a>
		<div class="loginbox">';
 
	// Check if the user is already logged in, if yes then redirect him to welcome page
	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
		echo'<a href="profile.php" class="loginbutton">'.$_SESSION["username"].'</a><a href="logout.php" class="loginbutton"> | logout</a>';
		
	}else{
		echo'<a href="login.php" class="loginbutton">Login</a>';
	}
	echo'</div>
	</div>
	<img src="pic/logo.png" alt="logo" id="logo">
	<a href="homepage.php"><div id="title"> | Craft Pop House</div></a>
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

$sql = "SELECT * FROM products WHERE productid = ".$pid;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$img = $row["img"];
		$name = $row["name"];
		$price = $row["price"];
		$rating = $row["rating"];
		$sellerid = $row["sellerid"];
		$description = $row["description"];
    }
} else {
    echo "0 results";
}

$sql = "SELECT * FROM seller WHERE id = ".$sellerid;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $seller = $row["name"];
    }
} else {
    echo "0 results";
}
$conn->close();
$count=1;
echo'
	<div id="outsidebox">
		<div id="productdetailwindow">
			<img src= '.$img.' width="300" height="300">
			</div>
		<div id="detailsinfo">
			<div>'.$name.'</div>
			<div class="stargroup">';
			while($count <= 5){
				if($count <= $rating){
					echo'<img src="pic/starscolor.png" alt="starslogo" class="starslogodetails">';
				}else{
					echo'<img src="pic/stars.png" alt="starslogo" class="starslogodetails">';
				}
				
				$count = $count +1;
			}
			echo'</div>
			<div id="detailsprice"> RM'.$price.'</div>
			<div>Seller: '.$seller.'</div>
			<div>Description: '.$description.'</div>
		</div>
	</div>
</body>
</html>';

?>