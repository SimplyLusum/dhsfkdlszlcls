<? include ("class/testimonial.class.php");

	$page=new Page($pgid); 
	
	$testimonial=new Testimonial();
	$rs=$testimonial->get_list();
	
?>

<div id="testimonial" class="general">
	<h1><? echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<? echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
	<div class="text"><? echo $page->get_content1(); ?></div>
	<ul>
		<? foreach ($rs as $row) { ?>
		<li>
			<div class="col1"><h2><? echo $row['t_title']; ?></h2><p><? echo $row['t_desc']; ?></p></div>
			<div class="col2"><a href="lightbox.php?iframe&pg=testimonial-video&id=<? echo $row['t_id']; ?>" class="video-popup"><img src="thumb.php?f=upload/testimonial_images/<? echo $row['t_video_pic']; ?>&w=114&h=300" /></a></div>
			<div class="col3">
				<a href="lightbox.php?pg=testimonial-pic&id=<? echo $row['t_id']; ?>" class="testimonial-popup"><img src="thumb.php?f=upload/testimonial_images/<? echo $row['t_before']; ?>&w=114&h=300" class="before" /></a>
				<a href="lightbox.php?pg=testimonial-pic&id=<? echo $row['t_id']; ?>" class="testimonial-popup"><img src="thumb.php?f=upload/testimonial_images/<? echo $row['t_after']; ?>&w=114&h=300" class="after" /></a>
			</div>
		</li>
		<? } ?>
	</ul>
	

</div><!-- video -->

