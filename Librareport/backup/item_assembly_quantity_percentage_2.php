<?php
include ('connection.php');

$sql = "";
$sql .= "SELECT ";
$sql .= "    itemList.L0_ListID, ";
$sql .= "    itemList.TimeCreated, ";
$sql .= "    itemList.TimeModified, ";
$sql .= "    itemList.L0_FullName, ";
$sql .= "    itemList.Type, ";
$sql .= "    itemList.l1_ListID, ";
$sql .= "    itemList.l1_FullName, ";
$sql .= "    itemList.item_Quantity, ";
$sql .= "    IFNULL( ";
$sql .= "        item_assembly_quantity_percentage.First_level_percentage, ";
$sql .= "        1 ";
$sql .= "    ) AS l1_percentage, ";
$sql .= "    IFNULL( ";
$sql .= "        item_assembly_quantity_percentage.level2_ListID, ";
$sql .= "        itemList.l1_ListID ";
$sql .= "    ) AS level2_ListID, ";
$sql .= "    IFNULL( ";
$sql .= "        item_assembly_quantity_percentage.level2_FullName, ";
$sql .= "        itemList.l1_FullName ";
$sql .= "    ) AS level2_FullName, ";
$sql .= "    IFNULL( ";
$sql .= "        item_assembly_quantity_percentage.item_numbers, ";
$sql .= "        itemList.item_Quantity ";
$sql .= "    ) AS item_numbers, ";
$sql .= "    IFNULL( ";
$sql .= "        item_assembly_quantity_percentage.Price_Percentage, ";
$sql .= "        1 ";
$sql .= "    ) AS Percentage, ";
$sql .= "    ( ";
$sql .= "        CASE WHEN item_assembly_quantity_percentage.ListID IS NOT NULL THEN 1 ELSE 0 ";
$sql .= "    END ";
$sql .= ") AS ";
$sql .= "STATUS ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        item.ListID AS L0_ListID, ";
$sql .= "        item.TimeCreated, ";
$sql .= "        item.TimeModified, ";
$sql .= "        item.FullName AS L0_FullName, ";
$sql .= "        item.Type, ";
$sql .= "        IFNULL( ";
$sql .= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefListID, ";
$sql .= "            item.ListID ";
$sql .= "        ) AS l1_ListID, ";
$sql .= "        IFNULL( ";
$sql .= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefFullName, ";
$sql .= "            item.FullName ";
$sql .= "        ) AS l1_FullName, ";
$sql .= "        IFNULL( ";
$sql .= "            iteminventoryassemblyline.ItemInventoryAssemblyLnQuantity, ";
$sql .= "            1 ";
$sql .= "        ) AS item_Quantity ";
$sql .= "    FROM ";
$sql .= "        item ";
$sql .= "    LEFT JOIN iteminventoryassemblyline ON item.ListID = iteminventoryassemblyline.ListID ";
$sql .= ") AS itemList ";
$sql .= "LEFT JOIN item_assembly_quantity_percentage ON itemList.L0_ListID = item_assembly_quantity_percentage.ListID AND itemList.l1_ListID = item_assembly_quantity_percentage.level1_ListID ";
$sql .= "ORDER BY ";
$sql .= "    `TimeModified` ";
$sql .= "DESC ";
$sql .= "    , ";
$sql .= "    `ListID`" ;


$outputArr = array();



$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());

while($row=mysqli_fetch_array($result))
{
	$outputArr[]=$row;
	
}

echo json_encode($outputArr);
mysqli_close($link);
?>