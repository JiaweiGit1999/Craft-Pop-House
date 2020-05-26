<?php
session_start();
foreach ($_SESSION["cart_item"] as $item){
			$item_price = $item["quantity"]*$item["price"];
			$total_quantity += $item["quantity"];
			//INSERT INTO orders(userid,status,quantity,date)VALUES (1,'preparing',$item["quantity"],NOW());
			echo '<tr>
					<td class="checkout_data"><img src="'.$item["img"].'" class="cart-item-image" />'.$item["name"].'</td>
					<td class="checkout_data">'.$item["price"].'</td>
					<td class="checkout_data">'.$item["quantity"].'</td>
					<td class="checkout_data">RM '.number_format($item_price, 2).'</td>
					</tr>';
		}
echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="buyingpage.css">
<script src="preview.js"></script>
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<body>
<header>
	<div id="header-content">
		<div id="website-logo">
			<img src="pic/logo.png" alt="logo" id="logo" onclick="location.href=\'homepage.php\'">
		</div>
	<form action="buyingpage.php">
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar"/>
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
<div class="loginbox">';

    // Check if the user is already logged in, if yes then redirect him to welcome page
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo '<a href="profile.php" class="loginbutton">'.$_SESSION["username"].'</a><a href="logout.php" class="loginbutton"> | logout</a>';
    $website="cart.php";
    }else{
    echo '<a href="login.php" class="loginbutton">Login</a>';
    $website="login.php";
    }

    echo '</div>
<div id="shopping-cart-button">
    <img src="pic/shopping-cart-solid.svg" height="50" width="50" onclick="location.href=\''. $website .'\'"/>
</div>
	</div>
	
</header>
	<div id="outsidebox">';
echo '<div style="color:green;border-style:dashed;">
		<h1>Checkout Successful!</h1>
		<p>The webpage will redirect back to the product management page in 5 seconds.</p>
	</div>';
echo'
	</div>
</body>
</html>';