<? include ("class/template.class.php");

	$page=new Page($pgid); 
	
	$template=new Template();
	$rs=$template->get_list();
	
?>

<div id="template" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<? echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
	<div class="text"><? echo $page->get_content1(); ?></div>
	<ul>
		<? foreach ($rs as $row) { ?>
		<li>
			<div class="col1"><h2><? echo $row['tmp_title']; ?></h2><p><? echo nl2br($row['tmp_desc']); ?></p></div>
			<div class="col2"><a href="upload/template_images/<? echo $row['tmp_pic']; ?>" class="group"><img src="thumb.php?f=upload/template_images/<? echo $row['tmp_pic']; ?>&w=179&h=133" /></a></div>
		</li>
		<? } ?>
	</ul>
	

</div><!-- video -->

