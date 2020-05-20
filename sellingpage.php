<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="confirmation_box.js"></script>
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<body>

<header>
	<div id="header-content">
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

$sql = "SELECT * FROM products where product_status!='deleted'";
$result = $conn->query($sql);


echo'<div id="sellersidenav">
		<a class="sellermenu" href="sellingpage.php">Your products</a>
		<a class="sellermenu" href="sellingpage.php">Your Sales</a>
		<a class="sellermenu" href="sellingpage.php">Sold products</a>
	</div>
	<div class="topmenu">
		<form id="sellermenuform">
			<button type="submit" value="Remove"><i class="fas fa-trash-alt"></i> Remove</button>
			<button type="submit" formaction="addproducts.php" value="Add Product"><i class="far fa-plus-square"></i> Add product</button>
		</form>
	</div>';
if(isset($_GET["pid"])){
    $_SESSION["sellerproductid"] = $_GET["pid"];
	header('Location:productform.php');
	exit;
}
echo'<div id="productdisplay">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<a href="?pid='.$row["productid"].'"><div class="productcolumn"> 
		<img src=' . $row["img"]. ' alt="testing" class="productimg" height="100" width="100">
		<p class="productname">' . $row["name"] . '</p>
		<p class="price"> RM' . $row["price"] . '</p>
		<div class="stargroup">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
		</div>
		<form action="delete_product.php" method="post" onsubmit="return validate()">
          <input type="hidden" name="ProductID" value="'.$row["productid"].'" />
          <input type="submit" id="submit" name="Delete" value="Delete" class="remove"/>
        </form>
	</div></a>';
    }
} else {
    echo "0 results";
}

$conn->close();
		
echo'</div>
</body>
</html>';  ?>
