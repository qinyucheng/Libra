<?php
set_time_limit(240);
include ('connection.php');
//$updateDate=date("Y-m-d");
$updateDate = date("Y-m-d", strtotime("-1 days"));
print $updateDate;
#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data QRemote", "", "");
#Set the SQL Statement
$sSQL = "sp_report PhysicalInventoryWorksheet show Text, Blank,ItemDesc, QuantityOnHand";
#Perform the query
$oResult = odbc_exec($oConnect, $sSQL);
$resTRUNCATE = mysqli_query($link, "TRUNCATE TABLE physicalinventoryworksheet"); // insert one row into new table
if ($resTRUNCATE) {
    print "TRUNCATE--Insert :Success<br>";
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
    //print_r($keys);
    //print_r($values);
    if (count($keys) > 1) {
        if (end($values) != '') {
            save_inventory($keys, $values, $link);
        }
    }
}
function save_inventory($keys, $values, $link) {
    $resInsert = mysqli_query($link, "INSERT INTO physicalinventoryworksheet (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
    if ($resInsert) {
        print "inventory--Insert :Success<br>";
    } else {
        print ("inventory--INSERT INTO physicalinventoryworksheet (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
    }
}
odbc_free_result($oResult);

	
    odbc_close($oConnect);
	mysqli_close($link);
?>