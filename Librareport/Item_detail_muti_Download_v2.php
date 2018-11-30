<?php
include ('connection_r.php');

$start_date = stripslashes(trim($_POST['startDate']));
$end_date = stripslashes(trim($_POST['endDate']));
$sql ="";
$sql .="SELECT  customerreffullname AS Customer, level2_listid,Ifnull(level2_fullname,'Return') AS ItemName, Description,Sum(items_sales_num) AS total_sales_items,(Sum(items_sales_amount)/(Sum(items_sales_num))) AS Unit, Sum(items_sales_amount) AS total_amount";
$sql .=" FROM    sales_shiped_new";
$sql .=" WHERE (customerreffullname='Ebay' OR customerreffullname='eBay PayPal' OR customerreffullname='Libra on line store') AND (shipdate >= '$start_date' AND shipdate <= '$end_date')";
$sql .= "    GROUP BY   level2_FullName";
$sql .= "     HAVING (SUM(items_sales_amount)<>'0' and  SUM(items_sales_amount)<>'0'); ";

$sql .="SELECT  customerreffullname AS Customer, level2_listid,Ifnull(level2_fullname,'Return') AS ItemName, Description,Sum(items_sales_num) AS total_sales_items,(Sum(items_sales_amount)/(Sum(items_sales_num))) AS Unit, Sum(items_sales_amount) AS total_amount";
$sql .=" FROM    sales_shiped_new";
$sql .=" WHERE customerreffullname='Amazon' AND (shipdate >= '$start_date' AND shipdate <= '$end_date')";
$sql .= "    GROUP BY   level2_FullName";
$sql .= "     HAVING (SUM(items_sales_amount)<>'0' and  SUM(items_sales_amount)<>'0'); ";

$sql .="SELECT  customerreffullname AS Customer, level2_listid,Ifnull(level2_fullname,'Return') AS ItemName, Description,Sum(items_sales_num) AS total_sales_items,(Sum(items_sales_amount)/(Sum(items_sales_num))) AS Unit, Sum(items_sales_amount) AS total_amount";
$sql .=" FROM    sales_shiped_new";
$sql .=" WHERE customerreffullname='Walmart' AND (shipdate >= '$start_date' AND shipdate <= '$end_date')";
$sql .= "    GROUP BY   level2_FullName";
$sql .= "     HAVING (SUM(items_sales_amount)<>'0' and  SUM(items_sales_amount)<>'0'); ";

$sql .="SELECT  customerreffullname AS Customer, level2_listid,Ifnull(level2_fullname,'Return') AS ItemName, Description,Sum(items_sales_num) AS total_sales_items,(Sum(items_sales_amount)/(Sum(items_sales_num))) AS Unit, Sum(items_sales_amount) AS total_amount";
$sql .=" FROM    sales_shiped_new";
$sql .=" WHERE customerreffullname='Newegg' AND (shipdate >= '$start_date' AND shipdate <= '$end_date')";
$sql .= "    GROUP BY   level2_FullName";
$sql .= "     HAVING (SUM(items_sales_amount)<>'0' and  SUM(items_sales_amount)<>'0'); ";
//echo $sql;
// Execute multi query

$ebaylibarPayPalArr = array();
$amazonArr = array();
$WalmartArr = array();
$NeweggArr = array();


if (mysqli_multi_query($link,$sql))
{
	$tableCount=0;
  do
    {
    // Store first result set
    if ($result=mysqli_store_result($link)) {
		
		$tableCount++;
      // Fetch one and one row
      while ($row=mysqli_fetch_object($result))
        {
			if($tableCount==1)
			{
				
				$ebaylibarPayPalArr[]=$row;
				
			}
			else if($tableCount==2)
			{
				$amazonArr[]=$row;
				
			}
			else if($tableCount==3)
			{
				$WalmartArr[]=$row;
				
			}
			else if($tableCount==4)
			{
				
				$NeweggArr[]=$row;
			}
			
        }
      // Free result set
      mysqli_free_result($result);
      }
    }
	
  while (mysqli_next_result($link));
 
  
} 
//$arrays=array_merge($sellersArr,$productsArr);

echo json_encode(array('ebaylibarPayPal'=>$ebaylibarPayPalArr,'amazon'=>$amazonArr,'walmart'=>$WalmartArr,'newegg'=>$NeweggArr));


mysqli_close($link);
?>