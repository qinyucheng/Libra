<?php
session_start();

getItemDtail();

function getItemDtail()
{
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_qbodbc";
$sql = "SELECT * FROM physicalinventoryworksheet ";
//echo $sql;
$outputArr = array();
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ("Could not connect to MySQL");
$result=mysqli_query($link, $sql) or die ("die" .mysqli_error($link));

while($row=mysqli_fetch_object($result))
{
	$outputArr[]=$row;
	
}
echo json_encode($outputArr);
 //echo $sql;
	mysqli_close($link);
}	

	
?>