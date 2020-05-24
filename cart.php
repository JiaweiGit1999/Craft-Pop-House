<?php
define('SCRIPT_DEBUG', true);
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByproductid = $db_handle->runQuery("SELECT * FROM products WHERE productid='" . $_GET["productid"] . "'");
			$itemArray = array($productByproductid[0]["productid"]=>array('name'=>$productByproductid[0]["name"], 'productid'=>$productByproductid[0]["productid"], 'quantity'=>$_POST["quantity"], 'price'=>$productByproductid[0]["price"], 'img'=>$productByproductid[0]["img"]));
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByproductid[0]["productid"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
						if($productByproductid[0]["productid"] == $k) {
							if(empty($_SESSION["cart_item"][$k]["quantity"])) {
								$_SESSION["cart_item"][$k]["quantity"] = 0;
							}
							$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
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
echo '
<!DOCTYPE html>
<html lang="en">
<title>Craft Pop House</title>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css"href="buyingpage.css">
<link rel="stylesheet" type="text/css"href="style.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
</head>
<body>

<header>
	<div id="header-content">
	<div id="topheadnav">
		<a href="sellingpage.php" id="sellingcentre">Seller Centre</a>
		<div class="loginbox">';
 
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
echo '<div id="shopping-cart">
	<div class="txt-heading">Shopping Cart</div>
	<a id="btnEmpty" href="cart.php?action=empty">Empty Cart</a>';
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;

	echo '<table class="tbl-cart" cellpadding="10" cellspacing="1">
		<tbody>
		<tr>
		<th style="text-align:left;">Name</th>
		<th style="text-align:left;">productid</th>
		<th style="text-align:right;" width="5%">Quantity</th>
		<th style="text-align:right;" width="10%">Unit Price</th>
		<th style="text-align:right;" width="10%">Price</th>
		<th style="text-align:center;" width="5%">Remove</th>
		</tr>';	
		foreach ($_SESSION["cart_item"] as $item){
			$item_price = $item["quantity"]*$item["price"];
			echo '<tr>
					<td><img src="'.$item["img"].'" class="cart-item-image" />'.$item["name"].'</td>
					<td>'.$item["productid"].'</td>
					<td style="text-align:right;">'.$item["quantity"].'</td>
					<td  style="text-align:right;">'.$item["price"].'</td>
					<td  style="text-align:right;">RM '.number_format($item_price, 2).'</td>
					<td style="text-align:center;"><a href="cart.php?action=remove&productid='.$item["productid"].'" class="btnRemoveAction"><img src="pic/icon-delete.png" alt="Remove Item" /></a></td>
					</tr>';
			$total_quantity += $item["quantity"];
			$total_price += ($item["price"]*$item["quantity"]);
		}
	echo '<tr>
		<td colspan="2" align="right">Total:</td>
		<td align="right">'.$total_quantity.'</td>
		<td align="right" colspan="2"><strong>RM '.number_format($total_price, 2).'</strong></td>
		<td></td>
		</tr>
		</tbody>
		</table>
		<form action="checkout.php">
			<input type="hidden" name="total_price" value="'.number_format($total_price, 2).'" />
			<input type="submit" name="Checkout" value="Checkout" id="checkout-button"/>
		</form>';
	} else {
	echo '<div class="no-records">Your Cart is Empty</div>';
	}
	echo '</div>
		<div id="product-grid">
		<div class="txt-heading">You May Also Like</div>';
		$productid ="SELECT * FROM products ORDER BY productid ASC";
		$product_array = $conn->query($productid);
		if (!empty($product_array)) { 
			foreach($product_array as $product){
			echo '<div class="product-item">
				<form method="post" action="cart.php?action=add&productid='.$product["productid"].'">
				<div class="product-image"><img src="'.$product["img"].'" height="150" width="250"></div>
				<div class="product-tile-footer">
				<div class="product-title">'.$product["name"].'</div>
				<div class="product-price">RM '.$product["price"].'</div>
				<div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="2" /><input type="submit" value="Add to Cart" class="btnAddAction" /></div>
				</div>
				</form>
			</div>';
			}
		}
	echo '</div>
	</body>
	</html>';
