<?php
session_start();
$start_date = stripslashes(trim($_POST['startDate']));
$end_date = stripslashes(trim($_POST['endDate']));
$CustomerName = stripslashes(trim($_POST['CustomerName']));
$selectedSql = "";

getItemDtail($start_date,$end_date,$CustomerName);

function getItemDtail($start_date,$end_date,$CustomerName)
{
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "qbodbc";
$sql = "";
$sql .= "SELECT  ";
$sql .= "        CustomerRefFullName as Customer, ";
$sql .= "        level2_ListID, ";
$sql .= "        ifnull(level2_FullName,'Return') as ItemName, ";
$sql .= "        Description, ";
$sql .= "        SUM(items_sales_num) AS total_sales_items, ";
$sql .= "        (SUM(items_sales_Amount)/(SUM(items_sales_num))) AS Unit, ";
$sql .= "        SUM(items_sales_Amount) AS total_amount ";
$sql .= "    FROM ";
$sql .= "        sales_shiped_new ";

if($CustomerName=="Ebay1")
{
	
$sql .= "    WHERE (CustomerRefFullName='Ebay' or CustomerRefFullName='eBay PayPal' or CustomerRefFullName='Libra on line store') and (shipdate >= '$start_date' AND shipdate <= '$end_date')   ";
	
}

else
{
	
$sql .= "    WHERE CustomerRefFullName='$CustomerName' and (shipdate >= '$start_date' AND shipdate <= '$end_date')   ";
}

$sql .= "    GROUP BY ";
$sql .= "    level2_FullName ";

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