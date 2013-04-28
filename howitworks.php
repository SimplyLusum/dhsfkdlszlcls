<?php $page=new Page($pgid); ?>

<div id="howitwork" class="general">
	<h1><?php echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<?php echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
    <div class="text"><?php echo $page->get_content1(); ?></div>
    <div id="hiw_nav">
        <a id="hiw_1" rel="1"></a>
        <a id="hiw_2" rel="2"></a>
        <a id="hiw_3" rel="3"></a>
        <a id="hiw_4" rel="4"></a>
        <a id="hiw_5" rel="5"></a>
    </div>
    
<?php
    $sql = "select * from tbl_hiw_hiw";
    $result = $g_dbconn->Execute($sql);
    $c = 1;
    while ($row = $result->fetchrow()) {
        $side .= "<div class=\"hiw_side hiw_side_".$c."\">\n<h1>".$row['hiw_side_title']."</h1>\n".$row['hiw_side_description']."\n</div><div style=\"clear: both;\"></div>\n\n";
        $right .= "<div class=\"hiw_right hiw_right_".$c."\">\n<h1 style=\"float: none;\">".$row['hiw_title']."</h1>\n".nl2br($row['hiw_description'])."\n</div>\n\n";
        $c ++;
    }
?>
	
	<div class="left">
        
        <?php echo $side; ?>
        
        <div id="downloadpdf">
            <p><strong>Online Kitchens Brochure</strong><br/>Download our latest design brochure for award winning design ideas, colours and styles.</pa>
            <a href="">Download</a>
        </div>
        
        <div id="printpage">
            <form><input type="button" value="Print this page" onclick="window.print();return false;" /></form> 
        </div>
        
    </div>
    
	<div class="right">
        <div id="belt">
            <?php echo $right; ?>        
        </div>
    </div>
    
    
</div><!-- howitwork -->

