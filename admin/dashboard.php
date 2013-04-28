<?php
$db_def = array
    (
    "0" => array("tbl_page_pg", "pg_id", "Pages", ""),
    "1" => array("tbl_gallery_gal", "gal_id", "Gallery", ""),
    "2" => array("tbl_faq_faq", "faq_id", "FAQs", ""),
    "3" => array("tbl_video_vdo", "vdo_id", "Videos", ""),
    "4" => array("tbl_testimonial_t", "t_id", "Testimonials", ""),
    "5" => array("tbl_template_tmp", "tmp_id", "Design Templates", ""),
    "6" => array("tbl_payment_tmp", "tmp_id", "Payment Templates", ""),
);



for ($i = 0; $i < count($db_def); $i++) {
    $SQLQuery = "Select count(" . $db_def["$i"][1] . ") as num from " . $db_def["$i"][0];
    $rs_count = $g_dbconn->Execute($SQLQuery);

    $db_def["$i"][3] = $rs_count->Fields("num");
}


$module = array();

$module['Website Management'] = array(
    "0" => array('Pages', 'page'),
    "1" => array('Faqs', 'faq'),
    "2" => array('How it Works', 'hiw'),
    "3" => array('Gallery', 'gallery'),
    "4" => array('Videos', 'video'),
    "5" => array('Testimonials', 'testimonial'),
    "6" => array("Templates", "template"),
    "7" => array("Homepage", "home"),
    "8" => array("Member", "member"),
    "9" => array("Shipping", "shipping"),
    "10" => array("Transaction", "transaction"),
    "11" => array("Color Ideas", "swatch"),
    "12" => array("Code", "code"),
    "13" => array('Terms & Conditions', 'term'),
    "14" => array("Payment", "payment"));


$module['Website Settings'] = array(
    "0" => array('Users', 'user'),
    "1" => array('Settings', 'setting'),
    "2" => array('Email Template', 'email'),
    "3" => array('FAQ Groups', 'group'),
    "4" => array('Terms & Conditions Groups', 'termgroup'));
?>
<div class="home-left">

    <div style="background: url('skin/images/home/test.jpg') top left no-repeat; width: 205px; height: 20px; padding: 10px 0 0 10px; color: #333333; font-weight: bold;">
        Website Hits
    </div>
    <div style="width: 213px; background: #f1f1f1; border-left: 1px solid #e3e3e3; border-right:  1px solid #e3e3e3;">
        <div style="width: 209px; margin: 0 auto 0 auto;" id="chartdiv">Visitors</div><script type="text/javascript">
            var chart = new FusionCharts("skin/swf/FCF_Line.swf", "ChartId", "200", "197", "", "", "f1f1f1");
            chart.setDataURL("chart.php");		   
            chart.render("chartdiv");
 
 

        </script>
    </div>
    <div style="background: url('skin/images/home/visitors-bottom.jpg') top left no-repeat; width: 215px; height: 9px;"></div>
    <div class="glance-view-top">Glance View</div>
    <div class="glance-view-items clearfix">
<?php for ($i = 0; $i < count($db_def); $i++) { ?>
            <div style="float: left; width: 138px; clear: left; margin: 0 0 3px 0; border-bottom: 1px dotted #eeeeee;"><?php echo $db_def["$i"][2]; ?></div>
            <div style="float: left; width: 45px; text-align: right; margin: 0 0 3px 0;border-bottom: 1px dotted #eeeeee; font-weight: bold;"><?php echo $db_def["$i"][3]; ?></div>
        <?php } ?>

    </div>
</div>
<div class="home-right">
<?php foreach ($module as $mod => $key) { ?>
        <h2><?php echo $mod; ?></h2>
        <ul class="module">
        <?php foreach ($key as $row) { ?>
                <li><a href="?pg=<?php echo $row[1]; ?>"><img src="images/icon_<?php echo $row[1]; ?>.gif" /><?php echo $row[0]; ?></a></li>
            <?php } ?>
        </ul>
        <?php } ?>     
</div>