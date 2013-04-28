<?php
$page=new Page($pgid); 
	
$tmp=explode("\n",$page->get_content2());

$q = "SELECT * FROM tbl_swatch_sw WHERE sw_type = 'Benchtop' and sw_status = 1";
$list1 = $g_dbconn->Execute($q)->GetArray();
$list1_items = array();

$q = "SELECT * FROM tbl_swatch_sw WHERE sw_type = 'Other' and sw_status = 1";
$list2 = $g_dbconn->Execute($q)->GetArray();
$list2_items = array();

foreach($list1 as $swatch) {
	$list1_items[] = "{url: 'upload/swatch_images/" . $swatch['sw_image'] . "', title: '" . $swatch['sw_title'] . "', cupboard_id: '" . $swatch['cupboard_id'] . "', wall_id: '" . $swatch['wall_id'] . "'}";
}

foreach($list2 as $swatch) {
	$list2_items[] = "{url: 'upload/swatch_images/" . $swatch['sw_image'] . "', title: '" . $swatch['sw_title'] . "', reference_id: '" . $swatch['sw_id'] . "'}";
}

$list1_items = implode(",\n",$list1_items);
$list2_items = implode(",\n",$list2_items);

?>
<script type="text/javascript">
var colorideas1_itemList = [
<?php echo $list1_items; ?>
];

var colorideas2_itemList = [
<?php echo $list2_items; ?>
];
/*colorideas2_itemList = [<?php echo $list2_items; ?>];*/
</script>
<div id="measurement" class="general">
	<h1><?php echo $page->get_title(); ?></h1>
	<p class="sendfriend"><a href="lightbox.php?iframe&pg=friend&url=<?php echo $pg; ?>" class="friend-popup"><img src="images/btn-sendfriend.gif" /></a></p>
	<div class="text"><?php echo $page->get_content1(); ?></div>
    
    <div id="colorideas">
        
      <div id="swatch1">
        <div id="swatch-carousel-1-count"></div>
        <h2><span><strong>Step 1</strong> Your Benchtop</span></h2>
        <div id="swatch-items-1" class="colorideas-items">
          <div id="swatch-items-1-selection" class="colorideas-items-selection png_bg">
            <img src="upload/swatch_images/1.jpg" alt="" height="172" width="172" />
          </div>
          <a id="swatch-prev-1" class="colorideas-swatch-prev png_bg" href="javascript:;">
            <img src="images/colorideas/ideas-button-left.jpg" height="50" width="31" alt="" />
          </a>
          <a id="swatch-next-1" class="colorideas-swatch-next png_bg" href="javascript:;">
            <img src="images/colorideas/ideas-button-right.jpg" height="50" width="31" alt="" />
          </a>
          <div id="swatch-carousel-1" class="colorideas-items-container">
            <ul class="colorideas-items-carousel">            
            </ul>
          </div>
        </div>
      </div>
      <div id="swatch-items-1-selection-title" class="colorideas-items-selection-title"></div>
    
      <div id="swatch2">
        <h2 class="colorideas-items-hidden"><span><strong>Optional</strong> Your Cabinet</span></h2>
        <div id="swatch-items-2" class="colorideas-items colorideas-items-hidden">
          <div id="swatch-items-2-selection" class="colorideas-items-selection png_bg">
            <img src="upload/swatch_images/1.jpg" alt="" height="172" width="172" />
          </div>
          <a id="swatch-prev-2" class="colorideas-swatch-prev png_bg" href="javascript:;">
            <img src="images/colorideas/ideas-button-left.jpg" height="50" width="31" alt="" />
          </a>
          <a id="swatch-next-2" class="colorideas-swatch-next png_bg" href="javascript:;">
            <img src="images/colorideas/ideas-button-right.jpg" height="50" width="31" alt="" />
          </a>
          <div id="swatch-carousel-2" class="colorideas-items-container">
            <ul class="colorideas-items-carousel">            
            </ul>
          </div>
        </div>
      </div>
      <div id="swatch-items-2-selection-title" class="colorideas-items-selection-title"></div>

      <div id="swatch3">
        <h2 class="colorideas-items-hidden"><span><strong>Optional</strong> Your Walls</span></h2>
        <div id="swatch-items-3" class="colorideas-items colorideas-items-hidden">
          <div id="swatch-items-3-selection" class="colorideas-items-selection png_bg">
            <img src="upload/swatch_images/1.jpg" alt="" height="172" width="172" />
          </div>
          <a id="swatch-prev-3" class="colorideas-swatch-prev png_bg" href="javascript:;">
            <img src="images/colorideas/ideas-button-left.jpg" height="50" width="31" alt="" />
          </a>
          <a id="swatch-next-3" class="colorideas-swatch-next png_bg" href="javascript:;">
            <img src="images/colorideas/ideas-button-right.jpg" height="50" width="31" alt="" />
          </a>
          <div id="swatch-carousel-3" class="colorideas-items-container">
            <ul class="colorideas-items-carousel">            
            </ul>
          </div>
        </div>
      </div> 
    <div id="swatch-items-3-selection-title" class="colorideas-items-selection-title"></div>
    
  </div>
    
<div id="colorideas-email-popup">
  <div id="colorideas-email-message"></div>
  <a href="javascript:;"><img src="images/colorideas/ideas-button-email.jpg" alt="Color swatch email preview" width="348" height="77" /></a>
</div>

</div><!-- howitwork -->

