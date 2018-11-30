<?php
include ('connection.php');
$sql = "SELECT DISTINCT`CustomerRefFullName` FROM `sales_items_tab_0824` group by `level2_FullName`,`CustomerRefFullName` ORDER by `level2_FullName` ,sum(`items_sales_price`) desc;";
$sql .= "SELECT DISTINCT `level2_FullName` FROM `sales_items_tab_0824` WHERE `level2_FullName` IS NOT NULL GROUP BY `level2_FullName`, `CustomerRefFullName` ORDER BY `level2_FullName`, SUM(`items_sales_price`) DESC;";
$p0='2018-01-01 00:00:00.000000'; 
$p1='2018-02-28 00:00:00.000000'; 
$sql.="call sp_items_sales_num_Amount('".$p0."','".$p1."')";
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