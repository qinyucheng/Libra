<?php
set_time_limit(240);
include ('connection.php');
//$updateDate=date("Y-m-d");
$updateDate = date("Y-m-d", strtotime("-1 days"));
print $updateDate;
#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data", "", "");
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
//odbc_free_result($oResult);



//update Item Table

updateItemTable($updateDate,$oConnect,$link);
function updateItemTable($updateDate,$oConnect,$link)
{
$item_sSQL = "SELECT * from item WHERE TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
print $item_sSQL;
$item_oResult = odbc_exec($oConnect, $item_sSQL);

if (!$item_oResult) {
    exit("Error in SQL");
}
$rows = array();
$item_count = 0;
while ($myRow = odbc_fetch_array($item_oResult)) {
    $rows[] = $myRow;
    $item_count++;
	print $item_count;
}
if ($item_count == 0) {
    print "item No data need update";
} else {
    foreach ($rows as $row) {
        $keys = array();
        $values = array();
        $getListId = '';
        $keysValue = array();
        foreach ($row as $key => $value) {
            if ($key == "ListID") {
                $getListId = "$value";
                $pare1 = $key;
                $pare1.= "=" . "'$value'";
            }
            if ($key == "Description" || $key == "SalesDesc" || $key == "PurchaseDesc") {
                $value = addslashes($value);
            }
            array_push($keys, $key);
            array_push($values, addslashes($value));
            $pare2 = $key;
            $pare2.= "=" . "'$value'";
            array_push($keysValue, $pare2);
        }
        $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
        if (mysqli_num_rows($result) > 0) {
            $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate) {
                print "item Update :Success<br>";
            } else {
                print ("item---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
            }
        } else {
            $resInsert = mysqli_query($link, "INSERT INTO itemtest (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert) {
                print "item--Insert :Success<br>";
            } else {
                print ("item--INSERT INTO itemtest (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
            }
        }
    }
	}
}

	
    odbc_close($oConnect);
	mysqli_close($link);
?>