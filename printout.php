<? $page=new Page($pgid); ?>

<div id="measurement" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<? echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
	<div class="text"><? echo $page->get_content1(); ?></div>
	

</div><!-- howitwork -->

