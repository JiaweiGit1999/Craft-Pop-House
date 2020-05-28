<?php
define('SCRIPT_DEBUG', true);
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_REQUEST["quantity"])) {
			$productByproductid = $db_handle->runQuery("SELECT * FROM products WHERE productid='" . $_GET["productid"] . "'");
			$itemArray = array($productByproductid[0]["productid"]=>array('name'=>$productByproductid[0]["name"], 'productid'=>$productByproductid[0]["productid"], 'quantity'=>$_REQUEST["quantity"], 'price'=>$productByproductid[0]["price"], 'img'=>$productByproductid[0]["img"]));
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByproductid[0]["productid"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
						if($productByproductid[0]["productid"] == $k) {
							if(empty($_SESSION["cart_item"][$k]["quantity"])) {
								$_SESSION["cart_item"][$k]["quantity"] = 0;
							}
							$_SESSION["cart_item"][$k]["quantity"] += $_REQUEST["quantity"];
						}
					}
				} else {
					$_SESSION["cart_item"] = $_SESSION["cart_item"] + $itemArray;
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["productid"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
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

<header>
	<div id="header-content">
	<div id="topheadnav">';
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

$sql = "SELECT * FROM seller WHERE id = $sellerid.";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $seller = $row["name"];
		$usericon = $row["backgroundurl"];
    }
} else {
    echo "0 results";
}
$countproducts = 0;
$sql = "SELECT * FROM products WHERE sellerid = $sellerid.";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $countproducts++;
    }
} else {
    echo "0 results";
}

$count=1;
echo'
	<div id="outsidebox">
		<div id="productdetailwindow">
			<img src= '.$img.' id="detailimg">
		</div>
		<div id="productdetails"><b>'.$name.'</b>
		<div class="stargroup">';
			while($count <= 5){
				if($count <= $rating){
					echo'<img src="pic/starscolor(1).png" alt="starslogo" class="starslogodetails">';
				}else{
					echo'<img src="pic/stars.png" alt="starslogo" class="starslogodetails">';
				}
				
				$count = $count +1;
			}
			echo'   | Rating: '.$rating.'</div></div>
		<div id="detailsinfo">
			<div id="detailsprice"> RM'.$price.'</div>
		</div>
		<form id="formbuydetails">
			<label for="name" id="quantitylabel">Quantity: </label>
			<input type="number" name="quantity" min="1" id="quantity"/>
			<input type="hidden" name="productid" value="'.$pid.'"/>
			<input type="hidden" name="action" value="add"/>
			<input type="submit" name="AddToCart" value="Add To Cart" id ="addtocart" class="btnAddAction" ></input>
		</form>
	</div>
	<div id="outsidebox">
		<img src='.$usericon.' width=100 height=100 id="sellerproductimg"></img>
		<div class="sellerproductdetails">
			<div id="productsellername">'.$seller.'</div>
			<input type="button" value="Chat Now" id="chatnow"></input>
		</div>
		<div class="sellerproductdetails" id ="specificdetails">
			<div>Rating: 5</div>
			<div>Products: '.$countproducts.'</div>
		</div>
	</div>
	<div id="outsidebox">
		<h2>Product Description</h2>
		<div>Description: '.$description.'</div>
	</div>
	<div id="outsidebox">
		<h2>Product Reviews</h2>
		';
		$sql = "SELECT * FROM comments WHERE productid =".$pid;
		$result = $conn->query($sql);
		$commentuserid = [];
		$comment = [];
		$commentdate = [];
		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				array_push($commentuserid,$row["userid"]);
				array_push($comment,$row["comment"]);
				array_push($commentdate,$row["date_of_comment"]);
			}
		} else {
			echo "0 results";
		}
		$i = 0;
		while($i < count($commentuserid)){
			$sql = "SELECT * FROM users WHERE id =".$commentuserid[$i];
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				// output data of each row
				while($row = $result->fetch_assoc()) {
					$commentusername = $row["username"];
					$commentusericon = $row["usericon"];
					echo'<div class="reviewbox">
						<img src="'.$commentusericon.'" width=100 height=100 id="reviewerimg">
						<div class="reviewuserdetails">
							<p>'.$commentusername.'</p>
							<p>'.$commentdate[$i].'</p>
						</div>
						<p class="usercomment">'.$comment[$i].'</p>
					</div>';
				}
			} else {
				echo "0 results";
			}
			$i++;
		}
		echo'
	</div>
</body>
</html>';
$conn->close();
?>