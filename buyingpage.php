<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<body>

<header >
	<img src="pic/logo.png" alt="logo" id="logo">
	<div id="title"> | Craft Pop House</div>
	<form>
		<label for="searchbar"></label>
		<input type="text" id="searchbar" name="searchbar">
		<input type="image" src="pic/searchlogo.png" alt="searchlogo" id="searchlogo">
	</form>
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



$sql = "SELECT name, price, quantity,img FROM products";
$result = $conn->query($sql);


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
	</div>';
    }
} else {
    echo "0 results";
}
$conn->close();
		
echo'</div>
</body>
</html>';  ?>
