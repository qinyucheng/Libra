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
$sql .= "    sales_table.level2_ListID, ";
$sql .= "    sales_table.level2_FullName, ";
$sql .= "    sales_table.Description, ";
$sql .= "    sales_table.shipdate, ";
$sql .= "    sales_table.items_sales_num, ";
$sql .= "    IFNULL(credit_Table.return_items, 0) AS return_items, ";
$sql .= "    sales_table.sales_amount, ";
$sql .= "    IFNULL(credit_Table.return_credit, 0) AS return_credit, ";
$sql .= "    ( ";
$sql .= "        sales_table.items_sales_num - IFNULL(credit_Table.return_items, 0) ";
$sql .= "    ) AS Final_sales_quantity, ";
$sql .= "     ( ";
$sql .= "        sales_table.sales_amount - IFNULL(credit_Table.return_credit, 0) ";
$sql .= "    ) AS Final_sales_amount ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        `level2_ListID`, ";
$sql .= "        `level2_FullName`, ";
$sql .= "        `Description`, ";
$sql .= "        DATE_FORMAT(shipdate, \"%Y-%m\") AS shipdate, ";
$sql .= "        SUM(`items_sales_num`) AS items_sales_num, ";
$sql .= "        SUM(`items_sales_Amount`) AS sales_amount ";
$sql .= "    FROM ";
$sql .= "        `sales_shiped` ";
$sql .= "    WHERE ";
$sql .= "        `shipdate` >= '$start_date'  and `shipdate` <= '$end_date' AND `level2_ListID` <> '' ";
$sql .= "    GROUP BY ";
$sql .= "        level2_ListID, ";
$sql .= "        DATE_FORMAT(shipdate, \"%Y-%m\"), ";
$sql .= "        level2_FullName ";
$sql .= ") AS sales_table ";
$sql .= "LEFT JOIN( ";
$sql .= "    SELECT ";
$sql .= "        item_assembly_quantity_percentage.level2_ListID, ";
$sql .= "        item_assembly_quantity_percentage.Level2_FullName, ";
$sql .= "        DATE_FORMAT(shipdate, \"%Y-%m\") AS shipdate, ";
$sql .= "        SUM( ";
$sql .= "            credit_tabl.CreditMemoLineQuantity * item_assembly_quantity_percentage.item_numbers ";
$sql .= "        ) AS return_items, ";
$sql .= "        SUM( ";
$sql .= "            credit_tabl.CreditMemoLineAmount * item_assembly_quantity_percentage.Price_Percentage ";
$sql .= "        ) AS return_credit ";
$sql .= "    FROM ";
$sql .= "        ( ";
$sql .= "        SELECT ";
$sql .= "            TxnID, ";
$sql .= "            TimeCreated, ";
$sql .= "            TimeModified, ";
$sql .= "            shipdate, ";
$sql .= "            CustomerRefListID, ";
$sql .= "            CustomerRefFullName, ";
$sql .= "            CreditMemoLineItemRefListID, ";
$sql .= "            CreditMemoLineItemRefFullName, ";
$sql .= "            CreditMemoLineQuantity, ";
$sql .= "            CreditMemoLineAmount ";
$sql .= "        FROM ";
$sql .= "            `creditmemoline` ";
$sql .= "    ) AS credit_tabl ";
$sql .= "LEFT JOIN item_assembly_quantity_percentage ON credit_tabl.CreditMemoLineItemRefListID = item_assembly_quantity_percentage.ListID ";
$sql .= "WHERE ";
$sql .= "    `shipdate` >= '$start_date'  and `shipdate` <= '$end_date' and CreditMemoLineItemRefListID <> '' ";
$sql .= "GROUP BY ";
$sql .= "    item_assembly_quantity_percentage.Level2_listID, ";
$sql .= "    DATE_FORMAT(shipdate, \"%Y-%m-01\") ";
$sql .= ") AS credit_Table ";
$sql .= "ON ";
$sql .= "    sales_table.level2_ListID = credit_Table.level2_ListID AND sales_table.shipdate = credit_Table.shipdate" ;
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