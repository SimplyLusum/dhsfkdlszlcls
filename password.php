<? include ("class/emailform.class.php");
	include ("class/member.class.php");
?>
<? $page=new Page($pgid); ?>
<?
	if (!empty($_POST)) { 
		
		$member=new Member();
		
		$result=$member->is_exist($_POST['email']);
		
		if ($result!=false) {
		
			$tmp=array();
			$tmp['name']=$result->Fields("m_name");
			$tmp['password']=$result->Fields("m_password");

			$email=new EmailForm(8);
			$email->generate_email($tmp);

			$email->send($result->Fields("m_name"),$result->Fields("m_email"));
			$display=1;
		} else { $display=2; }
	

	}	
?>

<div id="register" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<div class="left">
		<? echo $page->get_content1(); ?>
		<? if (!empty($_POST)) { ?>
			<div class="message">
			<? if ($display==1) { ?>
			Your password has been sent to your email address.
			<? } else { ?>
			Your email is not registered in our member database, please try again. 
			<? } ?>
			</div>
			<? } ?>		
		<br />
		<form action="" method="post" class="form" onsubmit="return validation(this)">
			<label>Please enter your Email Address:</label><input type="text" name="email" class="textbox" />
			<hr />
			<input type="image" src="images/btn-submit.gif" class="button" />
		</form>
		<script language="javascript">
		function validation(frm) {
			var str='';
			
			if (frm.email.value=='') { str+='Please enter your email address\n'; }
		
			
			if (str=='') { return true; } else { alert(str); frm.email.focus(); return false; }
		}
		
		</script>
	
	</div>
	<div class="right"><img src="<? echo $page->get_pic(); ?>" /></div>
	
</div><!-- howitwork -->

