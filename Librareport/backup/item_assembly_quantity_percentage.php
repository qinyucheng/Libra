<?php
include ('connection.php');



$sql = "";
$sql .= "SELECT ";
$sql .= "    item_assembly_quantity_percentage.ListID, ";
$sql .= "    item_assembly_quantity_percentage.FullName, ";
$sql .= "    item_assembly_quantity_percentage.Description, ";
$sql .= "    item_assembly_quantity_percentage.level1_ListID, ";
$sql .= "    item_assembly_quantity_percentage.level1_FullName, ";
$sql .= "    item_assembly_quantity_percentage.level2_ListID, ";
$sql .= "    item_assembly_quantity_percentage.level2_FullName, ";
$sql .= "    item_assembly_quantity_percentage.item_numbers, ";
$sql .= "    item_assembly_quantity_percentage.Price_Percentage, ";
$sql .= "    item_assembly_quantity_percentage.item_numbers ";
$sql .= "FROM ";
$sql .= "    ( ";
$sql .= "    SELECT ";
$sql .= "        level1_tab.ListID, ";
$sql .= "        level1_tab.FullName, ";
$sql .= "        level1_tab.Description, ";
$sql .= "        level1_tab.level0_listID, ";
$sql .= "        level1_tab.level1_listID, ";
$sql .= "        level1_tab.level1_FullName, ";
$sql .= "        level1_tab.level1_Quantity, ";
$sql .= "        IFNULL( ";
$sql .= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefListID, ";
$sql .= "            level1_tab.level1_listID ";
$sql .= "        ) AS level2_listID, ";
$sql .= "        IFNULL( ";
$sql .= "            iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefFullName, ";
$sql .= "            level1_tab.level1_FullName ";
$sql .= "        ) AS level2_FullName, ";
$sql .= "        IFNULL( ";
$sql .= "            iteminventoryassemblyline.ItemInventoryAssemblyLnQuantity, ";
$sql .= "            1 ";
$sql .= "        ) AS level2_Quantity, ";
$sql .= "        level1_tab.level1_Quantity * IFNULL( ";
$sql .= "            iteminventoryassemblyline.ItemInventoryAssemblyLnQuantity, ";
$sql .= "            1 ";
$sql .= "        ) AS item_numbers ";
$sql .= "    FROM ";
$sql .= "        ( ";
$sql .= "        SELECT ";
$sql .= "            item.ListID, ";
$sql .= "            item.FullName, ";
$sql .= "            item.Description, ";
$sql .= "            iteminventoryassemblyline.ListID AS level0_listID, ";
$sql .= "            IFNULL( ";
$sql .= "                iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefListID, ";
$sql .= "                item.ListID ";
$sql .= "            ) AS level1_listID, ";
$sql .= "            IFNULL( ";
$sql .= "                iteminventoryassemblyline.ItemInventoryAssemblyLnItemInventoryRefFullName, ";
$sql .= "                item.FullName ";
$sql .= "            ) AS level1_FullName, ";
$sql .= "            IFNULL( ";
$sql .= "                iteminventoryassemblyline.ItemInventoryAssemblyLnQuantity, ";
$sql .= "                1 ";
$sql .= "            ) AS level1_Quantity ";
$sql .= "        FROM ";
$sql .= "            item ";
$sql .= "        LEFT JOIN iteminventoryassemblyline ON item.ListID = iteminventoryassemblyline.ListID ";
$sql .= "    ) AS level1_tab ";
$sql .= "LEFT JOIN iteminventoryassemblyline ON level1_tab.level1_listID = iteminventoryassemblyline.ListID ";
$sql .= "WHERE ";
$sql .= "    level1_tab.level1_Quantity * IFNULL( ";
$sql .= "        iteminventoryassemblyline.ItemInventoryAssemblyLnQuantity, ";
$sql .= "        1 ";
$sql .= "    ) <> '0' ";
$sql .= ") AS QBitems ";
$sql .= "LEFT JOIN item_assembly_quantity_percentage ON QBitems.listID = item_assembly_quantity_percentage.listID AND QBitems.level2_listID = item_assembly_quantity_percentage.level2_listID" ;
$sql.= " ORDER BY";
$sql.= " item_assembly_quantity_percentage.ListID,";
$sql.= " item_assembly_quantity_percentage.FullName,";
$sql.= " item_assembly_quantity_percentage.level1_FullName ";


$outputArr = array();



$result=mysqli_query($link, $sql) or die ("die" .mysqli_error());

while($row=mysqli_fetch_array($result))
{
	$outputArr[]=$row;
	
}

echo json_encode($outputArr);
mysqli_close($link);
?>