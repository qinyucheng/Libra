<?php
set_time_limit(240);
include ('connection.php');
//$updateDate=date("Y-m-d");
$updateDate = date("Y-m-d", strtotime("-1 days"));
print $updateDate;
#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data QRemote", "", "");
#Set the SQL Statement
$item_sSQL = "SELECT * from ItemService ";
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
			/*
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
            array_push($keysValue, $pare2);*/
        }
		/*
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
        }*/
    }
}


    odbc_close($oConnect);
	mysqli_close($link);
?>