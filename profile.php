<?php echo '
<!DOCTYPE html>
<html>
<title>Craft Pop House</title>
<meta charset="UTF-8">

<link rel="stylesheet" type="text/css"href="buyingpage.css">
<script src="https://kit.fontawesome.com/c823101727.js" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css"href="style.css">
<script src="profile.js" ></script>
<script src="preview.js" ></script>
<body>

<header>
	<div id="header-content">
	<div id="topheadnav">';
	session_start();
	$userid = $_SESSION["loginid"];
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

$sql = "SELECT * FROM users Where id = ".$userid;
$result = $conn->query($sql);


echo'<div id="sellersidenav">
		<a class="sellermenu" href="profile.php">Your Profile</a>
		<a class="sellermenu" href="javascript:DisplayOrders()">Your Orders</a>
	</div>
	';

echo'<div id="profiledisplay"><div id="profilebox">';
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		$oldpass = $row["password"];
        echo ' 
		<div id="edit">
			<input type="button" name="edit" value="Edit" id="editprofile" onclick="DisplayProfileForm()"></input>
			<img src=' . $row["usericon"]. ' alt="testing" class="productimg" width="200" height="200">
			<p class="Username:">Username: ' . $row["username"] . '</p>
			<p class="Email: ">Email: ' . $row["email"] . '</p>
			<p class="Address: ">Address: ' . $row["address"] . '</p>
		</div>
		<form method="POST" action="" id="save"  onsubmit="return CheckProfileInfo()" name="editform" enctype="multipart/form-data">
			<input type="submit" name="save" value="Save" id="editprofile"></input>
			<input type="button" name="button" value="Cancel" id="Cancel" onclick="DisplayProfileInfo()"></input>
			<img id="image" src=' . $row["usericon"]. ' alt="testing" class="productimg" width="200" height="200">
			<p><input type="file" name="fileToUpload" id="fileToUpload" onchange="readURL(this);"/></p>
			<p class="profileform">Username: <input type="text" class="profileinputform" name="editusername" value=' . $row["username"] . ' required></input></p>
			<p id="userpass">'.$oldpass.'</p>
			<p class="profileform">Old Password: <input type="text" class="profileinputform" name="editoldpass" id = "oldpass" required></input></p>
			<p class="profileform">New Password: <input type="text" class="profileinputform" name="editnewpass" id = "newpass" required></input></p>
			<p class="profileform">Email: <input type="text" class="profileinputform" name="editemail" value=' . $row["email"] . ' requiredrequired></input></p>
			<p class="profileform">Address: <input type="text" class="profileinputform" name="editaddress" value=' . $row["address"] . ' required></input></p>
		</form>
	';
    }
} else {
    
}
		
echo'
<table class="tbl-cart" cellpadding="10" cellspacing="1" id="profileorderstable">
		<tbody>
		<tr>
		<th style="text-align:left;">Name</th>
		<th style="text-align:left;">Status</th>
		<th style="text-align:right;" width="5%">Quantity</th>
		<th style="text-align:right;" width="10%">Date</th>
		</tr>';
$sql = "SELECT * FROM orders Where userid = ".$userid;
$result = $conn->query($sql);
$countitems = 0;
$orderdate = [];
$orderstatus = [];
$quantity = [];
$productid = [];
$orderid = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
		array_push($orderdate,$row["date"]);
		array_push($orderstatus,$row["status"]);
		array_push($quantity,$row["quantity"]);
        array_push($productid,$row["productid"]);
		array_push($orderid,$row["orderid"]);
		
    }
} else {
    
}
$i = 0;
while($i < count($productid)){
	$sql = "SELECT * FROM products Where productid = ".$productid[$i];
	$result = $conn->query($sql);
	$countitems = 0;
	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo ' 
		<tr>
			<td><img src="'.$row["img"].'" class="cart-item-image" />'.$row["name"].'</td>
			<td>'.$orderstatus[$i].'</td>
			<td style="text-align:right;">'.$quantity[$i].'</td>
			<td  style="text-align:right;">'.$orderdate[$i].'</td>
		</tr>';
	
		}
	} else {
		
	}
	$i++;
}

echo'</tr>
		</tbody>
		</table>
		</div></div>
</body>
</html>';  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if($_POST["editoldpass"] == $oldpass){
		$target_dir = "pic/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
		$sql = 'UPDATE users 
		SET username = "'.$_POST["editusername"].'"
		,password = "'.$_POST["editnewpass"].'"
		,email = "'.$_POST["editemail"].'"
		,address = "'.$_POST["editaddress"].'"
		,usericon = "'.$target_file.'" 
		Where id = '.$userid;
		if ($conn->query($sql) === TRUE) {
			print_r ("Record updated successfully");
			header("Refresh:2");
		} else {
			print_r( "Error updating record: " . $conn->error);
		}
	}
}
$conn->close();?>
