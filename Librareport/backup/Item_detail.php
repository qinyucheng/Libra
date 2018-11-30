<?php
include ('connection.php');
$sql = "SELECT  DISTINCT`CustomerRefFullName`  FROM `detail_sales_2018` group by `itemName`,`CustomerRefFullName` ORDER by `itemName` ,sum(`Item_sales_price`) desc;";
$sql .= "SELECT DISTINCT `itemName` FROM `detail_sales_2018` WHERE itemName IS NOT NULL GROUP BY `itemName`, `CustomerRefFullName` ORDER BY `itemName`, SUM(`Item_sales_price`) DESC;";
$sql .= "SELECT  `itemName`,SUM(`Item_sales_Quantity`),sum(`Item_sales_price`),`CustomerRefFullName`  FROM `detail_sales_2018` group by `itemName`,`CustomerRefFullName` ORDER by `itemName` ,sum(`Item_sales_price`) desc;";

// Execute multi query

$sellersArr = array();
$itemsArr = array();
$salesAmount = array();


if (mysqli_multi_query($link,$sql))
{
	$tableCount=0;
  do
    {
    // Store first result set
    if ($result=mysqli_store_result($link)) {
		
		$tableCount++;
      // Fetch one and one row
      while ($row=mysqli_fetch_row($result))
        {
			if($tableCount==1)
			{
				$sellersArr[]=$row[0];
				
			}
			else if($tableCount==2)
			{
				
				$itemsArr[]=$row[0];
			}
			else if($tableCount==3)
			{
				
				$salesAmount[]=$row;
			}
			
        }
      // Free result set
      mysqli_free_result($result);
      }
    }
	
  while (mysqli_next_result($link));
 
  
} 
//$arrays=array_merge($sellersArr,$productsArr);

echo json_encode(array('sellersArr'=>$sellersArr,'itemsArr'=>$itemsArr,'salesAmount'=>$salesAmount));


mysqli_close($link);
?>