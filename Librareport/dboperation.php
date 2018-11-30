<?php
include ('connection.php');


$sql = "";
$sql .= "SELECT ";
$sql .= "    summary_sales.CustomerRefFullName, ";
$sql .= "	(summary_sales.sales_Amount-IFNULL(summary_RETURNS.return_Amount,0)) as sales_Amount_Summary, ";
$sql .= "	(summary_sales.sales_Quantity-IFNULL(summary_RETURNS.return_Quantity,0)) as sales_Quantity_Summary, ";
$sql .= "	summary_sales.MONTH ";
$sql .= "	 ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        SUM(`SalesReceiptLineQuantity`) AS sales_Quantity, ";
$sql .= "        SUM(`SalesReceiptLineAmount`) AS sales_Amount, ";
$sql .= "        DATE_FORMAT(`shipdate`, '%Y-%m') AS MONTH ";
$sql .= "    FROM ";
$sql .= "        `sales` ";
$sql .= "    WHERE ";
$sql .= "        ShipDate >= '2018-01-01' ";
$sql .= "    GROUP BY ";
$sql .= "        DATE_FORMAT(`shipdate`, '%Y-%m'), ";
$sql .= "        CustomerRefFullName ";
$sql .= "    ORDER BY ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        MONTH ";
$sql .= ") AS summary_sales ";
$sql .= "LEFT JOIN( ";
$sql .= "    SELECT ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        SUM(`CreditMemoLineQuantity`) AS return_Quantity, ";
$sql .= "        SUM(`CreditMemoLineAmount`) AS return_Amount, ";
$sql .= "        DATE_FORMAT(`shipdate`, '%Y-%m') AS MONTH ";
$sql .= "    FROM ";
$sql .= "        creditmemoline ";
$sql .= "    WHERE ";
$sql .= "        ShipDate >= '2018-01-01' ";
$sql .= "    GROUP BY ";
$sql .= "        DATE_FORMAT(`shipdate`, '%Y-%m'), ";
$sql .= "        CustomerRefFullName ";
$sql .= "    ORDER BY ";
$sql .= "        `CustomerRefFullName`, ";
$sql .= "        MONTH ";
$sql .= ") AS summary_RETURNS ";
$sql .= "ON ";
$sql .= "    summary_sales.MONTH = summary_RETURNS.MONTH AND summary_sales.CustomerRefFullName = summary_RETURNS.CustomerRefFullName" ;
$myArray = array();
if ($result = $link->query($sql)) {

    while($row = $result->fetch_array()) {
            $myArray[] = $row;
    }
    echo json_encode($myArray);
}
?>