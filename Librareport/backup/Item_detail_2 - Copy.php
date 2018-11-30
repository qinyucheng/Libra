<?php
include ('connection.php');

// Execute multi query

$sellersArr = array();
$itemsArr = array();
$salesAmount = array();
$p0='2018-01-10 00:00:00.000000'; 
$p1='2018-05-16 00:00:00.000000'; 
$sql="call sp_items_sales_num_Amount('".$p0."','".$p1."')";
echo $sql;

$outputArr = array();

$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());

while($row=mysqli_fetch_array($result))
{
	$outputArr[]=$row;
	
}

echo json_encode($outputArr);
mysqli_close($link);
?>