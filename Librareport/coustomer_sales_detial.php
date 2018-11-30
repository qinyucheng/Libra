<?php
include ('connection.php');

// $sql = "SELECT  * FROM `salesreceiptline" ;

$sql = "SELECT `CustomerRefFullName`, SUM(`SalesReceiptLineQuantity`) AS Quantity,  concat('$', format(SUM(`SalesReceiptLineAmount`), 2)) AS Amount, DATE_FORMAT(TimeCreated, '%m') as Month FROM `salesreceiptline` GROUP BY DATE_FORMAT(TimeCreated, '%Y-%m-01'), CustomerRefFullName ORDER by `CustomerRefFullName`,month";

$myArray = array();
if ($result = $link->query($sql)) {

    while($row = $result->fetch_array()) {
            $myArray[] = $row;
    }
    echo json_encode($myArray);
	mysqli_close($link);
}
?>