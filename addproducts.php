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
	<div class="loginbox">
		<a href="login.php" class="loginbutton">Login</a>
	</div>
</header>';
ob_start();
echo '
	<div id="outsidebox">
	<form action="addproducts.php" method="post" enctype="multipart/form-data">
		<h1>Add Products</h1>
		<p><img id="image" src="pic/no-image.jpg" height="100" width="100"/></p>
		<p><input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);" required="required"/></p>
		<p>Name: <input type="text" name="name" limit="20" required="required"/></p>
		<p>Price: RM <input type="number" name="price" min="0.01" step="0.01" value="0.00" placeholder="0.00" pattern="[0-9]+[.][0-9]{2}" required="required"/></p>
		<p>Quantity: <input type="number" name="quantity" min="1"/></p>
		<p>Description: <textarea name="description" required></textarea></p>
		<p><input type="submit" name="Submit"/>&nbsp;<input type="reset" name="Reset"/>
	</form>
	</div>
	';
echo '</body>
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
	$sql = "INSERT INTO Products (name,sellerid,price,description,quantity,img) VALUES ('$name',1,$price,'$description',$quantity,'$target_file')";
	$result = $conn->query($sql);
	echo $result;
	echo $uploadOk;
	if($result && $uploadOk == 1)
	{
		ob_end_clean();
		echo '<div style="color:green;border-style:dashed;">
			<h1>Add Product Successful!</h1>
			<p>The webpage will redirect back to the product management page in 5 seconds.</p>
		</div>';
		sleep(5);
		header("Location: sellingpage.php", true, 301);
		exit();
	}
	else
	{
		echo "Error";
	}

	$conn->close();
	$count=1;
}
?>