<?php

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

if(isset($_REQUEST["Delete"]))
{
	$productID = $_REQUEST["ProductID"];
	$sql = "UPDATE Products product_status SET product_status='deleted' where productid=".$productID."";
	$result = $conn->query($sql);
	if($result)
		header("Location:sellingpage.php");
}

$conn->close();
?>