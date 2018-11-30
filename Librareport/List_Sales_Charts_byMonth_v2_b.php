<?php
include ('connection_r.php');
$startDate = stripslashes(trim($_POST['startDate']));
$endtDate = stripslashes(trim($_POST['endDate']));


$sql = "";
$sql .= "SELECT ";
$sql .= "    FullName, ";
$sql .= "    SUM(`Quantity`) AS Quantity, ";
$sql .= "    SUM(`Amount`) AS Amount ";
$sql .= "FROM ";
$sql .= "    `sales_list_analysis` ";
$sql .= "WHERE ";
$sql .= "    ( ";
$sql .= "        `Date` >= '$startDate' AND `Date` <= '$endtDate' ";
$sql .= "    ) AND( ";
$sql .= "        `Name` = 'amazon' OR `Name` = 'ebay' OR NAME = 'Walmart' OR NAME = 'Libra on line store' ";
$sql .= "    ) AND `FullName` <> '' AND txntype = 'Sales Receipt' ";
$sql .= "GROUP BY ";
$sql .= "    `FullName` ";
$sql .= "ORDER BY ";
$sql .= "   SUM(`Quantity`) desc" ;

$myArray = array();
$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());
while($row=mysqli_fetch_object($result))
{
	$myArray[] = $row;
	
}
echo json_encode($myArray);
?>