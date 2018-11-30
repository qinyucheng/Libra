<?php
session_start();
$action = $_GET['action'];
if ($action == 'getData') {  
	$start_date = stripslashes(trim($_POST['startDate']));
	$end_date = stripslashes(trim($_POST['endDate']));
	$selectedArr =json_decode(stripslashes($_POST['selectedCustomer']));

	$selectedSql = "and";
	foreach ($selectedArr as $key=>$value) {
		$selectedSql .="  sales_table.CustomerRefFullName=";
		$selectedSql .="'".$value."'";
		if($key<(count($selectedArr)-1))
		{
			$selectedSql .=" or ";
			
		}
		
	}

	
	getItemDtail($start_date,$end_date,$selectedSql);
	
	
}
else if($action == 'default')
{
	
	$start_date = stripslashes(trim($_POST['startDate']));
	$end_date = stripslashes(trim($_POST['endDate']));
	$dataTable='sales_shiped_'; 
	$dataTable .='2018'; 
	$selectedSql = "";
	//$selectedSql .= "  and  sales_table.CustomerRefFullName='Amazon'" ;
	//$selectedSql .= "  or sales_table.CustomerRefFullName='Ebay'" ;
	//$selectedSql .= "  or sales_table.CustomerRefFullName='Libra on line store'" ;
	//$selectedSql .= "  or sales_table.CustomerRefFullName='Walmart'" ;
	
	getItemDtail($start_date,$end_date,$selectedSql);
}

else if($action == 'getAllCustomer')
{
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "qbodbc";
	$sql = "SELECT DISTINCT `CustomerRefFullName` FROM `sales` ORDER by`CustomerRefFullName`";
	$outputArr = array();
	$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ("Could not connect to MySQL");
	$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());
	while($row=mysqli_fetch_array($result))
	{
		$outputArr[]=$row;
		
	}
	 echo json_encode($outputArr);
	 //echo $sql;
		mysqli_close($link);

}
	
	


function getItemDtail($start_date,$end_date,$selectedSql)
{
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "qbodbc";
$sql = "";
$sql .= "SELECT ";
$sql .= "    sales_table.CustomerRefFullName AS Customer, ";
$sql .= "    sales_table.level2_FullName AS ItemName, ";
$sql .= "    ( ";
$sql .= "        sales_num - IFNULL(return_items, 0) ";
$sql .= "    ) AS total_sales_items, ";
$sql .= "    ( ";
$sql .= "        sales_amount - IFNULL(return_credit, 0) ";
$sql .= "    ) AS total_amoount ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        `SalesReceiptLineItemRefFullName`, ";
$sql .= "        level2_FullName, ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        SUM(`items_sales_num`) AS sales_num, ";
$sql .= "        SUM(`items_sales_Amount`) AS sales_amount ";
$sql .= "    FROM ";
$sql .= "        sales_shiped_2018 ";
$sql .= "    WHERE ";
$sql .= "        shipdate >= $start_date AND shipdate <= $end_date ";
$sql .= "    GROUP BY ";
$sql .= "        `level2_FullName`, ";
$sql .= "        CustomerRefFullName ";
$sql .= ") AS sales_table ";
$sql .= "LEFT JOIN( ";
$sql .= "    SELECT ";
$sql .= "        credit_tabl.CustomerRefFullName, ";
$sql .= "        item_assembly_quantity_percentage.level2_FullName, ";
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
$sql .= "    CreditMemoLineItemRefListID <> '' AND( ";
$sql .= "        credit_tabl.shipdate >= $start_date AND credit_tabl.shipdate <=  $end_date ";
$sql .= "    ) ";
$sql .= "GROUP BY ";
$sql .= "    item_assembly_quantity_percentage.level2_FullName, ";
$sql .= "    CustomerRefFullName ";
$sql .= ") AS credit_return ";
$sql .= "ON ";
$sql .= "    sales_table.level2_FullName = credit_return.level2_FullName AND sales_table.CustomerRefFullName = credit_return.CustomerRefFullName ";
$sql .= "WHERE ";
$sql .= "    sales_table.level2_FullName <> '' ";
$sql .= "    $selectedSql";

$sql .= "UNION ";
$sql .= "SELECT ";
$sql .= "    credit_return.CustomerRefFullName, ";
$sql .= "    credit_return.level2_FullName, ";
$sql .= "    (-1 * credit_return.return_items), ";
$sql .= "    (-1 * credit_return.return_credit) ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        `SalesReceiptLineItemRefFullName`, ";
$sql .= "        level2_FullName, ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        SUM(`items_sales_num`) AS sales_num, ";
$sql .= "        SUM(`items_sales_Amount`) AS sales_amount ";
$sql .= "    FROM ";
$sql .= "        sales_shiped_2018";
$sql .= "    WHERE ";
$sql .= "        shipdate >= $start_date AND shipdate <= $end_date ";
$sql .= "    GROUP BY ";
$sql .= "        `level2_FullName`, ";
$sql .= "        CustomerRefFullName ";
$sql .= ") AS sales_table ";
$sql .= "RIGHT JOIN( ";
$sql .= "    SELECT ";
$sql .= "        credit_tabl.CustomerRefFullName, ";
$sql .= "        item_assembly_quantity_percentage.level2_FullName, ";
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
$sql .= "    CreditMemoLineItemRefListID <> '' AND( ";
$sql .= "        credit_tabl.shipdate >= $start_date AND credit_tabl.shipdate <= $end_date ";
$sql .= "    ) ";
$sql .= "GROUP BY ";
$sql .= "    item_assembly_quantity_percentage.level2_FullName, ";
$sql .= "    CustomerRefFullName ";
$sql .= ") AS credit_return ";
$sql .= "ON ";
$sql .= "    sales_table.level2_FullName = credit_return.level2_FullName AND sales_table.CustomerRefFullName = credit_return.CustomerRefFullName ";
$sql .= "WHERE ";
$sql .= "    sales_table.CustomerRefFullName IS NULL" ;
$sql .= "    $selectedSql";
//echo $sql;
$outputArr = array();
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ("Could not connect to MySQL");
$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());

