<?php 

    include('../include/include_admin.php');  

	IsAdmin();
	
	ini_set("error_reporting", E_ALL & ~E_NOTICE);
	
	
	if (empty($_REQUEST['pg'])) { $pg="dashboard"; } else { $pg=$_REQUEST['pg']; }
	if (empty($_REQUEST['action'])) { $action="view"; } else { $action=$_REQUEST['action']; }
	
	$inc_body=$pg . ".php";
	
	switch ($pg) {
		case "logout": session_destroy(); header("location:login.php");  break;
	}

	$temp=explode("-",$action);
	if (@$temp[1]=="action") { include ($inc_body); }



?>

<?php include('skin/global/header.php'); ?>
<?php include($inc_body); ?>
<?php include('skin/global/footer.php'); ?>