<?php
include ("class/emailform.class.php");
include ("class/member.class.php");

$page = new Page($pgid);

	if (!empty($_POST)) { 
	
		$username = mysql_real_escape_string($_POST['username']);
		
		$sql = "
			SELECT COUNT(*) as num
			FROM tbl_member_m
			WHERE m_username = '" . $username . "'";
			
		$count = $g_dbconn->Execute($sql);
				
		if($count->fields['num'] != 0) {
		
			$usernameExists = true;
		
		} else {
		
			$member=new Member();
			$member->save($_POST);
	
			$sendto= Setting::get_value("email_register");
			$email=new EmailForm(3);
			$email->generate_email($_POST);
			$email->send("Admin",$sendto);

			$email=new EmailForm(5);
			$email->generate_email($_POST);

			$email->send($_POST['name'],$_POST['email']);
		
		}
		
	

	}	
?>

<div id="register" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<div class="left">
		<? echo $page->get_content1(); ?>
		<? if (!empty($_POST) and !$usernameExists) { ?>
			<div class="message">Thank you for submitting your member registration form, we will contact you as soon as possible.</div>
			<? } ?>		
		<br />
		<form action="" method="post" class="form" onsubmit="return validation(this)">
			<label>Your Name:</label><input type="text" name="name" class="textbox" value="<?php echo $_POST['name'] ?>" />
			<label>Your Email Address:</label><input type="text" name="email" class="textbox" value="<?php echo $_POST['email'] ?>" />
			<label>Username:</label><input type="text" name="username" class="textbox" autocomplete="off" value="<?php echo $_POST['username'] ?>" />
<?php if($usernameExists): ?>
            <div class="form-error">That username is already in use</div>
<?php endif ?>
			<label>Password:</label><input type="password" name="password" class="textbox" value="<?php echo $_POST['password'] ?>" />
			<label>Re-enter Password:</label><input type="password" name="password1" value="<?php echo $_POST['password1'] ?>" class="textbox" />
			<label>Home Phone:</label><input type="text" name="phone" class="textbox" value="<?php echo $_POST['phone'] ?>" />
			<label>Mob Phone:</label><input type="text" name="mobile" class="textbox" value="<?php echo $_POST['mobile'] ?>" />
			<label>Address:</label><input type="text" name="address" class="textbox" value="<?php echo $_POST['address'] ?>" />
			<label>Suburb:</label><input type="text" name="suburb" class="textbox" value="<?php echo $_POST['suburb'] ?>" />
			<label>City:</label><input type="text" name="city" class="textbox" value="<?php echo $_POST['city'] ?>" />
			<hr />
			<input type="image" src="images/btn-register.gif" class="button" />
		</form>
		<script language="javascript">
		function validation(frm) {
			var str='';
			if (frm.name.value=='') { str+='Please enter your name\n'; }
			if (frm.email.value=='') { str+='Please enter your email address\n'; }
			if (frm.username.value=='') { str+='Please enter a preferred username\n'; }
			if (frm.password.value=='') { str+='Please enter your preferred password\n'; }
			if (frm.password1.value=='') { str+='Please re-enter your password\n'; }
			if (frm.password.value!=frm.password1.value) { str+='Passwords do not match, please enter password again\n'; }
			
			if (str=='') { return true; } else { alert(str); frm.name.focus(); return false; }
		}
		
		</script>
	
	</div>
	<div class="right"><img src="<? echo $page->get_pic(); ?>" /></div>
	
</div><!-- howitwork -->