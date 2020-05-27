<?php 
session_start();

if(isset($_SESSION["sellerproductid"])){
    $pid = $_SESSION["sellerproductid"];
}

echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
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
function connectdb() {
    
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
	
	return $conn;
}

$conn = connectdb();
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

$sql = "SELECT * FROM seller WHERE id = ".$sellerid;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $seller = $row["name"];
    }
} else {
    echo "0 results";
}
$conn->close();
$count=1;

if(isset($_POST['submit'])){ 
	$conn = connectdb();
    $newname = $_REQUEST['name'];
	$newprice = (float)$_REQUEST['price'];
	$newdescription = $_REQUEST['description'];
	$target_file = "pic/";
	$newimg = $target_file . basename($_FILES["fileToUpload"]["name"]);
	move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $newimg);
	///$sql = "UPDATE products SET name=".$newname.",price=".$newprice.",description=".$newdescription."WHERE productid= 2";
	$sql = 'UPDATE products
SET 
    name = "'.$newname.'",
    price = '.number_format($newprice, 2).',
    description = "'.$newdescription.'",
	img = "'.$newimg.'"
WHERE
    productid = '.$pid;
	
	if ($conn->query($sql) === TRUE) {
		print_r ("Record updated successfully");
		header("Refresh:2");
	} else {
		print_r( "Error updating record: " . $conn->error);
	}
}else{
    //code to be executed  
}

echo'
	<h1>Edit products</h1>
	<div id="outsidebox">
		<div id="productdetailwindow">
			<img src= '.$img.' id="detailimg">
			<script>
				function readURL(input) {
				  if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.onload = function(e) {
						document.getElementById("detailimg").src=e.target.result;
					}
					reader.readAsDataURL(input.files[0]); // convert to base64 string
				  }
				}
			</script>
		</div>
		<form id="detailsinfo" method="post" action="" enctype="multipart/form-data">
			<p><input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);" required="required"/></p>
			<label for="name"> Name:</label>
			<input type="text" name="name"value="'.$name.'">
			<br>
			<br>
			<div class="stargroup">';
			while($count <= 5){
				if($count <= $rating){
					echo'<img src="pic/starscolor.png" alt="starslogo" class="starslogodetails">';
				}else{
					echo'<img src="pic/stars.png" alt="starslogo" class="starslogodetails">';
				}
				$count = $count +1;
			}
			echo'<br></div>
			<label for="price"> Price: RM</label>
			<input type="text" id="detailsprice" name="price" value="'.$price.'">
			<br><br>
			<label class="save" for="description"> Description:</label>
			<textarea name="description" rows="5" cols="50" class="save">'.$description.'</textarea>
			<input type="submit" name="submit" value="Save" id="saveproductdetails">
		</form>
		
	</div>
</body>
</html>';
?>