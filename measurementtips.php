<? $page=new Page($pgid); 
	
	$tmp=explode("\n",$page->get_content2());

?>

<div id="measurement" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<? echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
	<div class="text"><? echo $page->get_content1(); ?></div>
	<div class="tips-header"><? echo count($tmp); ?></div>
	<div class="tips-body">
		<table>
		<? for ($i=0; $i<count($tmp); $i++) { ?>
		<tr><td style="vertical-align:middle;background:url(images/tip<? echo ($i+1); ?>.gif) no-repeat left top;"><? echo $tmp[$i]; ?></td></tr>
		<tr><td class="space">&nbsp;</td></tr>
		<? } ?>
		</table>
	</div>
	<div class="note"><? echo $page->get_content3(); ?></div>
	<div class="wecan"><? echo $page->get_content4(); ?></div>

</div><!-- howitwork -->

