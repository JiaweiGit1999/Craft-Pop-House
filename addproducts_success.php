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
	<img src="pic/logo.png" alt="logo" id="logo">
	<div id="title"> | Craft Pop House</div>
	<form>
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar">
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
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