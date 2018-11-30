<?php
/**
 * @Description:this page use to  verify user login info
 * @Copyright (C) 2014 All Rights Reserved.
 * -----------------------------------------------------------------------------
 * @author: Qinyu
 * @Create: 2014-2-12
 * @Modify:
*/

session_start();
include("connection_r.php");
//get post command info from webPage 
$action = $_GET['action'];

// get user eamil and password, and to verify them.
// if user login successful, it is will create the session and return success, otherwise will return error message
$myArray = array();
if ($action == 'login') {  
	
	$useremail = stripslashes(trim($_POST['Email']));
	$pass = stripslashes(trim($_POST['Password']));
	
	
	// call the function(checkUser) to verify user email and password 
	$sql="select * from users where UserAccountName = '$useremail' AND `UserPass` = '$pass'";
   $myArray = array();
if ($result = $link->query($sql)) {
	$rows=mysqli_num_rows($result); 
		while ($obj=mysqli_fetch_object($result))
		{
			
				$_SESSION['Lastname'] = $obj->LastName;
				$_SESSION['FirstName'] = $obj->FirstName;
				$_SESSION['UserAccountName'] = $obj->UserAccountName;
				$_SESSION['UserRole'] =$obj->UserRole;
				// $myArray[] = $obj;
		
		}
    
}
	echo $rows;
}


//Logout system
elseif ($action == 'logout') {  
	unset($_SESSION);
	session_destroy();
	$arr['msg'] = 1;
	echo json_encode($arr);
}

 
?>
