<?php
include ('connection.php');

// Execute multi query

$salesAmount = array();
$p0='2018-01-01 00:00:00.000000'; 
$p1='2018-06-30 00:00:00.000000';
$p2='2018'; 
$sql="call sp_itemsales_detial_2018_2('".$p0."','".$p1."','".$p2."')";
//$sql="call sp_itemsales_detial_2018_2($p0,$p1,$p2)";


$outputArr = array();

$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());

while($row=mysqli_fetch_array($result))
{
	$outputArr[]=$row;
	
}

 echo json_encode($outputArr);
	mysqli_close($link);
?>