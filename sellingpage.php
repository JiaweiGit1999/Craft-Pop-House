<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<body>

<header >
	<img src="pic/logo.png" alt="logo" id="logo">
	<div id="title"> | Craft Pop House</div>
	<form>
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar">
		<button id="search_button" class="search" type="submit"><i class="fas fa-search"> Search</i></button>
	</form>
</header>
<div>

</div>';
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

$sql = "SELECT * FROM products where product_status!='deleted'";
$result = $conn->query($sql);


echo'<div id="sellersidenav">
		<a class="sellermenu" href="sellingpage.php">Your products</a>
		<a class="sellermenu" href="sellingpage.php">Your Sales</a>
		<a class="sellermenu" href="sellingpage.php">Sold products</a>
	</div>
	<div class="topmenu">
		<form id="sellermenuform">
			<button type="submit" value="Remove"><i class="fas fa-trash-alt"></i> Remove</button>
			<button type="submit" formaction="addproducts.php" value="Add Product"><i class="far fa-plus-square"></i> Add product</button>
		</form>
	</div>';

echo'<div id="productdisplay">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<div class="productcolumn"> 
		<img src=' . $row["img"]. ' alt="testing" class="productimg">
		<p class="productname">' . $row["name"] . '</p>
		<p class="price"> RM' . $row["price"] . '</p>
		<div class="stargroup">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
			<input type="image" src="pic/stars.png" alt="starslogo" class="starslogo">
		</div>
		<form action="sellingpage.php" method="post">
          <input type="hidden" name="ProductID" value="'.$row["productid"].'" />
          <input type="submit" name="Delete" value="Delete" />
        </form>
	</div>';
    }
} else {
    echo "0 results";
}

if(isset($_REQUEST["Delete"]))
{
	$productID = $_REQUEST["ProductID"];
	$sql = "UPDATE Products product_status SET product_status='deleted' where productid=".$productID."";
	$result = $conn->query($sql);
	if($result)
		header("Refresh:0");
}

$conn->close();
		
echo'</div>
</body>
</html>';  ?>
