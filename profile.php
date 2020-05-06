<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
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

$sql = "SELECT * FROM users Where id = 1";
$result = $conn->query($sql);


echo'<div id="sellersidenav">
		<a class="sellermenu" href="sellingpage.php">Your Profile</a>
		<a class="sellermenu" href="sellingpage.php">Sales History</a>
		<a class="sellermenu" href="sellingpage.php">Orders History</a>
	</div>';

echo'<div id="profiledisplay">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<div id="profilebox"> 
		<input type="button" name="edit" value="Edit" id="editprofile"></input>
		<img src=' . $row["usericon"]. ' alt="testing" class="productimg" width="200" height="200">
		<p class="Username:">Username: ' . $row["username"] . '</p>
		<p class="Email: ">Email: ' . $row["email"] . '</p>
		<p class="Address: ">Address: ' . $row["address"] . '</p>
		
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
