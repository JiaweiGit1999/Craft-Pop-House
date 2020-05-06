<?php
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
    session_start();

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
		<h1>Add Product Successful!</h1>
		<p>The webpage will redirect back to the product management page in 5 seconds.</p>
	</div>';
echo'
	</div>
</body>
</html>';
	header("refresh:5;url=sellingpage.php" );