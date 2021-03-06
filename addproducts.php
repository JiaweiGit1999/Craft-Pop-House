<?php
echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="buyingpage.css">
<script src="preview.js"></script>
<script src="confirmation_box.js"></script>
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
echo '
	<div id="outsidebox">
	<h1>Add Products</h1>
	<form action="addproducts.php" method="post" enctype="multipart/form-data" onsubmit="return validate()" id="addproductsform">
		
		<p><img id="image" src="pic/no-image.jpg" height="100" width="100"/></p>
		<p><input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);" required="required"/></p>
		<label for="name">Name: </label>
		<input type="text" name="name" limit="20" required="required"/>
		<label for="name">Price: RM</label>
		<input type="number" name="price" min="0.01" step="0.01" value="0.00" placeholder="0.00" pattern="[0-9]+[.][0-9]{2}" required="required"/>
		<label for="category">Choose a category:</label>
		<select id="category" name="category">
			<option value="Chair">Chair</option>
			<option value="Table">Table</option>
			<option value="Decoration">Decoration</option>
			<option value="Accessories" selected="selected">Accessories</option>
		</select>
		<label for="name">Quantity: </label>
		<input type="number" name="quantity" min="1"/>
		<label for="name">Description: </label>
		<textarea name="description" required></textarea>
		<div>
			<input type="submit" id="submit" name="Submit" value"Add"/>&nbsp;<input type="reset" name="Reset"/>
		</div>
	</form>
	</div>
</body>
</html>';

//upload image to database folder
if(isset($_REQUEST["Submit"])){
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
	$name = $_REQUEST["name"];
	$price = $_REQUEST["price"];
	$description = $_REQUEST["description"];
	$category = $_REQUEST["category"];
	$quantity = $_REQUEST["quantity"];
	$target_dir = "pic/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if($_FILES["fileToUpload"]["name"]=='' || file_exists($target_file) || 
	($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") ){
		$uploadOk = 0;
	}
	else if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    }
	else {
		echo "Sorry, there was an error uploading your file.";
	}
	$sql = "INSERT INTO Products (name,sellerid,price,description,category,quantity,img) VALUES ('$name',1,$price,'$description','$category',$quantity,'$target_file')";
	$result = $conn->query($sql);
	if($result)
	{
		header("Location:addproducts_success.php");
	}
	else
	{
		echo $conn->error;
	}

	$conn->close();
	$count=1;
}
?>
