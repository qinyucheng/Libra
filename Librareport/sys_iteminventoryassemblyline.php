<?php
set_time_limit(240);
include ('connection_r.php');
$start_date = stripslashes(trim($_POST['startDate']));
$end_date = stripslashes(trim($_POST['endDate']));
print $updateDate."<br>";

#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data QRemote", "", "");

#Set the SQL Statement

$sSQL = "SELECT * FROM iteminventoryassemblyline";

$oResult = odbc_exec($oConnect, $sSQL);

while ($myRow = odbc_fetch_array($oResult)) {
    $keys = array();
    $values = array();
	
    foreach ($myRow as $key => $value) {
        if ($key == "ListID" ||$key == "TimeCreated" ||$key == "TimeModified" ||$key == "Name" ||$key == "FullName" ||$key == "ParentRefListID" ||$key == "ParentRefFullName" ||$key == "ItemInventoryAssemblyLnItemInventoryRefListID" ||$key == "ItemInventoryAssemblyLnItemInventoryRefFullName" ||$key == "ItemInventoryAssemblyLnQuantity" ||$key == "FQPrimaryKey" ) {
			
			$value=addslashes($value);
			array_push($keys, $key);
			array_push($values, $value);
        }
		
 
    }
	
$resInsert = mysqli_query($link, "INSERT INTO iteminventoryassemblyline (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
	if ($resInsert)
	{
		
		print "iteminventoryassemblyline--Insert :Success<br>";

	}
	else
	{
		print ("iteminventoryassemblyline--INSERT INTO itemtest (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");

	}
}

    odbc_close($oConnect);
	mysqli_close($link);
?>