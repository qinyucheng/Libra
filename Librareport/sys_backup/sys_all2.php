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
set_time_limit(1200);

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
$updateDate='2014-01-01';
print $updateDate;
$oConnect = odbc_connect("QuickBooks Data", "", "");


if (!$oConnect)
{print "1234";
    exit("Connection Failed: " . $oConnect);
}

getData("salesreceiptline",$oConnect,$link,$updateDate);

function getData($tableName,$oConnect,$link,$updateDate)
{
    if ($tableName == "salesreceiptline")
    {
       //$sSQL = "SELECT * from SalesReceiptLine WHERE TimeCreated> {ts '2014-01-01 00:00:00.000'} and TimeCreated<= {ts '2015-01-01 00:00:00.000'}";
       $sSQL = "SELECT * from SalesReceiptLine WHERE TimeCreated>= {ts '2017-12-01 00:00:00.000'} and TimeCreated<= {ts '2017-12-31 00:00:00.000'}";
       

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
        if ($tableName == "salesreceiptline")
        {
            save_salesreceiptline($rows,$link,$updateDate);
				

        }
       
    }

    odbc_free_result($oResult);
}

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

      
            $resInsert = mysqli_query($link, "INSERT INTO sales2 (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "');"); // insert one row into new table
            if ($resInsert)
            {
               
                print "salesreceiptline--Insert2 :Success<br>";
            }
            else
            {
                print ("salesreceiptline--INSERT INTO sales2 (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')<br>");
            }
       
    }

}

echo json_encode(array('synchronize'=>'DONE'));
// Close the connection
odbc_close($oConnect);
exit("Connection exit: " .  odbc_connect("QuickBooks Data", "", ""));
mysqli_close($link);

?>
