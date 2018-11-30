<?php
include ('connection.php');

$action = $_GET['action'];
if($action=="ListID")
{
		$ListID = stripslashes(trim($_POST['ListID']));
		$sql = " SELECT * FROM `item_assembly_quantity_percentage` WHERE ListID=";
		$sql .="'$ListID'";
		
		if ($result=mysqli_query($link, $sql))
		  {
		  // Return the number of rows in result set
		  $rowcount=mysqli_num_rows($result);
		
		  mysqli_free_result($result);
		  
		  if($rowcount==1)
		{
			
			$arr['success'] = 1;
		}
		else
		{
			
			$arr['success'] = 0;
		}
		  
		  
 }
	echo json_encode($arr);
}

if($action=="update")
{
		$myJSONArr = array();
		$ListID = stripslashes(trim($_POST['ListID']));
		$myJSONArr = json_decode($_POST['myJSON']);
		
		$sql = "";
		
		for ($i = 0; $i < count($myJSONArr); $i++) {
			$ListID = "";
		$level1_ListID = "";
		$level2_ListID = "";
		$value = "";
				foreach ($myJSONArr[$i] as  $key => $value) {
					
					if($key=="ListID")
					{
						
						$ListID=$value;
					}if($key=="level1_ListID")
					{
						
						$level1_ListID=$value;
					}if($key=="level2_ListID")
					{
						
						$level2_ListID=$value;
					}if($key=="value")
					{
						
						$value=$value;
					}
					
			}
			$sql .= "UPDATE item_assembly_quantity_percentage SET Price_Percentage='$value' WHERE ListID='$ListID' and level1_ListID='$level1_ListID' and level2_ListID='$level2_ListID';";
 
}
  
 echo ($sql);
}


if($action=="insert")
{
		$myJSONArr = array();
		$ListID = stripslashes(trim($_POST['ListID']));
		$myJSONArr = json_decode($_POST['myJSON']);
		
		$sql = "";
		
		for ($i = 0; $i < count($myJSONArr); $i++) {
		$ListID = "";
		$FullName = "";
		$level1_FullName = "";
		$level2_FullName = "";
		$level1_ListID = "";
		$level2_ListID = "";
		$value = "";
				foreach ($myJSONArr[$i] as  $key => $value) {
					
					if($key=="ListID")
					{
						
						$ListID=$value;
					}if($key=="level1_ListID")
					{
						
						$level1_ListID=$value;
					}if($key=="level2_ListID")
					{
						
						$level2_ListID=$value;
					}if($key=="value")
					{
						
						$value=$value;
					}
					if($key=="FullName")
					{
						
						$FullName=$value;
					}
					if($key=="level1_FullName")
					{
						
						$level1_FullName=$value;
					}
					if($key=="level2_FullName")
					{
						
						$level2_FullName=$value;
					}
					
			}
			$sql .= "UPDATE item_assembly_quantity_percentage SET Price_Percentage='$value' WHERE ListID='$ListID' and level1_ListID='$level1_ListID' and level2_ListID='$level2_ListID';";
 
}
  
 echo ($sql);
}


mysqli_close($link);
?>