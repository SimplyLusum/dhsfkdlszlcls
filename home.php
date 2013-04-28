<? include_once("class/homepage.class.php");
	$home=new Homepage();
?>
<div id="home-left">
	<h1><? echo $home->get_title(); ?></h1>
	<h2><? echo $home->get_subtitle(); ?></h2>
	<div class="text"><? echo $home->get_content(); ?></div><!-- text -->
</div><!-- end home-left -->

<div id="home-right">
	<div id="video"><? echo $home->get_player(); ?>	</div><!-- video -->
	<div id="login">
		<a href="register" class="button">Virtual kitchen login</a>
		<div><a href="register">not member?</a> &nbsp;|&nbsp; <a href="password">forgot your password</a></div>
	</div><!-- login -->
	
</div><!-- home-right -->

<div id="option">
	<div id="btn1" class="option"><a href="design">Click here</a> to send us your design thoughts
  <p>We can draw up a design for you</p>
  </div>
	<div id="btn2" class="option"><a href="consultant">Click here</a> to have a luxury kitchen designed
  <p>Our consultant will contact you</p>
  </div>
</div><!-- option -->

<div id="logos"><img src="images/logos.jpg" /></div>
	
