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
sys_PhysicalInventoryWorksheet($oConnect, $link,$sys_log);

function sys_PhysicalInventoryWorksheet($oConnect, $link,&$sys_log) {
    #Set the SQL Statement
    $sSQL = "sp_report PhysicalInventoryWorksheet show Text, Blank,ItemDesc, QuantityOnHand";
    #Perform the query
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    $resTRUNCATE = mysqli_query($link, "TRUNCATE TABLE physicalinventoryworksheet"); // insert one row into new table
    if ($resTRUNCATE) {
        // print "TRUNCATE--Insert :Success<br>";
        
    } else {
        print ("TRUNCATE--ITRUNCATE TABLE physicalinventoryworksheet<br>");
    }
    while ($myRow = odbc_fetch_array($oResult)) {
        $keys = array();
        $values = array();
        foreach ($myRow as $key => $value) {
            if ($key == "Text" && $value != '') {
                $TextValue = $value;
            }
            if ($key == "Blank" && $value != '' && $TextValue != '') {
                if ($TextValue != 'Z group items') {
                    $keys[] = $key;
                    $value = addslashes($value);
                    $splitArr = explode("(", $value);
                    $values[] = $TextValue . ':' . $splitArr[0];
                }
            }
            if ($key == "ItemDesc" && $value != '' && $TextValue != '') {
                if ($TextValue != 'Z group items') {
                    $keys[] = $key;
                    $values[] = addslashes($value);
                }
            }
            if ($key == "QuantityOnHand" && $value != '' && $TextValue != '') {
                if ($TextValue != 'Z group items') {
                    $keys[] = $key;
                    $values[] = addslashes($value);
                    $checkValue = true;
                }
            }
        }
        if (count($keys) > 1) {
            if (end($values) != '') {
                $items++;
                save_inventory($keys, $values, $link);
            }
        }
    }
    echo "<br>total No. of  PhysicalInventory: $items";
	$sys_log .="total No. of  PhysicalInventory: $items \r\n";
	//$arr['PhysicalInventory'] = "total No. of  PhysicalInventory: $items";
    odbc_free_result($oResult);
}
function save_inventory($keys, $values, $link) {
    $resInsert = mysqli_query($link, "INSERT INTO physicalinventoryworksheet (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
    if ($resInsert) {
        //print "inventory--Insert :Success<br>";
        
    } else {
        print ("inventory--INSERT INTO physicalinventoryworksheet (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
    }
}
odbc_close($oConnect);

mysqli_close($link);
$t=time();
$filePath="sys_log/";
$filePath .=$t."_physicalinventory.txt";
file_put_contents($filePath,$sys_log);

?>
