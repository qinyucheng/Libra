<?php

$connectStatus=false;
$time_start = microtime(true);
if (extension_loaded('apc')) {
    echo "APC-User cache: " . apc_clear_cache('user') . "\n";
    echo "APC-System cache: " . apc_clear_cache() . "\n";
	
}

if (extension_loaded('apcu')) {
    echo "APCu cache: " . apcu_clear_cache() . "\n";
	
}
   
if (function_exists('opcache_reset')) {
    // Clear it twice to avoid some internal issues...
    opcache_reset();
    opcache_reset();
	
}

include ('connection.php');

// get data from QuickBooks
set_time_limit(200);

// Connect to a System DSN "QuickBooks Data" with no user or password
// $oConnect = odbc_connect("QuickBooks Data", "", "");
date_default_timezone_set('America/Chicago');
//$date = date('Y/M/D h:i:s a', strtotime("-5 days"));
//$timeStemp = time();
//print $date;
//print "SELECT * from item WHERE TimeModified >= {ts '" . date("Y-m-d",strtotime("-1 days")) . " 00:00:00.000'}";
//$date = date('Y/M/D h:i:s a', strtotime("-5 days"));
//$updateDate = date("Y-m-d",strtotime("-1 days"));
//$updateDate=date("Y-m-d");
$updateDate='2018-09-28';
print $updateDate;
$oConnect = odbc_connect("QuickBooks Data", "", "");


if (!$oConnect)
{
    exit("Connection Failed: " . $oConnect);
}
getData("item",$oConnect,$link,$updateDate);

function getData($tableName,$oConnect,$link,$updateDate)
{
    if ($tableName == "item")
    {
        $sSQL = "SELECT * from item WHERE TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
        //$sSQL = "SELECT * from item WHERE TimeModified >= {ts '" . date("Y-m-d") . " 00:00:00.000'}";
        //$sSQL = "SELECT * from item WHERE TimeModified >= {ts '" .'2018-09-20' . " 00:00:00.000'}";

    }
    else if ($tableName == "iteminventory")
    {
        $sSQL = "SELECT * from iteminventory WHERE TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
        //$sSQL = "SELECT * from iteminventory WHERE TimeModified >= {ts '" . date("Y-m-d") . " 00:00:00.000'}";
       //$sSQL = "SELECT * from iteminventory WHERE TimeModified >= {ts '" .'2018-09-20' . " 00:00:00.000'}";

    }
    else if ($tableName == "ItemInventoryAssemblyLine")
    {
        $sSQL = "SELECT * from ItemInventoryAssemblyLine WHERE TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
        //$sSQL = "SELECT * from ItemInventoryAssemblyLine WHERE TimeModified >= {ts '" . date("Y-m-d") . " 00:00:00.000'}";
        //$sSQL = "SELECT * from ItemInventoryAssemblyLine WHERE TimeModified >= {ts '" .'2018-09-20' . " 00:00:00.000'}";

    }
    else if ($tableName == "salesreceiptline")
    {
        $sSQL = "SELECT * from SalesReceiptLine WHERE TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
        //$sSQL = "SELECT * from SalesReceiptLine WHERE TimeModified >= {ts '" . date("Y-m-d") . " 00:00:00.000'}";
        //$sSQL = "SELECT * from SalesReceiptLine WHERE TimeModified >= {ts '" .'2018-09-25'. " 00:00:00.000'}";

    }
    else if ($tableName == "creditmemoline")
    {
       $sSQL = "SELECT * from CreditMemoLine WHERE TimeModified >= {ts '" . $updateDate . " 00:00:00.000'}";
       //$sSQL = "SELECT * from CreditMemoLine WHERE TimeModified >= {ts '" . date("Y-m-d") . " 00:00:00.000'}";
		
		//$sSQL = "SELECT * from CreditMemoLine WHERE TimeModified >=  {ts '2018-09-25 00:00:00.000'}";
		 
    }

    $oResult = odbc_exec($oConnect, $sSQL);
    if (!$oResult)
    {
        exit("Error in SQL");
    }
    $rows = array();
    $count = 0;
    while ($myRow = odbc_fetch_array($oResult))
    {

        $rows[] = $myRow;
        $count++;
		
    }
    if ($count == 0)
    {
        print $tableName . "No data need update";
        return;

    }
    else
    {
        if ($tableName == "item")
        {
            save_item($rows,$link);
        }

        else if ($tableName == "iteminventory")
        {
            save_iteminventory($rows,$link);

        }
        else if ($tableName == "itemInventoryAssemblyLine")
        {
            save_itemInventoryAssemblyLine($rows,$link);

        }
        else if ($tableName == "salesreceiptline")
        {
            save_salesreceiptline($rows,$link,$updateDate);
				

        }
        else if ($tableName == "creditmemoline")
        {
            save_creditmemoline($rows,$link,$updateDate);
			

        }

    }

    odbc_free_result($oResult);
}