while($row=mysqli_fetch_array($result))
{
	$outputArr[]=$row;
	
}
echo json_encode($outputArr);
 //echo $sql;
	mysqli_close($link);
}	



function getItemDtail2($start_date,$end_date,$selectedSql)
{
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "qbodbc";
$sql = "";
$sql .= "SELECT ";
$sql .= "    sales_table.CustomerRefFullName AS Customer, ";
$sql .= "    sales_table.level2_FullName AS ItemName, ";
$sql .= "    ( ";
$sql .= "        sales_num - IFNULL(return_items, 0) ";
$sql .= "    ) AS total_sales_items, ";
$sql .= "    ( ";
$sql .= "        sales_amount - IFNULL(return_credit, 0) ";
$sql .= "    ) AS total_amoount ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        `SalesReceiptLineItemRefFullName`, ";
$sql .= "        level2_FullName, ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        SUM(`items_sales_num`) AS sales_num, ";
$sql .= "        SUM(`items_sales_Amount`) AS sales_amount ";
$sql .= "    FROM ";
$sql .= "        sales_shiped_2018 ";
$sql .= "    WHERE ";
$sql .= "        shipdate >= $start_date AND shipdate <= $end_date ";
$sql .= "    GROUP BY ";
$sql .= "        `level2_FullName`, ";
$sql .= "        CustomerRefFullName ";
$sql .= ") AS sales_table ";
$sql .= "LEFT JOIN( ";
$sql .= "    SELECT ";
$sql .= "        credit_tabl.CustomerRefFullName, ";
$sql .= "        item_assembly_quantity_percentage.level2_FullName, ";
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
$sql .= "    CreditMemoLineItemRefListID <> '' AND( ";
$sql .= "        credit_tabl.shipdate >= $start_date AND credit_tabl.shipdate <=  $end_date ";
$sql .= "    ) ";
$sql .= "GROUP BY ";
$sql .= "    item_assembly_quantity_percentage.level2_FullName, ";
$sql .= "    CustomerRefFullName ";
$sql .= ") AS credit_return ";
$sql .= "ON ";
$sql .= "    sales_table.level2_FullName = credit_return.level2_FullName AND sales_table.CustomerRefFullName = credit_return.CustomerRefFullName ";
$sql .= "WHERE ";
$sql .= "    sales_table.level2_FullName <> '' ";
$sql .= "    $selectedSql";

$sql .= "UNION ";
$sql .= "SELECT ";
$sql .= "    credit_return.CustomerRefFullName, ";
$sql .= "    credit_return.level2_FullName, ";
$sql .= "    (-1 * credit_return.return_items), ";
$sql .= "    (-1 * credit_return.return_credit) ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        `SalesReceiptLineItemRefFullName`, ";
$sql .= "        level2_FullName, ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        SUM(`items_sales_num`) AS sales_num, ";
$sql .= "        SUM(`items_sales_Amount`) AS sales_amount ";
$sql .= "    FROM ";
$sql .= "        sales_shiped_2018";
$sql .= "    WHERE ";
$sql .= "        shipdate >= $start_date AND shipdate <= $end_date ";
$sql .= "    GROUP BY ";
$sql .= "        `level2_FullName`, ";
$sql .= "        CustomerRefFullName ";
$sql .= ") AS sales_table ";
$sql .= "RIGHT JOIN( ";
$sql .= "    SELECT ";
$sql .= "        credit_tabl.CustomerRefFullName, ";
$sql .= "        item_assembly_quantity_percentage.level2_FullName, ";
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
$sql .= "    CreditMemoLineItemRefListID <> '' AND( ";
$sql .= "        credit_tabl.shipdate >= $start_date AND credit_tabl.shipdate <= $end_date ";
$sql .= "    ) ";
$sql .= "GROUP BY ";
$sql .= "    item_assembly_quantity_percentage.level2_FullName, ";
$sql .= "    CustomerRefFullName ";
$sql .= ") AS credit_return ";
$sql .= "ON ";
$sql .= "    sales_table.level2_FullName = credit_return.level2_FullName AND sales_table.CustomerRefFullName = credit_return.CustomerRefFullName ";
$sql .= "WHERE ";
$sql .= "    sales_table.CustomerRefFullName IS NULL" ;
$sql .= "    $selectedSql";
echo $sql;
$outputArr = array();
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_name) or die ("Could not connect to MySQL");
$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());

while($row=mysqli_fetch_array($result))
{
	$outputArr[]=$row;
	
}
//echo json_encode($outputArr);
 //echo $sql;
	mysqli_close($link);
}	
?>