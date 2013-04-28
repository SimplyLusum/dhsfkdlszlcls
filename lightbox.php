<? include ("include/include.php");

	switch ($_REQUEST['pg']) {
		case "video":
			include("class/video.class.php");
			
			$video=new Video($_REQUEST['id']);
			$text=$video->get();
		
			break;
			
		case "gallery":
			include("class/gallery.class.php");
			
			$gallery=new Gallery($_REQUEST['id']);
			$text=$gallery->get();
			break;
		
		case "testimonial-video":
			include("class/testimonial.class.php");
			
			$testimonial=new Testimonial($_REQUEST['id']);
			$text=$testimonial->get();		
			break;
			
		case "testimonial-pic":
			include("class/testimonial.class.php");
			
			$testimonial=new Testimonial($_REQUEST['id']);
			$text=$testimonial->get();		
			break;			
			
		case "friend":
		 	if (!empty($_POST)) { 	
				include("class/emailform.class.php");
				$email=new EmailForm(4);
				$_POST['url']="<a href=http://" . $_SERVER['HTTP_HOST'] . "/" . $_POST['url'] . ">http://" . $_SERVER['HTTP_HOST'] . "/" . $_POST['url'] . "</a>";
				$email->generate_email($_POST);
				$email->send($_POST['friendname'],$_POST['friendemail']);
			}
			
			break;
			
		case "colorideas":
		 	if (!empty($_POST)) { 	
				include("class/emailform.class.php");
				$email=new EmailForm(6);
				
				$swatches = '';
				if($_POST['swatch1'] != '') $swatches .= '<strong>Benchtop:</strong> '.$_POST['swatch1'];
				if($_POST['swatch2'] != '') $swatches .= '<br /><strong>Second color:</strong> '.$_POST['swatch2'];
				if($_POST['swatch3'] != '') $swatches .= '<br /><strong>Additional Color:</strong> '.$_POST['swatch2'];
				
				//$_POST['url']="<a href=http://" . $_SERVER['HTTP_HOST'] . "/" . $_POST['url'] . ">http://" . $_SERVER['HTTP_HOST'] . "/" . $_POST['url'] . "</a>";
				
				$_POST['swatches'] = $swatches;
				
				$email->generate_email($_POST);
				$email->send($_POST['name'],$_POST['email']);
			}
			
			break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Welcome to Exhibition Hire Services</title>
<link href="reset.css" rel="stylesheet" type="text/css" />
<link href="lightbox.css" rel="stylesheet" type="text/css" />

</head>
<script language="javascript">
function close_window() { parent.$.fancybox.close(); }
</script>
<body>
<? if ($_REQUEST['pg']=="video") { ?>
	<div id="video-popup">
		<div class="player"><? echo $video->get_player(); ?></div>
		<h1><? echo $text['vdo_title']; ?></h1>
		<p><? echo nl2br($text['vdo_desc']); ?></p>
		<p class="button"><a href="javascript:close_window()"><img src="images/btn-close.gif" /></a>
	</div>
<? } ?>

<? if ($_REQUEST['pg']=="gallery") { ?>
	<div id="gallery-popup">
		<div class="player"><img src="crop.php?f=upload/gallery_images/<? echo $text['gal_pic']; ?>&w=424&h=319" /></div>
		<p style="margin-top:8px;"><? echo nl2br($text['gal_desc']); ?></p>
		<p class="button"><a href="javascript:close_window()"><img src="images/btn-close.gif" /></a>
	</div>
<? } ?>

<? if ($_REQUEST['pg']=="testimonial-video") { ?>
	<div id="video-popup">
		<div class="player"><? echo $testimonial->get_player(); ?></div>
		<h1><? echo $text['t_title']; ?></h1>
		<p><? echo nl2br($text['t_memo']); ?></p>
		<p class="button"><a href="javascript:close_window()"><img src="images/btn-close.gif" /></a>
	</div>
<? } ?>

<? if ($_REQUEST['pg']=="testimonial-pic") { ?>
	<div id="testimonial-popup">
		<div class="player">
			<img src="crop.php?f=upload/testimonial_images/<? echo $text['t_before']; ?>&w=310&h=232" class="before" />
			<img src="crop.php?f=upload/testimonial_images/<? echo $text['t_after']; ?>&w=310&h=232" class="after" />
		</div>
		<h1><? echo $text['t_title']; ?></h1>
		<p><? echo nl2br($text['t_memo']); ?></p>
		<p class="button"><a href="javascript:close_window()"><img src="images/btn-close.gif" /></a>
	</div>
<? } ?>

<? if ($_REQUEST['pg']=="friend") { ?>
	<div id="friend-popup">
		<h1>Send to a friend</h1>
		<? if (empty($_POST)) { ?>
		<p>Please fill in the following form to send this webpage to your friend</p>
		<form action="" method="post">
		<input type="hidden" name="pg" value="friend" />
		<input type="hidden" name="url" value="<? echo $_REQUEST['url']; ?>" />
		<p><label>Your Name</label><input type="text" name="yourname" class="textbox" /></p>
		<p><label>Your Email</label><input type="text" name="youremail" class="textbox" /></p>
		<p><label>Your Friend's Name</label><input type="text" name="friendname" class="textbox" /></p>
		<p><label>Your Friend's Email</label><input type="text" name="friendemail" class="textbox" /></p>
		<p><label>Message to your friend</label><textarea name="message" class="textarea"></textarea></p>
		<p><input type="image" src="images/btn-submit.gif" /></p>
		</form>
		<? } else { ?>
		<p>Your message has been sent to your friend successfully.</p>
		<p><a href="javascript:close_window()"><img src="images/btn-close.gif" /></a>
		<? } ?>
	</div>
<? } ?>

<? if ($_REQUEST['pg']=="colorideas") { ?>
	<div id="friend-popup">
		<? if (empty($_POST)) { ?>
		<p>Please fill in the following details and we'll send your color idea swatches to you</p>
		<form action="" method="post">
		<input type="hidden" name="pg" value="colorideas" />
		<input type="hidden" name="swatch1" value="<?php echo $_REQUEST['swatch1']; ?>" />
        <input type="hidden" name="swatch2" value="<?php echo $_REQUEST['swatch2']; ?>" />
        <input type="hidden" name="swatch3" value="<?php echo $_REQUEST['swatch3']; ?>" />
		<p><label>Your Name</label><input type="text" name="name" class="textbox" /></p>
		<p><label>Your Email Address</label><input type="text" name="email" class="textbox" /></p>
		<p><label>Home Phone</label><input type="text" name="homephone" class="textbox" /></p>
		<p><label>Mob Phone</label><input type="text" name="mobphone" class="textbox" /></p>
        <p><label>Address</label><input type="text" name="address" class="textbox" /></p>
        <p><label>Suburb</label><input type="text" name="suburb" class="textbox" /></p>
        <p><label>City</label><input type="text" name="city" class="textbox" /></p>
		<p><input type="image" src="images/btn-submit.gif" /></p>
		</form>
		<? } else { ?>
		<p>Your color ideas have been sent to your email address.</p>
		<p><a href="javascript:close_window()"><img src="images/btn-close.gif" /></a>
		<? } ?>
	</div>
<? } ?>
</body>
</html>
