<? include ("class/emailform.class.php"); ?>
<? $page=new Page($pgid); 

	if ($pg=="design") { $subject="Design Thoughts"; } else { $subject="Talk to consultant"; }
	
	if (!empty($_POST)) { 
		$email=new EmailForm(2);
		$email->generate_email($_POST);
		
		if ($pg=="design") { $rec=Setting::get_value("email_design"); } else { $rec=Setting::get_value("email_consult"); }
		
		$email->send("Administrator",$rec);

	}	
	
?>
<div id="sendus" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<div class="text"><? echo $page->get_content1(); ?></div>
	<? if (!empty($_POST)) { ?>
	<div class="message">Thank you for your enquiry, we will contact you as soon as possible</div>
	<? } ?>
	<form action="" method="post" class="form">
	<input type="hidden" name="subject" value="<? echo $subject; ?>" />
	
		<label>Your Name:</label><input type="text" name="name" class="textbox" />
		<label>Your Email Address:</label><input type="text" name="email" class="textbox" />
		<label>Contact Phone Number:</label><input type="text" name="phone" class="textbox" /><br /><br />
		<label>Description:</label><textarea name="description" class="textarea"></textarea>
		<input type="image" src="images/btn-submit.gif" class="button" />
	</form>
</div><!-- howitwork -->

