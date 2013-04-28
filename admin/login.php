<? include('../include/include_admin.php');    
	
	$error=0;
	
	if (!empty($_POST['flag'])) {
	
		$t_login=substr($_POST['login'],0,20);
		$t_password=substr($_POST['password'],0,20);
		$result=Dataface::Login($t_login,$t_password);
		
		if ($result!=false) { 
			$_SESSION['uid']=$result;
			header("Location: index.php"); die();
		} else { 
			$error=1;
		}

	}	
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>OnlineKitchens.co.nz Website Administration System</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="skin/css/global.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="login-container">
<img src="images/logo.jpg">
<form action="" method="post" name="frm_login" class="login-form">
<input type="hidden" name="flag" value="true">
<label>Username</label>
<input name="login" type="text" id="login2" size="20" maxlength="15" class="txtbox">
<label>Password</label>
<input name="password" type="password" id="password2" size="20" maxlength="15" class="txtbox">
<? if ($error==1) { ?>
<div class="login-error">Login or password is incorrect, please try again!</div>
<? } ?>
<input type="submit" class="submit" value="Login">
</form>
</div>
</body>
</html>
