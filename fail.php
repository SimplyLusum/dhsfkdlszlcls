<? $page=new Page($pgid); ?>

<div id="about" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<div class="left"><? echo $page->get_content1(); ?>
	
	<p><a href="payment.php">Click here to try again</a></p>
	</div>
	<div class="right"><img src="<? echo $page->get_pic(); ?>" /></div>

</div><!-- howitwork -->

