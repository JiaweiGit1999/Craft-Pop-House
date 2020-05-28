<?php
session_start();

echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="buyingpage.css">
<link rel="stylesheet" type="text/css"href="style.css">
<script src="preview.js"></script>
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
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
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$conn->query("INSERT INTO orders(userid,status,date)VALUES(".$_SESSION["loginid"].",'preparing',NOW());");
	$id = $conn->query("SELECT last_insert_id();");
	$order_id = $id->fetch_assoc()["last_insert_id()"];
	foreach ($_SESSION["cart_item"] as $item){
		$conn->query("INSERT INTO products_orders(productid,orderid)VALUES(".$item["productid"].",".$order_id.");");
	}
	$date = new DateTime("now", new DateTimeZone('Asia/Kuching') );
	echo'<div id="outsidebox">
	<div>
		<h1 style="background-color: white;text-align: left;padding-top: 2%;padding-bottom: 2%;margin: 0 40px;">Checkout Successful!</h1>
		<h2 style="background-color: white;padding: 20px 0;margin: 0 40px;">Order details</h2>
		<div id="shopping-cart">
		<p>Order ID: '.$order_id.'</p>
		<p>Date: '.$date->format('Y-m-d H:i:s').'</p>
		<table class="tbl-cart" id="checkout-table" style="width:100%;border-color:#999999">
			<tbody>
				<tr style="background-color:grey;">
					<th>Products Ordered</th>
					<th>Unit Price</th>
					<th>Amount</th>
					<th>Item Subtotal</th>
				</tr>';
				$total_quantity = 0;
				$total_price = 0;
				foreach ($_SESSION["cart_item"] as $item){
					$item_price = $item["quantity"]*$item["price"];
					$total_price += $item_price;
					$total_quantity += $item["quantity"];
					echo '<tr>
							<td class="checkout_data"><img src="'.$item["img"].'" class="cart-item-image" />'.$item["name"].'</td>
							<td class="checkout_data">'.$item["price"].'</td>
							<td class="checkout_data">'.$item["quantity"].'</td>
							<td class="checkout_data">RM '.number_format($item_price, 2).'</td>
						</tr>';
				}
				echo'
				<tr>
					<td colspan="2" align="right">Total:</td>
					<td align="right">'.$total_quantity.'</td>
					<td align="right" colspan="2"><strong>RM '.number_format($total_price, 2).'</strong></td>
				</tr>
			</tbody>
		</table>
		</div>
		<div style="text-align:center;">
			<form>
				<input type="submit" id="submit" name="submit" value="Back to Homepage" style="border-style:none;border:1px solid orange;background-color:white;font-size:18px;color:orange;"/>
			</form>
		</div>
	</div>
	</div>
</body>
</html>';
if(isset($_REQUEST["submit"])){
	unset($_SESSION["cart_item"]);
	header("Location:homepage.php");
}
?>