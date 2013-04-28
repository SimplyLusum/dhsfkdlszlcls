<? include ("class/video.class.php");

	$page=new Page($pgid); 
	
	$video=new Video();
	$rs=$video->get_list();
	
?>

<div id="video" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<? echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
	<div class="text"><? echo $page->get_content1(); ?></div>
	<ul>
		<? $i=0; foreach ($rs as $row) { ?>
		<li <? if ($i++%2==1) { echo "class=\"end\""; } ?>><a href="lightbox.php?iframe&pg=video&id=<? echo $row['vdo_id']; ?>" class="video-popup"><img src="images/video.jpg" /></a>
		<div class="data">
			<h2><? echo $row['vdo_title']; ?></h2>
			<p class="desc"><? echo $row['vdo_desc']; ?></p>
			<p class="link"><a href="lightbox.php?iframe&pg=video&id=<? echo $row['vdo_id']; ?>" class="video-popup">Watch this video</a></p>
		</div>
		</li>
		<? } ?>
	</ul>
	

</div><!-- video -->

