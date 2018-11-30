<?php
session_start();
$start_date = stripslashes(trim($_POST['startDate']));
$end_date = stripslashes(trim($_POST['endDate']));
$selectedSql = "";
getItemDtail($start_date,$end_date);

function getItemDtail($start_date,$end_date)
{
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "qbodbc";
$sql = "";
$sql .= "SELECT ";
$sql .= "        `level2_ListID`, ";
$sql .= "        `level2_FullName`, ";
$sql .= "        `Description`, ";
$sql .= "        DATE_FORMAT(shipdate, \"%Y-%m\") AS shipdate, ";
$sql .= "        SUM(`items_sales_num`) AS Final_sales_quantity, ";
$sql .= "        SUM(`items_sales_Amount`) AS Final_sales_amount ";
$sql .= "    FROM ";
$sql .= "        `sales_shiped_new` ";
$sql .= "    WHERE ";
$sql .= "       `shipdate` >= '$start_date'  and `shipdate` <= '$end_date' AND `level2_ListID` <> '' ";
$sql .= "    GROUP BY ";
$sql .= "        level2_ListID, ";
$sql .= "        DATE_FORMAT(shipdate, \"%Y-%m\"), ";
$sql .= "        level2_FullName" ;
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