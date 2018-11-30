<?php
include ('connection_r.php');
$startDate = stripslashes(trim($_POST['startDate']));
$endtDate = stripslashes(trim($_POST['endDate']));
$sql = "";
$sql .= "SELECT ";
$sql .= "    `CustomerRefFullName`, ";
$sql .= "    DATE_FORMAT(shipdate, \"%Y-%m\") AS Month, ";
$sql .= "    SUM(`items_sales_num`) as Quantity, ";
$sql .= "    SUM(`items_sales_Amount`) AS Amount ";
$sql .= "FROM ";
$sql .= "    `sales_shiped_new` ";
$sql .= "  WHERE     `CustomerRefFullName`<>''  and  (shipdate>='$startDate' and shipdate<='$endtDate')";

$sql .= "GROUP BY ";
$sql .= "    CustomerRefFullName, ";
$sql .= "    DATE_FORMAT(shipdate, \"%Y-%m-01\")" ;

$myArray = array();
$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());
while($row=mysqli_fetch_object($result))
{
	$myArray[] = $row;
	
}
echo json_encode($myArray);
?>