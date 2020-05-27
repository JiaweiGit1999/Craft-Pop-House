<?php
define('SCRIPT_DEBUG', true);
session_start();
require_once("dbcontroller.php");
if(isset($_REQUEST['Checkout'])){
	$total_price = $_REQUEST['total_price'];
}
else{
	$total_price = 0.01;
}
if(!isset($_SESSION["cart_item"]))
{
	header("Location:login.php");
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
	
</header>
<section>
<h2>Checkout</h2>
<h3 class="checkout">Order Details</h3>
<div id="shopping-cart">
<table class="tbl-cart" id="checkout-table">
	<tbody>
		<tr>
			<th>Products Ordered</th>
			<th>Unit Price</th>
			<th>Amount</th>
			<th>Item Subtotal</th>
		</tr>';
		$total_quantity = 0;
		foreach ($_SESSION["cart_item"] as $item){
			$item_price = $item["quantity"]*$item["price"];
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
	</tbody>
</table>
<h3>Payment Options</h3>
<div class="paypal">
<script src="https://www.paypal.com/sdk/js?client-id=AfwbD51iZ2J9Lx3cgGM5ZuFahQ6UkQMOA3MmNZ89eD0WBZC_g8KYqT60uBjDvSolIyHm40RLoaMUfbdN&currency=MYR">
	</script>
  
  <script>
  paypal.Buttons({
    createOrder: function(data, actions) {
      return actions.order.create({
        purchase_units: [{
          amount: {
            value: '.$total_price.'
          }
        }]
      });
    },
    onApprove: function(data, actions) {
      // This function captures the funds from the transaction.
	  
      return actions.order.capture().then(function(details) {
        // This function shows a transaction success message to your buyer.
        alert(\'Transaction completed by \' + details.payer.name.given_name);
		window.location.href = \'checkout-success.php\';
      });
    }
  }).render(\'.paypal\');
  </script>
</div>
</div>
</section>';

echo '
	</body>
	</html>'
	?>