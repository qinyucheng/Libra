<?php
session_start();
$level2_ListID = stripslashes(trim($_POST['ListID']));
$start_date = stripslashes(trim($_POST['startDate']));
$end_date = stripslashes(trim($_POST['endDate']));
$CustomerName = stripslashes(trim($_POST['CustomerName']));

$selectedSql = "";

getItemDtail($start_date,$end_date,$level2_ListID,$CustomerName);

function getItemDtail($start_date,$end_date,$level2_ListID,$CustomerName)
{
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "online_qbodbc";

$sql = "";
$sql .= "SELECT ";
$sql .= "    CustomerRefFullName AS Customer, ";
$sql .= "    level2_ListID, ";
$sql .= "    level2_FullName AS ItemName, ";
$sql .= "    Description, ";
$sql .= "    shipdate, ";
$sql .= "    items_sales_num, ";
$sql .= "    items_sales_Amount, ";
$sql .= "    QuantityOnHand ";
$sql .= "FROM ";
$sql .= "    sales_shiped_new left JOIN physicalinventoryworksheet on level2_FullName =Blank" ;

if($CustomerName=="Ebay1")
{
	
$sql .= "    WHERE   level2_ListID = '$level2_ListID' and (CustomerRefFullName='Ebay' or CustomerRefFullName='eBay PayPal' or CustomerRefFullName='Libra on line store') and (shipdate >= '$start_date' AND shipdate <= '$end_date')   ";
	
}


else if(empty($CustomerName))
{
	
$sql .= "    WHERE  shipdate >= '$start_date' AND shipdate <= '$end_date'  AND level2_ListID = '$level2_ListID'  and (items_sales_Amount<>'' or items_sales_Amount<>'0'  or items_sales_Amount<>'-0'";
}
else 
{
	
$sql .= "    WHERE CustomerRefFullName='$CustomerName' and shipdate >= '$start_date' AND shipdate <= '$end_date'  AND level2_ListID = '$level2_ListID'   ";
}
//echo $sql;
$outputArr = array();
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ("Could not connect to MySQL");
$result=mysqli_query($link, $sql) or die ("die" .mysqli_error($link));

while($row=mysqli_fetch_object($result))
{
	$outputArr[]=$row;
	
}
mysqli_close($link);
	echo json_encode($outputArr);
	
	
}	

	
?>