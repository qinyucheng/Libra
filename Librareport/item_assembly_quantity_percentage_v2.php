<?php
include ('connection_r.php');

$sql = "SELECT * FROM `item_assembly_quantity_percentage` where Type='Assembly' ORDER by `STATUS` DESC";

$outputArr = array();



$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());

while($row=mysqli_fetch_object($result))
{
	$outputArr[]=$row;
	
}

echo json_encode($outputArr);
mysqli_close($link);
?>