<?php
include ('connection.php');

// get data from QuickBooks
set_time_limit(120);

$sql=" SELECT * FROM sales WHERE FQPrimaryKey = '7822-1439190654|7838-1439190654|'";
$salesresult = mysqli_query($link, $sql);
while($sales_row=mysqli_fetch_array($salesresult))
	{
		
		$SalesReceiptLineItemRefListID=$sales_row['SalesReceiptLineItemRefListID'];
		$FQPrimaryKey=$sales_row['FQPrimaryKey'];
		 $result = mysqli_query($link, "SELECT * FROM sales_shiped WHERE FQPrimaryKey ='$FQPrimaryKey' ");
		 print "SELECT * FROM sales_shiped WHERE FQPrimaryKey ='$FQPrimaryKey' <br>";
		 if (mysqli_num_rows($result) > 0)
		{
			print "delete from  sales_shiped  WHERE FQPrimaryKey= '$FQPrimaryKey' "."<br>";
		
			$resUpdate = mysqli_query($link, "delete from  sales_shiped  WHERE FQPrimaryKey= '$FQPrimaryKey' "); 
			
			  if ($resUpdate)
            {
               print ("delete success!<br>");
                save_sales_shiped($sales_row,$SalesReceiptLineItemRefListID,$link);

            }
            else
            {
                print ("delete failed!<br>");

            }
			 
			 
		}
		else
		{
			 print ("no!<br>");
			 save_sales_shiped($sales_row,$SalesReceiptLineItemRefListID,$link);
			
		}
	
		
	}



function save_sales_shiped($sales_row,$ListID,$link)
{

  //1 get all percentage and quantity
   $result1 = mysqli_query($link, "SELECT * FROM item_assembly_quantity_percentage WHERE item_assembly_quantity_percentage.ListID ='$ListID' ");
   while($row=mysqli_fetch_array($result1))
	{

		$TxnID=$sales_row['TxnID'];
		$TimeCreated=$sales_row['TimeCreated'];
		$TimeModified=$sales_row['TimeModified'];
		$shipdate=$sales_row['shipdate'];
		$CustomerRefFullName=$sales_row['CustomerRefFullName'];
		$SalesReceiptLineItemRefListID=$sales_row['SalesReceiptLineItemRefListID'];
		$SalesReceiptLineItemRefFullName=$sales_row['SalesReceiptLineItemRefFullName'];
		$SalesReceiptLineDesc=$sales_row['SalesReceiptLineDesc'];
		$SalesReceiptLineQuantity=$sales_row['SalesReceiptLineQuantity'];
		$SalesReceiptLineAmount=$sales_row['SalesReceiptLineAmount'];
		$level1_FullName=$row['level1_FullName'];
		$level2_FullName=$row['level2_FullName'];
		$item_numbers=$row['item_numbers'];
		$Price_Percentage=$row['Price_Percentage'];
		$items_sales_num=$SalesReceiptLineQuantity*$item_numbers;
		$items_sales_Amount=$SalesReceiptLineAmount*$Price_Percentage;
		$FQPrimaryKey=$sales_row['FQPrimaryKey'];

		
		$pare1='FQPrimaryKey='.$FQPrimaryKey;
		$keys = array("TxnID", "TimeCreated", "TimeModified",  "shipdate", "CustomerRefFullName", "SalesReceiptLineItemRefListID", "SalesReceiptLineItemRefFullName", "SalesReceiptLineDesc", "SalesReceiptLineQuantity","SalesReceiptLineAmount", "level1_FullName", "level2_FullName", "item_numbers", "Price_Percentage", "items_sales_num", "items_sales_Amount", "FQPrimaryKey");
		$values = array(addslashes($TxnID), addslashes($TimeCreated), addslashes($TimeModified),  addslashes($shipdate), addslashes($CustomerRefFullName), addslashes($SalesReceiptLineItemRefListID), addslashes($SalesReceiptLineItemRefFullName), addslashes($SalesReceiptLineQuantity), addslashes($SalesReceiptLineDesc), addslashes($SalesReceiptLineAmount), addslashes($level1_FullName), addslashes($level2_FullName), addslashes($item_numbers), addslashes($Price_Percentage), addslashes($items_sales_num), addslashes($items_sales_Amount), addslashes($FQPrimaryKey));
		
		//print "INSERT INTO sales_shiped (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "');<br>";
		
            $resInsert = mysqli_query($link, "INSERT INTO sales_shiped (" . implode(", ", $keys) . ") VALUES ('" . implode("', '", $values) . "')"); // insert one row into new table
            if ($resInsert)
            {
                
                print "sales_shiped--Insert :Success<br>";

            }
            else
            {
                print ("sales_shiped--Insert :Failed<br>");

            }
		
	}


}

mysqli_close($link);

?>