function save_item($rows,$link)
{
    foreach ($rows as $row)
    {
       
        $keys = array();
        $values = array();
        $getListId = '';
        $keysValue = array();
        foreach ($row as $key => $value)
        {
            if ($key == "ListID")
            {

                $getListId = "$value";
                $pare1 = $key;
                $pare1 .= "=" . "'$value'";
            }
            if ($key == "Description" || $key == "SalesDesc" || $key == "PurchaseDesc")
            {

                $value = addslashes($value);
            }
            array_push($keys, $key);
            array_push($values, addslashes($value));
            $pare2 = $key;
            $pare2 .= "=" . "'$value'";
            array_push($keysValue, $pare2);
        }

        $result = mysqli_query($link, "SELECT * FROM Item WHERE ListID ='$getListId' ");

        if (mysqli_num_rows($result) > 0)
        {
            $resUpdate = mysqli_query($link, "UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate)
            {
               
                print "item Update :Success<br>";

            }
            else
            {
                print ("item---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");

            }
        }
        else
        {
            $resInsert = mysqli_query($link, "INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert)
            {
                
                print "item--Insert :Success<br>";

            }
            else
            {
                print ("item--INSERT INTO item (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");

            }
        }

    }
}
getData("iteminventory",$oConnect,$link,$updateDate);

function save_iteminventory($rows,$link)
{

    foreach ($rows as $row)
    {
       
        $keys = array();
        $values = array();
        $getListId = '';
        $keysValue = array();
        foreach ($row as $key => $value)
        {
            if ($key == "ListID")
            {

                $getListId = "$value";
                $pare1 = $key;
                $pare1 .= "=" . "'$value'";
            }
            if ($key == "Description" || $key == "SalesDesc" || $key == "PurchaseDesc")
            {

                 $value = addslashes($value);
            }
            array_push($keys, $key);
            array_push($values, addslashes($value));

            $pare2 = $key;
            $pare2 .= "=" . "'$value'";
            array_push($keysValue, $pare2);
        }

        $result = mysqli_query($link, "SELECT * FROM iteminventory WHERE ListID ='$getListId' ");

        if (mysqli_num_rows($result) > 0)
        {
            $resUpdate = mysqli_query($link, "UPDATE iteminventory SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate)
            {
               
                print "iteminventory--Update :Success<br>";

            }
            else
            {
                print ("iteminventory--UPDATE iteminventory SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br><br>");

            }
        }
        else
        {
            $resInsert = mysqli_query($link, "INSERT INTO iteminventory (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert)
            {
               
                print "iteminventory--Insert :Success<br>";

            }
            else
            {
                print ("iteminventory--INSERT INTO iteminventory (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");

            }
        }

    }
}
getData("ItemInventoryAssemblyLine",$oConnect,$link,$updateDate);

function save_ItemInventoryAssemblyLine($rows,$link)
{

    foreach ($rows as $row)
    {
        $count++;
        $keys = array();
        $values = array();
        $getListId = '';
        $keysValue = array();
        foreach ($row as $key => $value)
        {
            if ($key == "FQPrimaryKey")
            {

                $getListId = "$value";
                $pare1 = $key;
                $pare1 .= "=" . "'$value'";
            }

            array_push($keys, $key);
            array_push($values, addslashes($value));

            $pare2 = $key;
            $pare2 .= "=" . "'$value'";
            array_push($keysValue, $pare2);
        }

        $result = mysqli_query($link, "SELECT * FROM ItemInventoryAssemblyLine WHERE FQPrimaryKey ='$getListId' ");

        if (mysqli_num_rows($result) > 0)
        {
            $resUpdate = mysqli_query($link, "UPDATE ItemInventoryAssemblyLine SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate)
            {
               
                print "ItemInventoryAssemblyLine--Update :Success<br>";

            }
            else
            {
                print ("ItemInventoryAssemblyLine--UPDATE ItemInventoryAssemblyLine SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");

            }
        }
        else
        {
            $resInsert = mysqli_query($link, "INSERT INTO ItemInventoryAssemblyLine (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert)
            {
                
                print "ItemInventoryAssemblyLine--Insert :Success<br>";

            }
            else
            {
                print ("ItemInventoryAssemblyLine--INSERT INTO ItemInventoryAssemblyLine (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");

            }
        }

    }

}

getData("salesreceiptline",$oConnect,$link,$updateDate);

function save_salesreceiptline($rows,$link,$updateDate)
{
	
    foreach ($rows as $row)
    {
        
        $keys = array();
        $values = array();
        $FQPrimaryKey = '';
        $keysValue = array();
        foreach ($row as $key => $value)
        {
            if ($key == "TxnID" || $key == "TimeCreated" || $key == "TimeModified" || $key == "ShipDate" || $key == "CustomerRefListID" || $key == "CustomerRefFullName" || $key == "SalesReceiptLineItemRefListID" || $key == "SalesReceiptLineItemRefFullName" || $key == "SalesReceiptLineDesc" || $key == "SalesReceiptLineQuantity" || $key == "SalesReceiptLineRate" || $key == "SalesReceiptLineAmount" || $key == "FQPrimaryKey")
            {
                array_push($keys, $key);
                array_push($values, addslashes($value));
                if ($key == "FQPrimaryKey")
                {
                    $FQPrimaryKey = "$value";
                    $pare1 = $key;
                    $pare1 .= "=" . "'$value'";
                }
                if ($key == "SalesReceiptLineDesc")
                {

                   
                    $value = addslashes($value);
                }
                $pare2 = $key;
                $pare2 .= "=" . "'$value'";
                array_push($keysValue, $pare2);
            }
        }

        $result = mysqli_query($link, "SELECT * FROM sales WHERE FQPrimaryKey ='$FQPrimaryKey' ");
        if (mysqli_num_rows($result) > 0)
        {
            $resUpdate = mysqli_query($link, "UPDATE sales SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate)
            {
                
                print "salesreceiptline--Update2 :Success<br>";
            }
            else
            {
                print ("salesreceiptline--UPDATE sales SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");
            }
        }
        else
        {
            $resInsert = mysqli_query($link, "INSERT INTO sales (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "');"); // insert one row into new table
            if ($resInsert)
            {
               
                print "salesreceiptline--Insert2 :Success<br>";
            }
            else
            {
                print ("salesreceiptline--INSERT INTO sales (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
            }
        }
		

    }
	save_sales_shiped($link,$updateDate);


}


function save_sales_shiped($link,$updateDate)
{
	//$getDate=date("Y-m-d");
	//$getDate='2018-09-20';
	
	
			//delect from sales_shiped
		
		
			$resUpdate = mysqli_query($link, "delete from  sales_shiped_new  WHERE TimeModified>= '$updateDate' "); 
			
			  if ($resUpdate)
            {
			   print "delete from  sales_shiped_new  WHERE TimeModified>= '$updateDate' "."<br>";
               print ("delete success!<br>");
			   
				$sql = "";
				$sql .= "INSERT INTO sales_shiped_new( ";
				$sql .= "SELECT ";
				$sql .= "    sales.TxnID, ";
				$sql .= "    sales.TimeCreated, ";
				$sql .= "    sales.TimeModified, ";
				$sql .= "    sales.shipdate, ";
				$sql .= "    sales.CustomerRefFullName, ";
				$sql .= "    sales.SalesReceiptLineItemRefListID, ";
				$sql .= "    sales.SalesReceiptLineItemRefFullName, ";
				$sql .= "    sales.SalesReceiptLineDesc, ";
				$sql .= "    sales.SalesReceiptLineQuantity, ";
				$sql .= "    sales.SalesReceiptLineAmount, ";
				$sql .= "    item_assembly_quantity_percentage.level1_ListID, ";
				$sql .= "    item_assembly_quantity_percentage.level1_FullName, ";
				$sql .= "    item_assembly_quantity_percentage.level2_ListID, ";
				$sql .= "    item_assembly_quantity_percentage.level2_FullName, ";
				$sql .= "    item_assembly_quantity_percentage.Description, ";
				$sql .= "    item_assembly_quantity_percentage.item_numbers, ";
				$sql .= "    item_assembly_quantity_percentage.Price_Percentage, ";
				$sql .= "    ( ";
				$sql .= "        sales.SalesReceiptLineQuantity * item_numbers ";
				$sql .= "    ) AS items_sales_num, ";
				$sql .= "    ( ";
				$sql .= "        sales.SalesReceiptLineAmount * item_assembly_quantity_percentage.Price_Percentage ";
				$sql .= "    ) AS items_sales_Amount, ";
				$sql .= "    (1) AS sourceID, ";
				$sql .= "    ('sales') AS 'source', ";
				$sql .= "    sales.FQPrimaryKey ";
				$sql .= "FROM ";
				$sql .= "    sales ";
				$sql .= "LEFT JOIN item_assembly_quantity_percentage ON sales.SalesReceiptLineItemRefListID = item_assembly_quantity_percentage.ListID ";
				$sql .= "WHERE ";
				$sql .= "    (TimeModified >= '$updateDate'))" ;

				$resInsert = mysqli_query($link, $sql); // insert one row into new table
				if ($resInsert)
				{
				   
					print "sales_shiped--Insert_shiped :Success<br>";
					 print ("sales_shiped--$sql<br>");
					 
				}
				else
				{
					print ("sales_shiped--$sql<br>");
				}
              

            }
            else
            {
                print ("delete failed!<br>");

            }
			
			


}

function save_creditmemoline_shiped($link,$updateDate)
{
			$sql = "";
			$sql .= "insert INTO sales_shiped_new( ";
			$sql .= "SELECT ";
			$sql .= "    TxnID, ";
			$sql .= "    TimeCreated, ";
			$sql .= "    TimeModified, ";
			$sql .= "    shipdate, ";
			$sql .= "    CustomerRefFullName, ";
			$sql .= "    CreditMemoLineItemRefListID AS SalesReceiptLineItemRefListID, ";
			$sql .= "    CreditMemoLineItemRefFullName AS SalesReceiptLineItemRefFullName, ";
			$sql .= "    ('null') SalesReceiptLineDesc, ";
			$sql .= "   CreditMemoLineQuantity AS SalesReceiptLineQuantity, ";
			$sql .= "    CreditMemoLineAmount AS SalesReceiptLineAmount, ";
			$sql .= "    level1_ListID, ";
			$sql .= "    level1_FullName, ";
			$sql .= "    level2_ListID, ";
			$sql .= "    level2_FullName, ";
			$sql .= "    Description, ";
			$sql .= "    item_numbers, ";
			$sql .= "    Price_Percentage, ";
			$sql .= "    -( ";
			$sql .= "        CreditMemoLineQuantity * ifnull(item_numbers,0) ";
			$sql .= "    ) AS items_sales_num, ";
			$sql .= "    -( ";
			$sql .= "        CreditMemoLineAmount * ifnull(Price_Percentage,1) ";
			$sql .= "    ) AS items_sales_Amount, ";
			$sql .= "    FQPrimaryKey, ";
			$sql .= "    (2) AS sourceID, ";
			$sql .= "    ('creditmemoline') AS 'source' ";
			$sql .= "FROM ";
			$sql .= "    `creditmemoline` ";
			$sql .= "LEFT JOIN item_assembly_quantity_percentage ON CreditMemoLineItemRefListID = item_assembly_quantity_percentage.ListID ";
			$sql .= "WHERE ";
			$sql .= "    (TimeModified >= '$updateDate'))" ;

$resInsert = mysqli_query($link, $sql); // insert one row into new table
	if ($resInsert)
	{
	   
		print "sales_shiped--Insert_creditmemoline :Success<br>";
		 print ("sales_shiped--Insert_creditmemoline--$sql<br>");
		 
	}
	else
	{
		print ("sales_shiped--$sql<br>");
	}
	
	
}

getData("creditmemoline",$oConnect,$link,$updateDate);
function save_creditmemoline($rows,$link,$updateDate)
{
    
    foreach ($rows as $row)
    {
       
        $keys = array();
        $values = array();
        $getListId = '';
        $keysValue = array();
        foreach ($row as $key => $value)
        {
            if ($key == "FQPrimaryKey")
            {

                $getListId = "$value";
                $pare1 = $key;
                $pare1 .= "=" . "'$value'";
            }

            array_push($keys, $key);
            array_push($values, addslashes($value));

            $pare2 = $key;
			$addslashesValue=addslashes($value);
            $pare2 .= "=" . "'$addslashesValue'";
            array_push($keysValue, $pare2);
        }

        $result = mysqli_query($link, "SELECT * FROM creditmemoline WHERE FQPrimaryKey ='$getListId' ");
		print($getListId.'<br>');
		print(mysqli_num_rows($result).'<br>');
		
        if (mysqli_num_rows($result) > 0)
        {
            $resUpdate = mysqli_query($link, "UPDATE creditmemoline SET " . implode(", ", $keysValue) . "  WHERE $pare1;"); // update one row into new table
            if ($resUpdate)
            {
                
                print "creditmemoline--Update :Success<br>";

            }
            else
            {
                print ("creditmemoline--UPDATE creditmemoline SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>");

            }
        }
        else
        {
            $resInsert = mysqli_query($link, "INSERT INTO creditmemoline (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert)
            {
                
                echo "creditmemoline--Insert :Success";

            }
            else
            {
                print ("creditmemoline--INSERT INTO creditmemoline (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");

            }
        }

    }
	save_creditmemoline_shiped($link,$updateDate);

}

echo json_encode(array('synchronize'=>'DONE'));
// Close the connection
odbc_close($oConnect);



mysqli_close($link);

?>
