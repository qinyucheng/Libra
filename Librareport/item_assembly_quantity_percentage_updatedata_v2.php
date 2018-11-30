<?php
include ('connection_r.php');

$UpdateArr = $_REQUEST['activitiesArray'];
$index = 0;
 while ($index < count($UpdateArr ))
 {
	 
		$ListID=$UpdateArr[$index]['ListID'];
		$level1_ListID=$UpdateArr[$index]['level1_ListID'];
		$level2_ListID=$UpdateArr[$index]['level2_ListID'];
		$First_level_percentage=$UpdateArr[$index]['First_level_percentage'];
		
		
		//echo $ListID.':'.$level1_ListID.':'.$level2_ListID.':'.$First_level_percentage."</br>";
		
		if($level1_ListID==$level2_ListID)
		{
			//echo $level1_ListID."==".$level2_ListID;
			$Sql = "";
			$Sql .= "UPDATE item_assembly_quantity_percentage ";
			$Sql .= "	SET First_level_percentage = '$First_level_percentage', Price_Percentage = '$First_level_percentage', STATUS=1 ";
			$Sql .= "	WHERE ListID='$ListID' and level1_ListID='$level1_ListID' and level2_ListID='$level2_ListID';" ;
		
			$resUpdate = mysqli_query($link, $Sql); // update one row into new table
            if ($resUpdate) {
                //print "First_level_percentage1 :Success$Sql</br>";
               
            } else {
                //$msg.= "item---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>";
                //print ("First_level_percentage1---$Sql <br>");
            }

		}
		else{
			//echo $level1_ListID."!=".$level2_ListID."</br>";
			$Sql2 = "";
			$Sql2 .= "SELECT * FROM `item_assembly_quantity_percentage` where ListID='$level1_ListID'";
			$result=mysqli_query($link, $Sql2) or die ("die" .mysqli_error());
			while($row=mysqli_fetch_object($result))
			{
				foreach ($row as $key => $value)
				{
					if ($key == "level1_ListID" )
						{
							$l2_ListID=$value;
							
						}
						if ($key == "First_level_percentage" )
						{
							$l2_percentage=$value*$First_level_percentage;
							
						}
					
				}
				
				$Sql3 = "";
				$Sql3 .= "UPDATE item_assembly_quantity_percentage ";
				$Sql3 .= "	SET First_level_percentage = '$First_level_percentage', Price_Percentage = '$l2_percentage', STATUS=1 ";
				$Sql3 .= "	WHERE ListID='$ListID' and level1_ListID='$level1_ListID' and level2_ListID='$l2_ListID';" ;
			
				$resUpdate = mysqli_query($link, $Sql3); // update one row into new table
				if ($resUpdate) {
					//print "l2_level_percentage:Success</br>";
				   
				} else {
					//$msg.= "item---UPDATE item SET " . implode(", ", $keysValue) . "  WHERE $pare1;<br>";
					//print ("l2_level_percentage---$Sql3 <br>");
				}
				
			}
		
		}
	$index ++;	
 }

         


mysqli_close($link);
$arr['msg'] = 1;
	echo json_encode($arr);

?>