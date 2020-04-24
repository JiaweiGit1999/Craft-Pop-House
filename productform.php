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
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if($row["productid"] == 2){
			$img = $row["img"];
			$name = $row["name"];
			$price = $row["price"];
			$rating = $row["rating"];
			$sellerid = $row["sellerid"];
			$description = $row["description"];
		}
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
	///$sql = "UPDATE products SET name=".$newname.",price=".$newprice.",description=".$newdescription."WHERE productid= 2";
	echo $newprice;
	$sql = 'UPDATE products
SET 
    name = "'.$newname.'",
    price = '.$newprice.',
    description = "'.$newdescription.'"
WHERE
    productid = 2';
	
	if ($conn->query($sql) === TRUE) {
		echo "Record updated successfully";
	} else {
		echo "Error updating record: " . $conn->error;
	}
}else{
    //code to be executed  
}

echo'
	<h1>Edit products</h1>
	<div id="outsidebox">
		<div id="productdetailwindow">
			<img src= '.$img.' id="detailimg">
		</div>
		<form id="detailsinfo" method="post" action="">
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