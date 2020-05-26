<?php ;
echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
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

<div class="slideshow-container">

  <!-- Full-width images with number and caption text -->
  <div class="mySlides fade">
    <img src="pic/homeslide1.jpg" width="1000" height="400">
  </div>

  <div class="mySlides fade">
  
    <img src="pic/homeslide2.png" width="1000" height="400">
    <div class="text"><a  href="">Become a Buyer!</a></div>
  </div>

  <div class="mySlides fade">
    
    <img src="pic/homeslide3.jpg" width="1000" height="400">
    <div class="text"><a  href="">Become a Seller!</a></div>
  </div>

  <!-- Next and previous buttons -->
  <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
  <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
  <span class="dot" onclick="currentSlide(1)"></span>
  <span class="dot" onclick="currentSlide(2)"></span>
  <span class="dot" onclick="currentSlide(3)"></span>
</div>

<script>
var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  var i;
  var slides = document.getElementsByClassName("mySlides");
  var dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
</script>
';
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
<div id="homebestmenu">
<h2 id="sellhead">Top Entrepeneurs</h2>
</div>
<div id="hometopbox">
	<img src="pic/hushtail.png" width="350" height="400" id="homebigpic">
	<img src="pic/hushtail.png" width="200" height="180" class="homepic" id="home2pic">
	<img src="pic/hushtail.png" width="200" height="180" class="homepic" id="home3pic">
</div>
<div id="homephrasebox">
		<p>You Had Ideas,<br><br>
		you Innovated,<br><br>
		you Created,<br><br>
		And Now....<br><br>
		You have made it to the top<br><br>
		And Let The World Know Your Craft</p>
	</div>

</body>
</html>';  ?>
