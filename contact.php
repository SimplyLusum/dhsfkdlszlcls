<? include ("class/emailform.class.php"); ?>
<? $page=new Page($pgid); ?>
<? if (!empty($_POST)) { 
		$email=new EmailForm(1);
		$email->generate_email($_POST);
		$email->send("Administrator",Setting::get_value("email_contact"));

	}
?>
<div id="contact" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<div class="left">
		<? echo $page->get_content1(); ?>
		
		<? if (!empty($_POST)) { ?>
		<div class="message">Thank you for your enquiry, we will contact you as soon as possible</div>
		<? } ?>
		<form action="" method="post" class="form">
			<label>Your Email Address:</label><input type="text" name="email" class="textbox" />
			<label>Subject:</label><input type="text" name="subject" class="textbox" />
			<label>Message:</label><textarea name="message" class="textarea"></textarea>
			<input type="image" src="images/btn-submit.gif" class="button" />
		</form>
		
	</div>
	<div class="right"><img src="<? echo $page->get_pic(); ?>" /></div>

</div><!-- howitwork -->

