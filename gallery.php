<? include ("class/gallery.class.php");
	
	if (empty($_REQUEST['p'])) { $p=1; } else { $p=$_REQUEST['p']; }
	if (empty($_REQUEST['tag'])) { $tag=""; } else { $tag=$_REQUEST['tag']; }

	$page=new Page($pgid); 
	
	$gallery=new Gallery();
	$tags=$gallery->get_tags();
	$rs=$gallery->get_list($p,$tag);

	
?>

<div id="gallery" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<? echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
	<div class="text"><? echo $page->get_content1(); ?>	</div>
	<div class="filter">
	<form action="index.php" method="get">
	<input type="hidden" name="pg" value="gallery" />
	Category:  
		<select name="tag" onchange="this.form.submit()">
			<? foreach ($tags as $row) { ?>
			<option value="<? echo $row['tag']; ?>" <? if ($row['tag']==$tag) { echo "selected"; } ?>><? echo $row['tag']; ?></option>
			<? } ?>
		</select>
	</form>
	</div>
	<div class="clear"></div>
	<div id="gallery-frame">
		<ul>
			<? foreach ($rs as $row) { ?>
			<li><a href="lightbox.php?iframe&pg=gallery&id=<? echo $row['gal_id']; ?>" class="video-popup"><img src="crop.php?f=upload/gallery_images/<? echo $row['gal_pic']; ?>&w=142&h=107" /></a></li>
			<? } ?>
		</ul>
	</div>
	
	<div id="pages">
		<? for ($i=1; $i<=count($gallery->get_totalpage()); $i++) { ?>
		<a href="?pg=gallery&tag=<? echo $tag; ?>&p=<? echo $p; ?>" class="<? if ($i==$page) { echo "selected"; } ?> <? if ($i==$gallery->get_totalpage()) { echo "last"; } ?>"><? echo $i; ?></a>
		<? } ?>
	</div>
	

</div><!-- gallery -->

