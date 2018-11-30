<?php
set_time_limit(300);
include ('connection_r.php');
$updateDate = date("Y-m-d", strtotime("-1 days"));
$startDate = date("Y-m-d", strtotime("-1 days"));
$endDate = date("Y-m-d");
#Connect to a System DSN "QuickBooks Data" with no user or password
$oConnect = odbc_connect("QuickBooks Data QRemote", "", "");
$date = date('Y-m-d h:i:s a', time());
$sys_log=$date."\r\n";
#Set the SQL Statement
sys_Inventory($oConnect, $link, $updateDate,$sys_log);

function sys_Inventory($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemInventory where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        $keysValue = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name" || $key == "FullName" || $key == "ParentRefListID" || $key == "ParentRefFullName") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
        }
        array_push($keys, 'Type');
        array_push($values, 'Inventory');
        $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
        if (mysqli_num_rows($result) > 0) {
            $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate) {
                //print "item Update :Success</br>";
                //$msg.= "item Update :Success<br>";
                
            } else {
                //$msg.= "item---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>";
                print ("item---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
            }
        } else {
            $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert) {
                //print "item_Inventory--Insert :Success</br>";
                
            } else {
                print ("item_Inventory--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')</br>");
            }
        }
    }
    echo "<br>total No. of item_Inventory: $items";
	$sys_log .="total No. of item_Inventory: $items \r\n";
	//$arr['item_Inventory']="total No. of item_Inventory: $items";
	
    odbc_free_result($oResult);
}
sys_Assembly($oConnect, $link, $updateDate,$sys_log);
function sys_Assembly($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemInventoryAssembly  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        $keysValue = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name" || $key == "FullName" || $key == "ParentRefListID" || $key == "ParentRefFullName") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
        }
        array_push($keys, 'Type');
        array_push($values, 'Assembly');
        $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
        if (mysqli_num_rows($result) > 0) {
            $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate) {
                //print "item Update :Success<br>";
                
            } else {
                print ("item_Assembly---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
            }
        } else {
            $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert) {
                //print "item_Assembly--Insert :Success<br>";
                
            } else {
                print ("item_Assembly--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
            }
        }
    }
    echo "<br>total No. of item_Assembly: $items";
	$sys_log .="total No. of item_Assembly: $items \r\n";
    odbc_free_result($oResult);
}
sys_Service($oConnect, $link, $updateDate,$sys_log);
function sys_Service($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemService  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        $keysValue = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name" || $key == "FullName" || $key == "ParentRefListID" || $key == "ParentRefFullName") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
            array_push($keys, 'Type');
            array_push($values, 'Service');
            $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
            if (mysqli_num_rows($result) > 0) {
                $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
                if ($resUpdate) {
                    //print "item Update :Success<br>";
                    
                } else {
                    print ("item_Service---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
                }
            } else {
                $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
                if ($resInsert) {
                    //print "item_Service--Insert :Success<br>";
                    
                } else {
                    print ("item_Service--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
                }
            }
        }
    }
    echo "<br>total No. of item_Service: $items";
	$sys_log .="total No. of item_Service: $items \r\n";
    odbc_free_result($oResult);
}
sys_NonInventory($oConnect, $link, $updateDate,$sys_log);
function sys_NonInventory($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemNonInventory  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        $keysValue = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name" || $key == "FullName" || $key == "ParentRefListID" || $key == "ParentRefFullName") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
            array_push($keys, 'Type');
            array_push($values, 'NonInventory');
            $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
            if (mysqli_num_rows($result) > 0) {
                $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
                if ($resUpdate) {
                    // print "item Update :Success<br>";
                    
                } else {
                    print ("item_NonInventory---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
                }
            } else {
                $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
                if ($resInsert) {
                    print "item_NonInventory--Insert :Success<br>";
                } else {
                    print ("item_NonInventory--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
                }
            }
        }
    }
    echo "<br>total No. of item_NonInventory: $items";
	$sys_log .="total No. of item_NonInventory: $items \r\n";
    odbc_free_result($oResult);
}
sys_OtherCharge($oConnect, $link, $updateDate,$sys_log);
function sys_OtherCharge($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemOtherCharge  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        $keysValue = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name" || $key == "FullName" || $key == "ParentRefListID" || $key == "ParentRefFullName") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
            array_push($keys, 'Type');
            array_push($values, 'OtherCharge');
            $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
            if (mysqli_num_rows($result) > 0) {
                $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
                if ($resUpdate) {
                    // print "item Update :Success<br>";
                    
                } else {
                    print ("item_ItemOtherCharge---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
                }
            } else {
                $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
                if ($resInsert) {
                    //print "item_ItemOtherCharge--Insert :Success<br>";
                    
                } else {
                    print ("item_ItemOtherCharge-INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
                }
            }
        }
    }
    echo "<br>total No. of item_ItemOtherCharge: $items";
		$sys_log .="total No. of item_ItemOtherCharge: $items \r\n";
    odbc_free_result($oResult);
}
sys_Discount($oConnect, $link, $updateDate,$sys_log);
function sys_Discount($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemDiscount  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        $keysValue = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name" || $key == "FullName" || $key == "ParentRefListID" || $key == "ParentRefFullName") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
            array_push($keys, 'Type');
            array_push($values, 'Discount');
            $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
            if (mysqli_num_rows($result) > 0) {
                $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
                if ($resUpdate) {
                    // print "item Update :Success<br>";
                    
                } else {
                    print ("item_Discount---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
                }
            } else {
                $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
                if ($resInsert) {
                    //  print "item_Discount--Insert :Success<br>";
                    
                } else {
                    print ("item_Discount--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
                }
            }
        }
    }
    echo "<br>total No. of item_Discount: $items";
		$sys_log .="total No. of item_Discount: $items \r\n";
    odbc_free_result($oResult);
}
sys_SalesTax($oConnect, $link, $updateDate,$sys_log);
function sys_SalesTax($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemSalesTax  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        $keysValue = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
            array_push($keys, 'Type');
            array_push($values, 'SalesTax');
            $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
            if (mysqli_num_rows($result) > 0) {
                $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
                if ($resUpdate) {
                    //print "item Update :Success<br>";
                    
                } else {
                    print ("ItemSalesTax---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
                }
            } else {
                $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
                if ($resInsert) {
                    print "ItemSalesTax--Insert :Success<br>";
                } else {
                    print ("ItemSalesTax--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
                }
            }
        }
    }
   echo "<br>total No. of ItemSalesTax: $items";
		$sys_log .="total No. of ItemSalesTax: $items \r\n";
    odbc_free_result($oResult);
}
sys_Group($oConnect, $link, $updateDate,$sys_log);
function sys_Group($oConnect, $link, $updateDate,&$sys_log) {
    $sSQL = "SELECT * FROM ItemGroup  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name") {
                $value = addslashes($value);
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "ListID") {
                    $getListId = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
            array_push($keys, 'Type');
            array_push($values, 'Group');
            $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");
            if (mysqli_num_rows($result) > 0) {
                $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
                if ($resUpdate) {
                    //  print "item Update :Success<br>";
                    
                } else {
                    print ("ItemGroup---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
                }
            } else {
                $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
                if ($resInsert) {
                    // print "ItemGroup--Insert :Success<br>";
                    
                } else {
                    print ("ItemGroup--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
                }
            }
        }
    }
    echo "<br>total No. of ItemGroup: $items";
	$sys_log .="total No. of ItemGroup: $items \r\n";
    odbc_free_result($oResult);
}
sys_assemblyline($oConnect, $link, $updateDate,$sys_log);
function sys_assemblyline($oConnect, $link, $updateDate,&$sys_log) {
    #Set the SQL Statement
    $sSQL = "SELECT * FROM iteminventoryassemblyline  where TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
    $oResult = odbc_exec($oConnect, $sSQL);
    $items = 0;
    while ($myRow = odbc_fetch_array($oResult)) {
        $items++;
        $keys = array();
        $values = array();
        foreach ($myRow as $key => $value) {
            if ($key == "ListID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "Name" || $key == "FullName" || $key == "ParentRefListID" || $key == "ParentRefFullName" || $key == "ItemInventoryAssemblyLnItemInventoryRefListID" || $key == "ItemInventoryAssemblyLnItemInventoryRefFullName" || $key == "ItemInventoryAssemblyLnQuantity" || $key == "FQPrimaryKey") {
                $value = addslashes($value);
                //print ($key."***".$value."</br>");
                array_push($keys, $key);
                array_push($values, $value);
                if ($key == "FQPrimaryKey") {
                    $FQPrimaryKey = "$value";
                    $pare1 = $key;
                    $pare1.= "=" . "'$value'";
                } else {
                    $pare2 = $key;
                    $pare2.= "=" . "'$value'";
                    array_push($keysValue, $pare2);
                }
            }
        }
        $result = mysqli_query($link, "SELECT * FROM ItemInventoryAssemblyLine WHERE FQPrimaryKey ='$FQPrimaryKey' ");
        //print ("SELECT * FROM ItemInventoryAssemblyLine WHERE FQPrimaryKey ='$FQPrimaryKey'</br> ");
        if (mysqli_num_rows($result) > 0) {
            $resUpdate = mysqli_query($link, "UPDATE ItemInventoryAssemblyLine SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate) {
                //print "ItemInventoryAssemblyLine--Update :Success<br>";
                
            } else {
                print ("ItemInventoryAssemblyLine--UPDATE ItemInventoryAssemblyLine SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
            }
        } else {
            $resInsert = mysqli_query($link, "INSERT INTO ItemInventoryAssemblyLine (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert) {
                //print "ItemInventoryAssemblyLine--Insert :Success<br>";
                
            } else {
                print ("ItemInventoryAssemblyLine--INSERT INTO ItemInventoryAssemblyLine (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
            }
        }
    }
    echo "<br>total No. of iteminventoryassemblyline: $items";
	$sys_log .="total No. of iteminventoryassemblyline: $items \r\n";
    odbc_free_result($oResult);
}

odbc_close($oConnect);
sys_item_assembly_quantity_percentage($link,$sys_log);
function sys_item_assembly_quantity_percentage($link,&$sys_log) {
    $sql = "";
    $sql.= "SELECT ";
    $sql.= "    test2.ListID, ";
    $sql.= "	test2.TimeCreated, ";
    $sql.= "        test2.TimeModified, ";
    $sql.= "		test2.FullName, ";
    $sql.= "		test2.Type, ";
    $sql.= "		test2.level1_ListID, ";
    $sql.= "		test2.level1_FullName, ";
    $sql.= "		test2.l1_Quantity as item_Quantity, ";
    $sql.= "		(1) as First_level_percentage, ";
    $sql.= "		test2.l2_ListID as level2_ListID, ";
    $sql.= "		test2.l2_FullName as level2_FullName, ";
    $sql.= "		(test2.l1_Quantity*test2.l2_Quantity) as item_numbers, ";
    $sql.= "        item_assembly_quantity_percentage.item_numbers, ";
    $sql.= "		(1) as Price_Percentage, ";
    $sql.= "		(0) as STATUS ";
    $sql.= "		 ";
    $sql.= "FROM ";
    $sql.= "    ( ";
    $sql.= "    SELECT ";
    $sql.= "        test.ListID, ";
    $sql.= "        test.TimeCreated, ";
    $sql.= "        test.TimeModified, ";
    $sql.= "        test.FullName, ";
    $sql.= "        test.Type, ";
    $sql.= "        IFNULL(test.l1_ListID, test.ListID) AS level1_ListID, ";
    $sql.= "        IFNULL( ";
    $sql.= "            test.l1_FullName, ";
    $sql.= "            test.FullName ";
    $sql.= "        ) AS level1_FullName, ";
    $sql.= "        IFNULL(test.l1_Quantity, 1) AS l1_Quantity, ";
    $sql.= "        IFNULL( ";
    $sql.= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefListID, ";
    $sql.= "            IFNULL(test.l1_ListID, test.ListID) ";
    $sql.= "        ) AS l2_ListID, ";
    $sql.= "        IFNULL( ";
    $sql.= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefFullName, ";
    $sql.= "            IFNULL( ";
    $sql.= "                test.l1_FullName, ";
    $sql.= "                test.FullName ";
    $sql.= "            ) ";
    $sql.= "        ) AS l2_FullName, ";
    $sql.= "        IFNULL( ";
    $sql.= "            iteminventoryassemblyline.ItemInventoryAssemblyLnQuantity, ";
    $sql.= "            1 ";
    $sql.= "        ) AS l2_Quantity ";
    $sql.= "    FROM ";
    $sql.= "        ( ";
    $sql.= "        SELECT ";
    $sql.= "            item.ListID, ";
    $sql.= "            item.TimeCreated, ";
    $sql.= "            item.TimeModified, ";
    $sql.= "            item.Name, ";
    $sql.= "            item.FullName, ";
    $sql.= "            item.Type, ";
    $sql.= "            iteminventoryassemblyline.ListID AS l0_listID, ";
    $sql.= "            iteminventoryassemblyline.FullName AS l0_FullName, ";
    $sql.= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefListID AS l1_ListID, ";
    $sql.= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefFullName AS l1_FullName, ";
    $sql.= "            iteminventoryassemblyline.ItemInventoryAssemblyLnQuantity AS l1_Quantity ";
    $sql.= "        FROM ";
    $sql.= "            item ";
    $sql.= "        LEFT JOIN iteminventoryassemblyline ON item.ListID = iteminventoryassemblyline.ListID ";
    $sql.= "    ) AS test ";
    $sql.= "LEFT JOIN iteminventoryassemblyline ON test.l1_ListID = iteminventoryassemblyline.ListID ";
    $sql.= ") AS test2 ";
    $sql.= "LEFT JOIN item_assembly_quantity_percentage ON test2.ListID = item_assembly_quantity_percentage.listID AND test2.level1_ListID = item_assembly_quantity_percentage.level1_ListID AND test2.l2_listID = item_assembly_quantity_percentage.level2_ListID ";
    $sql.= "WHERE ";
    $sql.= "    test2.l2_listID <> '' AND item_assembly_quantity_percentage.level2_ListID IS NULL";
    $resCheck = mysqli_query($link, $sql); // insert one row into new table
    if ($resCheck) {
        $items = 0;
        while ($row = mysqli_fetch_Array($resCheck)) {
            $items++;
            $keys = array();
            $values = array();
            foreach ($row as $key => $value) {
                array_push($keys, $key);
                array_push($values, $value);
            }
            $resInsert = mysqli_query($link, "INSERT INTO item_assembly_quantity_percentage (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')");
        }
        echo "<br>total No. of  item_assembly_quantity_percentage: $items";
		$sys_log .="total No. of item_assembly_quantity_percentage: $items"."\r\n";
    } else {
        print ("item_assembly_quantity_percentage Check failure");
    }
}

mysqli_close($link);
$t=time();
$filePath="sys_log/";
$filePath .=$t."_items.txt";
file_put_contents($filePath,$sys_log);
?>
