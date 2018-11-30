<?php
set_time_limit(420);
include ('connection_r.php');
$updateDate = date("Y-m-d", strtotime("-1 days"));
$startDate = date("Y-m-d", strtotime("-1 days"));
$endDate = date("Y-m-d");
#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data QRemote", "", "");
#Set the SQL Statement
$date = date('Y-m-d h:i:s a', time());
$sys_log=$date."\r\n";
sys_sales($oConnect, $link, $startDate, $endDate,$sys_log);
function sys_sales($oConnect, $link, $startDate, $endDate,&$sys_log) {
    // Set the SQL Statement
    $sSQL = "sp_report SalesByItemDetail show Text, Blank, TxnType, Date,  Memo, Name, Quantity, UnitPrice, Amount parameters DateFrom = {d'$startDate'}, DateTo= {d'$endDate'}";
    // Perform the query
    $oResult = odbc_exec($oConnect, $sSQL);
    $keys = array("Type", "FullName", "Description", "TxnType", "Date", "Memo", "Name", "Quantity", "UnitPrice", "Amount");
    $lFieldCount = odbc_num_fields($oResult);
    $rows = array();
    //print_r($keys."<br>");
    while ($myRow = odbc_fetch_array($oResult)) {
        $rows[] = $myRow;
    }
    $values = array();
    $Type = "";
    $FullName = "";
    $NewFullName = "";
    $Description = "";
    if (count($rows) > 0) {
        // do delete then add
        $resUpdate = mysqli_query($link, "delete from  r_salesbyitemdetail  WHERE date>= '$startDate' ");
        //print ("delete from  r_salesbyitemdetail  WHERE date>= '$startDate'");
        if ($resUpdate) {
            $items = 0;
            for ($i = 0;$i < count($rows);++$i) {
                $values = array();
                $l1_parentCount = 0;
                $l2_parentCount = 0;
                $l3_parentCount = 0;
                // print $rows[$i]['Text'];
                //if ($rows[$i]['Text'] == "" && $rows[$i]['Blank'] == "" && $rows[$i]['Amount'] != "")
                if ($rows[$i]['UnitPrice'] != "") {
                    if ($i >= 3) {
                        if ($rows[$i - 1]['Text'] != "" && $rows[$i - 1]['Amount'] == "") {
                            $Description = $rows[$i - 1]['Text'];
                            if ($rows[$i - 2]['Text'] != "" && $rows[$i - 2]['Amount'] == "") {
                                $FullName = $rows[$i - 2]['Text'];
                                if ($rows[$i - 3]['Text'] != "" && $rows[$i - 3]['Amount'] == "") {
                                    $Type = $rows[$i - 3]['Text'];
                                }
                            }
                        }
                    }
                    if (strpos($Description, '(') !== false) {
                        $newDescription = preg_replace("/\([^)]+\)/", "", $Description);
                        $NewFullName = $FullName . ':' . $newDescription;
                        $splitArr = explode("(", $Description);
                        $newDescription2 = str_replace(")", "", $splitArr[1]);
                    } else {
                        $NewFullName = $FullName . ':' . $Description;
                        $newDescription2 = $Description;
                    }
                    array_push($values, $Type);
                    array_push($values, $NewFullName);
                    //array_push($values,  addslashes($Description));
                    array_push($values, addslashes($newDescription2));
                    array_push($values, $rows[$i]['TxnType']);
                    array_push($values, $rows[$i]['Date']);
                    array_push($values, addslashes($rows[$i]['Memo']));
                    array_push($values, $rows[$i]['Name']);
                    array_push($values, $rows[$i]['Quantity']);
                    array_push($values, $rows[$i]['UnitPrice']);
                    array_push($values, $rows[$i]['Amount']);
                    //print $Type . "**:***" . $FullName . "***:***" . $Description . "*************".$rows[$i]['TxnType'] . " - " . $rows[$i]['Date'] . " - " . $rows[$i]['Memo'] . " - " . $rows[$i]['Name'] . " - " . $rows[$i]['Quantity'] . " - " . $rows[$i]['UnitPrice'] . " - " . $rows[$i]['Amount'] . "<br />";
                    //print_r($values."<br>");
                    $resInsert = mysqli_query($link, "INSERT INTO r_salesbyitemdetail (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "');"); // insert one row into new table
                    if ($resInsert) {
                        //print ("r_salesbyitemdetail--Insert2 :Success<br>");
                        
                    } else {
                        print ("r_salesbyitemdetail--INSERT INTO r_salesbyitemdetail (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
                    }
                    $items++;
                }
            }
        } else {
            print ("delete failed!<br>");
        }
    }
    odbc_free_result($oResult);
	$sys_log .="total No. of SalesByItemDetail: $items \r\n";
	
	
    echo "<br>total No. of SalesByItemDetail: $items";
	//$arr['SalesByItemDetail'] = "total No. of SalesByItemDetail: $items";
	
}

sys_sales_shiped($link, $updateDate,$sys_log);
function sys_sales_shiped($link, $updateDate,&$sys_log) {
    $resDelete = mysqli_query($link, "delete from  sales_shiped_new  WHERE shipdate>= '$updateDate' ");
    if ($resDelete) {
        $sql = "";
        $sql.= "insert into sales_shiped_new (SELECT ";
        $sql.= "    (-1) AS TxnID, ";
        $sql.= "    ('2018-10-10') AS TimeCreated, ";
        $sql.= "    ('2018-10-10') AS TimeModified, ";
        $sql.= "    r_salesbyitemdetail.Date AS shipdate, ";
        $sql.= "    r_salesbyitemdetail.name AS CustomerRefFullName, ";
        $sql.= "    item_assembly_quantity_percentage.ListID AS SalesReceiptLineItemRefListID, ";
        $sql.= "    r_salesbyitemdetail.FullName AS SalesReceiptLineItemRefFullName, ";
        $sql.= "    r_salesbyitemdetail.Memo AS SalesReceiptLineDesc, ";
        $sql.= "    r_salesbyitemdetail.Quantity AS SalesReceiptLineQuantity, ";
        $sql.= "    r_salesbyitemdetail.Amount AS SalesReceiptLineAmount, ";
        $sql.= "    item_assembly_quantity_percentage.level1_ListID, ";
        $sql.= "    item_assembly_quantity_percentage.level1_FullName, ";
        $sql.= "    item_assembly_quantity_percentage.level2_ListID, ";
        $sql.= "    item_assembly_quantity_percentage.level2_FullName, ";
        $sql.= "    r_salesbyitemdetail.Description, ";
        $sql.= "    item_assembly_quantity_percentage.item_numbers, ";
        $sql.= "    item_assembly_quantity_percentage.Price_Percentage, ";
        $sql.= "    ( ";
        $sql.= "        r_salesbyitemdetail.Quantity * IFNULL(item_numbers, 1) ";
        $sql.= "    ) AS items_sales_num, ";
        $sql.= "    ( ";
        $sql.= "        r_salesbyitemdetail.Amount * IFNULL( ";
        $sql.= "            item_assembly_quantity_percentage.Price_Percentage, ";
        $sql.= "            1 ";
        $sql.= "        ) ";
        $sql.= "    ) AS items_sales_Amount, ";
        $sql.= "    (-1) AS FQPrimaryKey, ";
        $sql.= "    IF( ";
        $sql.= "        STRCMP( ";
        $sql.= "            \"Sales Receipt\", ";
        $sql.= "            r_salesbyitemdetail.TxnType ";
        $sql.= "        ) = 0, ";
        $sql.= "        \"1\", ";
        $sql.= "        \"0\" ";
        $sql.= "    )as sourceID, ";
        $sql.= "    r_salesbyitemdetail.TxnType AS 'source' ";
        $sql.= "FROM ";
        $sql.= "    r_salesbyitemdetail ";
        $sql.= "LEFT JOIN item_assembly_quantity_percentage ON r_salesbyitemdetail.FullName = item_assembly_quantity_percentage.FullName ";
        $sql.= "  WHERE r_salesbyitemdetail.Date>= '$updateDate'";
        //$sql .= "  WHERE r_salesbyitemdetail.Date>= '2018-10-01'";
        $sql.= ")";
        $resInsert = mysqli_query($link, $sql); // insert one row into new table
        if ($resInsert) {
            echo "<br>items_sales_: updated";
			$sys_log .="items_sales_: updated"."\r\n";
			
        } else {
            print ("sales_shiped--$sql<br>");
        }
    } else {
        print ("delete failed!<br>");
    }
}
$t=time();
$filePath="sys_log/";
$filePath .=$t."_salesDetail.txt";
file_put_contents($filePath,$sys_log);
odbc_close($oConnect);
mysqli_close($link);

?>
