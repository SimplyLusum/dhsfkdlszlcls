<?php 

include ("class/terms.class.php");

	$page=new Page($pgid); 
	$terms=new Terms();
    
    $sql = "select * from tbl_termgroup_grp order by grp_order asc";
    $result =  $g_dbconn->Execute($sql);
    $n = 0;
    while ($row = $result->fetchrow()) {
        if ($n == 0) { 
            $class="class=\"first active\"";
        } else {
            $class ="";
        }
        $style = "style=\"z-index: ".(10-$n)."\"";
        $n ++;
        $idx[] = $row['grp_id'];
        $name[] = $row['grp_name'];
        $description[] = $row['grp_description'];
        $side_tit[] = $row['grp_title'];
        $side_desc[] = $row['grp_desc'];
        $tabs .= "<li $ac $class $style><a rel=\"".$row['grp_id']."\">".$row['grp_name']."</a><span class=\"closetab\"></span></li>";     
    }
	
	$n = 0;

?>

<div id="faq" class="general">
	<h1><?php echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<?php echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
    <div class="clear"></div>
    <ul id="tabs">
        <?php echo $tabs; ?>
        <div class="clear"></div>
    </ul>
    <div id="faqs">
        
        <?php foreach ($idx as $idx): ?>
        <div id="tab_<?php echo $idx; ?>">
            <?php $rs=$terms->get_list($idx); ?>
            <div class="left">
                <h1><?php echo $side_tit[$n]; ?></h1>
                <div class="clear"></div>
                <p><?php echo $side_desc[$n]; ?></p>
                <br/><br/>
                <img src="<?php echo $page->get_pic(); ?>" />
            </div>
            <div class="right">
                <h1><?php echo $name[$n]; ?></h1>
                <div class="clear"></div>
                <p><?php echo $description[$n]; ?></p>
                <ul>
                <?php $i=1; foreach ($rs as $row) { ?>
                    <li><h2 id="question<?php echo $n."_".$i; ?>" class="question"><a href="javascript:faq_click('<?php echo $n."_".$i; ?>')"><?php echo $row['term_title']; ?></a></h2>
                    <p id="answer<?php echo $n."_".$i; ?>" class="answer"><?php echo $row['term_desc']; ?></p>
                    </li>
                <?php $i++; } ?>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
        <?php $n++; endforeach; ?>
        
    </div>

</div><!-- howitwork -->

